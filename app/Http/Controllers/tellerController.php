<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use App\Models\Rekening;
use App\Models\Setoran;
use App\Models\Penarikan;
use App\Models\Transfer;
use App\Models\Transaksi;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\SetoranExport;
use App\Exports\PenarikanExport;
use App\Exports\TransferExport;
use App\Models\Bukti_Tf;
use App\Models\Minimum_saldo;
use App\Models\RiwayatTf;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;


class tellerController extends Controller
{
    // ======================================================
    // DASHBOARD TELLER
    // ======================================================
    public function index()
    {
        $user = Auth::user();
        $teller = $user->petugas;

        $totalTabungan = Rekening::sum('saldo_saat_ini');

        $setoranHariIni = Setoran::whereDate('created_at', Carbon::today())
            ->sum('jumlah_penyetoran');

        $penarikanHariIni = Penarikan::whereDate('created_at', Carbon::today())
            ->sum('jumlah_penarikan');

        $biayaAdminSetoran = Setoran::whereDate('created_at', Carbon::today())
            ->sum('nominal_admin');

        $biayaAdminPenarikan = Penarikan::whereDate('created_at', Carbon::today())
            ->sum('nominal_admin');

        $biayaAdminTransfer = Transfer::whereDate('created_at', Carbon::today())
            ->sum('nominal_admin');

        $setor = Setoran::with('rekening.nasabah')->latest('created_at')->get();
        $tarik = Penarikan::with('rekening.nasabah')->latest('created_at')->get();
        $transactions = $setor->concat($tarik)->sortByDesc('created_at');

        return view('teller.dashboard', compact(
            'user',
            'teller',
            'totalTabungan',
            'setoranHariIni',
            'penarikanHariIni',
            'biayaAdminSetoran',
            'biayaAdminPenarikan',
            'biayaAdminTransfer',
            'transactions'
        ));
    }

    // ======================================================
    // ===================== SETORAN ========================
    // ======================================================

    public function exportSetoran(String $filter)
    {
        $query = Setoran::query();

        switch ($filter) {
            case 'hari_ini':
                $query->whereDate('created_at', Carbon::today());
                $judul = 'LAPORAN DATA SETORAN HARI INI';
                break;
            case 'minggu_ini':
                $query->whereBetween('created_at', [
                    Carbon::now()->startOfWeek(),
                    Carbon::now()->endOfWeek()
                ]);
                $judul = 'LAPORAN DATA SETORAN MINGGU INI';
                break;
            case 'bulan_ini':
                $query->whereMonth('created_at', Carbon::now()->month);
                $judul = 'LAPORAN DATA SETORAN BULAN INI';
                break;
            case 'tahun_ini':
                $query->whereYear('created_at', Carbon::now()->year);
                $judul = 'LAPORAN DATA SETORAN TAHUN INI';
                break;
            default:
                $judul = 'LAPORAN DATA SETORAN';
                break;
        }

        $data = $query->get();

        return Excel::download(
            new SetoranExport($data, $judul),
            'Setoran-' . now()->timestamp . '.xlsx'
        );
    }

