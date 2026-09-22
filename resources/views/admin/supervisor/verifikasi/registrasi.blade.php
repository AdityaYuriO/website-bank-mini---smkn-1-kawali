@extends('layouts.supervisor')

@section('title','Supervisor Dashboard')
@section('header_title')
Selamat Datang, {{ $user->name }}!
@endsection
@section('header_subtitle', 'Lorem Ipsum is simply dummy text of the printing.')

@section('styles')
<style>
    /* Fade animation */
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
<div id="viewTabelData" class="fade-in flex flex-1 flex-col justify-start">
    <!-- Section Title & Tabs -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-4 px-1">
        <h3 class="text-[20px] sm:text-[22px] font-bold text-gray-800">Verifikasi Registrasi</h3>
        <div class="flex bg-gray-100 p-1 rounded-xl w-full sm:w-[300px]">
            <a href="{{ route('supervisor.verifikasi.login') }}" class="flex-1 px-2 sm:px-4 py-2 text-gray-500 font-medium text-xs sm:text-[13px] hover:text-brand-blue transition-colors text-center">Login</a>
            <a href="{{ route('supervisor.verifikasi.registrasi') }}" class="flex-1 px-2 sm:px-4 py-2 bg-white rounded-lg shadow-sm text-brand-blue font-bold text-xs sm:text-[13px] text-center transition-all">Registrasi</a>
            <a href="{{ route('supervisor.verifikasi') }}" class="flex-1 px-2 sm:px-4 py-2 text-gray-500 font-medium text-xs sm:text-[13px] hover:text-brand-blue transition-colors text-center">Transfer</a>
        </div>
    </div>

    <!-- Table Card / Content -->
    <div class="bg-white rounded-2xl sm:rounded-[20px] shadow-card p-4 md:p-6 w-full flex flex-col">
        <div class="flex flex-col md:flex-row justify-between items-stretch md:items-center gap-3 mb-4 pb-3 border-b border-gray-100">
            <form action="{{ route('supervisor.verifikasi.registrasi') }}" method="get" class="flex gap-2 items-center w-full md:w-auto">
                <div class="relative flex-1 md:flex-initial">
                    <i class="ph ph-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-lg"></i>
                    <input type="text" placeholder="Cari data..."
                        value="{{ request('keyword') }}" name="keyword"
                        class="w-full md:w-[250px] pl-12 pr-4 py-2 bg-white border border-gray-100 rounded-xl text-[14px] focus:outline-none focus:border-brand-blue focus:ring-1 focus:ring-brand-blue text-gray-700 placeholder-gray-400 shadow-sm transition-all">
                </div>

                <button type="submit" class="px-3.5 py-2 bg-brand-blue text-white text-[14px] font-medium rounded-xl shadow-sm hover:opacity-90 transition-all shrink-0">
                    <i class="ph ph-magnifying-glass text-lg"></i>
                </button>
            </form>
            <div class="flex items-center gap-2 justify-between md:justify-end w-full md:w-auto">
                <span class="text-xs sm:text-[13px] text-gray-600 font-medium">Tampilkan:</span>
                <select onchange="changePerPage(this.value)" class="bg-white border border-gray-200 text-gray-700 text-xs sm:text-[13px] rounded-[10px] px-3 py-1.5 font-semibold focus:outline-none focus:border-brand-blue shadow-sm cursor-pointer">
                    <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10 data</option>
                    <option value="20" {{ $perPage == 20 ? 'selected' : '' }}>20 data</option>
                    <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50 data</option>
                    <option value="100" {{ $perPage == 100 ? 'selected' : '' }}>100 data</option>
                </select>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr>
                        <th class="py-4 px-2 text-[#a3a3a3] font-medium text-[13px] w-12 border-b border-gray-100">No</th>
                        <th class="py-4 px-2 text-[#a3a3a3] font-medium text-[13px] border-b border-gray-100">Nama Lengkap</th>
                        <th class="py-4 px-2 text-[#a3a3a3] font-medium text-[13px] border-b border-gray-100">Jabatan</th>
                        <th class="py-4 px-2 text-[#a3a3a3] font-medium text-[13px] border-b border-gray-100">No Rekening</th>
                        <th class="py-4 px-2 text-[#a3a3a3] font-medium text-[13px] border-b border-gray-100">Status</th>
                        <th class="py-4 px-2 text-[#a3a3a3] font-medium text-[13px] text-center w-40 border-b border-gray-100">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-[14px] text-gray-800 font-medium">
                    <!-- Row 1 -->
                    @foreach ( $allNasabah as $index => $nasabah )
                    <tr class="hover:bg-gray-50/50 transition-colors">
                                                <td class="py-4 px-2 border-b border-gray-50 text-gray-600 pl-4">
                            {{ $allNasabah->firstItem() + $index }}.
                        </td>
                        <td class="py-4 px-2 border-b border-gray-50">{{ $nasabah->nama_nasabah }}</td>
                        <td class="py-4 px-2 border-b border-gray-50">{{ $nasabah->jabatan }}</td>
                        <td class="py-4 px-2 border-b border-gray-50">{{ $nasabah->rekening->id }}</td>
                        <td class="py-4 px-2 border-b border-gray-50 text-center">
                            <button class="w-[28px] h-[28px] rounded-full bg-[#fef3c7] text-[#d97706] inline-flex items-center justify-center cursor-default" title="Pending">
                                <i class="ph-bold ph-clock text-[15px]"></i>
                            </button>
                        </td>
                        <td class="py-4 px-2 border-b border-gray-50">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('detail.rekening.super', $nasabah->id) }}">
                                    <button class="w-[30px] h-[30px] rounded-full bg-[#e2e8f0] text-brand-blue flex items-center justify-center hover:bg-gray-300 transition-colors" title="Lihat Detail"><i class="ph-fill ph-eye text-[16px]"></i></button>
                                </a>
                                <form id="form-approve-rek-{{ $nasabah->rekening->id }}" action="{{ route('rekening.aktif', $nasabah->rekening->id ) }}" method="post" class="inline">
                                    @csrf
                                    <button type="button" onclick="openConfirmModal({
                                        title: 'Setujui Rekening?',
                                        message: 'Apakah Anda yakin ingin mengaktifkan akun rekening untuk <strong>{{ $nasabah->nama_nasabah }}</strong>?',
                                        type: 'success',
                                        confirmText: 'Ya, Aktifkan',
                                        onConfirm: () => document.getElementById('form-approve-rek-{{ $nasabah->rekening->id }}').submit()
                                    })" class="w-[30px] h-[30px] rounded-full bg-[#d1fae5] text-[#10a163] flex items-center justify-center hover:bg-green-200 transition-colors" title="Setujui"><i class="ph-bold ph-check-circle text-[16px]"></i></button>
                                </form>
                                <form id="form-delete-nasabah-{{ $nasabah->id }}" action="{{ route('hapus.nasabah.super', $nasabah->id) }}" method="post" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" onclick="openConfirmModal({
                                        title: 'Hapus & Tolak Registrasi?',
                                        message: 'Apakah Anda yakin ingin menolak dan menghapus pendaftaran <strong>{{ $nasabah->nama_nasabah }}</strong>?',
                                        type: 'danger',
                                        confirmText: 'Ya, Hapus',
                                        onConfirm: () => document.getElementById('form-delete-nasabah-{{ $nasabah->id }}').submit()
                                    })" class="w-[30px] h-[30px] rounded-full bg-[#fee2e2] text-red-500 flex items-center justify-center hover:bg-red-200 transition-colors" title="Tolak"><i class="ph-bold ph-x-circle text-[16px]"></i></button>
                                </form>
                                <a href="{{ route('halaman.revisi', $nasabah->id) }}">
                                    <button class="w-[30px] h-[30px] rounded-full bg-[#fef3c7] text-[#d97706] flex items-center justify-center hover:bg-[#fde68a] transition-colors" title="Revisi">
                                        <i class="ph-bold ph-arrow-counter-clockwise text-[16px]"></i>
                                    </button>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <x-pagination :paginator="$allNasabah" />

    </div>
</div>
<script>
    function changePerPage(value) {
        const url = new URL(window.location.href);
        url.searchParams.set('per_page', value);
        url.searchParams.set('page', 1); // Reset kembali ke halaman 1 setiap kali jumlah data diubah
        window.location.href = url.toString();
    }
</script>
@endsection
