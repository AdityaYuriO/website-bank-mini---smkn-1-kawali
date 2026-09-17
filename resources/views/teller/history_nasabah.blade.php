@extends('layouts.teller')

@section('title', 'Teller - History Nasabah')
@section('header_title')
History Transaksi Nasabah
@endsection
@section('header_subtitle', 'Sistem Laporan Riwayat Transaksi Buku Tabungan Nasabah.')

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

<!-- ================= VIEW 1: TABEL HISTORY ================= -->
<div id="viewTabelData" class="fade-in block flex-1 flex flex-col justify-start">
    <!-- Search Bar Mobile -->
    <form action="" method="GET" class="md:hidden relative mb-5 m-0 p-0" id="searchBarContainer">
        <i class="ph ph-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-lg"></i>
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau no. rekening..." class="w-full pl-12 pr-4 py-3.5 bg-white border border-gray-100 rounded-2xl text-[14px] focus:outline-none focus:border-brand-blue focus:ring-1 focus:ring-brand-blue text-gray-700 placeholder-gray-400 shadow-sm transition-all">
    </form>

    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 mb-4 px-1">

        <h3 class="text-[22px] font-bold text-gray-800">
            Riwayat Transaksi
        </h3>

        <div class="flex flex-col sm:flex-row gap-2 w-full sm:w-auto">
            <!-- TOMBOL CETAK REKENING KORAN -->
            <button
                type="button"
                onclick="bukaModalCetakKoran()"
                class="bg-white border border-gray-200 text-[#143657] px-4 py-1.5 rounded-[10px] text-[13px] font-bold flex items-center gap-2 hover:bg-gray-50 transition-all shadow-sm w-full sm:w-auto justify-center">
                <i class="ph ph-scroll text-base"></i>
                Cetak Rekening Koran
            </button>
            <!-- TOMBOL CETAK BIODATA BUKU -->
            <button
                type="button"
                onclick="bukaModalCetakBiodata()"
                class="bg-white border border-gray-200 text-[#143657] px-4 py-1.5 rounded-[10px] text-[13px] font-bold flex items-center gap-2 hover:bg-gray-50 transition-all shadow-sm w-full sm:w-auto justify-center">
                <i class="ph ph-user-list text-base"></i>
                Cetak Biodata Buku
            </button>
            <!-- TOMBOL CETAK BUKU TABUNGAN -->
            <button
                type="button"
                onclick="bukaModalCetak()"
                class="bg-gradient-to-r from-[#143657] to-[#316392] text-white px-4 py-1.5 rounded-[10px] text-[13px] font-bold flex items-center gap-2 hover:opacity-90 transition-all shadow-md w-full sm:w-auto justify-center">
                <i class="ph ph-printer text-base"></i>
                Cetak Buku Tabungan
            </button>
        </div>

    </div>

    <div class="bg-white rounded-[20px] shadow-card p-6 w-full flex flex-col" id="historyTableCard">
        <div class="flex items-center gap-2 mb-4">
            <span class="text-[13px] text-gray-600 font-medium">Tampilkan:</span>
            <select onchange="changePerPage(this.value)" class="bg-white border border-gray-200 text-gray-700 text-[13px] rounded-[10px] px-3 py-1.5 font-semibold focus:outline-none focus:border-brand-blue shadow-sm cursor-pointer">
                <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10 data</option>
                <option value="20" {{ request('per_page') == 20 ? 'selected' : '' }}>20 data</option>
                <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50 data</option>
            </select>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse whitespace-nowrap">
                <thead>
                    <tr>
                        <th class="py-4 px-2 text-[#a3a3a3] font-medium text-[13px] w-12 border-b border-gray-100">No</th>
                        <th class="py-4 px-2 text-[#a3a3a3] font-medium text-[13px] border-b border-gray-100">Nama</th>
                        <th class="py-4 px-2 text-[#a3a3a3] font-medium text-[13px] border-b border-gray-100">No. Rek</th>
                        <th class="py-4 px-2 text-[#a3a3a3] font-medium text-[13px] border-b border-gray-100">J. Transaksi</th>
                        <th class="py-4 px-2 text-[#a3a3a3] font-medium text-[13px] border-b border-gray-100">Admin</th>
                        <th class="py-4 px-2 text-[#a3a3a3] font-medium text-[13px] border-b border-gray-100">Debit (Keluar)</th>
                        <th class="py-4 px-2 text-[#a3a3a3] font-medium text-[13px] border-b border-gray-100">Kredit (Masuk)</th>
                        <th class="py-4 px-2 text-[#a3a3a3] font-medium text-[13px] border-b border-gray-100">Saldo</th>
                        <th class="py-4 px-2 text-[#a3a3a3] font-medium text-[13px] text-center w-24 border-b border-gray-100">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-[14px] text-gray-800 font-medium">
                    <!-- Nanti data dilempar dari Controller, ini struktur untuk Blade-nya -->
                    @forelse($data ?? [] as $index => $d)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="py-4 px-2 border-b border-gray-50">{{ ($data->currentPage() - 1) * $data->perPage() + $loop->iteration }}.</td>
                        <td class="py-4 px-2 border-b border-gray-50">{{ $d->nama_nasabah }}</td>
                        <td class="py-4 px-2 border-b border-gray-50">{{ $d->no_rek }}</td>

                        <!-- Pewarnaan Jenis Transaksi -->
                        <td class="py-4 px-2 border-b border-gray-50">
                            @if($d->jenis_transaksi == 'Setoran')
                            <span class="bg-green-100 text-green-700 px-2 py-1 rounded-md text-xs font-bold">Setoran</span>
                            @elseif($d->jenis_transaksi == 'Penarikan')
                            <span class="bg-red-100 text-red-700 px-2 py-1 rounded-md text-xs font-bold">Penarikan</span>
                            @else
                            <span class="bg-blue-100 text-blue-700 px-2 py-1 rounded-md text-xs font-bold">{{ $d->jenis_transaksi }}</span>
                            @endif
                        </td>

                        <td class="py-4 px-2 border-b border-gray-50 text-gray-500">Rp {{ number_format($d->admin, 0, ',', '.') }}</td>
                        <td class="py-4 px-2 border-b border-gray-50 text-red-500 font-semibold">Rp {{ number_format($d->debit, 0, ',', '.') }}</td>
                        <td class="py-4 px-2 border-b border-gray-50 text-green-500 font-semibold">Rp {{ number_format($d->kredit, 0, ',', '.') }}</td>
                        <td class="py-4 px-2 border-b border-gray-50 text-gray-800 font-bold">Rp {{ number_format($d->saldo, 0, ',', '.') }}</td>

                        <td class="py-4 px-2 border-b border-gray-50 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <button type="button" onclick="lihatDetailHistory(
                                            '{{ $d->nama_nasabah }}',
                                            '{{ $d->no_rek }}',
                                            '{{ $d->jenis_transaksi }}',
                                            '{{ $d->admin }}',
                                            '{{ $d->debit }}',
                                            '{{ $d->kredit }}',
                                            '{{ $d->saldo }}',
                                            '{{ \Carbon\Carbon::parse($d->created_at)->format('d M Y, H:i') }}',
                                            '{{ addslashes($d->keterangan) }}'
                                        )" class="w-[28px] h-[28px] rounded-full bg-[#e2e8f0] text-brand-blue flex items-center justify-center hover:bg-gray-300 transition-colors" title="Lihat Detail">
                                    <i class="ph-fill ph-eye text-[15px]"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="py-10 text-center text-gray-400 font-medium">Tidak ada data transaksi.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <x-pagination :paginator="$data" />
    </div>
</div>

<!-- ================= VIEW 2: DETAIL TRANSAKSI ================= -->
<div id="viewDetailData" class="hidden fade-in flex-1 flex-col justify-start">
    <div class="flex items-center gap-3 mb-4 px-1">
        <button onclick="switchView('tabel')" class="w-8 h-8 flex items-center justify-center rounded-full bg-white shadow-sm border border-gray-200 text-gray-600 hover:bg-gray-50 transition-all">
            <i class="ph ph-arrow-left text-lg"></i>
        </button>
        <h3 class="text-[22px] font-bold text-gray-800">Detail Transaksi Nasabah</h3>
    </div>

    <div class="bg-white rounded-[24px] shadow-card p-6 md:p-10 w-full border border-gray-50">

        <!-- SECTION 1: DATA NASABAH -->
        <div class="mb-10">
            <div class="flex items-center gap-3 mb-8">
                <div class="w-[5px] h-6 bg-[#c0860b] rounded-full"></div>
                <h3 class="text-[20px] font-bold text-gray-800">Informasi Rekening</h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-5">
                <div>
                    <label class="block text-[13px] font-semibold text-gray-500 mb-2">No. Rekening</label>
                    <input type="text" id="hist_no_rek" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-[14px] text-gray-800 bg-gray-50 focus:outline-none" readonly>
                </div>
                <div>
                    <label class="block text-[13px] font-semibold text-gray-500 mb-2">Nama Nasabah</label>
                    <input type="text" id="hist_nama" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-[14px] text-gray-800 bg-gray-50 focus:outline-none" readonly>
                </div>
            </div>
        </div>

        <!-- SECTION 2: RINCIAN TRANSAKSI -->
        <div class="mb-10">
            <div class="flex items-center gap-3 mb-8">
                <div class="w-[5px] h-6 bg-[#c0860b] rounded-full"></div>
                <h3 class="text-[20px] font-bold text-gray-800">Rincian Transaksi</h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-5 mb-5">
                <div>
                    <label class="block text-[13px] font-semibold text-gray-500 mb-2">Jenis Transaksi</label>
                    <input type="text" id="hist_jenis" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-[14px] font-bold text-brand-blue bg-gray-50 focus:outline-none" readonly>
                </div>
                <div>
                    <label class="block text-[13px] font-semibold text-gray-500 mb-2">Tanggal & Waktu</label>
                    <input type="text" id="hist_tanggal" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-[14px] text-gray-800 bg-gray-50 focus:outline-none" readonly>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-[13px] font-semibold text-gray-500 mb-2">Keterangan Transaksi</label>
                    <input type="text" id="hist_keterangan" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-[14px] text-gray-600 bg-gray-50 focus:outline-none" readonly>
                </div>

                <div>
                    <label class="block text-[13px] font-semibold text-gray-500 mb-2">Debit (Uang Keluar)</label>
                    <input type="text" id="hist_debit" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-[14px] font-bold text-red-500 bg-gray-50 focus:outline-none" readonly>
                </div>
                <div>
                    <label class="block text-[13px] font-semibold text-gray-500 mb-2">Kredit (Uang Masuk)</label>
                    <input type="text" id="hist_kredit" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-[14px] font-bold text-green-500 bg-gray-50 focus:outline-none" readonly>
                </div>

                <div>
                    <label class="block text-[13px] font-semibold text-gray-500 mb-2">Biaya Admin</label>
                    <input type="text" id="hist_admin" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-[14px] text-gray-500 bg-gray-50 focus:outline-none" readonly>
                </div>
                <div>
                    <label class="block text-[13px] font-semibold text-gray-500 mb-2">Saldo Akhir</label>
                    <input type="text" id="hist_saldo" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-[14px] font-bold text-gray-800 bg-gray-50 focus:outline-none" readonly>
                </div>
            </div>
        </div>

        <!-- BUTTONS -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-12">
            <div class="hidden sm:block"></div>
            <button type="button" onclick="switchView('tabel')" class="w-full bg-[#797979] hover:bg-gray-600 text-white font-bold py-3.5 rounded-xl transition-colors text-[15px] shadow-sm">
                Kembali
            </button>
        </div>
    </div>
</div>

<!-- ================= MODAL CETAK BUKU TABUNGAN ================= -->
<div id="modalCetakBuku" class="hidden fixed inset-0 z-[100] bg-black/50 backdrop-blur-sm flex items-center justify-center p-4 fade-in">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-md overflow-hidden">
        <div class="p-5 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
            <h3 class="font-bold text-gray-800 text-[16px]">Cetak Buku Tabungan</h3>
            <button type="button" onclick="tutupModalCetak()" class="text-gray-400 hover:text-red-500 transition-colors">
                <i class="ph-bold ph-x text-lg"></i>
            </button>
        </div>
        
        <div class="p-6">
            <label class="block text-[13px] font-semibold text-gray-700 mb-2">Masukkan NIS / No. Rekening Nasabah</label>
            <div class="relative">
                <i class="ph ph-credit-card absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-lg"></i>
                <input type="text" id="inputNoRekening" placeholder="Contoh: 10029384 atau NIS"
                    class="w-full pl-10 pr-4 py-2.5 bg-white border border-gray-200 rounded-xl text-[14px] focus:outline-none focus:border-brand-blue focus:ring-1 focus:ring-brand-blue transition-all">
            </div>

            <!-- INPUT MULAI BARIS -->
            <label class="block text-[13px] font-semibold text-gray-700 mb-2 mt-4">Mulai Cetak dari Baris ke-?</label>
            <div class="relative">
                <i class="ph ph-list-numbers absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-lg"></i>
                <input type="number" id="inputBaris" value="1" min="1"
                    class="w-full pl-10 pr-4 py-2.5 bg-white border border-gray-200 rounded-xl text-[14px] focus:outline-none focus:border-brand-blue focus:ring-1 focus:ring-brand-blue transition-all">
            </div>

            <!-- OPSI BUKU HILANG / TERBIT BUKU BARU -->
            <div class="mt-4 p-3 bg-amber-50 border border-amber-200 rounded-xl flex items-start gap-3">
                <input type="checkbox" id="checkBukuBaru" class="mt-1 w-4 h-4 text-brand-blue rounded focus:ring-brand-blue border-gray-300 cursor-pointer">
                <label for="checkBukuBaru" class="text-xs text-amber-800 cursor-pointer font-medium leading-relaxed">
                    <span class="font-bold block text-amber-900">Buku Lama Hilang / Terbit Buku Baru?</span>
                    Centang ini jika nasabah ganti buku fisik baru. Sistem akan mengunci saldo terakhir dan memulai cetakan dari Baris 1 buku baru.
                </label>
            </div>

            <p class="text-xs text-gray-500 mt-2">Sistem hanya akan mencetak riwayat transaksi milik NIS / nomor rekening ini.</p>
        </div>

        <!-- FOOTER TOMBOL AKSI -->
        <div class="p-5 border-t border-gray-100 bg-gray-50 flex justify-end gap-3">
            <button type="button" onclick="tutupModalCetak()" class="px-4 py-2 rounded-xl text-[13px] font-bold text-gray-600 hover:bg-gray-200 transition-colors">
                Batal
            </button>
            <button type="button" id="btnProsesCetak" onclick="prosesCetak()" class="px-4 py-2 rounded-xl text-[13px] font-bold bg-brand-blue text-white hover:opacity-90 transition-colors flex items-center gap-2">
                <i class="ph ph-printer"></i> Cetak Sekarang
            </button>
        </div>
    </div>
</div>

<!-- ================= MODAL CETAK BIODATA BUKU ================= -->
<div id="modalCetakBiodata" class="hidden fixed inset-0 z-[100] bg-black/50 backdrop-blur-sm flex items-center justify-center p-4 fade-in">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-md overflow-hidden">
        <div class="p-5 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
            <h3 class="font-bold text-gray-800 text-[16px]">Cetak Biodata Buku Tabungan</h3>
            <button type="button" onclick="tutupModalCetakBiodata()" class="text-gray-400 hover:text-red-500 transition-colors">
                <i class="ph-bold ph-x text-lg"></i>
            </button>
        </div>
        <div class="p-6">
            <label class="block text-[13px] font-semibold text-gray-700 mb-2">Masukkan NIS / No. Rekening Nasabah</label>
            <div class="relative">
                <i class="ph ph-credit-card absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-lg"></i>
                <input type="text" id="inputNoRekeningBiodata" placeholder="Contoh: 10029384 atau NIS"
                    class="w-full pl-10 pr-4 py-2.5 bg-white border border-gray-200 rounded-xl text-[14px] focus:outline-none focus:border-brand-blue focus:ring-1 focus:ring-brand-blue transition-all">
            </div>
            <p class="text-xs text-gray-500 mt-2">Sistem akan mencetak nama dan nomor rekening untuk halaman biodata fisik di buku tabungan.</p>
        </div>
        <div class="p-5 border-t border-gray-100 bg-gray-50 flex justify-end gap-3">
            <button type="button" onclick="tutupModalCetakBiodata()" class="px-4 py-2 rounded-xl text-[13px] font-bold text-gray-600 hover:bg-gray-200 transition-colors">Batal</button>
            <button type="button" id="btnProsesCetakBiodata" onclick="prosesCetakBiodata()" class="px-4 py-2 rounded-xl text-[13px] font-bold bg-[#143657] text-white hover:opacity-90 transition-colors flex items-center gap-2">
                <i class="ph ph-printer"></i> Cetak Biodata
            </button>
        </div>
    </div>
</div>

<!-- ================= MODAL POPUP NIS TIDAK TERDAFTAR ================= -->
<div id="modalNisTidakTerdaftar" class="hidden fixed inset-0 z-[110] bg-black/50 backdrop-blur-sm flex items-center justify-center p-4 fade-in">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-sm overflow-hidden text-center p-6">
        <div class="w-16 h-16 bg-red-100 text-red-500 rounded-full flex items-center justify-center mx-auto mb-4">
            <i class="ph-bold ph-warning-circle text-3xl"></i>
        </div>
        <h3 class="font-bold text-gray-800 text-[18px] mb-2">NIS / Rekening Tidak Terdaftar</h3>
        <p class="text-[13px] text-gray-600 mb-6 leading-relaxed">
            Data NIS / Nomor Rekening <span id="nisNotFoundText" class="font-bold text-gray-800"></span> tidak ditemukan dalam sistem. Harap periksa kembali nomor yang Anda masukkan.
        </p>
        <button type="button" onclick="tutupModalNisTidakTerdaftar()" class="w-full py-2.5 rounded-xl text-[14px] font-bold bg-red-500 hover:bg-red-600 text-white transition-colors shadow-md">
            Tutup
        </button>
    </div>
</div>

<!-- ================= MODAL CETAK REKENING KORAN ================= -->
<div id="modalCetakKoran" class="hidden fixed inset-0 z-[100] bg-black/50 backdrop-blur-sm flex items-center justify-center p-4 fade-in">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-md overflow-hidden">
        <div class="p-5 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
            <h3 class="font-bold text-gray-800 text-[16px]">Cetak Rekening Koran</h3>
            <button type="button" onclick="tutupModalCetakKoran()" class="text-gray-400 hover:text-red-500 transition-colors">
                <i class="ph-bold ph-x text-lg"></i>
            </button>
        </div>
        <div class="p-6">
            <label class="block text-[13px] font-semibold text-gray-700 mb-2">Masukkan NIS / No. Rekening</label>
            <div class="relative mb-4">
                <i class="ph ph-credit-card absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-lg"></i>
                <input type="text" id="inputNoRekeningKoran" placeholder="Contoh: 10029384 atau NIS"
                    class="w-full pl-10 pr-4 py-2.5 bg-white border border-gray-200 rounded-xl text-[14px] focus:outline-none focus:border-brand-blue focus:ring-1 focus:ring-brand-blue transition-all">
            </div>
            
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-[13px] font-semibold text-gray-700 mb-2">Dari Tanggal</label>
                    <input type="date" id="inputTanggalAwalKoran" class="w-full px-3 py-2.5 bg-white border border-gray-200 rounded-xl text-[14px] focus:outline-none focus:border-brand-blue focus:ring-1 focus:ring-brand-blue transition-all">
                </div>
                <div>
                    <label class="block text-[13px] font-semibold text-gray-700 mb-2">Sampai Tanggal</label>
                    <input type="date" id="inputTanggalAkhirKoran" class="w-full px-3 py-2.5 bg-white border border-gray-200 rounded-xl text-[14px] focus:outline-none focus:border-brand-blue focus:ring-1 focus:ring-brand-blue transition-all">
                </div>
            </div>
        </div>
        <div class="p-5 border-t border-gray-100 bg-gray-50 flex justify-end gap-3">
            <button type="button" onclick="tutupModalCetakKoran()" class="px-4 py-2 rounded-xl text-[13px] font-bold text-gray-600 hover:bg-gray-200 transition-colors">Batal</button>
            <button type="button" id="btnProsesCetakKoran" onclick="prosesCetakKoran()" class="px-4 py-2 rounded-xl text-[13px] font-bold bg-[#143657] text-white hover:opacity-90 transition-colors flex items-center gap-2">
                <i class="ph ph-printer"></i> Cetak Koran
            </button>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // FUNGSI NAVIGASI
    function switchView(viewName) {
        const views = {
            'tabel': document.getElementById('viewTabelData'),
            'detail': document.getElementById('viewDetailData')
        };

        Object.values(views).forEach(v => {
            if (v) v.classList.add('hidden');
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
    }

    // FUNGSI GANTI JUMLAH DATA PER HALAMAN
    function changePerPage(value) {
        const url = new URL(window.location.href);
        url.searchParams.set('per_page', value);
        url.searchParams.set('page', 1);
        window.location.href = url.toString();
    }

    // AJAX Live Search & PJAX Pagination
    document.addEventListener("DOMContentLoaded", function() {
        let debounceTimeout = null;

        function performAjaxSearch(searchVal) {
            const query = new URLSearchParams(window.location.search);
            query.set('search', searchVal);
            query.delete('page');

            const targetUrl = `${window.location.pathname}?${query.toString()}`;
            window.history.replaceState({}, '', targetUrl);

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
                    const newCard = doc.getElementById('historyTableCard');
                    const currentCard = document.getElementById('historyTableCard');
                    if (newCard && currentCard) {
                        currentCard.innerHTML = newCard.innerHTML;
                    }
                })
                .catch(err => console.error('Gagal melakukan pencarian:', err));
        }

        document.querySelectorAll('input[name="search"]').forEach(input => {
            input.addEventListener('input', function() {
                clearTimeout(debounceTimeout);
                const val = this.value;
                debounceTimeout = setTimeout(() => {
                    performAjaxSearch(val);
                }, 300);
            });
        });

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
            const link = e.target.closest('#historyTableCard a');

            if (!link) return;

            if (link.target === '_blank') return;

            const targetUrl = link.href;

            if (targetUrl && targetUrl !== '#' && !targetUrl.startsWith('javascript:')) {
                e.preventDefault();
                window.history.pushState({}, '', targetUrl);

                fetch(targetUrl)
                    .then(response => response.text())
                    .then(html => {
                        const parser = new DOMParser();
                        const doc = parser.parseFromString(html, 'text/html');
                        const newCard = doc.getElementById('historyTableCard');
                        const currentCard = document.getElementById('historyTableCard');
                        if (newCard && currentCard) {
                            currentCard.innerHTML = newCard.innerHTML;
                        }
                    })
                    .catch(err => console.error('Gagal memuat halaman:', err));
            }
        });

        const btnExportExcel = document.getElementById('btnExportExcel');
        const dropdownExport = document.getElementById('dropdownExport');

        if (btnExportExcel) {
            btnExportExcel.addEventListener('click', function(e) {
                e.stopPropagation();
                dropdownExport.classList.toggle('hidden');
            });
        }

        document.addEventListener('click', function(e) {
            if (!e.target.closest('#dropdownExport') && !e.target.closest('#btnExportExcel')) {
                if (dropdownExport) dropdownExport.classList.add('hidden');
            }
        });
    });

    // Fungsi untuk merubah angka biasa menjadi format Rp. 10.000
    function formatRupiahJs(angka) {
        if (!angka) return "0";
        let clean = parseInt(angka.toString().replace(/\D/g, '')) || 0;
        return new Intl.NumberFormat('id-ID').format(clean);
    }

    // Fungsi menangkap data dari tombol dan mengisi inputan
    function lihatDetailHistory(nama, no_rek, jenis, admin, debit, kredit, saldo, tanggal, keterangan) {

        document.getElementById('hist_nama').value = nama;
        document.getElementById('hist_no_rek').value = no_rek;
        document.getElementById('hist_jenis').value = jenis.toUpperCase();
        document.getElementById('hist_tanggal').value = tanggal;

        // MENAMPILKAN KETERANGAN
        document.getElementById('hist_keterangan').value = keterangan;

        document.getElementById('hist_admin').value = 'Rp ' + formatRupiahJs(admin);
        document.getElementById('hist_debit').value = 'Rp ' + formatRupiahJs(debit);
        document.getElementById('hist_kredit').value = 'Rp ' + formatRupiahJs(kredit);
        document.getElementById('hist_saldo').value = 'Rp ' + formatRupiahJs(saldo);

        // Setelah data terisi, buka view detail
        switchView('detail');
    }

    // FUNGSI MODAL POPUP NIS TIDAK TERDAFTAR
    function bukaModalNisTidakTerdaftar(nisValue) {
        document.getElementById('nisNotFoundText').innerText = nisValue ? `"${nisValue}"` : '';
        document.getElementById('modalNisTidakTerdaftar').classList.remove('hidden');
    }

    function tutupModalNisTidakTerdaftar() {
        document.getElementById('modalNisTidakTerdaftar').classList.add('hidden');
    }

    // FUNGSI VERIFIKASI NIS / REKENING VIA AJAX
    async function verifikasiDanDapatkanNorek(inputVal, btnElement) {
        if (!inputVal) {
            bukaModalNisTidakTerdaftar('');
            return null;
        }

        const originalBtnText = btnElement ? btnElement.innerHTML : '';
        if (btnElement) {
            btnElement.disabled = true;
            btnElement.innerHTML = '<i class="ph ph-spinner animate-spin"></i> Memeriksa...';
        }

        try {
            const response = await fetch(`/cari-rekening/${encodeURIComponent(inputVal)}`);
            const data = await response.json();

            if (btnElement) {
                btnElement.disabled = false;
                btnElement.innerHTML = originalBtnText;
            }

            if (data && data.success && data.id_rekening) {
                return data.id_rekening;
            } else {
                bukaModalNisTidakTerdaftar(inputVal);
                return null;
            }
        } catch (err) {
            console.error('Gagal verifikasi NIS/Rekening:', err);
            if (btnElement) {
                btnElement.disabled = false;
                btnElement.innerHTML = originalBtnText;
            }
            bukaModalNisTidakTerdaftar(inputVal);
            return null;
        }
    }

