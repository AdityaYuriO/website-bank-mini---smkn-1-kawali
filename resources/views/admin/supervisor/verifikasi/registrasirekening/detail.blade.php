@extends('layouts.admin')

@section('title', 'Supervisor - Detail Verifikasi Rekening')

@section('header_title')
    Detail Verifikasi Registrasi Rekening
@endsection

@section('header_subtitle', 'Informasi pendaftaran rekening nasabah.')

@section('styles')
<style>
    .fade-in { animation: fadeIn 0.3s ease-in-out; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
</style>
@endsection

@section('content')
@php
    $nasabah = $nasabah ?? (object)[
        'id' => 1,
        'nama_nasabah' => 'Ahmad Fauzi',
        'nis_nip' => '212210045',
        'tempat_lahir' => 'Ciamis',
        'tanggal_lahir' => '2006-08-15',
        'jenis_kelamin' => 'Laki-Laki',
        'jenis_identitas' => 'Kartu Pelajar',
        'agama' => 'Islam',
        'pendidikan' => 'SMK',
        'jabatan' => 'Siswa',
        'no_hp' => '081234567890',
        'email' => 'ahmad.fauzi@student.smkn1kawali.sch.id',
        'alamat' => 'Jl. Veteran No. 12, RT 02/05',
        'kode_pos' => '46253',
        'nama_kontak_darurat' => 'Bambang Sudarmono',
        'no_hp_kontak_darurat' => '081987654321',
        'hubungan_kontak_darurat' => 'Orang Tua (Ayah)',
        'alamat_kontak_darurat' => 'Jl. Veteran No. 12, RT 02/05, Kawali, Ciamis',
        'jurusan' => (object)['nama_jurusan' => 'Rekayasa Perangkat Lunak'],
        'desa' => (object)['name' => 'Kawali'],
        'kecamatan' => (object)['name' => 'Kawali'],
        'kabupaten' => (object)['name' => 'Ciamis'],
        'provinsi' => (object)['name' => 'Jawa Barat'],
        'rekening' => (object)['id' => '320701892003', 'status_akun' => 'pending'],
    ];
@endphp

<div id="viewDetailData" class="fade-in flex-1 mt-4">
    <div class="bg-white rounded-[24px] shadow-card p-6 md:p-10 w-full border border-gray-50 overflow-y-auto custom-scrollbar">
        <form>
            <!-- SECTION 1: DATA PRIBADI -->
            <div class="mb-12">
                <div class="flex items-center gap-3 mb-8">
                    <div class="w-[5px] h-6 bg-[#c0860b] rounded-full"></div>
                    <h3 class="text-[20px] font-bold text-gray-800">Data Pribadi</h3>
                </div>

                <div class="space-y-5">
                    <!-- Row 1 -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                        <div>
                            <label class="block text-[13px] font-semibold text-gray-500 mb-2">Nama Lengkap</label>
                            <input type="text" value="{{ $nasabah->nama_nasabah }}" id="detail_nama" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-[14px] text-gray-700 bg-white cursor-default focus:outline-none" readonly>
                        </div>
                        <div>
                            <label class="block text-[13px] font-semibold text-gray-500 mb-2">NIS/NIP</label>
                            <input type="text" value="{{ $nasabah->nis_nip }}" id="detail_nip" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-[14px] text-gray-700 bg-white cursor-default focus:outline-none" readonly>
                        </div>
                        <div>
                            <label class="block text-[13px] font-semibold text-gray-500 mb-2">Jurusan</label>
                            <input type="text" value="{{ $nasabah->jurusan->nama_jurusan ?? '-' }}" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-[14px] text-gray-700 bg-white cursor-default focus:outline-none" readonly>
                        </div>
                    </div>

                    <!-- Row 2 -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                        <div>
                            <label class="block text-[13px] font-semibold text-gray-500 mb-2">Tempat Lahir</label>
                            <input type="text" value="{{ $nasabah->tempat_lahir }}" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-[14px] text-gray-700 bg-white cursor-default focus:outline-none" readonly>
                        </div>
                        <div>
                            <label class="block text-[13px] font-semibold text-gray-500 mb-2">Tanggal Lahir</label>
                            <input type="text" value="{{ $nasabah->tanggal_lahir }}" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-[14px] text-gray-700 bg-white cursor-default focus:outline-none" readonly>
                        </div>
                        <div>
                            <label class="block text-[13px] font-semibold text-gray-500 mb-2">Jenis Kelamin</label>
                            <input type="text" value="{{ $nasabah->jenis_kelamin }}" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-[14px] text-gray-700 bg-white cursor-default focus:outline-none" readonly>
                        </div>
                    </div>

                    <!-- Row 3 -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                        <div>
                            <label class="block text-[13px] font-semibold text-gray-500 mb-2">Jenis Identitas Utama</label>
                            <input type="text" value="{{ $nasabah->jenis_identitas }}" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-[14px] text-gray-700 bg-white cursor-default focus:outline-none" readonly>
                        </div>
                        <div>
                            <label class="block text-[13px] font-semibold text-gray-500 mb-2">Agama</label>
                            <input type="text" value="{{ $nasabah->agama }}" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-[14px] text-gray-700 bg-white cursor-default focus:outline-none" readonly>
                        </div>
                        <div>
                            <label class="block text-[13px] font-semibold text-gray-500 mb-2">Pendidikan</label>
                            <input type="text" value="{{ $nasabah->pendidikan }}" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-[14px] text-gray-700 bg-white cursor-default focus:outline-none" readonly>
                        </div>
                    </div>

                    <!-- Row 4 -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                        <div>
                            <label class="block text-[13px] font-semibold text-gray-500 mb-2">Jabatan</label>
                            <input type="text" id="detail_jabatan" value="{{ $nasabah->jabatan }}" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-[14px] text-gray-700 bg-white cursor-default focus:outline-none" readonly>
                        </div>
                        <div>
                            <label class="block text-[13px] font-semibold text-gray-500 mb-2">Telepon Selular</label>
                            <input type="text" value="{{ $nasabah->no_hp }}" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-[14px] text-gray-700 bg-white cursor-default focus:outline-none" readonly>
                        </div>
                        <div>
                            <label class="block text-[13px] font-semibold text-gray-500 mb-2">Email</label>
                            <input type="text" value="{{ $nasabah->email }}" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-[14px] text-gray-700 bg-white cursor-default focus:outline-none" readonly>
                        </div>
                    </div>

                    <!-- Row 5: Alamat Complex -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-[13px] font-semibold text-gray-500 mb-2">Alamat</label>
                            <textarea class="w-full h-[155px] border border-gray-200 rounded-lg px-4 py-3 text-[14px] text-gray-700 bg-white cursor-default resize-none focus:outline-none" readonly>{{ $nasabah->alamat }}</textarea>
                        </div>
                        <div class="flex flex-col gap-5">
                            <div>
                                <label class="block text-[13px] font-semibold text-gray-500 mb-2">Kelurahan</label>
                                <input type="text" value="{{ $nasabah->desa->name ?? '-' }}" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-[14px] text-gray-700 bg-white cursor-default focus:outline-none" readonly>
                            </div>
                            <div>
                                <label class="block text-[13px] font-semibold text-gray-500 mb-2">Kecamatan</label>
                                <input type="text" value="{{ $nasabah->kecamatan->name ?? '-' }}" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-[14px] text-gray-700 bg-white cursor-default focus:outline-none" readonly>
                            </div>
                        </div>
                    </div>

                    <!-- Row 6 -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                        <div>
                            <label class="block text-[13px] font-semibold text-gray-500 mb-2">Kab/Kota</label>
                            <input type="text" value="{{ $nasabah->kabupaten->name ?? '-' }}" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-[14px] text-gray-700 bg-white cursor-default focus:outline-none" readonly>
                        </div>
                        <div>
                            <label class="block text-[13px] font-semibold text-gray-500 mb-2">Provinsi</label>
                            <input type="text" value="{{ $nasabah->provinsi->name ?? '-' }}" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-[14px] text-gray-700 bg-white cursor-default focus:outline-none" readonly>
                        </div>
                        <div>
                            <label class="block text-[13px] font-semibold text-gray-500 mb-2">Kode Pos</label>
                            <input type="text" value="{{ $nasabah->kode_pos }}" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-[14px] text-gray-700 bg-white cursor-default focus:outline-none" readonly>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SECTION 2: DATA PIHAK YANG DAPAT DIHUBUNGI -->
            <div class="mb-12">
                <div class="flex items-center gap-3 mb-8">
                    <div class="w-[5px] h-6 bg-[#c0860b] rounded-full"></div>
                    <h3 class="text-[20px] font-bold text-gray-800">Data Pihak yang Dapat Dihubungi</h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-5">
                    <div class="space-y-5">
                        <div>
                            <label class="block text-[13px] font-semibold text-gray-500 mb-2">Nama Lengkap</label>
                            <input type="text" value="{{ $nasabah->nama_kontak_darurat }}" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-[14px] text-gray-700 bg-white cursor-default focus:outline-none" readonly>
                        </div>
                        <div>
                            <label class="block text-[13px] font-semibold text-gray-500 mb-2">Telepon Selular</label>
                            <input type="text" value="{{ $nasabah->no_hp_kontak_darurat }}" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-[14px] text-gray-700 bg-white cursor-default focus:outline-none" readonly>
                        </div>
                        <div>
                            <label class="block text-[13px] font-semibold text-gray-500 mb-2">Hubungan dengan Pemohon</label>
                            <input type="text" value="{{ $nasabah->hubungan_kontak_darurat }}" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-[14px] text-gray-700 bg-white cursor-default focus:outline-none" readonly>
                        </div>
                    </div>
                    <div>
                        <label class="block text-[13px] font-semibold text-gray-500 mb-2">Alamat</label>
                        <textarea class="w-full h-[220px] border border-gray-200 rounded-lg px-4 py-3 text-[14px] text-gray-700 bg-white cursor-default resize-none focus:outline-none" readonly>{{ $nasabah->alamat_kontak_darurat }}</textarea>
                    </div>
                </div>
            </div>

            <!-- SECTION 3: DATA REKENING -->
            <div class="mb-12">
                <div class="flex items-center gap-3 mb-8">
                    <div class="w-[5px] h-6 bg-[#c0860b] rounded-full"></div>
                    <h3 class="text-[20px] font-bold text-gray-800">Data Rekening</h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-[13px] font-semibold text-gray-500 mb-2">No. Rekening</label>
                        <input type="text" value="{{ $nasabah->rekening->id ?? '-' }}" id="detail_rek" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-[14px] text-gray-700 bg-white cursor-default focus:outline-none font-bold" readonly>
                    </div>
                    <div>
                        <label class="block text-[13px] font-semibold text-gray-500 mb-2">Status Rekening</label>
                        <input type="text" value="{{ $nasabah->rekening->status_akun ?? '-' }}" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-[14px] text-gray-700 bg-white cursor-default focus:outline-none font-bold" readonly>
                    </div>
                </div>
            </div>

            <!-- BUTTONS -->
            <div class="flex justify-center mt-12">
                <a href="{{ url('/admin/supervisor/verifikasi/registrasirekening') }}" class="w-full md:w-[400px] bg-[#797979] hover:bg-gray-600 text-white font-bold py-4 rounded-xl transition-colors text-[15px] flex items-center justify-center">Kembali</a>
            </div>
        </form>
    </div>
</div>
@endsection
