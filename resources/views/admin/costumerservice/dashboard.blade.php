@extends('layouts.admin')

@section('title', 'Customer Service - Dashboard')
@section('header_title')
    Selamat Datang, {{ $user->name ?? 'Administrator' }}!
@endsection
@section('header_subtitle', 'Pantau ringkasan statistik, aktivitas transaksi, dan statistik kelola nasabah Anda hari ini secara real-time.')

@section('content')
@php
    $jumlahNasabah = $jumlahNasabah ?? 142;
    $revisi = $revisi ?? 3;
    $jumlahPending = $jumlahPending ?? 5;
    $nasabah = $nasabah ?? collect([
        (object)[
            'id' => 1,
            'nama_nasabah' => 'Muhammad Rizky Pratama',
            'rekening' => (object)['status_akun' => 'non-aktif', 'id' => '320701892001']
        ],
        (object)[
            'id' => 2,
            'nama_nasabah' => 'Siti Aisyah',
            'rekening' => (object)['status_akun' => 'revisi', 'id' => '320701892002']
        ],
        (object)[
            'id' => 3,
            'nama_nasabah' => 'Budi Santoso, S.Pd.',
            'rekening' => (object)['status_akun' => 'non-aktif', 'id' => '320701892003']
        ],
    ]);
@endphp

<div id="viewMain" class="fade-in block">
<!-- Statistics Cards -->
<section class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-7">
    <!-- Total Nasabah -->
    <div class="bg-primary-gradient rounded-2xl p-6 text-white relative overflow-hidden shadow-lg h-[130px] flex flex-col justify-center">
        <div class="relative z-10">
            <p class="text-[11px] font-semibold tracking-[0.08em] text-blue-100/80 mb-2.5 uppercase">Total Nasabah</p>
            <h3 class="text-[28px] md:text-[32px] font-bold">{{ $jumlahNasabah }}</h3>
        </div>
        <div class="absolute -right-2 -bottom-2 flex opacity-10">
            <i class="ph-fill ph-users text-[120px] translate-y-6 translate-x-4"></i>
        </div>
    </div>

    <!-- Nasabah Hari Ini / Revisi -->
    <div class="bg-success-gradient rounded-2xl p-6 text-white relative overflow-hidden shadow-lg h-[130px] flex flex-col justify-center">
        <div class="relative z-10">
            <p class="text-[11px] font-semibold tracking-[0.08em] text-green-100/80 mb-2.5 uppercase">Revisi</p>
            <h3 class="text-[28px] md:text-[32px] font-bold">{{ $revisi }}</h3>
        </div>
        <div class="absolute -right-2 -bottom-2 flex opacity-10">
            <i class="ph-fill ph-users text-[120px] translate-y-6 translate-x-4"></i>
        </div>
    </div>

    <!-- Pending Verifikasi -->
    <div class="bg-warning-gradient rounded-2xl p-6 text-white relative overflow-hidden shadow-lg h-[130px] flex flex-col justify-center">
        <div class="relative z-10">
            <p class="text-[11px] font-semibold tracking-[0.08em] text-yellow-100/80 mb-2.5 uppercase">Pending Verifikasi</p>
            <h3 class="text-[28px] md:text-[32px] font-bold">{{ $jumlahPending }}</h3>
        </div>
        <div class="absolute -right-2 -bottom-2 flex opacity-10">
            <i class="ph-fill ph-users text-[120px] translate-y-6 translate-x-4"></i>
        </div>
    </div>
</section>

