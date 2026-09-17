<?php

namespace App\Http\Controllers;
use App\Models\Nasabah;
use App\Models\User;
use App\Models\Rekening;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Imports\NasabahImport;
use Maatwebsite\Excel\Facades\Excel;

class rekeningController extends Controller
{

    public function keloladata(Request $request) {
        $user = Auth::user();
        $cs = $user->petugas;
        $perPage = $request->input('per_page', 10);
        $provinsi = DB::table('provinsi')->get();
        $allNasabah = Nasabah::with('rekening', 'jurusan')
            ->orderByDesc('id')
            ->paginate($perPage)
            ->appends(['per_page' => $perPage]);

        return view('costumerservice.keloladata', compact('user', 'cs', 'provinsi', 'allNasabah', 'perPage'));
    }


    public function store(Request $request) {
        $request->validate([
            'nama_lengkap' => 'required',
            'nis_nip' => 'required',
            'password' => 'required',
            'jurusan' => 'required',
            'tempat_lahir' => 'required',
            'tanggal_lahir' => 'required',
            'jenis_kelamin' => 'required',
            'jenis_identitas' => 'required',
            'agama' => 'required',
            'pendidikan' => 'required',
            'jabatan' => 'required',
            'no_hp' => 'required',
            'email' => 'required',
            'alamat' => 'required',
            'kelurahan' => 'required',
            'kecamatan' => 'required',
            'kab_kota' => 'required',
            'provinsi' => 'required',
            'kode_pos' => 'required',
            'nama_kontak_darurat' => 'required',
            'nomor_kontak_darurat' => 'required',
            'hubungan_kontak_darurat' => 'required',
            'alamat_kontak_darurat' => 'required',
            // 'no_rekening' => 'required',
        ]);

        $dataEmail = Nasabah::where('email', $request->email)->first();
        $nis_nip = Nasabah::where('nis_nip', $request->nis_nip)->first();


        // $roleNasabah = Role::where('nama_role', 'nasabah')->first();
    if (!$nis_nip) {
        if (!$dataEmail) {

                        $userNasabah = User::create([
                            'name' => $request->nama_lengkap,
                            'role_id' => 1,
                            'password' => Hash::make($request->password),
                            'email' => $request->email,
                        ]);

                        $dataNasabah = Nasabah::create([
                            'user_id' => $userNasabah->id,
                            'nis_nip' => $request->nis_nip,
                            'nama_nasabah' => $request->nama_lengkap,
                            'tempat_lahir' => $request->tempat_lahir,
                            'tanggal_lahir' => $request->tanggal_lahir,
                            'jurusan_id' => $request->jurusan,
                            'jenis_kelamin' => $request->jenis_kelamin,
                            'pendidikan' => $request->pendidikan,
                            'alamat' => $request->alamat,
                            'kelurahan_id' => $request->kelurahan,
                            'kecamatan_id' => $request->kecamatan,
                            'kab_kota_id' => $request->kab_kota,
                            'provinsi_id' => $request->provinsi,
                            'kode_pos' => $request->kode_pos,
                            'email' => $request->email,
                            'agama' => $request->agama,
                            'no_hp' => $request->no_hp,
                            'password' => Hash::make($request->password),
                            'jabatan' => $request->jabatan,
                            'jenis_identitas' => $request->jenis_identitas,
                            'nama_kontak_darurat' => $request->nama_kontak_darurat,
                            'alamat_kontak_darurat' => $request->alamat_kontak_darurat,
                            'no_hp_kontak_darurat' => $request->nomor_kontak_darurat,
                            'hubungan_kontak_darurat' => $request->hubungan_kontak_darurat,
                            'pesan' => 'belum ada pesan',
                            'nama_perevisi' => 'belum ada perevisi',
                        ]);



                        if ( $request->jabatan == 'Siswa' ) {
                            $no_rekening = '03' . $request->jurusan . $request->nis_nip;
                        }

                        if ( $request->jabatan == 'Guru' ) {
                            $tanggal = Carbon::parse($request->tanggal_lahir)->format('Ymd');
                            $urutan = Nasabah::where('jabatan', 'Guru')->count() + 1;
                            $no_rekening = '01' . $tanggal . $urutan;
                        }

                        if ( $request->jabatan == 'TU' ) {
                            $tanggal = Carbon::parse($request->tanggal_lahir)->format('Ymd');
                            $urutan = Nasabah::where('jabatan', 'TU')->count() + 1;
                            $no_rekening = '02' . $tanggal . $urutan;
                        }

                        Rekening::create([
                            'id' => $no_rekening,
                            'nasabah_id' => $dataNasabah->id,
                            'saldo_saat_ini' => 0,
                            'status_akun' => 'non-aktif',
                        ]);

                        return redirect()->route('costumerservice.keloladata')->with('success','Data Rekening berhasil ditambah');
        } else {
            return back()->with('failed','Email Sudah Terdaftar');
        }
    } else {
        return back()->with('failed','NIS/NIP sudah terdaftar');
    }
    return back()->with('failed','Data Rekening gagal ditambah');
}