// FUNGSI MODAL CETAK BUKU
function bukaModalCetak() {
    document.getElementById('modalCetakBuku').classList.remove('hidden');
    document.getElementById('inputNoRekening').value = '';
    setTimeout(() => document.getElementById('inputNoRekening').focus(), 100);
}

function tutupModalCetak() {
    document.getElementById('modalCetakBuku').classList.add('hidden');
}

async function prosesCetak() {
    const noRekInput = document.getElementById('inputNoRekening').value.trim();
    const baris = document.getElementById('inputBaris').value.trim() || 1;
    // Tangkap status checkbox (bernilai true jika dicentang, false jika tidak)
    const isBukuBaru = document.getElementById('checkBukuBaru').checked ? 1 : 0;
    const btn = document.getElementById('btnProsesCetak');

    if (!noRekInput) {
        bukaModalNisTidakTerdaftar('');
        return;
    }

    const idRekening = await verifikasiDanDapatkanNorek(noRekInput, btn);
    if (idRekening) {
        tutupModalCetak();
        // Tambahkan query parameter &buku_baru=${isBukuBaru}
        window.open(`/teller/cetak-buku/${idRekening}?baris=${baris}&buku_baru=${isBukuBaru}`, '_blank');
    }
}

// FUNGSI MODAL CETAK BIODATA
function bukaModalCetakBiodata() {
    document.getElementById('modalCetakBiodata').classList.remove('hidden');
    document.getElementById('inputNoRekeningBiodata').value = '';
    setTimeout(() => document.getElementById('inputNoRekeningBiodata').focus(), 100);
}

