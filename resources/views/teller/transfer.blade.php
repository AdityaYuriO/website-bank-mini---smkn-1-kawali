@extends('layouts.teller')

@section('title', 'Teller - Data Transfer')
@section('header_title')
Selamat Datang, {{ $user->name }}!
@endsection
@section('header_subtitle', 'Sistem Administrasi Data Transaksi Transfer Antar Tabungan')

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

<!-- ================= VIEW 1: TABEL DATA TRANSFER ================= -->
<div id="viewTabelData" class="fade-in block flex-1 flex flex-col justify-start">
    <!-- Search Bar Mobile -->
    <form action="" method="GET" class="md:hidden relative mb-5 m-0 p-0">
        <i class="ph ph-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-lg"></i>
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama pengirim/penerima..." class="w-full pl-12 pr-4 py-3.5 bg-white border border-gray-100 rounded-2xl text-[14px] focus:outline-none focus:border-brand-blue focus:ring-1 focus:ring-brand-blue text-gray-700 placeholder-gray-400 shadow-sm transition-all">
    </form>

    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 mb-4 px-1">

        <h3 class="text-[22px] font-bold text-gray-800">
            Data Transfer
        </h3>

        <div class="flex flex-col sm:flex-row gap-2 w-full sm:w-auto">

            <!-- EXPORT EXCEL -->
            <div class="relative">

                <button
                    id="btnExportExcel"
                    type="button"
                    class="bg-green-600 text-white px-3 py-1.5 rounded-[10px] text-[13px] font-bold flex items-center gap-2 hover:bg-green-700 transition-all shadow-md w-full sm:w-auto justify-center">

                    <i class="ph ph-file-xls text-base"></i>
                    Export Excel
                    <i class="ph ph-caret-down"></i>

                </button>

                <div
                    id="dropdownExport"
                    class="hidden absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-lg border border-gray-100 z-50">

                    <a href="{{ route('transfer.export', ['filter' => 'hari_ini']) }}"
                        class="block px-4 py-3 hover:bg-gray-50 text-sm">
                        Hari Ini
                    </a>

                    <a href="{{ route('transfer.export', ['filter' => 'minggu_ini']) }}"
                        class="block px-4 py-3 hover:bg-gray-50 text-sm">
                        Minggu Ini
                    </a>

                    <a href="{{ route('transfer.export', ['filter' => 'bulan_ini']) }}"
                        class="block px-4 py-3 hover:bg-gray-50 text-sm">
                        Bulan Ini
                    </a>

                    <a href="{{ route('transfer.export', ['filter' => 'tahun_ini']) }}"
                        class="block px-4 py-3 hover:bg-gray-50 text-sm">
                        Tahun Ini
                    </a>

                    <hr>

                    <button
                        type="button"
                        onclick="toggleCustomTanggal()"
                        class="w-full text-left px-4 py-3 hover:bg-gray-50 text-sm">
                        Custom Tanggal
                    </button>

                </div>

                <div
                    id="customTanggalBox"
                    class="hidden absolute right-0 top-full mt-2 w-60 bg-white rounded-xl shadow-lg border border-gray-100 z-[60] p-3">

                    <form action="{{ route('transfer.export.custom') }}" method="GET">

                        <label class="block text-xs font-semibold text-gray-500 mb-1">
                            Dari Tanggal
                        </label>

                        <input
                            type="date"
                            name="start_date"
                            class="w-full border rounded-lg px-3 py-1.5 text-sm mb-3">

                        <label class="block text-xs font-semibold text-gray-500 mb-1">
                            Sampai Tanggal
                        </label>

                        <input
                            type="date"
                            name="end_date"
                            class="w-full border rounded-lg px-3 py-1.5 text-sm mb-3">

                        <button
                            type="submit"
                            class="w-full bg-green-600 hover:bg-green-700 text-white py-1.5 rounded-lg text-[12px] font-semibold transition-all">
                            Download Excel
                        </button>

                    </form>

                </div>

            </div>

            <!-- TAMBAH TRANSFER -->
            <button
                onclick="switchView('tambah')"
                class="bg-gradient-to-r from-[#143657] to-[#316392] text-white px-3 py-1.5 rounded-[10px] text-[13px] font-bold flex items-center gap-2 hover:opacity-90 transition-all shadow-md w-full sm:w-auto justify-center">

                <i class="ph ph-plus text-base"></i>
                Tambah Transfer

            </button>

        </div>

    </div>

    <div class="bg-white rounded-[20px] shadow-card p-6 w-full flex flex-col" id="transferTableCard">
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
                        <th class="py-4 px-2 text-[#a3a3a3] font-medium text-[13px] w-12 border-b border-gray-100">No</th>
                        <th class="py-4 px-2 text-[#a3a3a3] font-medium text-[13px] border-b border-gray-100">Norek. Pengirim</th>
                        <th class="py-4 px-2 text-[#a3a3a3] font-medium text-[13px] border-b border-gray-100">Norek. Penerima</th>
                        <th class="py-4 px-2 text-[#a3a3a3] font-medium text-[13px] border-b border-gray-100">Nominal Rupiah</th>
                        <th class="py-4 px-2 text-[#a3a3a3] font-medium text-[13px] border-b border-gray-100">Tanggal</th>
                        <th class="py-4 px-2 text-[#a3a3a3] font-medium text-[13px] border-b border-gray-100">Petugas</th>
                        <th class="py-4 px-2 text-[#a3a3a3] font-medium text-[13px] text-center w-36 border-b border-gray-100">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-[14px] text-gray-800 font-medium">
                    @forelse($data as $index => $d)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="py-4 px-2 border-b border-gray-50">{{ $data->firstItem() + $index }}.</td>
                        <td class="py-4 px-2 border-b border-gray-50">{{ $d->id_rekening_pengirim }}</td>
                        <td class="py-4 px-2 border-b border-gray-50">{{ $d->id_rekening_penerima }}</td>
                        <td class="py-4 px-2 border-b border-gray-50 text-gray-800">
                            Rp. {{ number_format($d->jumlah_transfer, 0, ',', '.') }}
                        </td>
                        <td class="py-4 px-2 border-b border-gray-50">
                            {{ $d->created_at->format('d-m-Y') }}
                        </td>
                        <td class="py-4 px-2 border-b border-gray-50">{{ optional($d->petugas)->user->name ?? '-' }}</td>
                        <td class="py-4 px-2 border-b border-gray-50 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <button type="button"
                                    onclick="tampilkanDetail(this)"
                                    data-petugas="{{ optional($d->petugas)->user->name ?? '-' }}"
                                    data-pengirim="{{ $d->rekeningPengirim->nama_nasabah ?? $d->id_rekening_pengirim }}"
                                    data-penerima="{{ $d->rekeningPenerima->nama_nasabah ?? $d->id_rekening_penerima }}"
                                    data-pilihan-biaya="{{ $d->pilihan_biaya_transaksi ?? 'Cash' }}"
                                    data-nominal="{{ number_format($d->jumlah_transfer, 0, ',', '.') }}"
                                    data-biaya="{{ number_format(($d->total_biaya - $d->jumlah_transfer), 0, ',', '.') }}"
                                    data-total="{{ number_format($d->total_biaya, 0, ',', '.') }}"
                                    data-catatan="{{ $d->catatan ?? '-' }}"
                                    class="w-[28px] h-[28px] rounded-full bg-[#e2e8f0] text-brand-blue flex items-center justify-center hover:bg-gray-300 transition-colors"
                                    title="Lihat Detail">
                                    <i class="ph-fill ph-eye text-[15px]"></i>
                                </button>

                               <button
                                    type="button"
                                    onclick='editData(
                                        "{{ $d->id }}",
                                        "{{ $d->id_rekening_pengirim }}",
                                        "{{ $d->id_rekening_penerima }}",
                                        "{{ $d->jumlah_transfer }}",
                                        "{{ $d->pilihan_biaya_transaksi }}",
                                        "{{ $user->name }}",
                                        "{{ $d->catatan }}"
                                    )'
                                    class="w-[28px] h-[28px] rounded-full bg-[#d1fae5] text-[#10a163] flex items-center justify-center hover:bg-green-200 transition-colors"
                                    title="Edit">
                                    <i class="ph-fill ph-pencil-simple text-[15px]"></i>
                                </button>

                                <a href="{{ route('transfer.struk', $d->id) }}"
                                    target="_blank"
                                    class="w-[28px] h-[28px] rounded-full bg-blue-100 text-blue-600 flex items-center justify-center hover:bg-blue-200 transition-colors"
                                    title="Cetak Struk">
                                    <i class="ph-fill ph-printer text-[15px]"></i>
                                </a>

                                <form id="delete-form-{{ $d->id }}" action="{{ route('transfer.delete', $d->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button
                                        type="button"
                                        onclick="confirmDeleteTransfer('{{ $d->id }}')"
                                        class="w-[28px] h-[28px] rounded-full bg-[#fee2e2] text-red-500 flex items-center justify-center hover:bg-red-200 transition-colors">
                                        <i class="ph-fill ph-trash text-[15px]"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-10 text-center text-gray-400 font-medium">Tidak ada data transfer</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <x-pagination :paginator="$data" />
    </div>
