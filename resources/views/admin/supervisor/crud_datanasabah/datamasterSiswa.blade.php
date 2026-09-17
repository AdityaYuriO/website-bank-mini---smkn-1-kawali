@extends('layouts.admin')

@section('title', 'Data Siswa - Supervisor')

@section('header_title', 'Data Siswa')
@section('header_subtitle', 'Formulir Tambah Data Siswa')

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
@php
    $provinsi = $provinsi ?? collect([
        (object)['id' => 32, 'name' => 'JAWA BARAT'],
        (object)['id' => 33, 'name' => 'JAWA TENGAH'],
        (object)['id' => 31, 'name' => 'DKI JAKARTA'],
        (object)['id' => 35, 'name' => 'JAWA TIMUR'],
    ]);
@endphp

<div class="fade-in flex flex-1 flex-col justify-start mb-8">
    
    <!-- Top Action Bar -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6 px-1">
        <div>
            <h3 class="text-[20px] md:text-[22px] font-bold text-gray-800 flex items-center gap-2">
                <i class="ph-fill ph-student text-brand-blue text-2xl"></i>
                Formulir Data Siswa
            </h3>
            <p class="text-[13px] text-gray-500 font-medium mt-0.5">Lengkapi informasi siswa untuk ditambahkan</p>
        </div>
    </div>

    <!-- Form Container Card -->
    <div class="bg-white rounded-[24px] shadow-card p-6 md:p-10 w-full border border-gray-50">
        <form onsubmit="event.preventDefault(); showToast('Data Siswa berhasil disimpan (Mode Preview)', 'success'); setTimeout(() => { window.location.href='{{ url('/admin/supervisor/datanasabah') }}'; }, 1000); return false;" id="formMasterSiswa">
            <!-- SECTION 1: INFORMASI DATA PRIBADI SISWA -->
            <div class="mb-10">
                <div class="flex items-center gap-3 mb-6 pb-3 border-b border-gray-100">
                    <div class="w-[5px] h-6 bg-brand-blue rounded-full"></div>
                    <h4 class="text-[18px] font-bold text-gray-800">Informasi Pribadi Siswa</h4>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-x-6 gap-y-5">
                    <!-- Nama Lengkap -->
                    <div class="lg:col-span-2">
                        <label class="block text-[13px] font-semibold text-gray-600 mb-2">
                            Nama Lengkap
                        </label>
                        <div class="relative">
                            <i class="ph ph-user absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-lg"></i>
                            <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap') }}" required class="w-full border border-gray-200 rounded-xl pl-11 pr-4 py-2.5 text-[14px] text-gray-800 focus:outline-none focus:border-brand-blue focus:ring-2 focus:ring-brand-blue/20 transition-all shadow-sm">
                        </div>
                    </div>

                    <!-- Jurusan -->
                    <div>
                        <label class="block text-[13px] font-semibold text-gray-600 mb-2">
                            Jurusan
                        </label>
                        <select name="jurusan_id" required class="w-full border border-gray-200 rounded-xl px-4 py-2.5 pr-10 text-[14px] text-gray-800 bg-white appearance-none bg-[url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%2218%22%20height%3D%2218%22%20viewBox%3D%220%200%2024%2024%22%20fill%3D%22none%22%20stroke%3D%22%236B7280%22%20stroke-width%3D%222.5%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%3E%3Cpath%20d%3D%22m6%209%206%206%206-6%22%2F%3E%3C%2Fsvg%3E')] bg-[length:18px] bg-[right_1rem_center] bg-no-repeat focus:outline-none focus:border-brand-blue focus:ring-2 focus:ring-brand-blue/20 transition-all shadow-sm hover:border-gray-300 cursor-pointer">
                            <option value="" disabled {{ old('jurusan_id') ? '' : 'selected' }}>Pilih Jurusan</option>
                            <option value="1" {{ old('jurusan_id') == '1' ? 'selected' : '' }}>TKRO (Teknik Kendaraan Ringan Otomotif)</option>
                            <option value="2" {{ old('jurusan_id') == '2' ? 'selected' : '' }}>TJKT (Teknik Jaringan Komputer & Telekomunikasi)</option>
                            <option value="3" {{ old('jurusan_id') == '3' ? 'selected' : '' }}>PPLG (Pengembangan Perangkat Lunak & Gim)</option>
                            <option value="4" {{ old('jurusan_id') == '4' ? 'selected' : '' }}>DPIB (Desain Pemodelan & Informasi Bangunan)</option>
                            <option value="5" {{ old('jurusan_id') == '5' ? 'selected' : '' }}>MPLB (Manajemen Perkantoran & Layanan Bisnis)</option>
                            <option value="6" {{ old('jurusan_id') == '6' ? 'selected' : '' }}>AKUNTANSI (Akuntansi & Keuangan Lembaga)</option>
                            <option value="7" {{ old('jurusan_id') == '7' ? 'selected' : '' }}>SK (Seni Karawitan)</option>
                        </select>
                    </div>

                    <!-- NIS -->
                    <div>
                        <label class="block text-[13px] font-semibold text-gray-600 mb-2">
                            NIS
                        </label>
                        <div class="relative">
                            <i class="ph ph-identification-card absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-lg"></i>
                            <input type="text" inputmode="numeric" oninput="this.value = this.value.replace(/[^0-9]/g, '')" name="nis" value="{{ old('nis') }}" required class="w-full border border-gray-200 rounded-xl pl-11 pr-4 py-2.5 text-[14px] text-gray-800 focus:outline-none focus:border-brand-blue focus:ring-2 focus:ring-brand-blue/20 transition-all shadow-sm">
                        </div>
                    </div>

                    <!-- NISN -->
                    <div>
                        <label class="block text-[13px] font-semibold text-gray-600 mb-2">
                            NISN
                        </label>
                        <div class="relative">
                            <i class="ph ph-cardholder absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-lg"></i>
                            <input type="text" inputmode="numeric" oninput="this.value = this.value.replace(/[^0-9]/g, '')" name="nisn" value="{{ old('nisn') }}" required class="w-full border border-gray-200 rounded-xl pl-11 pr-4 py-2.5 text-[14px] text-gray-800 focus:outline-none focus:border-brand-blue focus:ring-2 focus:ring-brand-blue/20 transition-all shadow-sm">
                        </div>
                    </div>

                    <!-- Jenis Kelamin -->
                    <div>
                        <label class="block text-[13px] font-semibold text-gray-600 mb-2">
                            Jenis Kelamin
                        </label>
                        <select name="jenis_kelamin" required class="w-full border border-gray-200 rounded-xl px-4 py-2.5 pr-10 text-[14px] text-gray-800 bg-white appearance-none bg-[url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%2218%22%20height%3D%2218%22%20viewBox%3D%220%200%2024%2024%22%20fill%3D%22none%22%20stroke%3D%22%236B7280%22%20stroke-width%3D%222.5%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%3E%3Cpath%20d%3D%22m6%209%206%206%206-6%22%2F%3E%3C%2Fsvg%3E')] bg-[length:18px] bg-[right_1rem_center] bg-no-repeat focus:outline-none focus:border-brand-blue focus:ring-2 focus:ring-brand-blue/20 transition-all shadow-sm hover:border-gray-300 cursor-pointer">
                            <option value="" disabled {{ old('jenis_kelamin') ? '' : 'selected' }}>Pilih Jenis Kelamin</option>
                            <option value="Laki-Laki" {{ old('jenis_kelamin') == 'Laki-Laki' ? 'selected' : '' }}>Laki-Laki</option>
                            <option value="Perempuan" {{ old('jenis_kelamin') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                    </div>

                    <!-- Tempat Lahir -->
                    <div>
                        <label class="block text-[13px] font-semibold text-gray-600 mb-2">
                            Tempat Lahir
                        </label>
                        <div class="relative">
                            <i class="ph ph-map-pin-line absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-lg"></i>
                            <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir') }}" required class="w-full border border-gray-200 rounded-xl pl-11 pr-4 py-2.5 text-[14px] text-gray-800 focus:outline-none focus:border-brand-blue focus:ring-2 focus:ring-brand-blue/20 transition-all shadow-sm">
                        </div>
                    </div>

                    <!-- Tanggal Lahir -->
                    <div>
                        <label class="block text-[13px] font-semibold text-gray-600 mb-2">
                            Tanggal Lahir
                        </label>
                        <div class="relative">
                            <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}" required class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-[14px] text-gray-800 focus:outline-none focus:border-brand-blue focus:ring-2 focus:ring-brand-blue/20 transition-all shadow-sm">
                        </div>
                    </div>

                    <!-- Agama -->
                    <div>
                        <label class="block text-[13px] font-semibold text-gray-600 mb-2">
                            Agama
                        </label>
                        <select name="agama" required class="w-full border border-gray-200 rounded-xl px-4 py-2.5 pr-10 text-[14px] text-gray-800 bg-white appearance-none bg-[url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%2218%22%20height%3D%2218%22%20viewBox%3D%220%200%2024%2024%22%20fill%3D%22none%22%20stroke%3D%22%236B7280%22%20stroke-width%3D%222.5%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%3E%3Cpath%20d%3D%22m6%209%206%206%206-6%22%2F%3E%3C%2Fsvg%3E')] bg-[length:18px] bg-[right_1rem_center] bg-no-repeat focus:outline-none focus:border-brand-blue focus:ring-2 focus:ring-brand-blue/20 transition-all shadow-sm hover:border-gray-300 cursor-pointer">
                            <option value="" disabled {{ old('agama') ? '' : 'selected' }}>Pilih Agama</option>
                            <option value="Islam" {{ old('agama') == 'Islam' ? 'selected' : '' }}>Islam</option>
                            <option value="Protestan" {{ old('agama') == 'Protestan' ? 'selected' : '' }}>Protestan</option>
                            <option value="Katholik" {{ old('agama') == 'Katholik' || old('agama') == 'Katolik' ? 'selected' : '' }}>Katholik</option>
                            <option value="Hindu" {{ old('agama') == 'Hindu' ? 'selected' : '' }}>Hindu</option>
                            <option value="Buddha" {{ old('agama') == 'Buddha' ? 'selected' : '' }}>Buddha</option>
                            <option value="Khonghucu" {{ old('agama') == 'Khonghucu' ? 'selected' : '' }}>Khonghucu</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- SECTION 2: DATA ALAMAT & DOMISILI -->
            <div class="mb-10">
                <div class="flex items-center gap-3 mb-6 pb-3 border-b border-gray-100">
                    <div class="w-[5px] h-6 bg-[#bd8607] rounded-full"></div>
                    <h4 class="text-[18px] font-bold text-gray-800">Alamat & Tempat Tinggal</h4>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-x-6 gap-y-5">
                    <!-- RT -->
                    <div>
                        <label class="block text-[13px] font-semibold text-gray-600 mb-2">
                            RT
                        </label>
                        <input type="text" inputmode="numeric" oninput="this.value = this.value.replace(/[^0-9]/g, '')" name="rt" value="{{ old('rt') }}" required class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-[14px] text-gray-800 focus:outline-none focus:border-brand-blue focus:ring-2 focus:ring-brand-blue/20 transition-all shadow-sm">
                    </div>

                    <!-- RW -->
                    <div>
                        <label class="block text-[13px] font-semibold text-gray-600 mb-2">
                            RW
                        </label>
                        <input type="text" inputmode="numeric" oninput="this.value = this.value.replace(/[^0-9]/g, '')" name="rw" value="{{ old('rw') }}" required class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-[14px] text-gray-800 focus:outline-none focus:border-brand-blue focus:ring-2 focus:ring-brand-blue/20 transition-all shadow-sm">
                    </div>

                    <!-- Dusun -->
                    <div>
                        <label class="block text-[13px] font-semibold text-gray-600 mb-2">
                            Dusun
                        </label>
                        <input type="text" name="dusun" value="{{ old('dusun') }}" required class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-[14px] text-gray-800 focus:outline-none focus:border-brand-blue focus:ring-2 focus:ring-brand-blue/20 transition-all shadow-sm">
                    </div>

                    <!-- Provinsi -->
                    <div>
                        <label class="block text-[13px] font-semibold text-gray-600 mb-2">
                            Provinsi
                        </label>
                        <select name="provinsi" id="provinsi" required class="w-full border border-gray-200 rounded-xl px-4 py-2.5 pr-10 text-[14px] text-gray-800 bg-white appearance-none bg-[url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%2218%22%20height%3D%2218%22%20viewBox%3D%220%200%2024%2024%22%20fill%3D%22none%22%20stroke%3D%22%236B7280%22%20stroke-width%3D%222.5%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%3E%3Cpath%20d%3D%22m6%209%206%206%206-6%22%2F%3E%3C%2Fsvg%3E')] bg-[length:18px] bg-[right_1rem_center] bg-no-repeat focus:outline-none focus:border-brand-blue focus:ring-2 focus:ring-brand-blue/20 transition-all shadow-sm hover:border-gray-300 cursor-pointer">
                            <option value="" disabled selected>Pilih Provinsi</option>
                            @foreach ($provinsi as $prov)
                                <option value="{{ $prov->id }}">
                                    {{ ucwords(strtolower($prov->name)) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Kabupaten / Kota -->
                    <div>
                        <label class="block text-[13px] font-semibold text-gray-600 mb-2">
                            Kabupaten/Kota
                        </label>
                        <select name="kab_kota" id="kabupaten" required class="w-full border border-gray-200 rounded-xl px-4 py-2.5 pr-10 text-[14px] text-gray-800 bg-white appearance-none bg-[url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%2218%22%20height%3D%2218%22%20viewBox%3D%220%200%2024%2024%22%20fill%3D%22none%22%20stroke%3D%22%236B7280%22%20stroke-width%3D%222.5%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%3E%3Cpath%20d%3D%22m6%209%206%206%206-6%22%2F%3E%3C%2Fsvg%3E')] bg-[length:18px] bg-[right_1rem_center] bg-no-repeat focus:outline-none focus:border-brand-blue focus:ring-2 focus:ring-brand-blue/20 transition-all shadow-sm hover:border-gray-300 cursor-pointer">
                            <option value="" disabled selected>Pilih Kabupaten</option>
                            <option value="Ciamis">Kab. Ciamis</option>
                            <option value="Tasikmalaya">Kab. Tasikmalaya</option>
                        </select>
                    </div>

                    <!-- Kecamatan -->
                    <div>
                        <label class="block text-[13px] font-semibold text-gray-600 mb-2">
                            Kecamatan
                        </label>
                        <select name="kecamatan" id="kecamatan" required class="w-full border border-gray-200 rounded-xl px-4 py-2.5 pr-10 text-[14px] text-gray-800 bg-white appearance-none bg-[url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%2218%22%20height%3D%2218%22%20viewBox%3D%220%200%2024%2024%22%20fill%3D%22none%22%20stroke%3D%22%236B7280%22%20stroke-width%3D%222.5%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%3E%3Cpath%20d%3D%22m6%209%206%206%206-6%22%2F%3E%3C%2Fsvg%3E')] bg-[length:18px] bg-[right_1rem_center] bg-no-repeat focus:outline-none focus:border-brand-blue focus:ring-2 focus:ring-brand-blue/20 transition-all shadow-sm hover:border-gray-300 cursor-pointer">
                            <option value="" disabled selected>Pilih Kecamatan</option>
                            <option value="Kawali">Kawali</option>
                            <option value="Lumbung">Lumbung</option>
                        </select>
                    </div>

                    <!-- Kelurahan / Desa -->
                    <div>
                        <label class="block text-[13px] font-semibold text-gray-600 mb-2">
                            Desa/Kelurahan
                        </label>
                        <select name="kelurahan" id="desa" required class="w-full border border-gray-200 rounded-xl px-4 py-2.5 pr-10 text-[14px] text-gray-800 bg-white appearance-none bg-[url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%2218%22%20height%3D%2218%22%20viewBox%3D%220%200%2024%2024%22%20fill%3D%22none%22%20stroke%3D%22%236B7280%22%20stroke-width%3D%222.5%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%3E%3Cpath%20d%3D%22m6%209%206%206%206-6%22%2F%3E%3C%2Fsvg%3E')] bg-[length:18px] bg-[right_1rem_center] bg-no-repeat focus:outline-none focus:border-brand-blue focus:ring-2 focus:ring-brand-blue/20 transition-all shadow-sm hover:border-gray-300 cursor-pointer">
                            <option value="" disabled selected>Pilih Desa</option>
                            <option value="Kawali">Kawali</option>
                            <option value="Karangpawitan">Karangpawitan</option>
                        </select>
                    </div>

                    <!-- Kode Pos -->
                    <div>
                        <label class="block text-[13px] font-semibold text-gray-600 mb-2">
                            Kode Pos
                        </label>
                        <div class="relative">
                            <i class="ph ph-mailbox absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-lg"></i>
                            <input type="text" inputmode="numeric" oninput="this.value = this.value.replace(/[^0-9]/g, '')" name="kode_pos" value="{{ old('kode_pos') }}" required class="w-full border border-gray-200 rounded-xl pl-11 pr-4 py-2.5 text-[14px] text-gray-800 focus:outline-none focus:border-brand-blue focus:ring-2 focus:ring-brand-blue/20 transition-all shadow-sm">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit Button Section -->
            <div class="flex items-center justify-end gap-3 pt-6 border-t border-gray-100">
                <a href="{{ url('/admin/supervisor/datanasabah') }}" class="px-6 py-3 rounded-xl bg-gray-100 text-gray-700 font-bold text-[14px] hover:bg-gray-200 transition-colors">
                    Kembali
                </a>
                <button type="submit" id="btnSubmit" class="bg-gradient-to-r from-[#143657] to-[#316392] text-white px-7 py-3 rounded-xl text-[14px] font-bold flex items-center gap-2 hover:opacity-90 transition-all shadow-md">
                    <i class="ph-bold ph-floppy-disk text-lg"></i> Simpan Data Siswa
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
