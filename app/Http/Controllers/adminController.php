<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Petugas;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Exports\PetugasTemplateExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\PetugasImport;
use App\Models\Nasabah;
use App\Models\data_siswa;
use App\Imports\NasabahImport;
use App\Exports\NasabahTemplateExport;
use App\Models\data_guru;
use App\Models\data_tendik;
use App\Models\Rekening;
use Carbon\Carbon;

class adminController extends Controller
{
    public function index() {
        return view('admin.supervisor.dashboard');
    }

    //petugas //

    public function halamanPetugas(Request $request)
    {
        $user = Auth::user();
        $perPage = $request->input('per_page', 10);
        $keyword = $request->keyword;

        // Sesuaikan dengan relasi role utama atau role2
        $petugas = Petugas::with(['user.role', 'user.role2'])
            ->whereHas('user', function ($query) {
                $query->whereHas('role', function($q) {
                    $q->whereIn('nama_role', ['customerservice', 'teller']);
                })->orWhereHas('role2', function($q) {
                    $q->whereIn('nama_role', ['customerservice', 'teller']);
                });
            })->when($keyword, function ($query, $keyword) {
                $query->where(function ($q) use ($keyword) {
                    $q->where('kelas', 'like', '%' . $keyword . '%')
                      ->orWhereHas('user', function ($userQuery) use ($keyword) {
                          $userQuery->where('name', 'like', '%' . $keyword . '%');
                      });
                });
            })
            ->orderByDesc('id')
            ->paginate($perPage)
            ->appends(['per_page' => $perPage, 'keyword' => $keyword]);

        $roles = Role::whereIn('nama_role', [
            'customerservice',
            'teller'
        ])->get();

        return view('admin.supervisor.datapetugas', compact(
            'petugas',
            'roles',
            'user',
            'perPage',
        ));
    }