    public function exportSetoranCustom(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date'   => 'required|date',
        ]);

        $data = Setoran::whereBetween(
            'created_at',
            [
                $request->start_date . ' 00:00:00',
                $request->end_date . ' 23:59:59'
            ]
        )->get();

        $judul = 'LAPORAN DATA SETORAN';

        return Excel::download(
            new SetoranExport($data, $judul),
            'Setoran-' . $request->start_date . '_sampai_' . $request->end_date . '.xlsx'
        );
    }

    public function cetakStruk(String $id)
    {
        $setoran = Setoran::with(['petugas', 'transaksi'])->findOrFail($id);
        $user = Auth::user();
        return view('teller.crud_setoran.struk', compact('setoran', 'user'));
    }

    public function setoran(Request $request)
    {
        $user = Auth::user();
        $teller = $user->petugas;
        $perPage = $request->input('per_page', 10);
        $transaksi = Transaksi::where('jenis_transaksi', 'setoran')
            ->orWhere('jenis_transaksi', 'Setoran')
            ->first();

        $query = Setoran::with(['petugas', 'transaksi'])->latest();

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_lengkap', 'like', '%' . $search . '%')
                    ->orWhere('nama_penyetor', 'like', '%' . $search . '%')
                    ->orWhere('id_rekening', 'like', '%' . $search . '%');
            });
        }

        $data = $query->paginate($perPage)->appends(['per_page' => $perPage]);

        return view('teller.setoran', compact('user', 'teller', 'data', 'transaksi', 'perPage'));
    }

    public function storeSetoran(Request $request)
    {
        $request->validate([
            'id_rekening'             => 'required|numeric|exists:rekening,id',
            'jumlah_penyetoran'       => 'required',
            'jumlah_penyetoran'       => 'required',
            'pilihan_biaya_transaksi' => 'required|in:Cash,Potong Saldo',
            'setoran'                 => 'required',
            'nama_penyetor'           => 'required',
            'transaksi_id'            => 'required|exists:transaksi,id',
            'nominal_admin'           => 'nullable'
        ]);

        $user = Auth::user();
        $teller = $user->petugas;

        $masterTransaksi = Transaksi::findOrFail($request->transaksi_id);
        $biayaAdmin = (int) $masterTransaksi->nominal;

        $jumlahSetoran = (int) preg_replace('/\D/', '', $request->jumlah_penyetoran);
        $pilihanBiaya = $request->pilihan_biaya_transaksi;

        // Kalkulasi saldo bersih yang akan masuk ke rekening
        $setoranMasukSaldo = ($pilihanBiaya === 'Potong Saldo')
            ? ($jumlahSetoran - $biayaAdmin)
            : $jumlahSetoran;

        if ($setoranMasukSaldo < 0) {
            return back()->with('error', 'Jumlah setoran tidak cukup untuk dipotong biaya admin!')->withInput();
        }

        DB::beginTransaction();
        try {
            $rekening = Rekening::lockForUpdate()->findOrFail($request->id_rekening);
            $rekening->saldo_saat_ini += $setoranMasukSaldo;
            $rekening->save();

            Setoran::create([
                'id_rekening'             => $request->id_rekening,
                'id_petugas'              => $teller->id,
                'setoran'                 => $request->setoran,
                'pilihan_biaya_transaksi' => $pilihanBiaya,
                'mata_uang'               => $request->mata_uang ?? 'IDR',
                'uang_terbilang'          => $request->uang_terbilang,
                'catatan'                 => $request->catatan,
                'jumlah_penyetoran'       => $jumlahSetoran,
                'transaksi_id'            => $request->transaksi_id,
                'total_biaya'             => $jumlahSetoran + $biayaAdmin,
                'nominal_admin'           => $biayaAdmin,
                'nama_lengkap'            => $request->nama_lengkap,
                'nama_penyetor'           => $request->nama_penyetor,
                'alamat_penyetor'         => $request->alamat_penyetor,
                'no_hp_penyetor'          => $request->no_hp_penyetor,
            ]);

            $this->sinkronisasiSaldo($request->id_rekening);
            DB::commit();

            return back()->with('success', 'Penyetoran berhasil disimpan!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }

    public function updateSetoran(Request $request, String $id)
    {
        $request->validate([
            'id_rekening'             => 'required|exists:rekening,id',
            'jumlah_penyetoran'       => 'required',
            'pilihan_biaya_transaksi' => 'required|in:Cash,Potong Saldo',
            'transaksi_id'            => 'required|exists:transaksi,id'
        ]);

        DB::beginTransaction();
        try {
            $user = Auth::user();
            $teller = $user->petugas;
            $setoran = Setoran::findOrFail($id);

            $masterTransaksiLama = Transaksi::find($setoran->transaksi_id);
            $biayaAdminLama = $masterTransaksiLama ? (int)$masterTransaksiLama->nominal : 0;

            // Hitung saldo lama
            $setoranMasukSaldoLama = ($setoran->pilihan_biaya_transaksi === 'Potong Saldo')
                ? ($setoran->jumlah_penyetoran - $biayaAdminLama)
                : $setoran->jumlah_penyetoran;

            // Balikan Saldo
            $rekeningLama = Rekening::lockForUpdate()->findOrFail($setoran->id_rekening);
            $rekeningLama->saldo_saat_ini -= $setoranMasukSaldoLama;
            $rekeningLama->save();

            // Hitung saldo baru
            $jumlahBaru = (int) preg_replace('/\D/', '', $request->jumlah_penyetoran);
            $masterTransaksiBaru = Transaksi::findOrFail($request->transaksi_id);
            $biayaAdminBaru = (int) $masterTransaksiBaru->nominal;
            $pilihanBiayaBaru = $request->pilihan_biaya_transaksi;

            $setoranMasukSaldoBaru = ($pilihanBiayaBaru === 'Potong Saldo')
                ? ($jumlahBaru - $biayaAdminBaru)
                : $jumlahBaru;

            if ($setoranMasukSaldoBaru < 0) {
                DB::rollBack();
                return back()->with('error', 'Jumlah setoran tidak cukup untuk dipotong biaya admin!')->withInput();
            }

            // Tambah Saldo
            $rekeningBaru = Rekening::lockForUpdate()->findOrFail($request->id_rekening);
            $rekeningBaru->saldo_saat_ini += $setoranMasukSaldoBaru;
            $rekeningBaru->save();

            // Update
            $setoran->update([
                'id_rekening'             => $request->id_rekening,
                'id_petugas'              => $teller->id,
                'nama_lengkap'            => $request->nama_lengkap,
                'jumlah_penyetoran'       => $jumlahBaru,
                'pilihan_biaya_transaksi' => $pilihanBiayaBaru,
                'transaksi_id'            => $request->transaksi_id,
                'total_biaya'             => $jumlahBaru + $biayaAdminBaru,
                'nominal_admin'           => $biayaAdminBaru,
                'setoran'                 => $request->setoran,
                'mata_uang'               => $request->mata_uang,
                'uang_terbilang'          => $request->uang_terbilang,
                'nama_penyetor'           => $request->nama_penyetor,
                'no_hp_penyetor'          => $request->no_hp_penyetor,
                'alamat_penyetor'         => $request->alamat_penyetor,
                'catatan'                 => $request->catatan,
            ]);

            if ($setoran->getOriginal('id_rekening') != $request->id_rekening) {
                $this->sinkronisasiSaldo($setoran->getOriginal('id_rekening'));
            }
            $this->sinkronisasiSaldo($request->id_rekening);


            DB::commit();
            return back()->with('success', 'Data setoran berhasil diupdate!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal update: ' . $e->getMessage());
        }
    }

    public function cariRekening(String $rekening)
    {
        $data = Rekening::with('nasabah')
            ->where('id', $rekening)
            ->orWhereHas('nasabah', function ($q) use ($rekening) {
                $q->where('nis_nip', $rekening);
            })
            ->first();

        if ($data && $data->nasabah) {
            return response()->json([
                'success'     => true,
                'id_rekening' => $data->id,
                'nama'        => $data->nasabah->nama_nasabah,
                'saldo'       => $data->saldo_saat_ini
            ]);
        }
        return response()->json(['success' => false, 'message' => 'NIS / No. Rekening tidak terdaftar']);
    }


    public function searchRekening(Request $request)
    {
        $search = $request->query('query');
        if (strlen($search) < 2) {
            return response()->json([]);
        }

        $data = Rekening::with('nasabah')
            ->where('id', 'like', '%' . $search . '%')
            ->orWhereHas('nasabah', function ($q) use ($search) {
                $q->where('nama_nasabah', 'like', '%' . $search . '%');
            })
            ->limit(10)->get();

        $results = $data->map(function ($item) {
            return [
                'id' => $item->id,
                'nama' => $item->nasabah->nama_nasabah ?? '-',
                'saldo' => $item->saldo_saat_ini
            ];
        });

        return response()->json($results);
    }

    public function destroySetoran(String $id)
    {
        $setoran = Setoran::findOrFail($id);
        $id_rek = $setoran->id_rekening;
        $setoran->delete();
        $this->sinkronisasiSaldo($id_rek);
        return back()->with('success', 'History setoran berhasil dihapus!');
    }

    // ======================================================
    // ===================== PENARIKAN ======================
    // ======================================================

    public function exportPenarikan(String $filter)
    {
        $query = Penarikan::query();
        switch ($filter) {
            case 'hari_ini':
                $query->whereDate('created_at', Carbon::today());
                $judul = 'LAPORAN DATA PENARIKAN HARI INI';
                break;
            case 'minggu_ini':
                $query->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
                $judul = 'LAPORAN DATA PENARIKAN MINGGU INI';
                break;
            case 'bulan_ini':
                $query->whereMonth('created_at', Carbon::now()->month);
                $judul = 'LAPORAN DATA PENARIKAN BULAN INI';
                break;
            case 'tahun_ini':
                $query->whereYear('created_at', Carbon::now()->year);
                $judul = 'LAPORAN DATA PENARIKAN TAHUN INI';
                break;
            default:
                $judul = 'LAPORAN DATA PENARIKAN';
                break;
        }

        $data = $query->get();
        return Excel::download(new PenarikanExport($data, $judul), 'Penarikan-' . $filter . '.xlsx');
    }

    public function exportPenarikanCustom(Request $request)
    {
        $request->validate(['start_date' => 'required|date', 'end_date' => 'required|date']);
        $data = Penarikan::whereBetween('created_at', [$request->start_date . ' 00:00:00', $request->end_date . ' 23:59:59'])->get();
        $judul = 'LAPORAN DATA PENARIKAN';
        return Excel::download(new PenarikanExport($data, $judul), 'Penarikan-' . $request->start_date . '_sampai_' . $request->end_date . '.xlsx');
    }

    public function cetakStrukPenarikan(String $id)
    {
        $penarikan = Penarikan::with(['petugas', 'transaksi'])->findOrFail($id);
        $user = Auth::user();
        return view('teller.crud_penarikan.struk', compact('penarikan', 'user'));
    }

    public function penarikan(Request $request)
    {
        $user = Auth::user();
        $teller = $user->petugas;
        $saldoMinimum = Minimum_saldo::where('id', 1)->value('nominal');
        $perPage = $request->input('per_page', 10);
        $transaksi = Transaksi::where('jenis_transaksi', 'penarikan')->first();
        $query = Penarikan::with(['petugas', 'transaksi'])->latest();

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_penarik', 'like', '%' . $search . '%')
                    ->orWhere('id_rekening', 'like', '%' . $search . '%');
            });
        }

        $data = $query->paginate($perPage)->appends(['per_page' => $perPage]);
        return view('teller.penarikan', compact('user', 'teller', 'data', 'transaksi', 'saldoMinimum', 'perPage'));
    }

    public function storePenarikan(Request $request)
    {
        $request->validate([
            'id_rekening'             => 'required|exists:rekening,id',
            'jumlah_penarikan'        => 'required',
            'nama_penarik'            => 'required',
            'transaksi_id'            => 'required|exists:transaksi,id',
            'pilihan_biaya_transaksi' => 'required|in:cash,potong_saldo'
        ]);

        $user = Auth::user();
        $teller = $user->petugas;

        $jumlahPenarikan = (int) str_replace('.', '', $request->jumlah_penarikan);
        $masterTransaksi = Transaksi::findOrFail($request->transaksi_id);
        $biayaAdminAsli = (int) $masterTransaksi->nominal;

        $totalPotongSaldo = $jumlahPenarikan;
        if ($request->pilihan_biaya_transaksi === 'potong_saldo') {
            $totalPotongSaldo += $biayaAdminAsli;
        }

        $saldoMinimum = Minimum_saldo::where('id', 1)->value('nominal') ?? 10000;

        DB::beginTransaction();
        try {
            $rekening = Rekening::lockForUpdate()->findOrFail($request->id_rekening);

            if (($rekening->saldo_saat_ini - $totalPotongSaldo) < $saldoMinimum) {
                DB::rollBack();
                return back()->with('error', 'Penarikan gagal! Saldo tidak cukup untuk memproses penarikan.');
            }

            $pilihanBiayaFormatted = ($request->pilihan_biaya_transaksi === 'potong_saldo') ? 'Potong Saldo' : 'Cash';
            $rekening->saldo_saat_ini -= $totalPotongSaldo;
            $rekening->save();

            Penarikan::create([
                'id_rekening'             => $request->id_rekening,
                'id_petugas'              => $teller->id,
                'nama_penarik'            => $request->nama_penarik,
                'jumlah_penarikan'        => $jumlahPenarikan,
                'transaksi_id'            => $request->transaksi_id,
                'total_biaya'             => $jumlahPenarikan + $biayaAdminAsli,
                'nominal_admin'           => $biayaAdminAsli,
                'pilihan_biaya_transaksi' => $pilihanBiayaFormatted,
            ]);
            $this->sinkronisasiSaldo($request->id_rekening);
            DB::commit();

            if ($request->pilihan_biaya_transaksi === 'cash') {
                return back()->with('success', 'Penarikan berhasil! Biaya admin Rp ' . number_format($biayaAdminAsli, 0, ',', '.') . ' dibayarkan tunai (saldo hanya terpotong nominal penarikan).');
            } else {
                return back()->with('success', 'Penarikan berhasil! Biaya admin otomatis memotong saldo.');
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memproses penarikan: ' . $e->getMessage());
        }
    }

    public function updatePenarikan(Request $request, String $id)
    {
        $request->validate([
            'id_rekening'             => 'required|exists:rekening,id',
            'jumlah_penarikan'        => 'required',
            'nama_penarik'            => 'required',
            'transaksi_id'            => 'required|exists:transaksi,id',
            'pilihan_biaya_transaksi' => 'required|in:cash,potong_saldo'
        ]);

        DB::beginTransaction();
        try {
            $user = Auth::user();
            $teller = $user->petugas;
            $penarikan = Penarikan::findOrFail($id);

            // Rollback saldo lama
            $rekeningLama = Rekening::lockForUpdate()->findOrFail($penarikan->id_rekening);
            $potonganLama = $penarikan->jumlah_penarikan;
            if (strtolower($penarikan->pilihan_biaya_transaksi) === 'potong saldo' || $penarikan->pilihan_biaya_transaksi === 'potong_saldo') {
                $potonganLama += $penarikan->nominal_admin;
            }
            $rekeningLama->saldo_saat_ini += $potonganLama;
            $rekeningLama->save();

            // Hitung biaya baru
            $jumlahBaru = (int) preg_replace('/\D/', '', $request->jumlah_penarikan);
            $masterTransaksi = Transaksi::findOrFail($request->transaksi_id);
            $biayaAdmin = (int) $masterTransaksi->nominal;

            $totalPotongBaru = $jumlahBaru;
            if ($request->pilihan_biaya_transaksi === 'potong_saldo') {
                $totalPotongBaru += $biayaAdmin;
            }

            // Potong saldo baru
            $rekeningBaru = Rekening::lockForUpdate()->findOrFail($request->id_rekening);
            $saldoMinimum = Minimum_saldo::where('id', 1)->value('nominal') ?? 10000;
            $sisaSaldo = $rekeningBaru->saldo_saat_ini - $totalPotongBaru;

            if ($sisaSaldo < $saldoMinimum) {
                DB::rollBack();
                return back()->with('error', 'Update gagal! Saldo minimum harus tersisa Rp ' . number_format($saldoMinimum, 0, ',', '.'));
            }

            $rekeningBaru->saldo_saat_ini -= $totalPotongBaru;
            $rekeningBaru->save();

            // Update record penarikan
            $pilihanBiayaFormatted = ($request->pilihan_biaya_transaksi === 'potong_saldo') ? 'Potong Saldo' : 'Cash';
            $penarikan->update([
                'id_petugas'              => $teller->id,
                'id_rekening'             => $request->id_rekening,
                'nama_penarik'            => $request->nama_penarik,
                'jumlah_penarikan'        => $jumlahBaru,
                'transaksi_id'            => $request->transaksi_id,
                'nominal_admin'           => $biayaAdmin,
                'total_biaya'             => $jumlahBaru + $biayaAdmin,
                'pilihan_biaya_transaksi' => $pilihanBiayaFormatted,
            ]);

            if ($penarikan->getOriginal('id_rekening') != $request->id_rekening) {
                $this->sinkronisasiSaldo($penarikan->getOriginal('id_rekening'));
            }
            $this->sinkronisasiSaldo($request->id_rekening);

            DB::commit();
            return back()->with('success', 'Data penarikan berhasil diupdate!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal mengupdate penarikan: ' . $e->getMessage());
        }
    }

    public function destroyPenarikan(String $id)
    {
        $penarikan = Penarikan::findOrFail($id);
        $id_rek = $penarikan->id_rekening;
        $penarikan->delete();
        $this->sinkronisasiSaldo($id_rek);
        return back()->with('success', 'History penarikan berhasil dihapus!');
    }

    // ======================================================
    // ===================== TRANSFER =======================
    // ======================================================

    public function exportTransfer(String $filter)
    {
        $query = Transfer::with(['rekeningPengirim.nasabah', 'rekeningPenerima.nasabah']);
        switch ($filter) {
            case 'hari_ini':
                $query->whereDate('created_at', Carbon::today());
                $judul = 'LAPORAN DATA TRANSFER HARI INI';
                break;
            case 'minggu_ini':
                $query->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
                $judul = 'LAPORAN DATA TRANSFER MINGGU INI';
                break;
            case 'bulan_ini':
                $query->whereMonth('created_at', Carbon::now()->month)->whereYear('created_at', Carbon::now()->year);
                $judul = 'LAPORAN DATA TRANSFER BULAN INI';
                break;
            case 'tahun_ini':
                $query->whereYear('created_at', Carbon::now()->year);
                $judul = 'LAPORAN DATA TRANSFER TAHUN INI';
                break;
            default:
                $judul = 'LAPORAN DATA TRANSFER';
                break;
        }
        $data = $query->get();
        return Excel::download(new TransferExport($data, $judul), 'Transfer-' . $filter . '.xlsx');
    }

    public function exportTransferCustom(Request $request)
    {
        $request->validate(['start_date' => 'required|date', 'end_date' => 'required|date']);
        $data = Transfer::whereBetween('created_at', [
            Carbon::parse($request->start_date)->startOfDay(),
            Carbon::parse($request->end_date)->endOfDay(),
        ])->get();
        return Excel::download(new TransferExport($data, 'LAPORAN DATA TRANSFER'), 'Transfer-' . $request->start_date . '-sampai-' . $request->end_date . '.xlsx');
    }

    public function cetakStrukTransfer(String $id)
    {
        $transfer = Transfer::with(['rekeningPengirim.nasabah', 'rekeningPenerima.nasabah', 'petugas', 'transaksi'])->findOrFail($id);
        $user = Auth::user();
        return view('teller.crud_transfer.struk', compact('transfer', 'user'));
    }

    public function transfer(Request $request)
    {
        $user = Auth::user();
        $teller = $user->petugas;
        $perPage = $request->input('per_page', 10);
        $transaksi = Transaksi::where('jenis_transaksi', 'transfer')->orWhere('jenis_transaksi', 'Transfer')->first();
        $query = Transfer::with(['petugas', 'transaksi', 'rekeningPengirim.nasabah', 'rekeningPenerima.nasabah'])->latest();

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('rekeningPengirim.nasabah', function ($subQuery) use ($search) {
                    $subQuery->where('nama_nasabah', 'like', '%' . $search . '%');
                })->orWhereHas('rekeningPenerima.nasabah', function ($subQuery) use ($search) {
                    $subQuery->where('nama_nasabah', 'like', '%' . $search . '%');
                })->orWhere('id_rekening_pengirim', 'like', '%' . $search . '%')
                    ->orWhere('id_rekening_penerima', 'like', '%' . $search . '%');
            });
        }

        $data = $query->paginate($perPage)->appends(['per_page' => $perPage]);
        return view('teller.transfer', compact('user', 'teller', 'data', 'transaksi', 'perPage'));
    }

    public function storeTransfer(Request $request)
    {
        $user = Auth::user();
        $teller = $user->petugas;

        $jumlahClean = (int) preg_replace('/\D/', '', $request->jumlah_transfer);
        $biayaClean  = (int) preg_replace('/\D/', '', $request->biaya_transaksi);
        $totalClean  = (int) preg_replace('/\D/', '', $request->total_biaya);

        $request->merge([
            'jumlah_transfer' => $jumlahClean,
            'biaya_transaksi' => $biayaClean,
            'total_biaya'     => $totalClean,
        ]);

        $request->validate([
            'id_rekening_pengirim'    => 'required|numeric|exists:rekening,id',
            'id_rekening_penerima'    => 'required|numeric|exists:rekening,id|different:id_rekening_pengirim',
            'jumlah_transfer'         => 'required|numeric|min:1',
            'pilihan_biaya_transaksi' => 'required|in:Cash,Potong Saldo',
        ]);

        $norekPengirim = $request->id_rekening_pengirim;
        $norekPenerima = $request->id_rekening_penerima;
        $nominal       = $request->jumlah_transfer;
        $biayaAdmin    = $biayaClean;
        $pilihanBiaya  = $request->pilihan_biaya_transaksi;

        $potonganPengirim = ($pilihanBiaya === 'Potong Saldo') ? ($nominal + $biayaAdmin) : $nominal;

        DB::beginTransaction();
        try {
            $pengirim = Rekening::lockForUpdate()->find($norekPengirim);
            $penerima = Rekening::lockForUpdate()->find($norekPenerima);

            if (!$pengirim || !$penerima) {
                throw new \Exception("Rekening tidak ditemukan.");
            }

            if ($pengirim->saldo_saat_ini < $potonganPengirim) {
                throw new \Exception("Saldo pengirim tidak cukup untuk transaksi ini.");
            }

            $pengirim->saldo_saat_ini -= $potonganPengirim;
            $pengirim->save();

            $penerima->saldo_saat_ini += $nominal;
            $penerima->save();

            Transfer::create([
                'id_rekening_pengirim'    => $norekPengirim,
                'id_rekening_penerima'    => $norekPenerima,
                'jumlah_transfer'         => $nominal,
                'pilihan_biaya_transaksi' => $pilihanBiaya,
                'transaksi_id'            => $request->transaksi_id,
                'total_biaya'             => $nominal + $biayaAdmin,
                'nominal_admin'           => $biayaAdmin,
                'datetime'                => now(),
                'catatan'                 => $request->catatan,
                'id_petugas'              => $teller->id,
            ]);
            $this->sinkronisasiSaldo($norekPengirim);
            $this->sinkronisasiSaldo($norekPenerima);
            DB::commit();
            return redirect()->back()->with('success', 'Transaksi transfer berhasil diproses!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal memproses transaksi: ' . $e->getMessage())->withInput();
        }
    }

    public function updateTransfer(Request $request, String $id)
    {
        $request->validate([
            'id_rekening_pengirim' => 'required',
            'id_rekening_penerima' => 'required',
        ]);

        $pengirimRepo = Rekening::find($request->id_rekening_pengirim);
        $penerimaRepo = Rekening::find($request->id_rekening_penerima);

        $request->merge([
            'id_rekening_pengirim' => $pengirimRepo ? $pengirimRepo->id : null,
            'id_rekening_penerima' => $penerimaRepo ? $penerimaRepo->id : null,
            'jumlah_transfer' => (int) preg_replace('/\D/', '', $request->jumlah_transfer),
            'biaya_transaksi' => (int) preg_replace('/\D/', '', $request->biaya_transaksi),
            'total_biaya' => (int) preg_replace('/\D/', '', $request->total_biaya),
        ]);

        $request->validate([
            'id_rekening_pengirim' => 'required|exists:rekening,id',
            'id_rekening_penerima' => 'required|exists:rekening,id',
            'jumlah_transfer'      => 'required|numeric|min:1000',
            'biaya_transaksi'      => 'required|numeric',
            'total_biaya'          => 'required|numeric',
            'transaksi_id'         => 'required|exists:transaksi,id',
        ], [
            'id_rekening_pengirim.exists' => 'Nomor rekening pengirim baru tidak valid!',
            'id_rekening_penerima.exists' => 'Nomor rekening penerima baru tidak valid!',
        ]);

        if ($request->id_rekening_pengirim == $request->id_rekening_penerima) {
            return back()->with('error', 'Tidak bisa transfer ke nomor rekening yang sama!')->withInput();
        }

        $saldoMinimum = 10000;

        DB::beginTransaction();

        try {
            $user = Auth::user();
            $tellerBaru = $user->petugas;
            $transfer = Transfer::findOrFail($id);

            $pengirimLama = Rekening::lockForUpdate()->findOrFail($transfer->id_rekening_pengirim);
            $penerimaLama = Rekening::lockForUpdate()->findOrFail($transfer->id_rekening_penerima);

            if ($penerimaLama->saldo_saat_ini < $transfer->jumlah_transfer) {
                DB::rollBack();
                return back()->with('error', 'Saldo penerima lama tidak cukup untuk rollback transaksi.')->withInput();
            }

            $pengirimLama->saldo_saat_ini += $transfer->total_biaya;
            $pengirimLama->save();
            $penerimaLama->saldo_saat_ini -= $transfer->jumlah_transfer;
            $penerimaLama->save();

            $pengirimBaru = Rekening::lockForUpdate()->findOrFail($request->id_rekening_pengirim);
            $penerimaBaru = Rekening::lockForUpdate()->findOrFail($request->id_rekening_penerima);

            if ($pengirimBaru->saldo_saat_ini < $request->total_biaya) {
                DB::rollBack();
                return back()->with('error', 'Saldo pengirim baru tidak mencukupi.')->withInput();
            }

            $sisaSaldo = $pengirimBaru->saldo_saat_ini - $request->total_biaya;
            if ($sisaSaldo < $saldoMinimum) {
                DB::rollBack();
                return back()->with('error', 'Saldo minimum harus tersisa Rp 10.000')->withInput();
            }

            $pengirimBaru->saldo_saat_ini -= $request->total_biaya;
            $pengirimBaru->save();
            $penerimaBaru->saldo_saat_ini += $request->jumlah_transfer;
            $penerimaBaru->save();

            $transfer->update([
                'id_rekening_pengirim' => $request->id_rekening_pengirim,
                'id_rekening_penerima' => $request->id_rekening_penerima,
                'jumlah_transfer'      => $request->jumlah_transfer,
                'biaya_transaksi'      => $request->biaya_transaksi,
                'total_biaya'          => $request->total_biaya,
                'transaksi_id'         => $request->transaksi_id,
                'id_petugas'           => $tellerBaru->id,
                'catatan'              => $request->catatan,
            ]);

            if ($transfer->getOriginal('id_rekening_pengirim') != $request->id_rekening_pengirim) {
                $this->sinkronisasiSaldo($transfer->getOriginal('id_rekening_pengirim'));
            }
            $this->sinkronisasiSaldo($request->id_rekening_pengirim);

            if ($transfer->getOriginal('id_rekening_penerima') != $request->id_rekening_penerima) {
                $this->sinkronisasiSaldo($transfer->getOriginal('id_rekening_penerima'));
            }
            $this->sinkronisasiSaldo($request->id_rekening_penerima);


            DB::commit();
            return back()->with('success', 'Data transfer berhasil diupdate!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal update data: ' . $e->getMessage())->withInput();
        }
    }

    public function destroyTransfer(String $id)
    {
        $transfer = Transfer::findOrFail($id);
        $id_rek_pengirim = $transfer->id_rekening_pengirim;
        $id_rek_penerima = $transfer->id_rekening_penerima;

        $transfer->delete();
        $this->sinkronisasiSaldo($id_rek_pengirim);
        $this->sinkronisasiSaldo($id_rek_penerima);
        return back()->with('success', 'History transfer berhasil dihapus!');
    }

    // ======================================================
    // ================= HISTORY NASABAH ====================
    // ======================================================

    public function historyNasabah(Request $request)
    {
        $user = Auth::user();
        $teller = $user->petugas;
        $perPage = $request->input('per_page', 10);
        $search = $request->input('search');

        $cleanNum = function ($val) {
            if (!$val) return 0;
            return is_numeric($val) ? (int) $val : (int) preg_replace('/\D/', '', $val);
        };

        // 1. SETORAN
        $querySetoran = Setoran::with('rekening.nasabah');
        if ($search) {
            $querySetoran->whereHas('rekening.nasabah', function ($q) use ($search) {
                $q->where('nama_nasabah', 'like', '%' . $search . '%');
            })->orWhere('id_rekening', 'like', '%' . $search . '%');
        }
        $setoran = $querySetoran->get()->map(function ($item) use ($cleanNum) {
            $potongan = str_contains(strtolower($item->pilihan_biaya_transaksi), 'potong') ? $item->nominal_admin : 0;
            return (object)[
                'id' => $item->id,
                'created_at' => $item->created_at,
                'nama_nasabah' => $item->rekening->nasabah->nama_nasabah ?? '-',
                'no_rek' => $item->id_rekening,
                'jenis_transaksi' => 'Setoran',
                'keterangan' => $item->catatan ?? '-', // Keterangan Setoran
                'admin' => $cleanNum($item->nominal_admin),
                'debit' => 0,
                'kredit' => $cleanNum($item->jumlah_penyetoran) - $cleanNum($potongan),
                'saldo' => $item->saldo_transaksi,
                'original_type' => 'setoran'
            ];
        });

        // 2. PENARIKAN
        $queryPenarikan = Penarikan::with('rekening.nasabah');
        if ($search) {
            $queryPenarikan->whereHas('rekening.nasabah', function ($q) use ($search) {
                $q->where('nama_nasabah', 'like', '%' . $search . '%');
            })->orWhere('id_rekening', 'like', '%' . $search . '%');
        }
        $penarikan = $queryPenarikan->get()->map(function ($item) use ($cleanNum) {
            $potongan = str_contains(strtolower($item->pilihan_biaya_transaksi), 'potong') ? $item->nominal_admin : 0;
            return (object)[
                'id' => $item->id,
                'created_at' => $item->created_at,
                'nama_nasabah' => $item->rekening->nasabah->nama_nasabah ?? '-',
                'no_rek' => $item->id_rekening,
                'jenis_transaksi' => 'Penarikan',
                'keterangan' => '-',
                'admin' => $cleanNum($item->nominal_admin),
                'debit' => $cleanNum($item->jumlah_penarikan) + $cleanNum($potongan),
                'kredit' => 0,
                'saldo' => $item->saldo_transaksi,
                'original_type' => 'penarikan'
            ];
        });

        // 3. TRANSFER LEWAT TELLER
        $transferKeluar = collect();
        $transferMasuk = collect();
        $queryTransfer = Transfer::with(['rekeningPengirim.nasabah', 'rekeningPenerima.nasabah'])->get();

        foreach ($queryTransfer as $t) {
            $potongan = str_contains(strtolower($t->pilihan_biaya_transaksi), 'potong') ? $t->nominal_admin : 0;

            $matchPengirim = !$search || stripos($t->rekeningPengirim->nasabah->nama_nasabah ?? '', $search) !== false || stripos($t->id_rekening_pengirim, $search) !== false;
            if ($matchPengirim) {
                $transferKeluar->push((object)[
                    'id' => $t->id,
                    'created_at' => $t->created_at,
                    'nama_nasabah' => $t->rekeningPengirim->nasabah->nama_nasabah ?? '-',
                    'no_rek' => $t->id_rekening_pengirim,
                    'jenis_transaksi' => 'Transfer Keluar',
                    // KELUAR -> Tampilkan Rek Penerima + Catatan
                    'keterangan' => $t->id_rekening_penerima . ($t->catatan ? ' ' . $t->catatan : ''),
                    'admin' => $cleanNum($t->nominal_admin),
                    'debit' => $cleanNum($t->jumlah_transfer) + $cleanNum($potongan),
                    'kredit' => 0,
                    'saldo' => $t->saldo_transaksi_pengirim,
                    'original_type' => 'transfer_keluar'
                ]);
            }

            $matchPenerima = !$search || stripos($t->rekeningPenerima->nasabah->nama_nasabah ?? '', $search) !== false || stripos($t->id_rekening_penerima, $search) !== false;
            if ($matchPenerima) {
                $transferMasuk->push((object)[
                    'id' => $t->id,
                    'created_at' => $t->created_at,
                    'nama_nasabah' => $t->rekeningPenerima->nasabah->nama_nasabah ?? '-',
                    'no_rek' => $t->id_rekening_penerima,
                    'jenis_transaksi' => 'Transfer Masuk',
                    // MASUK -> Tampilkan Rek Pengirim + Catatan
                    'keterangan' => $t->id_rekening_pengirim . ($t->catatan ? ' ' . $t->catatan : ''),
                    'admin' => 0,
                    'debit' => 0,
                    'kredit' => $cleanNum($t->jumlah_transfer),
                    'saldo' => $t->saldo_transaksi_penerima,
                    'original_type' => 'transfer_masuk'
                ]);
            }
        }

        // 4. TRANSFER ANTAR AKUN (NASABAH)
        $riwayatTfKeluar = collect();
        $riwayatTfMasuk = collect();
        $queryRiwayatTf = \App\Models\RiwayatTf::with(['pengirim.nasabah', 'penerima.nasabah'])->get();

        foreach ($queryRiwayatTf as $t) {
            $potongan = $t->nominal_admin ?? 0;
            $jumlahTf = $cleanNum($t->jumlah_transfer);
            $adminTf  = $cleanNum($potongan);

            $matchPengirim = !$search || stripos($t->pengirim->nasabah->nama_nasabah ?? '', $search) !== false || stripos($t->id_pengirim, $search) !== false;
            if ($matchPengirim) {
                $riwayatTfKeluar->push((object)[
                    'id' => $t->id,
                    'created_at' => $t->created_at ?? Carbon::now(),
                    'nama_nasabah' => $t->pengirim->nasabah->nama_nasabah ?? '-',
                    'no_rek' => $t->id_pengirim,
                    'jenis_transaksi' => 'Transfer Keluar (Nasabah)',
                    // KELUAR -> Tampilkan Rek Penerima
                    'keterangan' => $t->id_penerima,
                    'admin' => $adminTf,
                    'debit' => $jumlahTf + $adminTf,
                    'kredit' => 0,
                    'saldo' => $t->saldo_transaksi_pengirim,
                    'original_type' => 'riwayat_tf_keluar'
                ]);
            }

            $matchPenerima = !$search || stripos($t->penerima->nasabah->nama_nasabah ?? '', $search) !== false || stripos($t->id_penerima, $search) !== false;
            if ($matchPenerima) {
                $riwayatTfMasuk->push((object)[
                    'id' => $t->id,
                    'created_at' => $t->created_at ?? Carbon::now(),
                    'nama_nasabah' => $t->penerima->nasabah->nama_nasabah ?? '-',
                    'no_rek' => $t->id_penerima,
                    'jenis_transaksi' => 'Transfer Masuk (Nasabah)',
                    // MASUK -> Tampilkan Rek Pengirim
                    'keterangan' => $t->id_pengirim,
                    'admin' => 0,
                    'debit' => 0,
                    'kredit' => $jumlahTf,
                    'saldo' => $t->saldo_transaksi_penerima,
                    'original_type' => 'riwayat_tf_masuk'
                ]);
            }
        }

        // 5. TRANSFER DARI LUAR
        $queryBuktiTf = \App\Models\Bukti_Tf::with('buktiTf.nasabah')
            ->whereIn('status_verifikasi', ['berhasil']);

        if ($search) {
            $queryBuktiTf->where(function ($q) use ($search) {
                $q->whereHas('buktiTf.nasabah', function ($sub) use ($search) {
                    $sub->where('nama_nasabah', 'like', '%' . $search . '%');
                })->orWhere('id_rekening', 'like', '%' . $search . '%')
                    ->orWhere('nama_pengirim', 'like', '%' . $search . '%');
            });
        }
        $buktiTf = $queryBuktiTf->get()->map(function ($item) use ($cleanNum) {
            $waktu = $item->created_at ?? $item->datetime_tgl;
            return (object)[
                'id' => $item->id,
                'created_at' => $waktu ? Carbon::parse($waktu) : Carbon::now(),
                'nama_nasabah' => $item->buktiTf->nasabah->nama_nasabah ?? '-',
                'no_rek' => $item->id_rekening,
                'jenis_transaksi' => 'Transfer Dari Luar',
                'keterangan' => ($item->nama_pengirim ?? '-'),
                'admin' => $cleanNum($item->nominal_admin),
                'debit' => 0,
                'kredit' => $cleanNum($item->jumlah_transfer),
                'saldo' => $item->saldo_transaksi,
                'original_type' => 'bukti_tf'
            ];
        });

        // 6. GABUNGKAN DATA
        $allData = collect()->concat($setoran)
            ->concat($penarikan)
            ->concat($transferKeluar)
            ->concat($transferMasuk)
            ->concat($riwayatTfKeluar)
            ->concat($riwayatTfMasuk)
            ->concat($buktiTf)
            ->sortByDesc('created_at')
            ->values();

        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $currentPageItems = $allData->slice(($currentPage - 1) * $perPage, $perPage)->all();

        $data = new LengthAwarePaginator(
            $currentPageItems,
            $allData->count(),
            $perPage,
            $currentPage,
            [
                'path' => LengthAwarePaginator::resolveCurrentPath(),
                'query' => $request->query()
            ]
        );

        return view('teller.history_nasabah', compact('user', 'teller', 'data', 'perPage'));
    }

    public function sinkronisasiSaldo(String $id_rekening)
    {
        $cleanNum = function ($val) {
            if (!$val) return 0;
            return is_numeric($val) ? (int) $val : (int) preg_replace('/\D/', '', $val);
        };

        $setoran = \App\Models\Setoran::where('id_rekening', $id_rekening)->get()->map(function ($item) use ($cleanNum) {
            $potongan = str_contains(strtolower($item->pilihan_biaya_transaksi), 'potong') ? $item->nominal_admin : 0;
            return (object)[
                'type'   => 'setoran',
                'model'  => $item,
                'waktu'  => $item->created_at,
                'debit'  => 0,
                'kredit' => $cleanNum($item->jumlah_penyetoran) - $cleanNum($potongan)
            ];
        });

        $penarikan = \App\Models\Penarikan::where('id_rekening', $id_rekening)->get()->map(function ($item) use ($cleanNum) {
            $potongan = str_contains(strtolower($item->pilihan_biaya_transaksi), 'potong') ? $item->nominal_admin : 0;
            return (object)[
                'type'   => 'penarikan',
                'model'  => $item,
                'waktu'  => $item->created_at,
                'debit'  => $cleanNum($item->jumlah_penarikan) + $cleanNum($potongan),
                'kredit' => 0
            ];
        });

        $transferKeluar = \App\Models\Transfer::where('id_rekening_pengirim', $id_rekening)->get()->map(function ($item) use ($cleanNum) {
            $potongan = str_contains(strtolower($item->pilihan_biaya_transaksi), 'potong') ? $item->nominal_admin : 0;
            return (object)[
                'type'   => 'transfer_keluar',
                'model'  => $item,
                'waktu'  => $item->created_at,
                'debit'  => $cleanNum($item->jumlah_transfer) + $cleanNum($potongan),
                'kredit' => 0
            ];
        });

        $transferMasuk = \App\Models\Transfer::where('id_rekening_penerima', $id_rekening)->get()->map(function ($item) use ($cleanNum) {
            return (object)[
                'type'   => 'transfer_masuk',
                'model'  => $item,
                'waktu'  => $item->created_at,
                'debit'  => 0,
                'kredit' => $cleanNum($item->jumlah_transfer)
            ];
        });

        $riwayatTfKeluar = \App\Models\RiwayatTf::where('id_pengirim', $id_rekening)->get()->map(function ($item) use ($cleanNum) {
            return (object)[
                'type'   => 'riwayat_tf_keluar',
                'model'  => $item,
                'waktu'  => $item->created_at ?? Carbon::now(),
                'debit'  => $cleanNum($item->jumlah_transfer) + $cleanNum($item->nominal_admin),
                'kredit' => 0
            ];
        });

        $riwayatTfMasuk = \App\Models\RiwayatTf::where('id_penerima', $id_rekening)->get()->map(function ($item) use ($cleanNum) {
            return (object)[
                'type'   => 'riwayat_tf_masuk',
                'model'  => $item,
                'waktu'  => $item->created_at ?? Carbon::now(),
                'debit'  => 0,
                'kredit' => $cleanNum($item->jumlah_transfer)
            ];
        });

        $buktiTf = \App\Models\Bukti_Tf::where('id_rekening', $id_rekening)
            ->whereIn('status_verifikasi', ['berhasil'])->get()->map(function ($item) use ($cleanNum) {
                $waktu = $item->created_at ?? $item->datetime_tgl;
                return (object)[
                    'type'   => 'bukti_tf',
                    'model'  => $item,
                    'waktu'  => $waktu ? Carbon::parse($waktu) : Carbon::now(),
                    'debit'  => 0,
                    'kredit' => $cleanNum($item->jumlah_transfer)
                ];
            });

        $semuaTransaksi = collect()
            ->concat($setoran)->concat($penarikan)->concat($transferKeluar)
            ->concat($transferMasuk)->concat($riwayatTfKeluar)->concat($riwayatTfMasuk)->concat($buktiTf);

        $sorted = $semuaTransaksi->sortBy([
            ['waktu', 'asc'],
            ['model.id', 'asc'],
        ]);

        \Illuminate\Support\Facades\DB::beginTransaction();
        try {
            // Ambil data rekening untuk mengecek saldo awal (jika ada kolom saldo_awal)
            $rek = \App\Models\Rekening::find($id_rekening);
            $saldoBerjalan = $rek->saldo_awal ?? 0; // <-- PERBAIKAN: Mulai dari saldo_awal, bukan 0

            foreach ($sorted as $tx) {
                $saldoBerjalan -= $tx->debit;
                $saldoBerjalan += $tx->kredit;

                if ($tx->type === 'setoran' || $tx->type === 'penarikan') {
                    \Illuminate\Support\Facades\DB::table($tx->type)->where('id', $tx->model->id)->update(['saldo_transaksi' => $saldoBerjalan]);
                } elseif ($tx->type === 'transfer_keluar') {
                    \Illuminate\Support\Facades\DB::table('transfer')->where('id', $tx->model->id)->update(['saldo_transaksi_pengirim' => $saldoBerjalan]);
                } elseif ($tx->type === 'transfer_masuk') {
                    \Illuminate\Support\Facades\DB::table('transfer')->where('id', $tx->model->id)->update(['saldo_transaksi_penerima' => $saldoBerjalan]);
                } elseif ($tx->type === 'riwayat_tf_keluar') {
                    \Illuminate\Support\Facades\DB::table('riwayat_tf')->where('id', $tx->model->id)->update(['saldo_transaksi_pengirim' => $saldoBerjalan]);
                } elseif ($tx->type === 'riwayat_tf_masuk') {
                    \Illuminate\Support\Facades\DB::table('riwayat_tf')->where('id', $tx->model->id)->update(['saldo_transaksi_penerima' => $saldoBerjalan]);
                } elseif ($tx->type === 'bukti_tf') {
                    \Illuminate\Support\Facades\DB::table('bukti_tf')->where('id', $tx->model->id)->update(['saldo_transaksi' => $saldoBerjalan]);
                }
            }

            \Illuminate\Support\Facades\DB::table('rekening')->where('id', $id_rekening)->update(['saldo_saat_ini' => $saldoBerjalan]);
            \Illuminate\Support\Facades\DB::commit();
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            throw $e;
        }
    }

    public function cetakBuku(Request $request, String $id_rekening)
    {
        $mulai_baris = $request->query('baris', 1);
        $is_buku_baru = $request->query('buku_baru', 0); // <--- Tangkap parameter buku_baru

        $rekening = Rekening::with('nasabah')
            ->where('id', $id_rekening)
            ->orWhereHas('nasabah', function ($q) use ($id_rekening) {
                $q->where('nis_nip', $id_rekening);
            })
            ->firstOrFail();

        $id_rekening = $rekening->id;

        $cleanNum = function ($val) {
            if (!$val) return 0;
            return is_numeric($val) ? (int) $val : (int) preg_replace('/\D/', '', $val);
        };

        // 1. TARIK SEMUA DATA TRANSAKSI
        $setoran = Setoran::where('id_rekening', $id_rekening)->get()->map(function ($item) use ($cleanNum) {
            $potongan = str_contains(strtolower($item->pilihan_biaya_transaksi), 'potong') ? $item->nominal_admin : 0;
            return (object)[
                'tanggal'     => $item->created_at,
                'jenis'       => 'ST',
                'biaya_admin' => $cleanNum($item->nominal_admin),
                'debit'       => 0,
                'kredit'      => $cleanNum($item->jumlah_penyetoran) - $cleanNum($potongan),
                'saldo'       => $item->saldo_transaksi
            ];
        });

        $penarikan = Penarikan::where('id_rekening', $id_rekening)->get()->map(function ($item) use ($cleanNum) {
            $potongan = str_contains(strtolower($item->pilihan_biaya_transaksi), 'potong') ? $item->nominal_admin : 0;
            return (object)[
                'tanggal'     => $item->created_at,
                'jenis'       => 'TT',
                'biaya_admin' => $cleanNum($item->nominal_admin),
                'debit'       => $cleanNum($item->jumlah_penarikan) + $cleanNum($potongan),
                'kredit'      => 0,
                'saldo'       => $item->saldo_transaksi
            ];
        });

        $transferKeluar = Transfer::with('rekeningPenerima.nasabah')
            ->where('id_rekening_pengirim', $id_rekening)
            ->get()->map(function ($t) use ($cleanNum) {
                $potongan = str_contains(strtolower($t->pilihan_biaya_transaksi), 'potong') ? $t->nominal_admin : 0;
                return (object)[
                    'tanggal'     => $t->created_at,
                    'jenis'       => 'TFK',
                    'keterangan'  => $t->id_rekening_penerima . ' ' . ($t->rekeningPenerima->nasabah->nama_nasabah ?? '-'),
                    'biaya_admin' => $cleanNum($t->nominal_admin),
                    'debit'       => $cleanNum($t->jumlah_transfer) + $potongan,
                    'kredit'      => 0,
                    'saldo'       => $t->saldo_transaksi_pengirim
                ];
            });

        $transferMasuk = Transfer::with('rekeningPengirim.nasabah')
            ->where('id_rekening_penerima', $id_rekening)
            ->get()->map(function ($t) use ($cleanNum) {
                return (object)[
                    'tanggal'     => $t->created_at,
                    'jenis'       => 'TFM',
                    'keterangan'  => $t->id_rekening_pengirim . ' ' . ($t->rekeningPengirim->nasabah->nama_nasabah ?? '-'),
                    'biaya_admin' => 0,
                    'debit'       => 0,
                    'kredit'      => $cleanNum($t->jumlah_transfer),
                    'saldo'       => $t->saldo_transaksi_penerima
                ];
            });

        $transferKeluarNasabah = RiwayatTf::where('id_pengirim', $id_rekening)->get()->map(function ($t) use ($cleanNum) {
            $potongan = $cleanNum($t->nominal_admin);
            return (object)[
                'tanggal'     => $t->created_at ?? Carbon::now(),
                'jenis'       => 'TFK',
                'keterangan'  => $t->id_penerima . ' ' . $t->nama_penerima,
                'biaya_admin' => $potongan,
                'debit'       => $cleanNum($t->jumlah_transfer) + $potongan,
                'kredit'      => 0,
                'saldo'       => $t->saldo_transaksi_pengirim
            ];
        });

        $transferMasukNasabah = RiwayatTf::where('id_penerima', $id_rekening)->get()->map(function ($t) use ($cleanNum) {
            return (object)[
                'tanggal'     => $t->created_at ?? Carbon::now(),
                'jenis'       => 'TFM',
                'keterangan'  => $t->id_pengirim . ' ' . ($t->pengirim->nasabah->nama_nasabah ?? '-'),
                'biaya_admin' => 0,
                'debit'       => 0,
                'kredit'      => $cleanNum($t->jumlah_transfer),
                'saldo'       => $t->saldo_transaksi_penerima
            ];
        });

        $transferDariLuar = Bukti_Tf::where('id_rekening', $id_rekening)
            ->whereIn('status_verifikasi', ['berhasil'])
            ->get()
            ->map(function ($t) use ($cleanNum) {
                $waktu = $t->created_at ?? $t->datetime_tgl;
                return (object)[
                    'tanggal'     => $waktu ? Carbon::parse($waktu) : Carbon::now(),
                    'jenis'       => 'TFL',
                    'keterangan'  => ($t->nama_pengirim ?? '-'),
                    'biaya_admin' => $cleanNum($t->nominal_admin),
                    'debit'       => 0,
                    'kredit'      => $cleanNum($t->jumlah_transfer),
                    'saldo'       => $t->saldo_transaksi
                ];
            });

        // Urutkan seluruh transaksi berdasarkan tanggal ascending
        $allTransaksi = collect()->concat($setoran)->concat($penarikan)
            ->concat($transferKeluar)->concat($transferMasuk)
            ->concat($transferMasukNasabah)->concat($transferKeluarNasabah)->concat($transferDariLuar)
            ->sortBy('tanggal')->values();


        // 2. LOGIKA BUKU BARU / BUKU HILANG
        if ($is_buku_baru == 1 && $allTransaksi->isNotEmpty()) {
            // Ambil transaksi paling baru saat buku dinyatakan hilang
            $lastTx = $allTransaksi->last();

            // Simpan timestamp/ID batas transaksi buku lama ke database
            $rekening->update([
                'buku_baru_transaksi_id' => Carbon::parse($lastTx->tanggal)->timestamp
            ]);

            // Refresh model rekening agar mendapatkan data terbaru
            $rekening->refresh();
        }

        // 3. FILTER TRANSAKSI BERDASARKAN BATAS BUKU HILANG (JIKA ADA)
        if ($rekening->buku_baru_transaksi_id) {
            $batasTimestamp = $rekening->buku_baru_transaksi_id;

            // Cari transaksi terakhir yang terjadi SEBELUM / PAS batas buku hilang
            $lastOldTx = $allTransaksi->filter(function ($tx) use ($batasTimestamp) {
                return Carbon::parse($tx->tanggal)->timestamp <= $batasTimestamp;
            })->last();

            // Saldo yang dibawa ke buku baru
            $saldoPindahan = $lastOldTx ? $lastOldTx->saldo : 0;

            // Ambil transaksi yang terjadi SETELAH batas buku hilang
            $transaksiBaru = $allTransaksi->filter(function ($tx) use ($batasTimestamp) {
                return Carbon::parse($tx->tanggal)->timestamp > $batasTimestamp;
            })->values();

            // Buat 1 baris dummy penyesuaian Saldo Pindahan untuk dicetak di Baris 1
            $barisSaldoPindahan = (object)[
                'tanggal'     => Carbon::createFromTimestamp($batasTimestamp),
                'jenis'       => 'SP',
                'keterangan'  => 'PINDAHAN BUKU LAMA',
                'biaya_admin' => 0,
                'debit'       => 0,
                'kredit'      => 0,
                'saldo'       => $saldoPindahan
            ];

            // Gabungkan baris Saldo Pindahan di urutan paling awal
            $transaksi = collect([$barisSaldoPindahan])->concat($transaksiBaru);
        } else {
            $transaksi = $allTransaksi;
        }

        return view('teller.cetak_buku', compact('rekening', 'transaksi', 'mulai_baris'));
    }

    public function cetakBiodataBuku($id_rekening)
    {
        // Mengambil data rekening beserta data relasi nasabah 
        $rekening = Rekening::with('nasabah')
            ->where('id', $id_rekening)
            ->orWhereHas('nasabah', function ($q) use ($id_rekening) {
                $q->where('nis_nip', $id_rekening);
            })
            ->firstOrFail();

        // Mengembalikan view untuk format cetak biodata di buku tabungan
        return view('teller.cetak_biodata_buku', compact('rekening'));
    }
    public function cetakKoran(Request $request, String $id_rekening)
    {
        // 1. Tangkap parameter tanggal dari URL
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');

        if (!$startDate || !$endDate) {
            return abort(400, 'Rentang tanggal awal dan akhir harus diisi!');
        }

        // Setup format tanggal dari jam 00:00 sampai 23:59
        $start = $startDate . ' 00:00:00';
        $end = $endDate . ' 23:59:59';

        // 2. Ambil data rekening
        $rekening = Rekening::with('nasabah')
            ->where('id', $id_rekening)
            ->orWhereHas('nasabah', function ($q) use ($id_rekening) {
                $q->where('nis_nip', $id_rekening);
            })
            ->firstOrFail();

        $id_rekening = $rekening->id;

        $cleanNum = function ($val) {
            if (!$val) return 0;
            return is_numeric($val) ? (int) $val : (int) preg_replace('/\D/', '', $val);
        };

        // 3. Tarik data dari semua tabel transaksi DENGAN FILTER TANGGAL

        $setoran = Setoran::where('id_rekening', $id_rekening)
            ->whereBetween('created_at', [$start, $end])
            ->get()->map(function ($item) use ($cleanNum) {
                $potongan = str_contains(strtolower($item->pilihan_biaya_transaksi), 'potong') ? $item->nominal_admin : 0;
                return (object)[
                    'tanggal'     => $item->created_at ? \Carbon\Carbon::parse($item->created_at) : \Carbon\Carbon::now(),
                    'jenis'       => 'ST',
                    'biaya_admin' => $cleanNum($item->nominal_admin),
                    'debit'       => 0,
                    'kredit'      => $cleanNum($item->jumlah_penyetoran) - $cleanNum($potongan),
                    'saldo'       => $item->saldo_transaksi
                ];
            });

        $penarikan = Penarikan::where('id_rekening', $id_rekening)
            ->whereBetween('created_at', [$start, $end])
            ->get()->map(function ($item) use ($cleanNum) {
                $potongan = str_contains(strtolower($item->pilihan_biaya_transaksi), 'potong') ? $item->nominal_admin : 0;
                return (object)[
                    'tanggal'     => $item->created_at ? \Carbon\Carbon::parse($item->created_at) : \Carbon\Carbon::now(),
                    'jenis'       => 'TT',
                    'biaya_admin' => $cleanNum($item->nominal_admin),
                    'debit'       => $cleanNum($item->jumlah_penarikan) + $cleanNum($potongan),
                    'kredit'      => 0,
                    'saldo'       => $item->saldo_transaksi
                ];
            });

        $transferKeluar = Transfer::with('rekeningPenerima.nasabah')
            ->where('id_rekening_pengirim', $id_rekening)
            ->whereBetween('created_at', [$start, $end])
            ->get()->map(function ($t) use ($cleanNum) {
                $potongan = str_contains(strtolower($t->pilihan_biaya_transaksi), 'potong') ? $t->nominal_admin : 0;
                return (object)[
                    'tanggal'     => $t->created_at ? \Carbon\Carbon::parse($t->created_at) : \Carbon\Carbon::now(),
                    'jenis'       => 'TFK',
                    'keterangan'  => $t->id_rekening_penerima . ' ' .  ($t->rekeningPenerima->nasabah->nama_nasabah ?? '-'),
                    'biaya_admin' => $cleanNum($t->nominal_admin),
                    'debit'       => $cleanNum($t->jumlah_transfer) + $cleanNum($potongan),
                    'kredit'      => 0,
                    'saldo'       => $t->saldo_transaksi_pengirim
                ];
            });

        $transferMasuk = Transfer::with('rekeningPengirim.nasabah')
            ->where('id_rekening_penerima', $id_rekening)
            ->whereBetween('created_at', [$start, $end])
            ->get()->map(function ($t) use ($cleanNum) {
                return (object)[
                    'tanggal'     => $t->created_at ? \Carbon\Carbon::parse($t->created_at) : \Carbon\Carbon::now(),
                    'jenis'       => 'TFM',
                    'keterangan'  => $t->id_rekening_pengirim . ' ' . ($t->rekeningPengirim->nasabah->nama_nasabah ?? '-'),
                    'biaya_admin' => 0,
                    'debit'       => 0,
                    'kredit'      => $cleanNum($t->jumlah_transfer),
                    'saldo'       => $t->saldo_transaksi_penerima
                ];
            });

        $transferKeluarNasabah = RiwayatTf::where('id_pengirim', $id_rekening)
            ->whereBetween('created_at', [$start, $end])
            ->get()->map(function ($t) use ($cleanNum) {
                $potongan = $cleanNum($t->nominal_admin);
                return (object)[
                    'tanggal'     => $t->created_at ? \Carbon\Carbon::parse($t->created_at) : \Carbon\Carbon::now(),
                    'jenis'       => 'TFK',
                    'keterangan'  => $t->id_penerima . ' ' . $t->nama_penerima,
                    'biaya_admin' => $potongan,
                    'debit'       => $cleanNum($t->jumlah_transfer) + $potongan,
                    'kredit'      => 0,
                    'saldo'       => $t->saldo_transaksi_pengirim
                ];
            });

        $transferMasukNasabah = RiwayatTf::where('id_penerima', $id_rekening)
            ->whereBetween('created_at', [$start, $end])
            ->get()->map(function ($t) use ($cleanNum) {
                return (object)[
                    'tanggal'     => $t->created_at ? \Carbon\Carbon::parse($t->created_at) : \Carbon\Carbon::now(),
                    'jenis'       => 'TFM',
                    'keterangan'  => $t->id_pengirim . ' ' . ($t->pengirim->nasabah->nama_nasabah ?? '-'),
                    'biaya_admin' => 0,
                    'debit'       => 0,
                    'kredit'      => $cleanNum($t->jumlah_transfer),
                    'saldo'       => $t->saldo_transaksi_penerima
                ];
            });

        $transferDariLuar = Bukti_Tf::where('id_rekening', $id_rekening)
            ->whereIn('status_verifikasi', ['berhasil'])
            ->where(function ($query) use ($start, $end) {
                $query->whereBetween('created_at', [$start, $end])
                    ->orWhereBetween('datetime_tgl', [$start, $end]);
            })
            ->get()
            ->map(function ($t) use ($cleanNum) {
                $waktu = $t->created_at ?? $t->datetime_tgl;
                return (object)[
                    'tanggal'     => $waktu ? \Carbon\Carbon::parse($waktu) : \Carbon\Carbon::now(),
                    'jenis'       => 'TFL',
                    'keterangan'  => ($t->nama_pengirim ?? '-'),
                    'biaya_admin' => $cleanNum($t->nominal_admin),
                    'debit'       => 0,
                    'kredit'      => $cleanNum($t->jumlah_transfer),
                    'saldo'       => $t->saldo_transaksi
                ];
            });

        // 4. Gabungin semua dan lempar ke view Cetak Koran
        $transaksi = collect()->concat($setoran)->concat($penarikan)
            ->concat($transferKeluar)->concat($transferMasuk)
            ->concat($transferMasukNasabah)->concat($transferKeluarNasabah)->concat($transferDariLuar)
            ->sortBy('tanggal')->values();

        return view('teller.cetak_koran', compact('rekening', 'transaksi', 'startDate', 'endDate'));
    }
}
