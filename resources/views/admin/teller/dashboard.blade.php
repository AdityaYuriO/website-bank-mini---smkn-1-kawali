@extends('layouts.admin')

@section('title', 'Teller Dashboard')
@section('header_title')
Selamat Datang, {{ $user->name ?? 'Administrator' }}!
@endsection
@section('header_subtitle', 'Pantau ringkasan transaksi kas, setoran, penarikan, dan perputaran saldo harian Anda secara real-time.')

@section('content')
@php
    $totalTabungan = $totalTabungan ?? 145850000;
    $setoranHariIni = $setoranHariIni ?? 6250000;
    $penarikanHariIni = $penarikanHariIni ?? 1800000;
    $biayaAdminSetoran = $biayaAdminSetoran ?? 0;
    $biayaAdminPenarikan = $biayaAdminPenarikan ?? 0;
    $biayaAdminTransfer = $biayaAdminTransfer ?? 2500;

    $transactions = $transactions ?? collect([
        (object)[
            'jumlah_penyetoran' => 500000,
            'is_setor' => true,
            'created_at' => \Carbon\Carbon::now()->subMinutes(20),
            'rekening' => (object)[
                'nasabah' => (object)['nama_nasabah' => 'Muhammad Rizky Pratama']
            ]
        ],
        (object)[
            'jumlah_penarikan' => 200000,
            'is_setor' => false,
            'created_at' => \Carbon\Carbon::now()->subHours(1),
            'rekening' => (object)[
                'nasabah' => (object)['nama_nasabah' => 'Dewi Sartika, S.Pd.']
            ]
        ],
        (object)[
            'jumlah_penyetoran' => 1500000,
            'is_setor' => true,
            'created_at' => \Carbon\Carbon::now()->subHours(2),
            'rekening' => (object)[
                'nasabah' => (object)['nama_nasabah' => 'Ahmad Fauzi']
            ]
        ],
        (object)[
            'jumlah_penyetoran' => 750000,
            'is_setor' => true,
            'created_at' => \Carbon\Carbon::now()->subHours(3),
            'rekening' => (object)[
                'nasabah' => (object)['nama_nasabah' => 'Siti Nurhaliza']
            ]
        ],
        (object)[
            'jumlah_penarikan' => 300000,
            'is_setor' => false,
            'created_at' => \Carbon\Carbon::now()->subHours(4),
            'rekening' => (object)[
                'nasabah' => (object)['nama_nasabah' => 'Budi Santoso']
            ]
        ],
    ]);
@endphp