    public function edit(String $id) {
        $user = Auth::user();
        $cs = $user->petugas;
        $nasabah = Nasabah::with('rekening')->findOrFail($id);

        $provinsi = DB::table('provinsi')->get();
        $kabupaten = DB::table('kabupaten')->where('provinsi_id', $nasabah->provinsi_id)->get();
        $kecamatan = DB::table('kecamatan')->where('kabupaten_id', $nasabah->kab_kota_id)->get();
        $desa = DB::table('desa')->where('kecamatan_id', $nasabah->kecamatan_id)->get();

        return view('costumerservice.crudnasabah.edit', compact('user', 'cs', 'nasabah', 'provinsi', 'kabupaten', 'kecamatan', 'desa'));
    }

    public function update(Request $request, String $id) {

        $request->validate([
            'nama_lengkap' => 'required',
            'nis_nip' => 'required',
            'jurusan' => 'required',
            'tempat_lahir' => 'required',
            'tanggal_lahir' => 'required',
            'jenis_kelamin' => 'required',
            'jenis_identitas' => 'required',
            'agama' => 'required',
            'pendidikan' => 'required',
            'jabatan' => 'required',
            'no_hp' => 'required',
            'email' => 'required',
            'alamat' => 'required',
            'kelurahan' => 'required',
            'kecamatan' => 'required',
            'kab_kota' => 'required',
            'provinsi' => 'required',
            'kode_pos' => 'required',
            'nama_kontak_darurat' => 'required',
            'nomor_kontak_darurat' => 'required',
            'hubungan_kontak_darurat' => 'required',
            'alamat_kontak_darurat' => 'required',
        ]);

        $nasabah = Nasabah::findOrFail($id);
        $user = User::findOrFail($nasabah->user_id);
        $rekening = Rekening::where('nasabah_id', $nasabah->id)->first();

        if ( $request->jabatan == 'Siswa' ) {
            $no_rekening = '03' . $request->jurusan . $request->nis_nip;
        }

        if ( $request->jabatan == 'Guru' ) {
            $tanggal = Carbon::parse($request->tanggal_lahir)->format('Ymd');
            $urutan = Nasabah::where('jabatan', 'Guru')->count() + 1;
            $no_rekening = '01' . $urutan . $tanggal;
        }

        if ( $request->jabatan == 'TU' ) {
            $tanggal = Carbon::parse($request->tanggal_lahir)->format('Ymd');
            $urutan = Nasabah::where('jabatan', 'TU')->count() + 1;
            $no_rekening = '02' . $urutan . $tanggal;
        }

        $user->update([
            'name' => $request->nama_lengkap,
            'role_id' => 1,
            'email' => $request->email,
        ]);

        $nasabah->update([
            'nis_nip' => $request->nis_nip,
            'nama_nasabah' => $request->nama_lengkap,
            'tempat_lahir' => $request->tempat_lahir,
            'tanggal_lahir' => $request->tanggal_lahir,
            'jurusan_id' => $request->jurusan,
            'jenis_kelamin' => $request->jenis_kelamin,
            'pendidikan' => $request->pendidikan,
            'alamat' => $request->alamat,
            'kelurahan_id' => $request->kelurahan,
            'kecamatan_id' => $request->kecamatan,
            'kab_kota_id' => $request->kab_kota,
            'provinsi_id' => $request->provinsi,
            'kode_pos' => $request->kode_pos,
            'email' => $request->email,
            'agama' => $request->agama,
            'no_hp' => $request->no_hp,
            'jabatan' => $request->jabatan,
            'jenis_identitas' => $request->jenis_identitas,
            'nama_kontak_darurat' => $request->nama_kontak_darurat,
            'alamat_kontak_darurat' => $request->alamat_kontak_darurat,
            'no_hp_kontak_darurat' => $request->nomor_kontak_darurat,
            'hubungan_kontak_darurat' => $request->hubungan_kontak_darurat,
        ]);

            $rekening->update([
                'id' => $no_rekening,
                'status_akun' => 'non-aktif'
            ]);


        return redirect()->route('costumerservice.keloladata')->with('success', 'data nasabah berhasil di ubah');

    }

    public function destroy(String $id) {
        $nasabah = Nasabah::FindOrFail($id);
        $user = User::where('id', $nasabah->user_id)->first();
        $rekening = Rekening::where('nasabah_id', $nasabah->id)->first();

        $rekening->delete();
        $nasabah->delete();
        $user->delete();


        return redirect()->route('costumerservice.keloladata')->with('success','data nasabah berhasil di hapus');
    }

    public function halamanImport() {
        return view('costumerservice.crudnasabah.import');
    }

    public function import(Request $request) {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
        ], [
            'file.required' => 'Silakan pilih file Excel terlebih dahulu.',
            'file.mimes' => 'Format file harus berupa Excel (.xlsx atau .xls).',
        ]);

        try {
            Excel::import(new NasabahImport, $request->file('file'));
            return redirect()->route('costumerservice.keloladata')->with('success', 'Data nasabah berhasil di-import dari Excel!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mengimpor file: ' . $e->getMessage());
        }
    }

    public function print(String $id) {
        $nasabah = Nasabah::with('rekening')->FindOrFail($id);

        return view('costumerservice.crudnasabah.print', compact('nasabah'));
    }


}