<!-- Bottom Content -->
<section class="grid grid-cols-1 lg:grid-cols-12 gap-6">
    <!-- Left Column: Aktivitas Terkini -->
    <div class="lg:col-span-8 flex flex-col gap-3.5">
        <div class="flex justify-between items-end mb-1 px-1">
            <h3 class="text-[20px] font-bold text-gray-800">Aktivitas Terkini</h3>
            <button onclick="switchView('history')" class="text-[12px] font-semibold text-gray-800 hover:text-brand-blue">Lihat Semua</button>
        </div>

        <div class="flex flex-col gap-3">
            <!-- Item 1 -->
            @foreach ( $nasabah as $nsbh )
                @if ( $nsbh->rekening->status_akun == 'non-aktif' )
                <div class="bg-white rounded-[16px] p-4 px-5 flex items-center justify-between shadow-card">
                    <div class="flex items-center gap-3.5">
                        <i class="ph-fill ph-user-circle text-[32px] md:text-[40px] text-brand-blue"></i>
                        <div>
                            <h4 class="font-bold text-[14px] md:text-[15px] text-gray-800">{{ $nsbh->nama_nasabah }}</h4>
                            <p class="text-[11px] md:text-[12px] text-gray-500 mt-0.5">Registrasi Akun Baru</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <a href="{{ url('/admin/costumerservice/keloladata') }}">
                        <button class="w-[28px] md:w-[30px] h-[28px] md:h-[30px] rounded-full bg-[#e2e8f0] text-brand-blue flex items-center justify-center hover:bg-gray-300 transition-colors">
                            <i class="ph-fill ph-eye text-[14px] md:text-[16px]"></i>
                        </button>
                        </a>
                        <button class="w-[28px] md:w-[30px] h-[28px] md:h-[30px] rounded-full bg-red-100 flex items-center justify-center cursor-default">
                            <i class="ph ph-x-circle text-[20px] text-red-800 " title="Non Aktif"></i>
                        </button>
                    </div>
                </div>
                @elseif ($nsbh->rekening->status_akun == 'revisi')
                <div class="bg-white rounded-[16px] p-4 px-5 flex items-center justify-between shadow-card">
                    <div class="flex items-center gap-3.5">
                        <i class="ph-fill ph-user-circle text-[32px] md:text-[40px] text-brand-blue"></i>
                        <div>
                            <h4 class="font-bold text-[14px] md:text-[15px] text-gray-800">{{ $nsbh->nama_nasabah }}</h4>
                            <p class="text-[11px] md:text-[12px] text-gray-500 mt-0.5">Revisi Akun</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <a href="{{ url('/admin/costumerservice/keloladata') }}">
                        <button class="w-[28px] md:w-[30px] h-[28px] md:h-[30px] rounded-full bg-[#e2e8f0] text-brand-blue flex items-center justify-center hover:bg-gray-300 transition-colors">
                            <i class="ph-fill ph-eye text-[14px] md:text-[16px]"></i>
                        </button>
                        </a>
                        <button class="w-[28px] md:w-[30px] h-[28px] md:h-[30px] rounded-full bg-[#fef3c7] text-[#d97706] flex items-center justify-center cursor-default">
                            <i class="ph-bold ph-warning-circle text-[15px]"></i>
                        </button>
                    </div>
                </div>
                @endif
            @endforeach
        </div>
    </div>

    <!-- Right Column: Info & Aksi Cepat -->
    <div class="lg:col-span-4 flex flex-col gap-6">
        <div class="bg-[#fce8e8] rounded-[20px] p-5 shadow-sm border border-red-100">
            <div class="flex items-center gap-2 mb-2 text-[#d32f2f]">
                <i class="ph-fill ph-warning text-[20px]"></i>
                <h4 class="font-bold text-[15px]">Integritas & Keamanan</h4>
            </div>
            <p class="text-[12px] text-[#d32f2f] leading-relaxed">
                Pastikan setiap proses verifikasi data nasabah dilakukan sesuai dengan standar operasional Bank Mini SMKN 1 Kawali.
            </p>
        </div>

        <div class="flex flex-col gap-3.5 mb-5">
            <div class="px-1 mb-1">
                <h3 class="text-[20px] font-bold text-gray-800">Aksi Cepat</h3>
            </div>
            <button onclick="window.location.href='{{ url('/admin/costumerservice/keloladata') }}?view=tambah'" class="bg-white rounded-[16px] p-4 flex items-center gap-4 shadow-card hover:bg-gray-50 transition-colors text-left w-full border border-transparent hover:border-gray-100">
                <div class="text-brand-blue bg-[#f8f9fb] p-2 rounded-lg">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="4" width="18" height="16" rx="2" ry="2"/><line x1="8" y1="8" x2="8" y2="12"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="16" y1="8" x2="16" y2="12"/><line x1="8" y1="16" x2="16" y2="16"/>
                    </svg>
                </div>
                <div>
                    <h4 class="font-bold text-[15px] text-brand-blue mb-0.5">Buka Rekening</h4>
                    <p class="text-[11px] text-gray-500">Proses pembukaan buku rekening</p>
                </div>
            </button>
        </div>
    </div>
</section>
</div>

<!-- VIEW HISTORY: Semua Aktivitas (10 Terakhir) -->
<div id="viewHistory" class="fade-in hidden">
    <div class="bg-white rounded-[24px] shadow-[0_4px_24px_rgba(0,0,0,0.02)] border border-gray-50 p-8 lg:p-10">
        <div class="flex justify-between items-center mb-10">
            <button onclick="switchView('main')" class="text-[12px] lg:text-[14px] font-bold text-gray-800 hover:text-brand-blue transition-colors flex items-center gap-2">
                Kembali
            </button>
            <h3 class="text-[14px] lg:text-[22px] font-bold text-gray-800">Riwayat Aktivitas</h3>
        </div>

        <div class="lg:space-y-5">
            <!-- Row 1 -->
            @foreach ($nasabah as $nsbh )
            <div class="flex justify-between items-center bg-white p-4 rounded-[20px] border border-gray-50 hover:border-gray-100 hover:shadow-sm transition-all">
                <div class="flex items-center gap-4">
                    <i class="ph-fill ph-user-circle text-[30px] lg:text-[40px] text-brand-blue"></i>
                    <div>
                        <h4 class="font-bold text-[13px] lg:text-[15px] text-gray-800">{{ $nsbh->nama_nasabah }}</h4>
                        <p class="text-[10px] lg:text-[12px] text-gray-500 mt-0.5">Registrasi Akun Baru • </p>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <a href="{{ url('/admin/costumerservice/keloladata') }}">
                    <button class="w-[32px] h-[32px] rounded-full bg-[#f1f5f9] text-brand-blue flex items-center justify-center hover:bg-gray-200 transition-colors">
                        <i class="ph-fill ph-eye text-[16px]"></i>
                    </button>
                    </a>
                    <button class="w-[32px] h-[32px] rounded-full bg-[#fef3c7] text-[#d97706] flex items-center justify-center cursor-default">
                        <i class="ph-bold ph-clock text-[16px]"></i>
                    </button>
                </div>
            </div>
            @endforeach
            <!-- Pagination -->
            <x-pagination :total="1" />
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function switchView(viewName) {
        const mainView = document.getElementById('viewMain');
        const historyView = document.getElementById('viewHistory');

        if (viewName === 'history') {
            mainView.classList.remove('block');
            mainView.classList.add('hidden');
            historyView.classList.remove('hidden');
            historyView.classList.add('block');
        } else {
            historyView.classList.remove('block');
            historyView.classList.add('hidden');
            mainView.classList.remove('hidden');
            mainView.classList.add('block');
        }

        window.scrollTo({ top: 0, behavior: 'smooth' });
        const scrollableMain = document.querySelector('main');
        if(scrollableMain) scrollableMain.scrollTo({ top: 0, behavior: 'smooth' });
    }
</script>
@endsection
