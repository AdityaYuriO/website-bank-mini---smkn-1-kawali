<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Bukti_Tf;
use App\Models\Nasabah;
use App\Models\RiwayatTf;

class AdminController extends Controller
{
    public function dashboardSupervisor()
    {
        // $user = Auth::user();
        // $super = $user->petugas;

        // Admin Transaksi
        $adminTotal = Bukti_Tf::where('status_verifikasi', 'berhasil')->sum('nominal_admin');
        $adminAntarNasabah = RiwayatTf::get()->sum('nominal_admin');

        // Total Nasabah
        $totalNasabah = User::where('role_id', 1)->get()->count();
        $nasabahTf = Bukti_Tf::latest()->get();
        $nasabahTfPending = Bukti_Tf::where('status_verifikasi', 'pending')->get();
        $totalSaldoTabungan = Bukti_Tf::where('status_verifikasi', 'berhasil')->sum('jumlah_transfer');
        $nasabahPending = Nasabah::with('rekening')
            ->whereHas('rekening', function ($query) {
                $query->where('status_akun', 'non-aktif');
            })->orderByDesc('id')->get();
        $totalPendingRegistrasi = $nasabahPending->count();
        $totalPendingTransfer = $nasabahTfPending->count();
        $totalPending = $totalPendingRegistrasi + $totalPendingTransfer;

        return view('admin.supervisor.dashboard', compact('totalNasabah', 'nasabahTfPending', 'nasabahPending', 'totalPendingRegistrasi', 'totalPendingTransfer', 'totalPending', 'totalSaldoTabungan', 'adminTotal', 'adminAntarNasabah','nasabahTf'));
    }

    public function verifikasiTFF(Request $request){
        $perPage = $request->input('per_page', 10);
        $keyword = $request->keyword;
        $bukti_tf = Bukti_Tf::when($keyword, function ($query, $keyword) {
            return $query->where('nama_penerima', 'LIKE', '%' . $keyword . '%')
                ->orWhere('nama_pengirim', 'like', '%' . $keyword . '%')
                ->orWhere('id_rekening', 'like', '%' . $keyword . '%');
        })->latest()->paginate($perPage)
            ->appends(['per_page' => $perPage, 'keyword' => $keyword]);

            return view('admin.supervisor.verifikasi.transfer', compact('bukti_tf'));
    }
}