function tutupModalCetakBiodata() {
    document.getElementById('modalCetakBiodata').classList.add('hidden');
}

async function prosesCetakBiodata() {
    const noRekInput = document.getElementById('inputNoRekeningBiodata').value.trim();
    const btn = document.getElementById('btnProsesCetakBiodata');

    if (!noRekInput) {
        bukaModalNisTidakTerdaftar('');
        return;
    }

    const idRekening = await verifikasiDanDapatkanNorek(noRekInput, btn);
    if (idRekening) {
        tutupModalCetakBiodata();
        window.open(`/teller/cetak-biodata/${idRekening}`, '_blank');
    }
} // <--- PENUTUP UNTUK prosesCetakBiodata()

// ================= FUNGSI MODAL CETAK REKENING KORAN =================
function bukaModalCetakKoran() {
    document.getElementById('modalCetakKoran').classList.remove('hidden');
    document.getElementById('inputNoRekeningKoran').value = '';
    document.getElementById('inputTanggalAwalKoran').value = '';
    document.getElementById('inputTanggalAkhirKoran').value = '';
    setTimeout(() => document.getElementById('inputNoRekeningKoran').focus(), 100);
}

function tutupModalCetakKoran() {
    document.getElementById('modalCetakKoran').classList.add('hidden');
}

