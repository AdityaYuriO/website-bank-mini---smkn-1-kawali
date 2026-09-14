@extends('layouts.supervisor')

@section('title', 'Data GTK - Supervisor')

@section('header_title', 'Data GTK')
@section('header_subtitle', 'Formulir Tambah Data GTK')

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
<div class="fade-in flex flex-1 flex-col justify-start mb-8">
    
    <!-- Top Action Bar -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6 px-1">
        <div>
            <h3 class="text-[20px] md:text-[22px] font-bold text-gray-800 flex items-center gap-2">
                <i class="ph-fill ph-chalkboard-teacher text-brand-blue text-2xl"></i>
                Formulir Data GTK
            </h3>
            <p class="text-[13px] text-gray-500 font-medium mt-0.5">Lengkapi informasi GTK (Guru / Tenaga Kependidikan) untuk ditambahkan</p>
        </div>
    </div>

    <!-- Popup Modal Alert Sukses / Gagal (Kotak Popup Standar Web) -->
    @if (session('success') || session('error') || session('failed') || $errors->any())
    <div id="popupAlertModal" class="fixed inset-0 z-[100] flex items-center justify-center p-4">
        <!-- Overlay backdrop blur -->
        <div class="fixed inset-0 bg-black/40 backdrop-blur-sm transition-opacity duration-300" onclick="closePopupModal()"></div>

        <!-- Modal Content Container -->
        <div id="popupAlertContent" class="bg-white rounded-[28px] w-full max-w-[400px] p-8 shadow-2xl relative z-10 transform transition-all scale-95 opacity-0 duration-300">
            <div class="flex flex-col items-center text-center">

            @if (session('success'))
                <!-- Success Icon Badge -->
                <div class="w-20 h-20 rounded-full bg-emerald-50 flex items-center justify-center mb-6">
                    <i class="ph-fill ph-check-circle text-[48px] text-emerald-500"></i>
                </div>

                <!-- Title -->
                <h3 class="text-[22px] font-bold text-gray-900 mb-2">Input Data Berhasil!</h3>
                
                <!-- Description -->
                <p class="text-gray-500 text-[14px] leading-relaxed mb-8">
                    {{ session('success') }}
                </p>

                <!-- Action Button -->
                <div class="w-full">
                    <button onclick="closePopupModal()" type="button" class="w-full px-6 py-3.5 rounded-xl bg-emerald-500 hover:bg-emerald-600 text-white font-bold text-[14px] transition-colors shadow-lg shadow-emerald-500/20 active:scale-95 cursor-pointer">
                        Mengerti
                    </button>
                </div>

            @elseif (session('error') || session('failed') || $errors->any())
                <!-- Error Icon Badge -->
                <div class="w-20 h-20 rounded-full bg-red-50 flex items-center justify-center mb-6">
                    <i class="ph-fill ph-warning-circle text-[48px] text-red-500"></i>
                </div>

                <!-- Title -->
                <h3 class="text-[22px] font-bold text-gray-900 mb-2">Gagal Menyimpan Data</h3>
                
                <!-- Description / Errors -->
                <div class="w-full text-left bg-red-50 p-4 rounded-xl border border-red-100 mb-8 max-h-48 overflow-y-auto custom-scrollbar">
                    @if(session('error'))
                        <p class="font-medium text-red-700 text-center mb-2 text-[14px]">{{ session('error') }}</p>
                    @endif

                    @if(session('failed'))
                        <p class="font-medium text-red-700 text-center mb-2 text-[14px]">{{ session('failed') }}</p>
                    @endif

                    @if($errors->any())
                        <div class="font-bold text-red-800 mb-1 flex items-center gap-1.5 text-[13px]">
                            <i class="ph-bold ph-warning text-base"></i> Terdapat kesalahan formulir:
                        </div>
                        <ul class="list-disc pl-5 space-y-1 text-red-700 text-[13px]">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    @endif
                </div>

                <!-- Action Button -->
                <div class="w-full">
                    <button onclick="closePopupModal()" type="button" class="w-full px-6 py-3.5 rounded-xl bg-red-500 text-white font-bold text-[14px] hover:bg-red-600 transition-colors shadow-lg shadow-red-500/30 cursor-pointer">
                        Perbaiki Data
                    </button>
                </div>
            @endif

            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const modal = document.getElementById('popupAlertModal');
            const content = document.getElementById('popupAlertContent');
            if (modal && content) {
                setTimeout(() => {
                    content.classList.remove('scale-95', 'opacity-0');
                    content.classList.add('scale-100', 'opacity-100');
                }, 50);
            }
        });

        function closePopupModal() {
            const modal = document.getElementById('popupAlertModal');
            const content = document.getElementById('popupAlertContent');
            if (!modal || !content) return;

            content.classList.remove('scale-100', 'opacity-100');
            content.classList.add('scale-95', 'opacity-0');
            setTimeout(() => {
                modal.remove();
            }, 250);
        }

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closePopupModal();
            }
        });
    </script>
    @endif

    <!-- Form Container Card -->
    <div class="bg-white rounded-[24px] shadow-card p-6 md:p-10 w-full border border-gray-50">
        <form action="{{ route('datamaster.gtk') }}" method="POST" id="formMasterGTK">
            @csrf

            <!-- SECTION 1: INFORMASI DATA PRIBADI & KEPEGAWAIAN GTK -->
            <div class="mb-10">
                <div class="flex items-center gap-3 mb-6 pb-3 border-b border-gray-100">
                    <div class="w-[5px] h-6 bg-brand-blue rounded-full"></div>
                    <h4 class="text-[18px] font-bold text-gray-800">Informasi Pribadi & Kepegawaian</h4>
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

                    <!-- Jabatan -->
                    <div>
                        <label class="block text-[13px] font-semibold text-gray-600 mb-2">
                            Jabatan
                        </label>
                        <select name="jabatan" required class="w-full border border-gray-200 rounded-xl px-4 py-2.5 pr-10 text-[14px] text-gray-800 bg-white appearance-none bg-[url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%2218%22%20height%3D%2218%22%20viewBox%3D%220%200%2024%2024%22%20fill%3D%22none%22%20stroke%3D%22%236B7280%22%20stroke-width%3D%222.5%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%3E%3Cpath%20d%3D%22m6%209%206%206%206-6%22%2F%3E%3C%2Fsvg%3E')] bg-[length:18px] bg-[right_1rem_center] bg-no-repeat focus:outline-none focus:border-brand-blue focus:ring-2 focus:ring-brand-blue/20 transition-all shadow-sm hover:border-gray-300 cursor-pointer">
                            <option value="" disabled {{ old('jabatan') ? '' : 'selected' }}>Pilih Jabatan</option>
                            <option value="Guru" {{ old('jabatan') == 'Guru' ? 'selected' : '' }}>Guru</option>
                            <option value="TU" {{ old('jabatan') == 'TU' ? 'selected' : '' }}>TU (Tata Usaha / Tenaga Kependidikan)</option>
                        </select>
                    </div>

                    <!-- NUPTK -->
                    <div>
                        <label class="block text-[13px] font-semibold text-gray-600 mb-2">
                            NUPTK
                        </label>
                        <div class="relative">
                            <i class="ph ph-identification-badge absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-lg"></i>
                            <input type="text" inputmode="numeric" oninput="this.value = this.value.replace(/[^0-9]/g, '')" name="nuptk" value="{{ old('nuptk') }}" required class="w-full border border-gray-200 rounded-xl pl-11 pr-4 py-2.5 text-[14px] text-gray-800 focus:outline-none focus:border-brand-blue focus:ring-2 focus:ring-brand-blue/20 transition-all shadow-sm">
                        </div>
                    </div>

                    <!-- NIP -->
                    <div>
                        <label class="block text-[13px] font-semibold text-gray-600 mb-2">
                            NIP
                        </label>
                        <div class="relative">
                            <i class="ph ph-identification-card absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-lg"></i>
                            <input type="text" inputmode="numeric" oninput="this.value = this.value.replace(/[^0-9]/g, '')" name="nip" value="{{ old('nip') }}" required class="w-full border border-gray-200 rounded-xl pl-11 pr-4 py-2.5 text-[14px] text-gray-800 focus:outline-none focus:border-brand-blue focus:ring-2 focus:ring-brand-blue/20 transition-all shadow-sm">
                        </div>
                    </div>

                    <!-- NIK -->
                    <div>
                        <label class="block text-[13px] font-semibold text-gray-600 mb-2">
                            NIK
                        </label>
                        <div class="relative">
                            <i class="ph ph-cardholder absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-lg"></i>
                            <input type="text" inputmode="numeric" oninput="this.value = this.value.replace(/[^0-9]/g, '')" name="nik" value="{{ old('nik') }}" required class="w-full border border-gray-200 rounded-xl pl-11 pr-4 py-2.5 text-[14px] text-gray-800 focus:outline-none focus:border-brand-blue focus:ring-2 focus:ring-brand-blue/20 transition-all shadow-sm">
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
                        </select>
                    </div>

                    <!-- Kecamatan -->
                    <div>
                        <label class="block text-[13px] font-semibold text-gray-600 mb-2">
                            Kecamatan
                        </label>
                        <select name="kecamatan" id="kecamatan" required class="w-full border border-gray-200 rounded-xl px-4 py-2.5 pr-10 text-[14px] text-gray-800 bg-white appearance-none bg-[url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%2218%22%20height%3D%2218%22%20viewBox%3D%220%200%2024%2024%22%20fill%3D%22none%22%20stroke%3D%22%236B7280%22%20stroke-width%3D%222.5%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%3E%3Cpath%20d%3D%22m6%209%206%206%206-6%22%2F%3E%3C%2Fsvg%3E')] bg-[length:18px] bg-[right_1rem_center] bg-no-repeat focus:outline-none focus:border-brand-blue focus:ring-2 focus:ring-brand-blue/20 transition-all shadow-sm hover:border-gray-300 cursor-pointer">
                            <option value="" disabled selected>Pilih Kecamatan</option>
                        </select>
                    </div>

                    <!-- Kelurahan / Desa -->
                    <div>
                        <label class="block text-[13px] font-semibold text-gray-600 mb-2">
                            Desa/Kelurahan
                        </label>
                        <select name="kelurahan" id="desa" required class="w-full border border-gray-200 rounded-xl px-4 py-2.5 pr-10 text-[14px] text-gray-800 bg-white appearance-none bg-[url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%2218%22%20height%3D%2218%22%20viewBox%3D%220%200%2024%2024%22%20fill%3D%22none%22%20stroke%3D%22%236B7280%22%20stroke-width%3D%222.5%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%3E%3Cpath%20d%3D%22m6%209%206%206%206-6%22%2F%3E%3C%2Fsvg%3E')] bg-[length:18px] bg-[right_1rem_center] bg-no-repeat focus:outline-none focus:border-brand-blue focus:ring-2 focus:ring-brand-blue/20 transition-all shadow-sm hover:border-gray-300 cursor-pointer">
                            <option value="" disabled selected>Pilih Desa</option>
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
                <button type="submit" id="btnSubmit" class="bg-gradient-to-r from-[#143657] to-[#316392] text-white px-7 py-3 rounded-xl text-[14px] font-bold flex items-center gap-2 hover:opacity-90 transition-all shadow-md cursor-pointer">
                    <i class="ph-bold ph-floppy-disk text-lg"></i> Simpan Data GTK
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
$(document).ready(function () {

    // ==========================
    // PROVINSI -> KABUPATEN
    // ==========================
    $('#provinsi').change(function () {
        let id = $(this).val();

        $('#kabupaten').html('<option value="" disabled selected>Pilih Kabupaten</option>');
        $('#kecamatan').html('<option value="" disabled selected>Pilih Kecamatan</option>');
        $('#desa').html('<option value="" disabled selected>Pilih Desa</option>');

        if (!id) return;

        $.ajax({
            url: '/get-kabupaten/' + id,
            type: 'GET',
            success: function(data){
                $.each(data, function(index, item){
                    $('#kabupaten').append(
                        '<option value="'+item.id+'">'+item.name+'</option>'
                    );
                });
            },
            error: function(xhr){
                console.error('Gagal mengambil data kabupaten:', xhr.responseText);
            }
        });
    });

    // ==========================
    // KABUPATEN -> KECAMATAN
    // ==========================
    $('#kabupaten').change(function () {
        let id = $(this).val();

        $('#kecamatan').html('<option value="" disabled selected>Pilih Kecamatan</option>');
        $('#desa').html('<option value="" disabled selected>Pilih Desa</option>');

        if (!id) return;

        $.ajax({
            url: '/get-kecamatan/' + id,
            type: 'GET',
            success: function(data){
                $.each(data, function(index, item){
                    $('#kecamatan').append(
                        '<option value="'+item.id+'">'+item.name+'</option>'
                    );
                });
            },
            error: function(xhr){
                console.error('Gagal mengambil data kecamatan:', xhr.responseText);
            }
        });
    });

    // ==========================
    // KECAMATAN -> DESA
    // ==========================
    $('#kecamatan').change(function () {
        let id = $(this).val();

        $('#desa').html('<option value="" disabled selected>Pilih Desa</option>');

        if (!id) return;

        $.ajax({
            url: '/get-desa/' + id,
            type: 'GET',
            success: function(data){
                $.each(data, function(index, item){
                    $('#desa').append(
                        '<option value="'+item.id+'">'+item.name+'</option>'
                    );
                });
            },
            error: function(xhr){
                console.error('Gagal mengambil data desa:', xhr.responseText);
            }
        });
    });

    // ==========================
    // Form Submit Indicator
    // ==========================
    $('#formMasterGTK').on('submit', function() {
        let btn = $('#btnSubmit');
        btn.prop('disabled', true);
        btn.html('<i class="ph ph-spinner animate-spin text-lg"></i> Menyimpan...');
    });
});
</script>
@endsection