</div>

<!-- ================= MODAL POPUP TRANSFER (BERHASIL / GAGAL) ================= -->
<div id="transferPopupModal" class="hidden fixed inset-0 z-[100] flex items-center justify-center p-4">
    <!-- Overlay backdrop blur -->
    <div class="fixed inset-0 bg-black/40 backdrop-blur-sm transition-opacity duration-300" onclick="closeTransferPopup()"></div>

    <!-- Modal Content Container -->
    <div id="transferPopupContent" class="bg-white rounded-[28px] w-full max-w-[400px] p-8 shadow-2xl relative z-10 transform transition-all scale-95 opacity-0 duration-300">
        <div class="flex flex-col items-center text-center">

            <!-- Success Icon Badge -->
            <div id="transferPopupSuccessBadge" class="hidden w-20 h-20 rounded-full bg-emerald-50 items-center justify-center mb-6">
                <i class="ph-fill ph-check-circle text-[48px] text-emerald-500"></i>
            </div>

            <!-- Error Icon Badge -->
            <div id="transferPopupErrorBadge" class="hidden w-20 h-20 rounded-full bg-red-50 items-center justify-center mb-6">
                <i class="ph-fill ph-warning-circle text-[48px] text-red-500"></i>
            </div>

            <!-- Title -->
            <h3 id="transferPopupTitle" class="text-[22px] font-bold text-gray-900 mb-2"></h3>

            <!-- Description / Message -->
            <div id="transferPopupMessage" class="text-gray-500 text-[14px] leading-relaxed mb-8 w-full"></div>

            <!-- Action Button -->
            <div class="w-full">
                <button id="transferPopupButton" onclick="closeTransferPopup()" type="button" class="w-full px-6 py-3.5 rounded-xl font-bold text-[14px] transition-colors active:scale-95 cursor-pointer">
                    OK
                </button>
            </div>

        </div>
    </div>
