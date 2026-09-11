<?php

namespace App\Http\Controllers;

use App\Models\data_siswa;
use App\Models\data_guru;
use App\Models\data_tendik;

use Illuminate\Http\Request;

class siswaController extends Controller
{
    public function getData($nomor)
    {
        // Cari berdasarkan NIS
        $siswa = data_siswa::where('nis', $nomor)->first();

        if ($siswa) {
            return response()->json([
                'status' => true,
                'jenis' => 'siswa',
                'data' => $siswa
            ]);
        }

        // Cari berdasarkan NIP
        $guru = data_guru::where('nip', $nomor)->first();

        if ($guru) {
            return response()->json([
                'status' => true,
                'jenis' => 'guru',
                'data' => $guru
            ]);
        }

        // Cari berdasarkan NIK
        $tendik = data_tendik::where('nip', $nomor)->first();

        if ($tendik) {
            return response()->json([
                'status' => true,
                'jenis' => 'tendik',
                'data' => $tendik
            ]);
        }

        // Tidak ditemukan
        return response()->json([
            'status' => false,
            'message' => 'Data tidak ditemukan'
        ]);
    }
}