    public function update(Request $request, $id)
    {
        $petugas = Petugas::with('user')->findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'kelas' => 'required',
            'email' => 'required|email|unique:users,email,' . $petugas->user->id,
            'role_id' => 'required|exists:roles,id',
            'role_id_2' => 'nullable|exists:roles,id|different:role_id',
            'password' => 'nullable|min:6',
        ]);

        try {
            DB::transaction(function () use ($request, $petugas) {
                $petugas->update(['kelas' => $request->kelas]);

                $userData = [
                    'name' => $request->name,
                    'email' => $request->email,
                    'role_id' => $request->role_id,
                    'role_id_2' => $request->role_id_2,
                ];

                if ($request->filled('password')) {
                    $userData['password'] = Hash::make($request->password);
                }

                $petugas->user->update($userData);
            });

            return redirect()
                ->route('halaman.petugas.admin')
                ->with('success', 'Data petugas berhasil diupdate');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Data petugas gagal diupdate');
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'kelas' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'role_id' => 'required|exists:roles,id',
            'role_id_2' => 'nullable|exists:roles,id|different:role_id',
        ]);

        try {
            DB::transaction(function () use ($request) {
                $user = User::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                    'role_id' => $request->role_id,
                    'role_id_2' => $request->role_id_2,
                ]);

                Petugas::create([
                    'user_id' => $user->id,
                    'kelas' => $request->kelas,
                ]);
            });

            return redirect()
                ->route('halaman.petugas.admin')
                ->with('success', 'Data petugas berhasil ditambahkan');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Data petugas gagal ditambahkan: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            DB::transaction(function () use ($id) {
                $petugas = Petugas::with('user')->findOrFail($id);
                $user = $petugas->user;
                $petugas->delete();
                if ($user) {
                    $user->delete();
                }
            });

            return redirect()
                ->route('halaman.petugas.admin')
                ->with('success', 'Data petugas berhasil dihapus');
        } catch (\Exception $e) {
            return back()->with('error', 'Data petugas gagal dihapus');
        }
    }

    public function downloadTemplate()
    {
        return Excel::download(new PetugasTemplateExport, 'template_import_petugas.xlsx');
    }

    public function importExcel(Request $request)
    {
        $request->validate([
            'file_excel' => 'required|mimes:xlsx,xls,csv|max:2048',
        ]);

        try {
            Excel::import(new PetugasImport, $request->file('file_excel'));
            return redirect()->route('halaman.petugas.admin')->with('success', 'Data petugas berhasil di-import!');
        } catch (\Exception $e) {
            return redirect()->route('halaman.petugas.admin')->with('error', 'Gagal import: ' . $e->getMessage());
        }
    }

    ///data nasabah

    public function halamanNasabah() {
        $userNasabah = Nasabah::all();
        return view('admin.supervisor.datanasabah', compact('userNasabah'));
    }

    public function detailNasabah(String $id)
    {
        $user = Auth::user();
        $nasabah = Nasabah::with('rekening', 'jurusan', 'provinsi', 'kabupaten', 'kecamatan', 'desa')->findOrFail($id);
        return view('admin.supervisor.crud_datanasabah.detail', compact('user', 'nasabah'));
    }

    public function print(String $id)
    {
        $user = Auth::user();
        $nasabah = Nasabah::with('rekening')->FindOrFail($id);

        return view('admin.supervisor.crud_datanasabah.print', compact('nasabah', 'user'));
    }

    // data master
    public function halamanDataMaster() {
        $user = Auth::user();
        $provinsi = DB::table('provinsi')->get();
        $jurusan = DB::table('jurusan')->get();

        return view('admin.supervisor.crud_datanasabah.datamasterSiswa', compact('user', 'provinsi', 'jurusan'));
    }

    public function dataMaster( Request $request) {

        $request->validate([
            'nama_lengkap' => 'required',
            'nis' => 'required|max:9',
            'jabatan' => 'required',
            'nisn' => 'required|max:10',
            'jurusan_id' => 'required',
            'jenis_kelamin' => 'required',
            'tempat_lahir' => 'required',
            'tanggal_lahir' => 'required|date',
            'agama' => 'required',
            'rt' => 'required',
            'rw' => 'required',
            'dusun' => 'required',
            'kelurahan' => 'required',
            'kecamatan' => 'required',
            'kode_pos' => 'required',
        ]);

        $dataSiswa = DB::table('data_siswa')
            ->where('nis', $request->nis)->first();

        $dataNisn = DB::table('data_siswa')
            ->where('nisn', $request->nisn)->first();

        if (!$dataSiswa) {
            if (!$dataNisn) {
        data_siswa::create([
            'nama_lengkap' => $request->nama_lengkap,
            'jabatan' => $request->jabatan,
            'nis' => $request->nis,
            'nisn' => $request->nisn,
            'jurusan_id' => $request->jurusan_id,
            'jenis_kelamin'=> $request->jenis_kelamin,
            'tempat_lahir' => $request->tempat_lahir,
            'tanggal_lahir' => $request->tanggal_lahir,
            'agama' => $request->agama,
            'rt' => $request->rt,
            'rw' => $request->rw,
            'dusun' => $request->dusun,
            'kelurahan_id' => $request->kelurahan,
            'kecamatan_id' => $request->kecamatan,
            'kode_pos' => $request->kode_pos,
        ]);

        return redirect()->route('data.siswa.admin')->with('success','Data siswa berhasil ditambahkan');
            }
        }
        return redirect()->back()->with('success', 'data gagal ditambahkan');
    }

    public function halamanMasterGTK() {
        $user = Auth::user();
        $provinsi = DB::table('provinsi')->get();

        return view('admin.supervisor.crud_datanasabah.datamasterGTK', compact('user', 'provinsi' ));
    }

    public function dataMasterGTK(Request $request) {
        $request->validate([
            'nama_lengkap' => 'required',
            'jabatan' => 'required',
            'nuptk' => 'required',
            'nip' => 'required',
            'nik' => 'required',
            'jenis_kelamin' => 'required',
            'tempat_lahir' => 'required',
            'tanggal_lahir' => 'required',
            'agama' => 'required',
            'kode_pos' => 'required',
            'rt' => 'required',
            'rw' => 'required',
            'dusun' => 'required',
            'kelurahan' => 'required',
            'kecamatan' => 'required',
        ]);

        if( $request->jabatan === 'TU' ) {
            data_tendik::create([
                'nama_lengkap' => $request->nama_lengkap,
                'jabatan' => $request->jabatan,
                'nuptk' => $request->nuptk,
                'nip' => $request->nip,
                'nik' => $request->nik,
                'jenis_kelamin' => $request->jenis_kelamin,
                'tempat_lahir' => $request->tempat_lahir,
                'tanggal_lahir' => $request->tanggal_lahir,
                'agama' => $request->agama,
                'kode_pos' => $request->kode_pos,
                'rt' => $request->rt,
                'rw' => $request->rw,
                'dusun' => $request->dusun,
                'kelurahan_id' => $request->kelurahan,
                'kecamatan_id' => $request->kecamatan,
            ]);
            return back()->with('success', 'data Tenaga Pendidik berhasil ditambah');
        } else if ( $request->jabatan === 'Guru' ) {
            data_guru::create([
                'nama_lengkap' => $request->nama_lengkap,
                'jabatan' => $request->jabatan,
                'nuptk' => $request->nuptk,
                'nip' => $request->nip,
                'nik' => $request->nik,
                'jenis_kelamin' => $request->jenis_kelamin,
                'tempat_lahir' => $request->tempat_lahir,
                'tanggal_lahir' => $request->tanggal_lahir,
                'agama' => $request->agama,
                'kode_pos' => $request->kode_pos,
                'rt' => $request->rt,
                'rw' => $request->rw,
                'dusun' => $request->dusun,
                'kelurahan_id' => $request->kelurahan_id,
                'kecamatan_id' => $request->kecamatan_id,
            ]);
            return back()->with('success', 'data guru berhasil ditambah');
        } else {
            return back()->with('failed', 'datamaster gagal ditambah');
        }
    }

    //verifikasi registrasi rekening
    public function verifikasiNasabah(Request $request)
    {
        $user = Auth::user();
        $perPage = $request->input('per_page', 10);
        $keyword = $request->keyword;

        $allNasabah = Nasabah::with('rekening')
            ->whereHas('rekening', function ($query) {
                $query->where('status_akun', 'non-aktif');
            })
            // Tambahkan logika pencarian di sini
            ->when($keyword, function ($query, $keyword) {
                $query->where(function ($q) use ($keyword) {
                    $q->where('nama_nasabah', 'like', '%' . $keyword . '%')
                        ->orWhere('jabatan', 'like', '%' . $keyword . '%')
                        ->orWhereHas('rekening', function ($qRekening) use ($keyword) {
                            $qRekening->where('id', 'like', '%' . $keyword . '%');
                        });
                });
            })
            ->orderByDesc('id')
            ->paginate($perPage)
            // Pastikan keyword ditambahkan ke appends agar tidak hilang saat pindah halaman
            ->appends([
                'per_page' => $perPage,
                'keyword' => $keyword
            ]);

        return view('admin.supervisor.verifikasi.registrasirekening', compact('user', 'allNasabah', 'perPage', 'keyword'));
    }

    public function aktif(String $id)
    {
        $rekening = Rekening::FindOrFail($id);

        $rekening->status_akun = 'aktif';

        $rekening->save();

        //  Ubah nama route di bawah ini agar sesuai dengan web.php kamu
        return redirect()->route('verifikasi.nasabah.admin')->with('success', 'Rekening Telah Aktif');
    }


    public function destroyNasabah(String $id)
    {
        $nasabah = Nasabah::FindOrFail($id);
        $user = User::where('id', $nasabah->user_id)->first();
        $rekening = Rekening::where('nasabah_id', $nasabah->id)->first();

        $rekening->delete();
        $nasabah->delete();
        $user->delete();


        return redirect()->route('verifikasi.nasabah.admin')->with('success', 'data nasabah berhasil di hapus');
    }

    public function detail(String $id)
    {
        $user = Auth::user();
        $nasabah = Nasabah::with('rekening', 'jurusan', 'provinsi', 'kabupaten', 'kecamatan', 'desa')->findOrFail($id);
        return view('admin.supervisor.verifikasi.registrasirekening.detail', compact('user', 'nasabah'));
    }

    public function halamanRevisi(String $id)
    {
        $user = Auth::user();
        $nasabah = Nasabah::FindOrFail($id);
        $rekening = Rekening::where('nasabah_id', $nasabah->id);
        return view('admin.supervisor.verifikasi.registrasirekening.revisi', compact('nasabah', 'rekening', 'user'));
    }

    public function revisi(String $id, Request $request)
    {
        $request->validate([
            'pesan' => 'required',
            'nama_perevisi' => 'required',
            'status_akun' => 'required',
        ]);

        $nasabah = Nasabah::FindOrFAil($id);
        $nasabah->update([
            'pesan' => $request->pesan,
            'nama_perevisi' => $request->nama_perevisi,
        ]);

        $rekening = Rekening::where('nasabah_id', $nasabah->id);
        $rekening->update([
            'status_akun' => $request->status_akun,
        ]);

        return redirect()->route('verifikasi.nasabah.admin')->with('success', 'data revisi berhasil di kirim');
    }

    // customer service
    public function dashboardCS() {
        return view('admin.costumerservice.dashboard');
    }

    public function keloladata(Request $request)
    {
        $user = Auth::user();
        $perPage = $request->input('per_page', 10);
        $provinsi = DB::table('provinsi')->get();
        $allNasabah = Nasabah::with('rekening', 'jurusan')
            ->orderByDesc('id')
            ->paginate($perPage)
            ->appends(['per_page' => $perPage]);

        return view('admin.costumerservice.keloladata', compact('user', 'provinsi', 'allNasabah', 'perPage'));
    }


    // public function detailCs(String $id) {
    //     $user = Auth::user();
    //     $nasabah = Nasabah::with('rekening','jurusan')->findOrFail($id);
    //     return view('admin.costumerservice.crudnasabah.detail',compact('user','cs','nasabah'));
    // }

    public function storeNasabah(Request $request)
    {
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



                if ($request->jabatan == 'Siswa') {
                    $no_rekening = '03' . $request->jurusan . $request->nis_nip;
                }

                if ($request->jabatan == 'Guru') {
                    $tanggal = Carbon::parse($request->tanggal_lahir)->format('Ymd');
                    $urutan = Nasabah::where('jabatan', 'Guru')->count() + 1;
                    $no_rekening = '01' . $tanggal . $urutan;
                }

                if ($request->jabatan == 'TU') {
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

                return redirect()->route('kelola.data.cs.admin')->with('success', 'Data Rekening berhasil ditambah');
            } else {
                return back()->with('failed', 'Email Sudah Terdaftar');
            }
        } else {
            return back()->with('failed', 'NIS/NIP sudah terdaftar');
        }
        return back()->with('failed', 'Data Rekening gagal ditambah');
    }
        public function printNasabah(String $id)
    {
        $nasabah = Nasabah::with('rekening')->FindOrFail($id);

        return view('admin.costumerservice.crudnasabah.print', compact('nasabah'));
    }

    public function destroyNasabahCs(String $id)
    {
        $nasabah = Nasabah::FindOrFail($id);
        $user = User::where('id', $nasabah->user_id)->first();
        $rekening = Rekening::where('nasabah_id', $nasabah->id)->first();

        $rekening->delete();
        $nasabah->delete();
        $user->delete();


        return redirect()->route('kelola.data.cs.admin')->with('success', 'data nasabah berhasil di hapus');
    }

    public function editNasabah(String $id)
    {
        $user = Auth::user();
        $nasabah = Nasabah::with('rekening')->findOrFail($id);

        $provinsi = DB::table('provinsi')->get();
        $kabupaten = DB::table('kabupaten')->where('provinsi_id', $nasabah->provinsi_id)->get();
        $kecamatan = DB::table('kecamatan')->where('kabupaten_id', $nasabah->kab_kota_id)->get();
        $desa = DB::table('desa')->where('kecamatan_id', $nasabah->kecamatan_id)->get();

        return view('admin.costumerservice.crudnasabah.edit', compact('user', 'nasabah', 'provinsi', 'kabupaten', 'kecamatan', 'desa'));
    }

    public function updateNasabah(Request $request, String $id)
    {

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

        if ($request->jabatan == 'Siswa') {
            $no_rekening = '03' . $request->jurusan . $request->nis_nip;
        }

        if ($request->jabatan == 'Guru') {
            $tanggal = Carbon::parse($request->tanggal_lahir)->format('Ymd');
            $urutan = Nasabah::where('jabatan', 'Guru')->count() + 1;
            $no_rekening = '01' . $urutan . $tanggal;
        }

        if ($request->jabatan == 'TU') {
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


        return redirect()->route('kelola.data.cs.admin')->with('success', 'data nasabah berhasil di ubah');
    }

    public function downloadTemplateNasabah()
    {
        return Excel::download(new NasabahTemplateExport, 'template_import_nasabah.xlsx');
    }

    public function halamanImportNasabah() {
        return view('admin.costumerservice.crudnasabah.import');
    }

    public function importNasabah(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
        ], [
            'file.required' => 'Silakan pilih file Excel terlebih dahulu.',
            'file.mimes' => 'Format file harus berupa Excel (.xlsx atau .xls).',
        ]);

        try {
            Excel::import(new NasabahImport, $request->file('file'));
            return redirect()->route('kelola.data.cs.admin')->with('success', 'Data nasabah berhasil di-import dari Excel!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mengimpor file: ' . $e->getMessage());
        }
    }

}
