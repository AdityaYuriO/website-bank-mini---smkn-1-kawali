@extends('layouts.cs')

@section('title', 'Customer Service - Kelola Data')
@section('header_title')
selamat datang {{ $user->name }}!
@endsection
@section('header_subtitle', 'Pusat kendali untuk menambah, mengubah, dan menghapus data seluruh nasabah terdaftar.')

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

@section('header_actions')
<div id="searchBarContainer" class="relative w-full md:w-auto hidden md:block transition-all">
    <i class="ph ph-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-lg"></i>
    <input type="text" id="searchInput" placeholder="Search" class="pl-10 pr-4 py-2 border border-gray-200 rounded-lg text-[13px] w-[260px] focus:outline-none focus:border-brand-blue focus:ring-1 focus:ring-brand-blue text-gray-700 placeholder-gray-400">
</div>
@endsection

@section('content')
<!-- VIEW 1: TABEL DATA NASABAH -->
<div id="viewTabelData" class="fade-in block flex-1 flex flex-col justify-start">
    <!-- Search Bar Mobile -->
    <div class="md:hidden relative mb-5">
        <i class="ph ph-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-lg"></i>
        <input type="text" id="searchMobile" placeholder="Cari nama atau no. rekening..." class="w-full pl-12 pr-4 py-3.5 bg-white border border-gray-100 rounded-2xl text-[14px] focus:outline-none focus:border-brand-blue focus:ring-1 focus:ring-brand-blue text-gray-700 placeholder-gray-400 shadow-sm transition-all">
    </div>

    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-4 px-1">
        <h3 class="text-[22px] font-bold text-gray-800">Data Nasabah</h3>
        <div class="flex flex-col sm:flex-row gap-2.5 w-full sm:w-auto">
            <a href="{{ route('download.template') }}" class="w-full sm:w-auto">
                <button type="button" class="w-full bg-white border border-gray-200 text-gray-700 px-4 py-2.5 rounded-[10px] text-[13px] font-bold flex items-center gap-2 hover:bg-gray-50 active:scale-95 transition-all shadow-sm justify-center">
                    <i class="ph ph-file-xls text-base text-green-600"></i> Download Template
                </button>
            </a>
            <a href="{{ route('halaman.import') }}" class="w-full sm:w-auto">
                <button type="button" class="w-full bg-white border border-gray-200 text-gray-700 px-4 py-2.5 rounded-[10px] text-[13px] font-bold flex items-center gap-2 hover:bg-gray-50 active:scale-95 transition-all shadow-sm justify-center">
                    <i class="ph ph-file-arrow-up text-base text-brand-blue"></i> Import Data
                </button>
            </a>
            <button onclick="switchView('tambah')" class="bg-gradient-to-r from-[#143657] to-[#316392] text-white px-4 py-2.5 rounded-[10px] text-[13px] font-bold flex items-center gap-2 hover:opacity-90 active:scale-95 transition-all shadow-md w-full sm:w-auto justify-center">
                <i class="ph ph-plus text-base"></i> Tambah Data
            </button>
        </div>
    </div>

    <div class="bg-white rounded-[20px] shadow-card p-4 md:p-6 w-full flex flex-col">
        <div class="flex items-center gap-2 mb-4">
            <span class="text-[13px] text-gray-600 font-medium">Tampilkan:</span>
            <select onchange="changePerPage(this.value)" class="bg-white border border-gray-200 text-gray-700 text-[13px] rounded-[10px] px-3 py-1.5 font-semibold focus:outline-none focus:border-brand-blue shadow-sm cursor-pointer">
                <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10 data</option>
                <option value="20" {{ $perPage == 20 ? 'selected' : '' }}>20 data</option>
                <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50 data</option>
                <option value="100" {{ $perPage == 100 ? 'selected' : '' }}>100 data</option>
            </select>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr>
                        <th class="py-4 px-2 text-[#a3a3a3] font-medium text-[13px] w-12 border-b border-gray-100 text-center">No</th>
                        <th class="py-4 px-2 text-[#a3a3a3] font-medium text-[13px] border-b border-gray-100">Nama</th>
                        <th class="py-4 px-2 text-[#a3a3a3] font-medium text-[13px] border-b border-gray-100">Jabatan</th>
                        <th class="py-4 px-2 text-[#a3a3a3] font-medium text-[13px] border-b border-gray-100">No. Rekening</th>
                        <th class="py-4 px-4 text-[#a3a3a3] font-medium text-[13px] border-b border-gray-100 text-left">Status</th>
                        <th class="py-4 px-2 text-[#a3a3a3] font-medium text-[13px] text-center w-36 border-b border-gray-100">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-[14px] text-gray-800 font-medium">
                    <!-- Row 1 -->
                    @foreach ($allNasabah as $index => $nasabah )
                    <tr class="hover:bg-gray-50/50 transition-colors nasabah-row">
                        <td class="py-4 px-2 border-b border-gray-50 text-gray-600 pl-4">
                            {{ $allNasabah->firstItem() + $index }}.
                        </td>
                        <td class="py-4 px-2 border-b border-gray-50">{{ $nasabah->nama_nasabah }}</td>
                        <td class="py-4 px-2 border-b border-gray-50">{{ $nasabah->jabatan }}</td>
                        <td class="py-4 px-2 border-b border-gray-50">{{ $nasabah->rekening->id ?? 'Rekening Belum Dibuat'}}</td>
                        <td class="py-4 px-4 border-b border-gray-50 text-left">
                            @if ($nasabah->rekening?->status_akun == 'aktif')
                            <span class="w-7 h-7 rounded-full bg-green-100 text-green-800 text-brand-blue flex items-center justify-center transition-colors shadow-sm">
                                <i class="ph ph-check-circle text-[20px]" title="Aktif"></i>
                            </span>
                            @elseif( $nasabah->rekening?->status_akun == 'non-aktif' )
                            <span class="w-7 h-7 rounded-full bg-red-100 text-red-800 text-brand-blue flex items-center justify-center transition-colors shadow-sm">
                                <i class="ph ph-x-circle text-[20px]" title="Non Aktif"></i>
                            </span>
                            @elseif ( $nasabah->rekening?->status_akun == 'revisi')
                            <button class="w-[28px] h-[28px] rounded-full bg-[#fef3c7] text-[#d97706] inline-flex items-center justify-center cursor-default" title="Revisi">
                                <i class="ph-bold ph-warning-circle text-[15px]"></i>
                            </button>
                            @else
                            <span class="text-[11px] text-gray-400 italic">status belum ada</span>
                            @endif
                        </td>
                        <td class="py-4 px-2 border-b border-gray-50">
                            <div class="flex items-center justify-center gap-2">
                                <button
                                    onclick="showDetail(
                                    '{{ $nasabah->nama_nasabah }}',
                                    '{{ $nasabah->nis_nip }}',
                                    '{{ $nasabah->jurusan->nama_jurusan }}',
                                    '{{ $nasabah->tempat_lahir }}',
                                    '{{ $nasabah->tanggal_lahir }}',
                                    '{{ $nasabah->jenis_kelamin }}',
                                    '{{ $nasabah->jenis_identitas }}',
                                    '{{ $nasabah->agama }}',
                                    '{{ $nasabah->pendidikan }}',
                                    '{{ $nasabah->jabatan }}',
                                    '{{ $nasabah->no_hp }}',
                                    '{{ $nasabah->email }}',
                                    '{{ $nasabah->alamat }}',
                                    '{{ $nasabah->desa->name }}',
                                    '{{ $nasabah->kecamatan->name }}',
                                    '{{ $nasabah->kabupaten->name }}',
                                    '{{ $nasabah->provinsi->name }}',
                                    '{{ $nasabah->kode_pos }}',
                                    '{{ $nasabah->nama_kontak_darurat }}',
                                    '{{ $nasabah->no_hp_kontak_darurat }}',
                                    '{{ $nasabah->hubungan_kontak_darurat }}',
                                    '{{ $nasabah->alamat_kontak_darurat }}',
                                    '{{ $nasabah->rekening->id ?? 'rekening belum dibuat' }}',
                                    '{{ $nasabah->rekening->status_akun ?? 'status belum ada'}}',
                                    '{{ $nasabah->pesan }}',
                                    '{{ $nasabah->nama_perevisi ?? '-' }}' 
                                    )"
                                    class="w-[28px] h-[28px] rounded-full bg-[#e2e8f0] text-brand-blue flex items-center justify-center hover:bg-gray-300 transition-colors"
                                    title="Lihat Detail">

                                    <i class="ph-fill ph-eye text-[15px]"></i>
                                </button>
                                <a href="{{ route('edit.nasabah', $nasabah->id) }}">
                                    <button class="w-[28px] h-[28px] rounded-full bg-[#d1fae5] text-[#10a163] flex items-center justify-center hover:bg-green-200 transition-colors"
                                        title="Edit">
                                        <i class="ph-fill ph-pencil-simple text-[15px]"></i>
                                    </button>
                                </a>
                                <form id="form-delete-nasabah-{{ $nasabah->id }}" action="{{ route('hapus.nasabah', $nasabah->id) }}" method="post" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" onclick="openConfirmModal({
                                                title: 'Hapus Data Nasabah?',
                                                message: 'Apakah Anda yakin ingin menghapus data nasabah <strong>{{ $nasabah->nama_nasabah }}</strong>? Tindakan ini tidak dapat dibatalkan.',
                                                type: 'danger',
                                                confirmText: 'Ya, Hapus Data',
                                                onConfirm: () => document.getElementById('form-delete-nasabah-{{ $nasabah->id }}').submit()
                                            })" class="w-[28px] h-[28px] rounded-full bg-[#fee2e2] text-red-500 flex items-center justify-center hover:bg-red-200 transition-colors" title="Hapus">
                                        <i class="ph-fill ph-trash text-[15px]"></i>
                                    </button>
                                </form>
                                <a href="{{ route('print', $nasabah->id) }}">
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

        <!-- Pagination Container -->
        <x-pagination :paginator="$allNasabah" />

    </div>
</div>

<!-- ================= CRUD VIEWS (Separated Files) ================= -->
@include('costumerservice.crudnasabah.tambah')
@if(isset($nasabah))
@include('costumerservice.crudnasabah.detail')
@endif
<!-- ================= MODAL KONFIRMASI (GLOBAL) ================= -->
<div id="confirmModal" class="fixed inset-0 z-50 hidden bg-black/40 backdrop-blur-sm flex items-center justify-center p-4 transition-opacity opacity-0">
    <div class="bg-white rounded-[24px] shadow-xl w-full max-w-sm p-6 transform scale-95 transition-all transition-transform duration-300">
        
        <!-- Icon & Title -->
        <div class="flex flex-col items-center text-center mb-6">
            <div id="confirmIconContainer" class="w-16 h-16 rounded-full flex items-center justify-center mb-4">
                <i id="confirmIcon" class="text-[32px]"></i>
            </div>
            <h3 id="confirmTitle" class="text-[18px] font-bold text-gray-800 mb-2">Konfirmasi</h3>
            <p id="confirmMessage" class="text-[14px] text-gray-500 leading-relaxed"></p>
        </div>

        <!-- Buttons -->
        <div class="flex gap-3 w-full">
            <button onclick="closeConfirmModal()" class="flex-1 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-xl text-[14px] transition-colors">
                Batal
            </button>
            <button id="confirmBtn" class="flex-1 py-2.5 text-white font-semibold rounded-xl text-[14px] transition-colors shadow-sm">
                Ya, Lanjutkan
            </button>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function switchView(viewName) {
        const views = {
            'tabel': document.getElementById('viewTabelData'),
            'tambah': document.getElementById('viewTambahData'),
            'edit': document.getElementById('viewEditData'),
            'detail': document.getElementById('viewDetailData')
        };

        const searchContainer = document.getElementById('searchBarContainer');
        if (searchContainer) {
            if (viewName === 'tabel') {
                searchContainer.classList.remove('hidden', 'md:hidden');
                searchContainer.classList.add('hidden', 'md:block');
            } else {
                searchContainer.classList.remove('md:block');
                searchContainer.classList.add('hidden', 'md:hidden');
            }
        }

        Object.values(views).forEach(v => {
            if (v) {
                v.classList.remove('block', 'flex');
                v.classList.add('hidden');
            }
        });

        const activeView = views[viewName];
        if (activeView) {
            activeView.classList.remove('hidden');
            if (viewName === 'tabel') {
                activeView.classList.add('flex');
            } else {
                activeView.classList.add('block');
            }
        }
        document.querySelector('main').scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    }

    function changePerPage(value) {
        const url = new URL(window.location.href);
        url.searchParams.set('per_page', value);
        url.searchParams.set('page', 1); // Reset kembali ke halaman 1 setiap kali jumlah data diubah
        window.location.href = url.toString();
    }

    // Handle view parameter from URL
    document.addEventListener('DOMContentLoaded', function() {
        const urlParams = new URLSearchParams(window.location.search);
        const view = urlParams.get('view');
        if (view) {
            switchView(view);
        }
    });

    function showDetail(
        nama,
        nis,
        jurusan,
        tempat_lahir,
        tanggal_lahir,
        jenis_kelamin,
        identitas,
        agama,
        pendidikan,
        jabatan,
        telepon,
        email,
        alamat,
        kelurahan,
        kecamatan,
        kab_kota,
        provinsi,
        kode_pos,
        kontak_nama,
        kontak_telepon,
        kontak_hubungan,
        kontak_alamat,
        rekening,
        status,
        pesan,
        nama_perevisi
    ) {
        document.getElementById('detail_nama').value = nama;
        document.getElementById('detail_nis').value = nis;
        document.getElementById('detail_jurusan').value = jurusan;
        document.getElementById('detail_tempat_lahir').value = tempat_lahir;
        document.getElementById('detail_tanggal_lahir').value = tanggal_lahir;
        document.getElementById('detail_jenis_kelamin').value = jenis_kelamin;
        document.getElementById('detail_identitas').value = identitas;
        document.getElementById('detail_agama').value = agama;
        document.getElementById('detail_pendidikan').value = pendidikan;
        document.getElementById('detail_jabatan').value = jabatan;
        document.getElementById('detail_telepon').value = telepon;
        document.getElementById('detail_email').value = email;
        document.getElementById('detail_alamat').value = alamat;
        document.getElementById('detail_kelurahan').value = kelurahan;
        document.getElementById('detail_kecamatan').value = kecamatan;
        document.getElementById('detail_kab_kota').value = kab_kota;
        document.getElementById('detail_provinsi').value = provinsi;
        document.getElementById('detail_kode_pos').value = kode_pos;

        document.getElementById('detail_kontak_nama').value = kontak_nama;
        document.getElementById('detail_kontak_telepon').value = kontak_telepon;
        document.getElementById('detail_kontak_hubungan').value = kontak_hubungan;
        document.getElementById('detail_kontak_alamat').value = kontak_alamat;

        document.getElementById('detail_rekening').value = rekening;
        document.getElementById('detail_status').value = status;

        const revisiSection = document.getElementById('revisi_section');
        const detailPesan = document.getElementById('detail_pesan');
        const labelPerevisi = document.getElementById('label_perevisi');

        if (status === 'revisi') {
            revisiSection.classList.remove('hidden');
            detailPesan.innerText = pesan;
            labelPerevisi.innerText = `📝 Pesan Revisi dari ${nama_perevisi}`;
        } else {
            revisiSection.classList.add('hidden');
            detailPesan.innerText = '';
        }

        switchView('detail');
    }

    // Client-side Search and Pagination
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput');
        const searchMobile = document.getElementById('searchMobile');
        const tableBody = document.querySelector('table tbody');
        const rows = Array.from(tableBody.querySelectorAll('.nasabah-row'));
        const paginationContainer = document.getElementById('paginationContainer');

        let currentPage = 1;
        const itemsPerPage = 5;
        let filteredRows = [...rows];

        function updateTable() {
            const totalItems = filteredRows.length;
            const totalPages = Math.ceil(totalItems / itemsPerPage) || 1;

            if (currentPage > totalPages) {
                currentPage = totalPages;
            }

            const startIndex = (currentPage - 1) * itemsPerPage;
            const endIndex = startIndex + itemsPerPage;

            // Hide all rows first
            rows.forEach(row => row.style.display = 'none');

            // Show only rows for current page and update numbers
            filteredRows.slice(startIndex, endIndex).forEach((row, index) => {
                row.style.display = '';
                const numCell = row.querySelector('.row-number');
                if (numCell) {
                    numCell.textContent = (startIndex + index + 1) + '.';
                }
            });

            renderPagination(totalPages);
        }

        function renderPagination(totalPages) {
            if (!paginationContainer) return;

            let html = `<div class="flex items-center justify-end gap-1.5 mt-5 pt-2">`;

            // Prev Button
            html += `
                <button type="button" id="prevPageBtn" class="w-[28px] h-[28px] rounded-[8px] bg-brand-blue text-white flex items-center justify-center text-[12px] hover:bg-[#152a42] transition-all duration-200 shadow-sm hover:shadow-md disabled:opacity-50 disabled:cursor-not-allowed" ${currentPage === 1 ? 'disabled' : ''}>
                    <i class="ph-bold ph-caret-left"></i>
                </button>
            `;

            for (let i = 1; i <= totalPages; i++) {
                if (i === currentPage) {
                    html += `
                        <span class="w-[28px] h-[28px] flex items-center justify-center text-[14px] font-extrabold text-brand-blue">${i}</span>
                    `;
                } else {
                    html += `
                        <button type="button" class="page-num-btn w-[28px] h-[28px] rounded-[8px] bg-brand-blue text-white flex items-center justify-center text-[13px] font-bold hover:bg-[#152a42] transition-all duration-200 shadow-sm hover:shadow-md" data-page="${i}">
                            ${i}
                        </button>
                    `;
                }

                if (totalPages > 5 && i === 3 && totalPages > i + 1) {
                    html += `<span class="w-[20px] flex items-center justify-center text-[13px] font-bold text-gray-400 tracking-widest">...</span>`;
                    i = totalPages - 1;
                }
            }

            // Next Button
            html += `
                <button type="button" id="nextPageBtn" class="w-[28px] h-[28px] rounded-[8px] bg-brand-blue text-white flex items-center justify-center text-[12px] hover:bg-[#152a42] transition-all duration-200 shadow-sm hover:shadow-md disabled:opacity-50 disabled:cursor-not-allowed" ${currentPage === totalPages ? 'disabled' : ''}>
                    <i class="ph-bold ph-caret-right"></i>
                </button>
            `;

            html += `</div>`;
            paginationContainer.innerHTML = html;

            // Page navigation event handlers
            const prevBtn = document.getElementById('prevPageBtn');
            if (prevBtn && currentPage > 1) {
                prevBtn.onclick = () => {
                    currentPage--;
                    updateTable();
                };
            }

            const nextBtn = document.getElementById('nextPageBtn');
            if (nextBtn && currentPage < totalPages) {
                nextBtn.onclick = () => {
                    currentPage++;
                    updateTable();
                };
            }

            const numBtns = paginationContainer.getElementsByClassName('page-num-btn');
            Array.from(numBtns).forEach(btn => {
                btn.onclick = function() {
                    currentPage = parseInt(this.getAttribute('data-page'));
                    updateTable();
                };
            });
        }

        function handleSearch(keyword) {
            keyword = keyword.toLowerCase().trim();

            filteredRows = rows.filter(row => {
                const nama = row.cells[1]?.textContent.toLowerCase() || '';
                const jabatan = row.cells[2]?.textContent.toLowerCase() || '';
                const rekening = row.cells[3]?.textContent.toLowerCase() || '';
                return nama.includes(keyword) || jabatan.includes(keyword) || rekening.includes(keyword);
            });

            currentPage = 1;
            updateTable();
        }

        if (searchInput) {
            searchInput.addEventListener('input', function() {
                if (searchMobile) searchMobile.value = this.value;
                handleSearch(this.value);
            });
        }

        if (searchMobile) {
            searchMobile.addEventListener('input', function() {
                if (searchInput) searchInput.value = this.value;
                handleSearch(this.value);
            });
        }

    });
    // Fungsi untuk memunculkan Modal
    function openConfirmModal({ title, message, type = 'danger', confirmText = 'Ya', onConfirm }) {
        const modal = document.getElementById('confirmModal');
        const iconContainer = document.getElementById('confirmIconContainer');
        const icon = document.getElementById('confirmIcon');
        const btnConfirm = document.getElementById('confirmBtn');

        // Reset class warna
        iconContainer.className = 'w-16 h-16 rounded-full flex items-center justify-center mb-4';
        btnConfirm.className = 'flex-1 py-2.5 text-white font-semibold rounded-xl text-[14px] transition-colors shadow-sm';

        // Set Warna & Ikon berdasarkan 'type' (danger atau success)
        if (type === 'danger') {
            iconContainer.classList.add('bg-red-100', 'text-red-500');
            icon.className = 'ph-fill ph-warning-circle text-[32px] text-red-500';
            btnConfirm.classList.add('bg-red-500', 'hover:bg-red-600');
        } else if (type === 'success') {
            iconContainer.classList.add('bg-green-100', 'text-green-500');
            icon.className = 'ph-fill ph-check-circle text-[32px] text-green-500';
            btnConfirm.classList.add('bg-green-500', 'hover:bg-green-600');
        } else {
            // Default (biru)
            iconContainer.classList.add('bg-blue-100', 'text-blue-500');
            icon.className = 'ph-fill ph-info text-[32px] text-blue-500';
            btnConfirm.classList.add('bg-brand-blue', 'hover:bg-blue-800');
        }

        // Set Teks
        document.getElementById('confirmTitle').innerText = title;
        document.getElementById('confirmMessage').innerHTML = message;
        btnConfirm.innerText = confirmText;

        // Set Fungsi saat tombol "Ya" ditekan
        btnConfirm.onclick = function() {
            onConfirm();
            closeConfirmModal(); // Tutup modal sambil memproses
        };

        // Tampilkan Modal dengan animasi
        modal.classList.remove('hidden');
        // Sedikit delay agar transisi CSS berjalan
        setTimeout(() => {
            modal.classList.remove('opacity-0');
            modal.firstElementChild.classList.remove('scale-95');
            modal.firstElementChild.classList.add('scale-100');
        }, 10);
    }

    // Fungsi untuk menutup Modal
    function closeConfirmModal() {
        const modal = document.getElementById('confirmModal');
        
        // Animasi keluar
        modal.classList.add('opacity-0');
        modal.firstElementChild.classList.remove('scale-100');
        modal.firstElementChild.classList.add('scale-95');

        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300); // Sesuaikan dengan durasi transisi CSS (duration-300)
    }
</script>
@endsection