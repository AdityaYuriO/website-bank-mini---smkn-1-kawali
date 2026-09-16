@extends('layouts.admin')

@section('title', 'Supervisor - Data Nasabah')
@section('header_title', 'Selamat Datang, ' . ($user->name ?? 'Supervisor') . '!')
@section('header_subtitle', 'Lorem Ipsum is simply dummy text of the printing.')

@section('styles')
<style>
    /* Animasi transisi antar view */
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
    $perPage = $perPage ?? 10;
    $userNasabah = $userNasabah ?? collect([
        (object)[
            'id' => 1,
            'nama_nasabah' => 'Muhammad Rizky Pratama',
            'jabatan' => 'Siswa (RPL)',
            'rekening' => (object)[
                'id' => '320701892001',
                'status_akun' => 'aktif'
            ]
        ],
        (object)[
            'id' => 2,
            'nama_nasabah' => 'Dewi Sartika, S.Pd.',
            'jabatan' => 'Guru / GTK',
            'rekening' => (object)[
                'id' => '320701892002',
                'status_akun' => 'aktif'
            ]
        ],
        (object)[
            'id' => 3,
            'nama_nasabah' => 'Anisa Rahmawati',
            'jabatan' => 'Siswa (AKL)',
            'rekening' => (object)[
                'id' => '320701892003',
                'status_akun' => 'revisi'
            ]
        ]
    ]);
@endphp

<!-- ================= VIEW 1: TABEL DATA NASABAH ================= -->
<div id="viewTabelData" class="fade-in flex flex-1 flex-col justify-start">

    <div class="mb-4 px-1">
        <h3 class="text-[20px] md:text-[22px] font-bold text-gray-800">Data Nasabah</h3>
    </div>

    <!-- Table Card -->
    <div class="bg-white rounded-2xl sm:rounded-[20px] shadow-card p-4 sm:p-6 w-full flex flex-col">
        <div class="flex flex-col md:flex-row justify-between items-stretch md:items-center gap-3 mb-4 pb-3 border-b border-gray-100">
            <div class="flex gap-2 items-center w-full md:w-auto">
                <div class="relative flex-1 md:flex-initial">
                    <i class="ph ph-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-lg"></i>
                    <input type="text" id="searchInput" placeholder="Cari data..." onkeyup="liveSearchTable()"
                        class="w-full md:w-[250px] pl-12 pr-4 py-2 bg-white border border-gray-100 rounded-xl text-[14px] focus:outline-none focus:border-brand-blue focus:ring-1 focus:ring-brand-blue text-gray-700 placeholder-gray-400 shadow-sm transition-all">
                </div>

                <button type="button" class="px-3.5 py-2 bg-brand-blue text-white text-[14px] font-medium rounded-xl shadow-sm hover:opacity-90 transition-all shrink-0">
                    <i class="ph ph-magnifying-glass text-lg"></i>
                </button>
            </div>
            <div class="flex items-center gap-2 justify-between md:justify-end w-full md:w-auto">
                <span class="text-xs sm:text-[13px] text-gray-600 font-medium">Tampilkan:</span>
                <select class="bg-white border border-gray-200 text-gray-700 text-xs sm:text-[13px] rounded-[10px] px-3 py-1.5 font-semibold focus:outline-none focus:border-brand-blue shadow-sm cursor-pointer">
                    <option value="10">10 data</option>
                    <option value="20">20 data</option>
                    <option value="50">50 data</option>
                    <option value="100">100 data</option>
                </select>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse whitespace-nowrap">
                <thead>
                    <tr>
                        <th class="py-4 px-2 text-[#a3a3a3] font-medium text-[13px] w-16 border-b border-gray-100 pl-4">No</th>
                        <th class="py-4 px-4 text-[#a3a3a3] font-medium text-[13px] border-b border-gray-100">Nama</th>
                        <th class="py-4 px-4 text-[#a3a3a3] font-medium text-[13px] border-b border-gray-100">Jabatan</th>
                        <th class="py-4 px-4 text-[#a3a3a3] font-medium text-[13px] border-b border-gray-100">No. Rekening</th>
                        <th class="py-4 px-4 text-[#a3a3a3] font-medium text-[13px] border-b border-gray-100 text-center">Status</th>
                        <th class="py-4 px-2 text-[#a3a3a3] font-medium text-[13px] text-center w-24 border-b border-gray-100">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-[14px] text-gray-800 font-medium">
                    @foreach ( $userNasabah as $index => $nasabah )
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="py-4 px-2 border-b border-gray-50 text-gray-600 pl-4">
                            {{ $index + 1 }}.
                        </td>
                        <td class="py-4 px-4 border-b border-gray-50">{{ $nasabah->nama_nasabah}}</td>
                        <td class="py-4 px-4 border-b border-gray-50">{{ $nasabah->jabatan }}</td>
                        <td class="py-4 px-4 border-b border-gray-50">{{ $nasabah->rekening->id ?? 'Data Kosong' }}</td>
                        <td class="py-4 px-2 border-b border-gray-50 text-center">

                            @if ($nasabah->rekening?->status_akun == 'aktif')
                            <button
                                class="w-[28px] h-[28px] rounded-full bg-green-100 text-green-700 inline-flex items-center justify-center cursor-default"
                                title="Aktif"
                                type="button">
                                <i class="ph ph-check-circle text-[20px]"></i>
                            </button>

                            @elseif ($nasabah->rekening?->status_akun == 'non-aktif')
                            <button
                                class="w-[28px] h-[28px] rounded-full bg-red-100 text-red-700 inline-flex items-center justify-center cursor-default"
                                title="Non Aktif"
                                type="button">
                                <i class="ph ph-x-circle text-[20px]"></i>
                            </button>

                            @elseif ($nasabah->rekening?->status_akun == 'revisi')
                            <button
                                class="w-[28px] h-[28px] rounded-full bg-[#fef3c7] text-[#d97706] inline-flex items-center justify-center cursor-default"
                                title="Revisi"
                                type="button">
                                <i class="ph-bold ph-warning-circle text-[20px]"></i>
                            </button>
                            @else
                            <span class="text-[11px] text-gray-400 italic">Belum Ada Akun</span>
                            @endif
                        </td>

                        <td class="py-4 px-2 border-b border-gray-50 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ url('/admin/supervisor/detail/nasabah') }}">
                                    <button class="w-[30px] h-[30px] rounded-full bg-[#e2e8f0] text-brand-blue flex items-center justify-center hover:bg-gray-300 transition-colors" title="Lihat Detail"><i class="ph-fill ph-eye text-[16px]"></i></button>
                                </a>
                                <a href="{{ url('/admin/supervisor/print/nasabah') }}">
                                    <button type="button"
                                            class="download-struk w-[28px] h-[28px] rounded-full bg-blue-100 text-blue-600 flex items-center justify-center hover:bg-blue-200 transition-colors focus:outline-none"
                                            title="Cetak Struk">
                                        <i class="ph-fill ph-printer text-[15px]"></i>
                                    </button>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    function liveSearchTable() {
        const input = document.getElementById('searchInput');
        if (!input) return;
        const filter = input.value.toLowerCase().trim();
        const tbody = document.querySelector('table tbody');
        if (!tbody) return;
        const rows = tbody.querySelectorAll('tr:not(#noDataRow)');

        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            if (text.includes(filter)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }
</script>
@endsection