</div>

<!-- ================= CRUD VIEWS MODULAR ================= -->
@include('teller.crud_transfer.tambah')
@include('teller.crud_transfer.detail')
@include('teller.crud_transfer.edit')

@endsection

@section('scripts')
<script>
    // Tarik data konfigurasi master dari DB lewat controller bray
    const BIAYA_ADMIN_MASTER = parseInt("{{ $transaksi->nominal ?? 0 }}") || 0;
    const MASTER_TRANSAKSI_ID = "{{ $transaksi->id ?? '' }}";

    // Kunci Pengendali Halaman (Switch View)
    function switchView(viewName) {
        const views = {
            'tabel': document.getElementById('viewTabelData'),
            'tambah': document.getElementById('viewTambahData'),
            'edit': document.getElementById('viewEditData'),
            'detail': document.getElementById('viewDetailData')
        };

        Object.values(views).forEach(v => {
            if (v) v.classList.add('hidden');
        });

        const activeView = views[viewName];
        const searchBar = document.getElementById('searchBarContainer');

        if (activeView) {
            activeView.classList.remove('hidden');
            if (viewName === 'tabel') {
                activeView.classList.add('flex');
                if (searchBar) searchBar.classList.remove('md:hidden');
            } else {
                activeView.classList.add('block');
                if (searchBar) searchBar.classList.add('md:hidden');
            }
        }
        document.querySelector('main')?.scrollTo({
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

    // --- LOGIKA UTILITY ANGKA ---
    function cleanNumber(value) {
        return parseInt(value.toString().replace(/\D/g, '')) || 0;
    }

    function formatNumber(num) {
        return new Intl.NumberFormat('id-ID').format(num);
    }

    function hitungBiaya(prefix) {
        const inputNominal = document.getElementById(`${prefix}_jumlah_transfer`);
        const inputAdmin = document.getElementById(`${prefix}_biaya_transaksi`);
        const inputTotal = document.getElementById(`${prefix}_total_biaya`);
        const inputTotalView = document.getElementById(`${prefix}_total_biaya_view`);

        if (!inputNominal) return;

        let nominal = cleanNumber(inputNominal.value);
        let total = nominal + BIAYA_ADMIN_MASTER;

        if (inputAdmin) inputAdmin.value = BIAYA_ADMIN_MASTER;
        if (inputTotal) inputTotal.value = total;
        if (inputTotalView) inputTotalView.value = 'Rp. ' + formatNumber(total);
    }

    function tampilkanDetail(button) {
        // Pastikan semua atribut menggunakan prefix 'data-'
        const petugas = button.getAttribute('data-petugas');
        const pengirim = button.getAttribute('data-pengirim');
        const penerima = button.getAttribute('data-penerima');
        const pilihan_biaya = button.getAttribute('data-pilihan-biaya');
        const nominal = button.getAttribute('data-nominal');
        const biaya = button.getAttribute('data-biaya');
        const total = button.getAttribute('data-total');
        const catatan = button.getAttribute('data-catatan');

        // Masukkan ke input (tambahkan pengecekan agar tidak error jika elemen tidak ditemukan)
        const setVal = (id, val) => {
            const el = document.getElementById(id);
            if (el) el.value = val;
        };

        setVal('detail_petugas', petugas);
        setVal('detail_pengirim', pengirim);
        setVal('detail_penerima', penerima);
        setVal('detail_pilihan_biaya', pilihan_biaya);
        setVal('detail_nominal', "Rp. " + nominal);
        setVal('detail_biaya_transaksi', "Rp. " + biaya);
        setVal('detail_total_biaya', "Rp. " + total);
        setVal('detail_catatan', catatan);

        switchView('detail');
    }
    // --- VIEW EDIT & INJEKSI DATA ---
    function editData(id, pengirim, penerima, nominal, pilihan_biaya, petugas, catatan) {

        document.getElementById('edit_id').value = id;

        document.getElementById('edit_id_rekening_pengirim').value = pengirim;

        document.getElementById('edit_id_rekening_penerima').value = penerima;

        document.getElementById('edit_jumlah_transfer').value = formatNumber(nominal);

        document.getElementById('edit_pilihan_biaya_transaksi').value = pilihan_biaya || 'Cash';

        if(document.getElementById('edit_petugas')) {
            document.getElementById('edit_petugas').value = petugas || '-';
        } 
        document.getElementById('edit_catatan').value = catatan ?? '';

        document.getElementById('edit_transaksi_id').value = MASTER_TRANSAKSI_ID;

        // AUTO HITUNG TOTAL
        calculateEditTotal();

        // AUTO CEK REKENING
        cekRekeningTransfer('edit', 'pengirim');
        cekRekeningTransfer('edit', 'penerima');

        const formEdit = document.getElementById('editForm');
        formEdit.action = `/transfer/update/${id}`;

        switchView('edit');
    }

    // --- AJAX DETEKSI PEMILIK REKENING (TAMBAH / EDIT) ---
    function cekRekeningTransfer(prefix, jenis) {

        const pengirimInput = document.getElementById(`${prefix}_id_rekening_pengirim`);
        const penerimaInput = document.getElementById(`${prefix}_id_rekening_penerima`);

        const infoPengirim = document.getElementById(`${prefix}_info_pengirim`);
        const infoPenerima = document.getElementById(`${prefix}_info_penerima`);

        let inputTarget = (jenis === 'pengirim') ?
            pengirimInput :
            penerimaInput;

        let infoTarget = (jenis === 'pengirim') ?
            infoPengirim :
            infoPenerima;

        if (!inputTarget || !infoTarget) return;

        let rekening = inputTarget.value.trim();

        // kosong = jangan tampil apa-apa
        if (rekening === '') {
            infoTarget.innerHTML = '';
            return;
        }

        // hanya angka
        rekening = rekening.replace(/\D/g, '');

        if (rekening.length < 1) {
            infoTarget.innerHTML = '';
            return;
        }

        // VALIDASI REKENING SAMA
        let norekPengirim = pengirimInput.value.trim();
        let norekPenerima = penerimaInput.value.trim();

        if (
            norekPengirim !== '' &&
            norekPenerima !== '' &&
            norekPengirim === norekPenerima
        ) {
            infoTarget.innerHTML =
                `<span class="text-red-500">
                    ❌ Rekening pengirim & penerima tidak boleh sama!
                </span>`;
            return;
        }

        infoTarget.innerHTML =
            `<span class="text-gray-400">Memeriksa...</span>`;

        fetch(`/cari-rekening/${rekening}`)
            .then(res => res.json())
            .then(data => {

                if (data.success) {

                    // PENGIRIM → nama + saldo
                    if (jenis === 'pengirim') {

                        infoTarget.innerHTML =
                            `<span class="text-green-600 font-semibold">
                                ✓ ${data.nama}
                            </span>
                            | Saldo: Rp. ${formatNumber(data.saldo)}`;

                    } else {

                        // PENERIMA → nama doang
                        infoTarget.innerHTML =
                            `<span class="text-green-600 font-semibold">
                                ✓ ${data.nama}
                            </span>`;
                    }

                } else {

                    infoTarget.innerHTML =
                        `<span class="text-red-500">
                            ❌ Nomor rekening tidak ditemukan
                        </span>`;
                }
            })
            .catch((err) => {

                console.log(err);

                infoTarget.innerHTML =
                    `<span class="text-red-500">
                        ⚠️ Gagal memverifikasi data
                    </span>`;
            });
    }

    // --- INITIALIZE EVENT LISTENERS (ANTI BENTROK) ---
    document.addEventListener('DOMContentLoaded', function() {

        // EDIT PENGIRIM hanya angka
        document.getElementById('edit_id_rekening_pengirim')
            ?.addEventListener('input', function() {
                this.value = this.value.replace(/\D/g, '');
            });

        // EDIT PENERIMA hanya angka
        document.getElementById('edit_id_rekening_penerima')
            ?.addEventListener('input', function() {
                this.value = this.value.replace(/\D/g, '');
            });

        const tambahNominal = document.getElementById('tambah_jumlah_transfer');
        if (tambahNominal) {
            tambahNominal.addEventListener('input', function() {
                let val = this.value.replace(/\D/g, '');
                this.value = val ? formatNumber(val) : '';
                hitungBiaya('tambah');
            });
        }

        const editNominal = document.getElementById('edit_jumlah_transfer');
        if (editNominal) {
            editNominal.addEventListener('input', function() {
                let val = this.value.replace(/\D/g, '');
                this.value = val ? formatNumber(val) : '';
                hitungBiaya('edit');
            });
        }

        // SANITASI DATA SEBELUM SUBMIT (Kunci utama data sukses tersimpan bray!)
        const formTambah = document.getElementById('tambahPenarikanForm') || document.querySelector('#viewTambahData form');
        if (formTambah) {
            formTambah.addEventListener('submit', function() {
                if (tambahNominal) tambahNominal.value = cleanNumber(tambahNominal.value);
            });
        }

        const formEdit = document.getElementById('editForm');
        if (formEdit) {
            formEdit.addEventListener('submit', function() {
                if (editNominal) editNominal.value = cleanNumber(editNominal.value);
            });
        }

        // --- AUTOCOMPLETE SUGGESTIONS FOR NOREK SENDER & RECIPIENT ---
        function setupAutocomplete(inputId, suggestionsId, infoId, type) {
            const input = document.getElementById(inputId);
            const suggestions = document.getElementById(suggestionsId);
            const info = document.getElementById(infoId);

            if (!input || !suggestions) return;

            let debounceTimer;

            input.addEventListener('input', function() {
                clearTimeout(debounceTimer);
                const query = this.value.trim();

                // Run original validation in real-time
                cekRekeningTransfer('tambah', type);

                if (query.length < 2) {
                    suggestions.innerHTML = '';
                    suggestions.classList.add('hidden');
                    return;
                }

                debounceTimer = setTimeout(() => {
                    fetch(`/search-rekening?query=${query}`)
                        .then(res => res.json())
                        .then(data => {
                            if (data.length === 0) {
                                suggestions.innerHTML = '<div class="px-4 py-3 text-sm text-gray-500 font-medium">Tidak ada hasil</div>';
                                suggestions.classList.remove('hidden');
                                return;
                            }

                            let html = '';
                            data.forEach(item => {
                                html += `
                                    <div class="px-4 py-3 hover:bg-gray-50 cursor-pointer border-b border-gray-50 transition-colors flex flex-col" data-id="${item.id}" data-nama="${item.nama}" data-saldo="${item.saldo}">
                                        <span class="font-bold text-[14px] text-gray-800">${item.id}</span>
                                        <span class="text-[11px] text-gray-400 font-medium mt-0.5">${item.nama}</span>
                                    </div>
                                `;
                            });
                            suggestions.innerHTML = html;
                            suggestions.classList.remove('hidden');
                        })
                        .catch(err => console.error('Gagal memuat rekomendasi:', err));
                }, 250);
            });

            // Handle clicking suggestion
            suggestions.addEventListener('click', function(e) {
                const item = e.target.closest('[data-id]');
                if (!item) return;

                const id = item.dataset.id;
                const nama = item.dataset.nama;
                const saldo = item.dataset.saldo;

                input.value = id;
                suggestions.innerHTML = '';
                suggestions.classList.add('hidden');

                // Trigger verify details
                if (type === 'pengirim') {
                    if (info) {
                        info.innerHTML = `
                            <span class="text-green-600 font-semibold">
                                ✓ ${nama}
                            </span>
                            | Saldo: Rp. ${formatNumber(saldo)}
                        `;
                    }
                } else if (type === 'penerima') {
                    if (info) {
                        info.innerHTML = `
                            <span class="text-green-600 font-semibold">
                                ✓ ${nama}
                            </span>
                        `;
                    }
                }
            });

            // Close suggestions on clicking outside
            document.addEventListener('click', function(e) {
                if (!input.contains(e.target) && !suggestions.contains(e.target)) {
                    suggestions.innerHTML = '';
                    suggestions.classList.add('hidden');
                }
            });
        }

        setupAutocomplete('tambah_id_rekening_pengirim', 'tambah_rekening_pengirim_suggestions', 'tambah_info_pengirim', 'pengirim');
        setupAutocomplete('tambah_id_rekening_penerima', 'tambah_rekening_penerima_suggestions', 'tambah_info_penerima', 'penerima');
    });

    function confirmDeleteTransfer(id) {
        const msg = 'Apakah Anda yakin ingin menghapus history transaksi transfer ini?';
        openDeleteModal(function() {
            document.getElementById('delete-form-' + id).submit();
        }, msg);
    }

    // AJAX Live Search & PJAX Pagination
    document.addEventListener("DOMContentLoaded", function() {
        let debounceTimeout = null;

        function performAjaxSearch(searchVal) {
            const query = new URLSearchParams(window.location.search);
            query.set('search', searchVal);
            query.delete('page'); // Reset page to 1 on new search

            const targetUrl = `${window.location.pathname}?${query.toString()}`;
            window.history.replaceState({}, '', targetUrl);

            // Sync other search input fields
            document.querySelectorAll('input[name="search"]').forEach(input => {
                if (input.value !== searchVal) {
                    input.value = searchVal;
                }
            });

            fetch(targetUrl)
                .then(response => response.text())
                .then(html => {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    const newCard = doc.getElementById('transferTableCard');
                    const currentCard = document.getElementById('transferTableCard');
                    if (newCard && currentCard) {
                        currentCard.innerHTML = newCard.innerHTML;
                    }
                })
                .catch(err => console.error('Gagal melakukan pencarian:', err));
        }

        // Attach input listener to both desktop & mobile search inputs
        document.querySelectorAll('input[name="search"]').forEach(input => {
            input.addEventListener('input', function() {
                clearTimeout(debounceTimeout);
                const val = this.value;
                debounceTimeout = setTimeout(() => {
                    performAjaxSearch(val);
                }, 300);
            });
        });

        // Prevent form reloads on Enter
        document.querySelectorAll('form').forEach(form => {
            const hasSearchInput = form.querySelector('input[name="search"]');
            if (hasSearchInput) {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    performAjaxSearch(hasSearchInput.value);
                });
            }
        });

        // AJAX Pagination click interceptor
        document.addEventListener('click', function(e) {

            const link = e.target.closest('#transferTableCard a');

            if (!link) return;

            // biarkan link download / pdf normal
            if (link.target === '_blank') return;

            if (link.getAttribute('href') &&
                !link.getAttribute('href').startsWith('#')) {

                e.preventDefault();

                const targetUrl = link.getAttribute('href');

                window.history.pushState({}, '', targetUrl);

                fetch(targetUrl)
                    .then(response => response.text())
                    .then(html => {
                        const parser = new DOMParser();
                        const doc = parser.parseFromString(html, 'text/html');

                        const newCard =
                            doc.getElementById('transferTableCard');

                        const currentCard =
                            document.getElementById('transferTableCard');

                        if (newCard && currentCard) {
                            currentCard.innerHTML = newCard.innerHTML;
                        }
                    });
            }
        });

        // ===========================
        // EXPORT EXCEL DROPDOWN
        // ===========================

        const btnExportExcel = document.getElementById('btnExportExcel');
        const dropdownExport = document.getElementById('dropdownExport');
        const customTanggalBox = document.getElementById('customTanggalBox');

        if (btnExportExcel) {

            btnExportExcel.addEventListener('click', function(e) {

                e.stopPropagation();

                dropdownExport.classList.toggle('hidden');

                if (!customTanggalBox.classList.contains('hidden')) {
                    customTanggalBox.classList.add('hidden');
                }

            });

        }

        window.toggleCustomTanggal = function() {

            customTanggalBox.classList.toggle('hidden');

        }

        document.addEventListener('click', function(e) {

            if (
                !e.target.closest('#dropdownExport') &&
                !e.target.closest('#btnExportExcel') &&
                !e.target.closest('#customTanggalBox')
            ) {

                dropdownExport.classList.add('hidden');
                customTanggalBox.classList.add('hidden');

            }

        });

    });

    // =====================================
    // FUNCTION POPUP ALERT TRANSFER (SUKSES / GAGAL)
    // =====================================
    function openTransferPopup(title, messageHTML, type = 'success') {
        const modal = document.getElementById('transferPopupModal');
        const content = document.getElementById('transferPopupContent');
        const successBadge = document.getElementById('transferPopupSuccessBadge');
        const errorBadge = document.getElementById('transferPopupErrorBadge');
        const titleEl = document.getElementById('transferPopupTitle');
        const messageEl = document.getElementById('transferPopupMessage');
        const buttonEl = document.getElementById('transferPopupButton');

        if (!modal || !content) return;

        titleEl.textContent = title;
        messageEl.innerHTML = messageHTML;

        if (type === 'success') {
            successBadge.classList.remove('hidden');
            successBadge.classList.add('flex');
            errorBadge.classList.add('hidden');
            errorBadge.classList.remove('flex');

            buttonEl.className = "w-full px-6 py-3.5 rounded-xl bg-emerald-500 hover:bg-emerald-600 text-white font-bold text-[14px] transition-colors shadow-lg shadow-emerald-500/20 active:scale-95 cursor-pointer";
            buttonEl.textContent = "Selesai";
        } else {
            errorBadge.classList.remove('hidden');
            errorBadge.classList.add('flex');
            successBadge.classList.add('hidden');
            successBadge.classList.remove('flex');

            buttonEl.className = "w-full px-6 py-3.5 rounded-xl bg-red-500 hover:bg-red-600 text-white font-bold text-[14px] transition-colors shadow-lg shadow-red-500/30 active:scale-95 cursor-pointer";
            buttonEl.textContent = "Mengerti";
        }

        modal.classList.remove('hidden');
        setTimeout(() => {
            content.classList.remove('scale-95', 'opacity-0');
            content.classList.add('scale-100', 'opacity-100');
        }, 50);
    }

    function closeTransferPopup() {
        const modal = document.getElementById('transferPopupModal');
        const content = document.getElementById('transferPopupContent');
        if (!modal || !content) return;

        content.classList.remove('scale-100', 'opacity-100');
        content.classList.add('scale-95', 'opacity-0');
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 250);
    }

    window.openTransferPopup = openTransferPopup;
    window.closeTransferPopup = closeTransferPopup;

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeTransferPopup();
        }
    });

    @if (session('success'))
        document.addEventListener('DOMContentLoaded', function() {
            const successMsg = @json(session('success'));
            if (!successMsg.toLowerCase().includes('dihapus')) {
                openTransferPopup('Transfer Berhasil!', successMsg, 'success');
            }
        });
    @elseif (session('error') || $errors->any())
        document.addEventListener('DOMContentLoaded', function() {
            let errMessage = @json(session('error') ?? '');
            @if($errors->any())
                let errList = '<ul class="list-disc pl-5 text-left text-red-600 text-xs space-y-1 mt-2">';
                @foreach($errors->all() as $err)
                    errList += '<li>' + @json($err) + '</li>';
                @endforeach
                errList += '</ul>';
                errMessage += errList;
            @endif
            openTransferPopup('Transfer Gagal', errMessage || 'Terjadi kesalahan saat memproses transfer.', 'error');
        });
    @endif
</script>
@endsection