<div id="viewMain" class="fade-in block">
    <!-- Statistics Cards -->
    <section class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-7">
        <!-- Jumlah Tabungan -->
        <div class="bg-primary-gradient rounded-2xl p-6 text-white relative overflow-hidden shadow-lg h-[130px] flex flex-col justify-center">
            <div class="relative z-10">
                <p class="text-[11px] font-semibold tracking-[0.08em] text-blue-100/80 mb-2.5 uppercase">Jumlah Tabungan</p>
                <h3 class="text-[24px] font-bold">
                    Rp. {{ number_format($totalTabungan, 0, ',', '.') }}
                </h3>
            </div>
            <div class="absolute -right-2 -bottom-2 flex opacity-10"><i class="ph-fill ph-users text-[120px] translate-y-6 translate-x-4"></i></div>
        </div>

        <!-- Setoran Hari Ini -->
        <div class="bg-success-gradient rounded-2xl p-6 text-white relative overflow-hidden shadow-lg h-[130px] flex flex-col justify-center">
            <div class="relative z-10">
                <p class="text-[11px] font-semibold tracking-[0.08em] text-green-100/80 mb-2.5 uppercase">Setoran Hari Ini</p>
                <h3 class="text-[24px] font-bold">Rp. {{ number_format($setoranHariIni, 0, ',', '.') }}</h3>
            </div>
            <div class="absolute -right-2 -bottom-2 flex opacity-10"><i class="ph-fill ph-users text-[120px] translate-y-6 translate-x-4"></i></div>
        </div>

        <!-- Penarikan Hari Ini -->
        <div class="bg-warning-gradient rounded-2xl p-6 text-white relative overflow-hidden shadow-lg h-[130px] flex flex-col justify-center">
            <div class="relative z-10">
                <p class="text-[11px] font-semibold tracking-[0.08em] text-yellow-100/80 mb-2.5 uppercase">Penarikan Hari Ini</p>
                <h3 class="text-[24px] font-bold">Rp. {{ number_format($penarikanHariIni, 0, ',', '.') }}</h3>
            </div>
            <div class="absolute -right-2 -bottom-2 flex opacity-10"><i class="ph-fill ph-users text-[120px] translate-y-6 translate-x-4"></i></div>
        </div>
    </section>

    <!-- Bottom Content -->
    <section class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        <!-- Left Column: Transaksi Terbaru -->
        <div class="lg:col-span-8 flex flex-col gap-3.5">

            <div class="flex justify-between items-end mb-1 px-1">
                <h3 class="text-[20px] font-bold text-gray-800">Transaksi Terbaru</h3>
                <button onclick="switchView('history')" class="text-[12px] font-semibold text-gray-800 hover:text-brand-blue">Lihat Semua</button>
            </div>

            <div class="flex flex-col gap-3">
                @foreach($transactions->take(3) as $t)
                @php
                $isSetor = isset($t->is_setor) ? $t->is_setor : ($t instanceof \App\Models\Setoran);
                $namaNasabah = $t->rekening->nasabah->nama_nasabah ?? '-';
                $jenis = $isSetor ? 'Setor Tunai' : 'Tarik Tunai';
                $nominal = $isSetor ? ($t->jumlah_penyetoran ?? 0) : ($t->jumlah_penarikan ?? 0);
                $color = $isSetor ? 'text-[#10a163]' : 'text-[#ef4444]';
                $prefix = $isSetor ? '+ ' : '- ';
                @endphp
                <div class="bg-white rounded-[16px] p-4 px-5 flex items-center justify-between shadow-card">
                    <div class="flex items-center gap-3.5">
                        <i class="ph-fill ph-user-circle text-[40px] text-brand-blue"></i>
                        <div>
                            <h4 class="font-bold text-[15px] text-gray-800">{{ $namaNasabah }}</h4>
                            <p class="text-[12px] text-gray-500 mt-0.5">{{ $jenis }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-4">
                        <span class="font-bold text-[15px] {{ $color }}">
                            {{ $prefix }} Rp. {{ number_format($nominal, 0, ',', '.') }}
                        </span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Right Column: Aksi Cepat -->
        <div class="lg:col-span-4 flex flex-col gap-3.5 mb-5">
            <div class="mb-1 px-1">
                <h3 class="text-[20px] font-bold text-gray-800">Aksi Cepat</h3>
            </div>
            <div class="flex flex-col gap-3">
                <div class="bg-white rounded-[16px] p-4 flex items-center gap-4 shadow-card hover:bg-gray-50 transition-colors text-left w-full border border-transparent hover:border-gray-100">
                    <div class="text-brand-blue bg-[#f8f9fb] p-2 rounded-lg">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="2" y="6" width="20" height="12" rx="2"/><circle cx="12" cy="12" r="2"/><path d="M6 12h.01M18 12h.01"/></svg>
                    </div>
                    <div>
                        <h4 class="font-bold text-[15px] text-brand-blue mb-0.5">Biaya Admin Setoran</h4>
                        <p class="text-[11px] text-gray-500">Rp. {{ number_format($biayaAdminSetoran, 0, ',', '.') }}</p>
                    </div>
                </div>
                <div class="bg-white rounded-[16px] p-4 flex items-center gap-4 shadow-card hover:bg-gray-50 transition-colors text-left w-full border border-transparent hover:border-gray-100">
                    <div class="text-brand-blue bg-[#f8f9fb] p-2 rounded-lg">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 12V7H5a2 2 0 0 1 0-4h14v4"/><path d="M3 5v14a2 2 0 0 0 2 2h16v-5"/><path d="M18 12a2 2 0 0 0 0 4h4v-4Z"/></svg>
                    </div>
                    <div>
                        <h4 class="font-bold text-[15px] text-brand-blue mb-0.5">Biaya Admin Penarikan</h4>
                        <p class="text-[11px] text-gray-500">Rp. {{ number_format($biayaAdminPenarikan, 0, ',', '.') }}</p>
                    </div>
                </div>
                <div class="bg-white rounded-[16px] p-4 flex items-center gap-4 shadow-card hover:bg-gray-50 transition-colors text-left w-full border border-transparent hover:border-gray-100">
                    <div class="text-brand-blue bg-[#f8f9fb] p-2 rounded-lg">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M17 3l4 4-4 4"/><path d="M3 7h18"/><path d="M7 21l-4-4 4-4"/><path d="M21 17H3"/></svg>
                    </div>
                    <div>
                        <h4 class="font-bold text-[15px] text-brand-blue mb-0.5">Biaya Admin Transfer</h4>
                        <p class="text-[11px] text-gray-500">Rp. {{ number_format($biayaAdminTransfer, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- VIEW HISTORY: Semua Transaksi (10 Terakhir) -->
<div id="viewHistory" class="fade-in hidden">
    <div class="bg-white rounded-[24px] shadow-[0_4px_24px_rgba(0,0,0,0.02)] border border-gray-50 p-8 lg:p-10">
        <div class="flex justify-between items-center gap-4 mb-8 md:mb-10">
            <button onclick="switchView('main')" class="text-[10px] lg:text-[14px] font-bold text-gray-800 hover:text-brand-blue transition-colors flex items-center gap-2 w-fit">
                <i class="ph ph-arrow-left"></i> Kembali
            </button>
            <h3 class="text-[12px] lg:text-[22px] font-bold text-gray-800">Semua Transaksi</h3>
        </div>

        <div class="lg:space-y-5" id="transactionList">
            @forelse($transactions as $at)
            @php
            $isSetor = isset($at->is_setor) ? $at->is_setor : ($at instanceof \App\Models\Setoran);
            $nominal = $isSetor ? ($at->jumlah_penyetoran ?? 0) : ($at->jumlah_penarikan ?? 0);
            $color = $isSetor ? '#10a163' : '#ef4444';
            @endphp
            <div class="transaction-item flex justify-between items-center bg-white p-4 rounded-[20px] border border-gray-50">
                <div class="flex items-center gap-2">
                    <i class="ph-fill ph-user-circle text-[40px] text-brand-blue"></i>
                    <div>
                        <h4 class="font-bold text-[10px] lg:text-[15px] text-gray-800">
                            {{ $at->rekening->nasabah->nama_nasabah ?? '-' }}
                        </h4>
                        <p class="text-[8px] lg:text-[12px] text-gray-500 mt-0.5">
                            {{ $isSetor ? 'Setor Tunai' : 'Tarik Tunai' }} • {{ $at->created_at->format('H:i') }}
                        </p>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <span class="font-bold text-[10px] lg:text-[15px]" style="color: {{ $color }}">
                        {{ $isSetor ? '+' : '-' }} Rp. {{ number_format($nominal, 0, ',', '.') }}
                    </span>
                </div>
            </div>
            @empty
            <div class="text-center text-gray-400 py-8 font-medium">Tidak ada data transaksi</div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div id="paginationContainer"></div>

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

        // Scroll top
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
        const scrollableMain = document.querySelector('main');
        if (scrollableMain) scrollableMain.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        const itemsPerPage = 5;
        const items = document.querySelectorAll('#transactionList .transaction-item');
        const paginationContainer = document.getElementById('paginationContainer');
        let currentPage = 1;

        const totalItems = items.length;
        const totalPages = Math.ceil(totalItems / itemsPerPage);

        function showPage(page) {
            currentPage = page;

            items.forEach((item, index) => {
                if (index >= (page - 1) * itemsPerPage && index < page * itemsPerPage) {
                    item.style.setProperty('display', 'flex', 'important');
                } else {
                    item.style.setProperty('display', 'none', 'important');
                }
            });

            renderPagination();
        }

        function renderPagination() {
            if (!paginationContainer || totalItems === 0) {
                if (paginationContainer) paginationContainer.innerHTML = '';
                return;
            }

            const pageCount = totalPages > 0 ? totalPages : 1;

            let html = `<div class="flex items-center justify-end gap-1.5 mt-5 pt-2">`;

            // Previous Button
            const prevDisabled = currentPage === 1 ? 'disabled opacity-50 cursor-not-allowed' : '';
            html += `
                <button onclick="goToPage(${currentPage - 1})" class="w-[28px] h-[28px] rounded-[8px] bg-brand-blue text-white flex items-center justify-center text-[12px] hover:bg-[#152a42] transition-all duration-200 shadow-sm hover:shadow-md ${prevDisabled}" ${currentPage === 1 ? 'disabled' : ''}>
                    <i class="ph-bold ph-caret-left"></i>
                </button>
            `;

            // Page numbers
            for (let i = 1; i <= pageCount; i++) {
                if (i === currentPage) {
                    html += `<span class="w-[28px] h-[28px] flex items-center justify-center text-[14px] font-extrabold text-brand-blue">${i}</span>`;
                } else {
                    html += `
                        <button onclick="goToPage(${i})" class="w-[28px] h-[28px] rounded-[8px] bg-brand-blue text-white flex items-center justify-center text-[13px] font-bold hover:bg-[#152a42] transition-all duration-200 shadow-sm hover:shadow-md">
                            ${i}
                        </button>
                    `;
                }
            }

            // Next Button
            const nextDisabled = currentPage === pageCount ? 'disabled opacity-50 cursor-not-allowed' : '';
            html += `
                <button onclick="goToPage(${currentPage + 1})" class="w-[28px] h-[28px] rounded-[8px] bg-brand-blue text-white flex items-center justify-center text-[12px] hover:bg-[#152a42] transition-all duration-200 shadow-sm hover:shadow-md ${nextDisabled}" ${currentPage === pageCount ? 'disabled' : ''}>
                    <i class="ph-bold ph-caret-right"></i>
                </button>
            `;

            html += `</div>`;
            paginationContainer.innerHTML = html;
        }

        window.goToPage = function(page) {
            if (page < 1 || page > totalPages) return;
            showPage(page);
        };

        showPage(1);
    });
</script>
@endsection
