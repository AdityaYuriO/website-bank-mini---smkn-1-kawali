<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Bukti_Tf;
use App\Models\Rekening;
use App\Exports\BuktiTfExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Nasabah;
use App\Models\VerifikasiLogin;
use Carbon\Carbon;
use App\Models\Transaksi;
use App\Models\Petugas;
use App\Models\RiwayatTf;
use App\Models\Minimum_saldo;
use App\Models\data_siswa;
use App\Models\Provinsi;
use App\Models\Kabupaten;
use App\Models\Kecamatan;
use App\Models\Desa;
use App\Models\data_guru;
use App\Models\data_tendik;



class AdminSupervisorController extends Controller
{
    public function dashboardSupervisor()
    {
        $user = Auth::user();
        $super = $user->petugas ?? 'test';

        // Admin Transaksi
        $adminTotal = Bukti_Tf::where('status_verifikasi', 'berhasil')->sum('nominal_admin');
        $adminAntarNasabah = RiwayatTf::get()->sum('nominal_admin');

        // Total Nasabah
        $totalNasabah = User::where('role_id', 1)->get()->count();
        $nasabahTf = Bukti_Tf::latest()->get();
        $nasabahTfPending = Bukti_Tf::where('status_verifikasi', 'pending')->get();
        $totalSaldoTabungan = Rekening::where('status_akun', 'aktif')->sum('saldo_saat_ini');
        $nasabahPending = Nasabah::with('rekening')
            ->whereHas('rekening', function ($query) {
                $query->where('status_akun', 'non-aktif');
            })->orderByDesc('id')->get();
        $totalPendingRegistrasi = $nasabahPending->count();
        $totalPendingTransfer = $nasabahTfPending->count();
        $totalPending = $totalPendingRegistrasi + $totalPendingTransfer;
        return view('admin.supervisor.dashboard', compact('user', 'super', 'adminTotal', 'adminAntarNasabah', 'totalNasabah', 'nasabahTf', 'nasabahTfPending', 'totalSaldoTabungan', 'nasabahPending', 'totalPendingRegistrasi', 'totalPendingTransfer', 'totalPending'));
    }

    public function verifikasiTransferSupervisor(Request $request)
    {
        $user = Auth::user();
        $super = $user->petugas ?? 'test';

        $perPage = $request->input('per_page', 10);
        $keyword = $request->keyword;
        $bukti_tf = Bukti_Tf::when($keyword, function ($query, $keyword) {
            return $query->where('nama_penerima', 'LIKE', '%' . $keyword . '%')
                ->orWhere('nama_pengirim', 'like', '%' . $keyword . '%')
                ->orWhere('id_rekening', 'like', '%' . $keyword . '%');
        })->latest()->paginate($perPage)
            ->appends(['per_page' => $perPage, 'keyword' => $keyword]);

        return view('admin.supervisor.verifikasi.transfer', compact('bukti_tf', 'user', 'super', 'keyword', 'perPage'));
    }

    public function searchDataSupervisor(Request $request)
    {

        $user = Auth::user();
        $super = $user->petugas ?? 'test';

        $perPage = $request->input('per_page', 10);
        $keyword = $request->keyword;
        $bukti_tf = Bukti_Tf::when($keyword, function ($query, $keyword) {
            return $query->where('nama_penerima', 'LIKE', '%' . $keyword . '%')
                ->orWhere('nama_pengirim', 'like', '%' . $keyword . '%')
                ->orWhere('id_rekening', 'like', '%' . $keyword . '%');
        })->latest()->paginate($perPage)
            ->appends(['per_page' => $perPage, 'keyword' => $keyword]);

        return view('admin.supervisor.verifikasi.transfer', compact('bukti_tf', 'user', 'super', 'keyword', 'perPage'));
    }

    public function exportExcelSupervisor(Request $request)
    {
        // Tangkap input filter tanggal dari form
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        // Kirim tanggal tersebut ke dalam Constructor class BuktiTfExport
        return Excel::download(new BuktiTfExport($startDate, $endDate), 'riwayat-transfer.xlsx');
    }

    public function detailTfSupervisor(String $id)
    {
        $data = Bukti_Tf::find($id);

        return view('admin.supervisor.verifikasi.transfer.detail', compact('data'));
    }

    public function verifikasiTf(Request $request, String $id)
    {
        $data = Bukti_Tf::findOrFail($id);

        if ($data->status_verifikasi !== 'pending') {
            return redirect()->back()->with('error', 'Transaksi ini sudah diproses sebelumnya.');
        }

        $request->validate([
            'status_verifikasi' => 'required|in:berhasil,gagal'
        ]);

        try {
            DB::transaction(function () use ($request, $data) {

                if ($request->status_verifikasi === 'berhasil') {
                    $rekening = Rekening::where('id', $data->id_rekening)->first();

                    if (!$rekening) {
                        throw new \Exception('Nomor rekening tujuan tidak ditemukan.');
                    }

                    // 1. Tambahkan saldo
                    $rekening->increment('saldo_saat_ini', $data->jumlah_transfer);

                    // 2. Update status verifikasi
                    $data->status_verifikasi = 'berhasil';
                    $data->save();

                    // 3. PANGGIL SINKRONISASI agar saldo transaksi di history terisi otomatis
                    // Pastikan fungsi ini sudah PUBLIC di tellerController.php
                    $teller = new \App\Http\Controllers\tellerController();
                    $teller->sinkronisasiSaldo($rekening->id);
                } else {
                    $data->status_verifikasi = 'gagal';
                    $data->save();
                }
            });

            return redirect()->back()->with('success', 'Transaksi berhasil diproses!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memproses verifikasi: ' . $e->getMessage());
        }
    }

    public function verifikasiLogin(Request $request)
    {
        $user = Auth::user();
        $perPage = $request->input('per_page', 10);
        $keyword = $request->keyword;

        // Tambahkan 'user.role' dan 'user.role2' pada method with()
        $data = VerifikasiLogin::with(['user.role', 'user.role2'])
            ->when($keyword, function ($query, $keyword) {
                $query->whereHas('user', function ($q) use ($keyword) {
                    $q->where('name', 'like', '%' . $keyword . '%')
                        ->orWhere('email', 'like', '%' . $keyword . '%');
                });
            })
            ->latest()
            ->paginate($perPage)
            ->appends([
                'per_page' => $perPage,
                'keyword' => $keyword
            ]);

        return view(
            'admin.supervisor.verifikasi.login',
            compact('user', 'data', 'perPage', 'keyword')
        );
    }

    public function setujuiLogin(String $id)
    {
        VerifikasiLogin::findOrFail($id)
            ->update([
                'status' => 'disetujui',
                'supervisor_id' => Auth::id(),
                'waktu_verifikasi' => Carbon::now()
            ]);

        return back()->with('success', 'Login disetujui');
    }

    public function tolakLogin(String $id)
    {
        VerifikasiLogin::findOrFail($id)
            ->update([
                'status' => 'ditolak',
                'supervisor_id' => Auth::id(),
                'waktu_verifikasi' => Carbon::now()
            ]);

        return back()->with('success', 'Login ditolak');
    }

    public function destroyAllLogin()
    {
        VerifikasiLogin::truncate(); // atau ::query()->delete(); jika ada constraint foreign key

        return redirect()->back()->with('success', 'Semua data verifikasi login berhasil dihapus.');
    }
}