async function prosesCetakKoran() {
    const noRekInput = document.getElementById('inputNoRekeningKoran').value.trim();
    const tglAwal = document.getElementById('inputTanggalAwalKoran').value;
    const tglAkhir = document.getElementById('inputTanggalAkhirKoran').value;
    const btn = document.getElementById('btnProsesCetakKoran');

    if (!noRekInput) {
        if (typeof bukaModalNisTidakTerdaftar === 'function') {
            bukaModalNisTidakTerdaftar('');
        } else {
            alert("Harap masukkan NIS atau No. Rekening!");
        }
        return;
    }

    if (!tglAwal || !tglAkhir) {
        alert("Harap pilih rentang Tanggal Awal dan Tanggal Akhir!");
        return;
    }

    if (typeof verifikasiDanDapatkanNorek === 'function') {
        const idRekening = await verifikasiDanDapatkanNorek(noRekInput, btn);
        if (idRekening) {
            tutupModalCetakKoran();
            window.open(`/teller/cetak-koran/${idRekening}?start_date=${tglAwal}&end_date=${tglAkhir}`, '_blank');
        }
    } else {
        tutupModalCetakKoran();
        window.open(`/teller/cetak-koran/${noRekInput}?start_date=${tglAwal}&end_date=${tglAkhir}`, '_blank');
    }
}
</script>
@endsection