@extends('layouts.cs')

@section('title', 'CS - Edit Nasabah')

@section('header_title')
Edit Data Nasabah
@endsection

@section('header_subtitle', 'Ubah data informasi nasabah.')

@section('styles')
<style>
    .fade-in {
        animation: fadeIn 0.3s ease-in-out;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>
@endsection

@section('content')
<div id="viewEditData" class="fade-in flex-1 mt-4">
    <div class="bg-white rounded-[24px] shadow-card p-6 md:p-10 w-full border border-gray-50">
        <form action="{{ route('update.nasabah', $nasabah->id) }}" method="POST" id="nasabahFormEdit">
            @csrf
            @method('PUT')

            <input type="hidden" value="non-aktif" name="status_akun">
            <!-- SECTION 1: DATA PRIBADI -->
            <div class="mb-10">
                <div class="flex items-center gap-3 mb-8">
                    <div class="w-[5px] h-6 bg-[#c0860b] rounded-full"></div>
                    <h3 class="text-[20px] font-bold text-gray-800">Edit Data Nasabah</h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-x-6 gap-y-5">
                    <div>
                        <label class="block text-[13px] font-semibold text-gray-500 mb-2">Nama Lengkap</label>
                        <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap', $nasabah->nama_nasabah) }}" id="edit_nama" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-[14px] text-gray-800 focus:outline-none focus:border-brand-blue focus:ring-1 focus:ring-brand-blue transition-colors">
                    </div>
                    <div>
                        <label class="block text-[13px] font-semibold text-gray-500 mb-2">NIS/NIP</label>
                        <input type="text" inputmode="numeric" oninput="this.value = this.value.replace(/[^0-9]/g, '')" name="nis_nip" value="{{ old('nis_nip', $nasabah->nis_nip) }}" id="edit_nis" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-[14px] text-gray-800 focus:outline-none focus:border-brand-blue focus:ring-1 focus:ring-brand-blue transition-colors">
                    </div>
                    <div>
                        <label class="block text-[13px] font-semibold text-gray-500 mb-2">Jurusan</label>
                        <select name="jurusan" id="edit_jurusan" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 pr-10 text-[14px] text-gray-800 bg-white appearance-none bg-[url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%2218%22%20height%3D%2218%22%20viewBox%3D%220%200%2024%2024%22%20fill%3D%22none%22%20stroke%3D%22%236B7280%22%20stroke-width%3D%222.5%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%3E%3Cpath%20d%3D%22m6%209%206%206%206-6%22%2F%3E%3C%2Fsvg%3E')] bg-[length:18px] bg-[right_1rem_center] bg-no-repeat focus:outline-none focus:border-brand-blue focus:ring-2 focus:ring-brand-blue/20 transition-all shadow-sm hover:border-gray-300 cursor-pointer">
                            <option value="1" {{ old('jurusan', $nasabah->jurusan_id) == 1 ? 'selected' : '' }}>TKRO</option>
                            <option value="2" {{ old('jurusan', $nasabah->jurusan_id) == 2 ? 'selected' : '' }}>TJKT</option>
                            <option value="3" {{ old('jurusan', $nasabah->jurusan_id) == 3 ? 'selected' : '' }}>PPLG</option>
                            <option value="4" {{ old('jurusan', $nasabah->jurusan_id) == 4 ? 'selected' : '' }}>DPIB</option>
                            <option value="5" {{ old('jurusan', $nasabah->jurusan_id) == 5 ? 'selected' : '' }}>MPLB</option>
                            <option value="6" {{ old('jurusan', $nasabah->jurusan_id) == 6 ? 'selected' : '' }}>AKL</option>
                            <option value="7" {{ old('jurusan', $nasabah->jurusan_id) == 7 ? 'selected' : '' }}>SK</option>
                            <option value="8" {{ old('jurusan', $nasabah->jurusan_id) == 8 ? 'selected' : '' }}>TU</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[13px] font-semibold text-gray-500 mb-2">Tempat Lahir</label>
                        <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir', $nasabah->tempat_lahir) }}" id="edit_tempat_lahir" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-[14px] text-gray-800 focus:outline-none focus:border-brand-blue focus:ring-1 focus:ring-brand-blue transition-colors">
                    </div>
                    <div>
                        <label class="block text-[13px] font-semibold text-gray-500 mb-2">Tanggal Lahir</label>
                        <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir', $nasabah->tanggal_lahir) }}" id="edit_tanggal_lahir" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-[14px] text-gray-800 focus:outline-none focus:border-brand-blue focus:ring-1 focus:ring-brand-blue transition-colors">
                    </div>
                    <div>
                        <label class="block text-[13px] font-semibold text-gray-500 mb-2">Jenis Kelamin</label>
                        <select name="jenis_kelamin" id="edit_jenis_kelamin" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 pr-10 text-[14px] text-gray-800 bg-white appearance-none bg-[url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%2218%22%20height%3D%2218%22%20viewBox%3D%220%200%2024%2024%22%20fill%3D%22none%22%20stroke%3D%22%236B7280%22%20stroke-width%3D%222.5%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%3E%3Cpath%20d%3D%22m6%209%206%206%206-6%22%2F%3E%3C%2Fsvg%3E')] bg-[length:18px] bg-[right_1rem_center] bg-no-repeat focus:outline-none focus:border-brand-blue focus:ring-2 focus:ring-brand-blue/20 transition-all shadow-sm hover:border-gray-300 cursor-pointer">
                            <option value="Laki-Laki" {{ old('jenis_kelamin', $nasabah->jenis_kelamin) == 'Laki-Laki' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="Perempuan" {{ old('jenis_kelamin', $nasabah->jenis_kelamin) == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[13px] font-semibold text-gray-500 mb-2">Jenis Identitas Utama</label>
                        <input type="text" name="jenis_identitas" value="{{ old('jenis_identitas', $nasabah->jenis_identitas) }}" id="edit_identitas" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-[14px] text-gray-800 focus:outline-none focus:border-brand-blue focus:ring-1 focus:ring-brand-blue transition-colors">
                    </div>
                    <div>
                        <label class="block text-[13px] font-semibold text-gray-500 mb-2">Agama</label>
                        <select id="edit_agama" name="agama" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 pr-10 text-[14px] text-gray-800 bg-white appearance-none bg-[url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%2218%22%20height%3D%2218%22%20viewBox%3D%220%200%2024%2024%22%20fill%3D%22none%22%20stroke%3D%22%236B7280%22%20stroke-width%3D%222.5%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%3E%3Cpath%20d%3D%22m6%209%206%206%206-6%22%2F%3E%3C%2Fsvg%3E')] bg-[length:18px] bg-[right_1rem_center] bg-no-repeat focus:outline-none focus:border-brand-blue focus:ring-2 focus:ring-brand-blue/20 transition-all shadow-sm hover:border-gray-300 cursor-pointer">
                            <option value="Islam" {{ old('agama', $nasabah->agama) == 'Islam' ? 'selected' : '' }}>Islam</option>
                            <option value="Kristen" {{ old('agama', $nasabah->agama) == 'Kristen' ? 'selected' : '' }}>Kristen</option>
                            <option value="Katolik" {{ old('agama', $nasabah->agama) == 'Katolik' ? 'selected' : '' }}>Katolik</option>
                            <option value="Hindu" {{ old('agama', $nasabah->agama) == 'Hindu' ? 'selected' : '' }}>Hindu</option>
                            <option value="Budha" {{ old('agama', $nasabah->agama) == 'Budha' ? 'selected' : '' }}>Budha</option>
                            <option value="Khonghucu" {{ old('agama', $nasabah->agama) == 'Khonghucu' ? 'selected' : '' }}>Khonghucu</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[13px] font-semibold text-gray-500 mb-2">Pendidikan</label>
                        <select name="pendidikan" id="edit_pendidikan" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 pr-10 text-[14px] text-gray-800 bg-white appearance-none bg-[url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%2218%22%20height%3D%2218%22%20viewBox%3D%220%200%2024%2024%22%20fill%3D%22none%22%20stroke%3D%22%236B7280%22%20stroke-width%3D%222.5%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%3E%3Cpath%20d%3D%22m6%209%206%206%206-6%22%2F%3E%3C%2Fsvg%3E')] bg-[length:18px] bg-[right_1rem_center] bg-no-repeat focus:outline-none focus:border-brand-blue focus:ring-2 focus:ring-brand-blue/20 transition-all shadow-sm hover:border-gray-300 cursor-pointer">
                            <option value="SD" {{ old('pendidikan', $nasabah->pendidikan) == 'SD' ? 'selected' : '' }}>SD</option>
                            <option value="SMP" {{ old('pendidikan', $nasabah->pendidikan) == 'SMP' ? 'selected' : '' }}>SMP</option>
                            <option value="SMK" {{ old('pendidikan', $nasabah->pendidikan) == 'SMK' ? 'selected' : '' }}>SMK</option>
                            <option value="SMA" {{ old('pendidikan', $nasabah->pendidikan) == 'SMA' ? 'selected' : '' }}>SMA</option>
                            <option value="D1" {{ old('pendidikan', $nasabah->pendidikan) == 'D1' ? 'selected' : '' }}>D1</option>
                            <option value="D2" {{ old('pendidikan', $nasabah->pendidikan) == 'D2' ? 'selected' : '' }}>D2</option>
                            <option value="D3" {{ old('pendidikan', $nasabah->pendidikan) == 'D3' ? 'selected' : '' }}>D3</option>
                            <option value="S1" {{ old('pendidikan', $nasabah->pendidikan) == 'S1' ? 'selected' : '' }}>S1</option>
                            <option value="S2" {{ old('pendidikan', $nasabah->pendidikan) == 'S2' ? 'selected' : '' }}>S2</option>
                            <option value="S3" {{ old('pendidikan', $nasabah->pendidikan) == 'S3' ? 'selected' : '' }}>S3</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[13px] font-semibold text-gray-500 mb-2">Jabatan</label>
                        <select name="jabatan" id="edit_jabatan" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 pr-10 text-[14px] text-gray-800 bg-white appearance-none bg-[url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%2218%22%20height%3D%2218%22%20viewBox%3D%220%200%2024%2024%22%20fill%3D%22none%22%20stroke%3D%22%236B7280%22%20stroke-width%3D%222.5%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%3E%3Cpath%20d%3D%22m6%209%206%206%206-6%22%2F%3E%3C%2Fsvg%3E')] bg-[length:18px] bg-[right_1rem_center] bg-no-repeat focus:outline-none focus:border-brand-blue focus:ring-2 focus:ring-brand-blue/20 transition-all shadow-sm hover:border-gray-300 cursor-pointer">
                            <option value="Siswa" {{ old('jabatan', $nasabah->jabatan) == 'Siswa' ? 'selected' : '' }}>Siswa</option>
                            <option value="Guru" {{ old('jabatan', $nasabah->jabatan) == 'Guru' ? 'selected' : '' }}>Guru</option>
                            <option value="TU" {{ old('jabatan', $nasabah->jabatan) == 'TU' ? 'selected' : '' }}>TU</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[13px] font-semibold text-gray-500 mb-2">Telepon Selular</label>
                        <input type="text" inputmode="numeric" oninput="this.value = this.value.replace(/[^0-9]/g, '')" name="no_hp" value="{{ old('no_hp', $nasabah->no_hp) }}" id="edit_telepon" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-[14px] text-gray-800 focus:outline-none focus:border-brand-blue focus:ring-1 focus:ring-brand-blue transition-colors">
                    </div>
                    <div>
                        <label class="block text-[13px] font-semibold text-gray-500 mb-2">Email</label>
                        <input type="email" name="email" value="{{ old('email', $nasabah->email) }}" id="edit_email" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-[14px] text-gray-800 focus:outline-none focus:border-brand-blue focus:ring-1 focus:ring-brand-blue transition-colors">
                    </div>
                    <div class="col-span-1 md:col-span-2">
                        <label class="block text-[13px] font-semibold text-gray-500 mb-2">Alamat</label>
                        <textarea id="edit_alamat" name="alamat" class="w-full h-[155px] border border-gray-200 rounded-lg px-4 py-3 text-[14px] text-gray-800 resize-none focus:outline-none focus:border-brand-blue focus:ring-1 focus:ring-brand-blue transition-colors">{{ old('alamat', $nasabah->alamat) }}</textarea>
                    </div>
                    <div class="col-span-1 flex flex-col gap-5">
                    <div>
                        <label class="block text-[13px] font-semibold text-gray-500 mb-2">Provinsi</label>
                        <select name="provinsi" id="provinsi" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 pr-10 text-[14px] text-gray-800 bg-white appearance-none bg-[url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%2218%22%20height%3D%2218%22%20viewBox%3D%220%200%2024%2024%22%20fill%3D%22none%22%20stroke%3D%22%236B7280%22%20stroke-width%3D%222.5%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%3E%3Cpath%20d%3D%22m6%209%206%206%206-6%22%2F%3E%3C%2Fsvg%3E')] bg-[length:18px] bg-[right_1rem_center] bg-no-repeat focus:outline-none focus:border-brand-blue focus:ring-2 focus:ring-brand-blue/20 transition-all shadow-sm hover:border-gray-300 cursor-pointer">
                            <option value="" disabled selected>Pilih Provinsi</option>
                            @foreach ($provinsi as $prov)
                            <option value="{{ $prov->id }}" {{ old('provinsi', $nasabah->provinsi_id) == $prov->id ? 'selected' : '' }}>{{ ucwords(strtolower($prov->name)) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-[13px] font-semibold text-gray-500 mb-2">Kab/Kota</label>
                        <select name="kab_kota" id="kabupaten" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 pr-10 text-[14px] text-gray-800 bg-white appearance-none bg-[url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%2218%22%20height%3D%2218%22%20viewBox%3D%220%200%2024%2024%22%20fill%3D%22none%22%20stroke%3D%22%236B7280%22%20stroke-width%3D%222.5%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%3E%3Cpath%20d%3D%22m6%209%206%206%206-6%22%2F%3E%3C%2Fsvg%3E')] bg-[length:18px] bg-[right_1rem_center] bg-no-repeat focus:outline-none focus:border-brand-blue focus:ring-2 focus:ring-brand-blue/20 transition-all shadow-sm hover:border-gray-300 cursor-pointer">
                            <option value="" disabled selected>Pilih Kabupaten</option>
                            @foreach ($kabupaten as $kab)
                            <option value="{{ $kab->id }}" {{ old('kab_kota', $nasabah->kab_kota_id) == $kab->id ? 'selected' : '' }}>{{ ucwords(strtolower($kab->name)) }}</option>
                            @endforeach
                        </select>
                    </div>
                    </div>
                    <div>
                        <label class="block text-[13px] font-semibold text-gray-500 mb-2">Kecamatan</label>
                        <select name="kecamatan" id="kecamatan" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 pr-10 text-[14px] text-gray-800 bg-white appearance-none bg-[url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%2218%22%20height%3D%2218%22%20viewBox%3D%220%200%2024%2024%22%20fill%3D%22none%22%20stroke%3D%22%236B7280%22%20stroke-width%3D%222.5%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%3E%3Cpath%20d%3D%22m6%209%206%206%206-6%22%2F%3E%3C%2Fsvg%3E')] bg-[length:18px] bg-[right_1rem_center] bg-no-repeat focus:outline-none focus:border-brand-blue focus:ring-2 focus:ring-brand-blue/20 transition-all shadow-sm hover:border-gray-300 cursor-pointer">
                            <option value="" disabled selected>Pilih Kecamatan</option>
                            @foreach ($kecamatan as $kec)
                            <option value="{{ $kec->id }}" {{ old('kecamatan', $nasabah->kecamatan_id) == $kec->id ? 'selected' : '' }}>{{ ucwords(strtolower($kec->name)) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-[13px] font-semibold text-gray-500 mb-2">Kelurahan/Desa</label>
                        <select name="kelurahan" id="desa" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 pr-10 text-[14px] text-gray-800 bg-white appearance-none bg-[url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%2218%22%20height%3D%2218%22%20viewBox%3D%220%200%2024%2024%22%20fill%3D%22none%22%20stroke%3D%22%236B7280%22%20stroke-width%3D%222.5%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%3E%3Cpath%20d%3D%22m6%209%206%206%206-6%22%2F%3E%3C%2Fsvg%3E')] bg-[length:18px] bg-[right_1rem_center] bg-no-repeat focus:outline-none focus:border-brand-blue focus:ring-2 focus:ring-brand-blue/20 transition-all shadow-sm hover:border-gray-300 cursor-pointer">
                            <option value="" disabled selected>Pilih Desa</option>
                            @foreach ($desa as $ds)
                            <option value="{{ $ds->id }}" {{ old('kelurahan', $nasabah->kelurahan_id) == $ds->id ? 'selected' : '' }}>{{ ucwords(strtolower($ds->name)) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-[13px] font-semibold text-gray-500 mb-2">Kode Pos</label>
                        <input type="text" inputmode="numeric" oninput="this.value = this.value.replace(/[^0-9]/g, '')" name="kode_pos" value="{{ old('kode_pos', $nasabah->kode_pos) }}" id="edit_kode_pos" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-[14px] text-gray-800 focus:outline-none focus:border-brand-blue focus:ring-1 focus:ring-brand-blue transition-colors">
                    </div>
                </div>
            </div>

            <!-- SECTION 2: DATA PIHAK YANG DAPAT DIHUBUNGI -->
            <div class="mb-10">
                <div class="flex items-center gap-3 mb-8">
                    <div class="w-[5px] h-6 bg-[#c0860b] rounded-full"></div>
                    <h3 class="text-[20px] font-bold text-gray-800">Data Pihak yang Dapat Dihubungi</h3>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-5">
                    <div class="flex flex-col gap-5">
                        <div>
                            <label class="block text-[13px] font-semibold text-gray-500 mb-2">Nama Lengkap</label>
                            <input type="text" name="nama_kontak_darurat" value="{{ old('nama_kontak_darurat', $nasabah->nama_kontak_darurat) }}" id="edit_kontak_nama" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-[14px] text-gray-800 focus:outline-none focus:border-brand-blue focus:ring-1 focus:ring-brand-blue transition-colors">
                        </div>
                        <div>
                            <label class="block text-[13px] font-semibold text-gray-500 mb-2">Telepon Selular</label>
                            <input type="text" inputmode="numeric" oninput="this.value = this.value.replace(/[^0-9]/g, '')" name="nomor_kontak_darurat" value="{{ old('nomor_kontak_darurat', $nasabah->no_hp_kontak_darurat) }}" id="edit_kontak_telepon" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-[14px] text-gray-800 focus:outline-none focus:border-brand-blue focus:ring-1 focus:ring-brand-blue transition-colors">
                        </div>
                        <div>
                            <label class="block text-[13px] font-semibold text-gray-500 mb-2">Hubungan dengan Pemohon</label>
                            <input type="text" name="hubungan_kontak_darurat" value="{{ old('hubungan_kontak_darurat', $nasabah->hubungan_kontak_darurat) }}" id="edit_kontak_hubungan" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-[14px] text-gray-800 focus:outline-none focus:border-brand-blue focus:ring-1 focus:ring-brand-blue transition-colors">
                        </div>
                    </div>
                    <div>
                        <label class="block text-[13px] font-semibold text-gray-500 mb-2">Alamat</label>
                        <textarea id="edit_kontak_alamat" name="alamat_kontak_darurat" class="w-full h-[225px] border border-gray-200 rounded-lg px-4 py-3 text-[14px] text-gray-800 resize-none focus:outline-none focus:border-brand-blue focus:ring-1 focus:ring-brand-blue transition-colors">{{ old('alamat_kontak_darurat', $nasabah->alamat_kontak_darurat) }}</textarea>
                    </div>
                </div>
            </div>

            <!-- SECTION 3: DATA REKENING -->
            <div class="mb-10">
                <div class="flex items-center gap-3 mb-8">
                    <div class="w-[5px] h-6 bg-[#c0860b] rounded-full"></div>
                    <h3 class="text-[20px] font-bold text-gray-800">Data Rekening</h3>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-5">
                    <div>
                        <label class="block text-[13px] font-semibold text-gray-500 mb-2">Status Rekening</label>
                        <input type="text" value="{{ $nasabah->rekening->status_akun ?? '' }}" id="edit_status" readonly class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-[14px] text-gray-400 focus:outline-none bg-gray-50 cursor-not-allowed">
                    </div>
                </div>
            </div>

            <!-- BUTTONS -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mt-12">
                <a href="{{ route('costumerservice.keloladata') }}" class="w-full bg-[#797979] hover:bg-gray-600 text-white font-bold py-3.5 rounded-xl transition-colors text-[15px] flex items-center justify-center">Kembali</a>
                <button type="submit" class="w-full bg-button-gradient hover:bg-[#0e8f56] text-white font-bold py-3.5 rounded-xl transition-colors text-[15px]">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
    $(document).ready(function() {
        $('#provinsi').change(function() {
            let id_provinsi = $(this).val();
            if (!id_provinsi) return;

            $.ajax({
                url: '/get-kabupaten/' + id_provinsi,
                type: 'GET',
                success: function(data) {
                    $('#kabupaten').empty();
                    $('#kabupaten').append(`<option value="">Pilih Kabupaten</option>`);
                    $('#kecamatan').empty().append(`<option value="">Pilih Kecamatan</option>`);
                    $('#desa').empty().append(`<option value="">Pilih Desa</option>`);
                    data.forEach(function(item) {
                        $('#kabupaten').append(`<option value="${item.id}">${item.name}</option>`);
                    });
                }
            });
        });

        $('#kabupaten').change(function() {
            let id_kabupaten = $(this).val();
            if (!id_kabupaten) return;

            $.ajax({
                url: '/get-kecamatan/' + id_kabupaten,
                type: 'GET',
                success: function(data) {
                    $('#kecamatan').empty();
                    $('#kecamatan').append(`<option value="">Pilih Kecamatan</option>`);
                    $('#desa').empty().append(`<option value="">Pilih Desa</option>`);
                    data.forEach(function(item) {
                        $('#kecamatan').append(`<option value="${item.id}">${item.name}</option>`);
                    });
                }
            });
        });

        $('#kecamatan').change(function() {
            let id_kecamatan = $(this).val();
            if (!id_kecamatan) return;

            $.ajax({
                url: '/get-desa/' + id_kecamatan,
                type: 'GET',
                success: function(data) {
                    $('#desa').empty();
                    $('#desa').append(`<option value="">Pilih Desa</option>`);
                    data.forEach(function(item) {
                        $('#desa').append(`<option value="${item.id}">${item.name}</option>`);
                    });
                }
            });
        });
    });
</script>
@endsection
