@extends('layouts.admin')

@section('title','Supervisor Dashboard')
@section('header_title')
Selamat Datang, {{ $user->name ?? 'Administrator' }}!
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
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-5 px-1 gap-4">
        <h3 class="text-[20px] sm:text-[24px] font-bold text-gray-800">Pending Verifikasi</h3>

        <div class="flex bg-gray-100 p-1 rounded-xl w-full sm:w-[300px]">
            <a href="{{ url('/admin/supervisor/verifikasi/login') }}" class="flex-1 px-2 sm:px-4 py-2 text-gray-500 font-medium text-xs sm:text-[13px] hover:text-brand-blue transition-colors text-center">Login</a>
            <a href="{{ url('/admin/supervisor/verifikasi/registrasi') }}" class="flex-1 px-2 sm:px-4 py-2 text-gray-500 font-medium text-xs sm:text-[13px] hover:text-brand-blue transition-colors text-center">Registrasi</a>
            <a href="{{ url('/admin/supervisor/verifikasi/transfer') }}" class="flex-1 px-2 sm:px-4 py-2 bg-white rounded-lg shadow-sm text-brand-blue font-bold text-xs sm:text-[13px] text-center transition-all">Transfer</a>
        </div>
    </div>

    <!-- Table Card -->
    <div class="bg-white rounded-2xl sm:rounded-[20px] shadow-card p-4 sm:p-6 w-full flex flex-col mb-5">
        <div class="flex flex-col md:flex-row justify-between items-stretch md:items-center gap-3 mb-4 pb-3 border-b border-gray-100">
            <form action="{{ url('/admin/supervisor/verifikasi/transfer') }}" method="get" class="flex gap-2 items-center w-full md:w-auto">
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
            <div class="flex flex-wrap items-center justify-between md:justify-end gap-3 w-full md:w-auto">
                <div class="flex items-center gap-2">
                    <span class="text-xs sm:text-[13px] text-gray-600 font-medium shrink-0">Tampilkan:</span>
                    <select onchange="changePerPage(this.value)" class="bg-white border border-gray-200 text-gray-700 text-xs sm:text-[13px] rounded-[10px] px-3 py-1.5 font-semibold focus:outline-none focus:border-brand-blue shadow-sm cursor-pointer">
                        <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10 data</option>
                        <option value="20" {{ $perPage == 20 ? 'selected' : '' }}>20 data</option>
                        <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50 data</option>
                        <option value="100" {{ $perPage == 100 ? 'selected' : '' }}>100 data</option>
                    </select>
                </div>
                @if ($bukti_tf->isNotEmpty())
                <div class="relative inline-block text-left" id="filterDropdownContainer">
                    <button type="button" onclick="toggleFilterDropdown()" class="inline-flex items-center gap-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs sm:text-sm font-semibold px-3 sm:px-4 py-1.5 rounded-xl transition">
                        <i class="ph-bold ph-sliders-horizontal"></i> Filter Tanggal Export
                    </button>

                    <div id="filterDropdownMenu" class="hidden absolute right-0 mt-2 w-full sm:w-72 origin-top-right rounded-2xl bg-white shadow-xl ring-1 ring-black ring-opacity-5 focus:outline-none p-4 z-50 border border-gray-100">
                        <form action="{{ route('admin.supervisor.exportExcel') }}" method="GET" class="space-y-4">
                            <div>
                                <label class="block text-xs font-semibold text-gray-500 mb-1">Dari Tanggal</label>
                                <input type="date" name="start_date" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-[#1c3a5a]">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-500 mb-1">Sampai Tanggal</label>
                                <input type="date" name="end_date" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-[#1c3a5a]">
                            </div>

                            <div class="border-t border-gray-100 pt-3 flex justify-end">
                                <button type="submit" class="w-full bg-[#059669] hover:bg-[#047857] text-white font-semibold text-xs py-2 rounded-lg flex items-center justify-center gap-2 transition">
                                    <i class="ph-bold ph-file-xls text-sm"></i> Unduh Excel
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr>
                        <th class="py-4 px-2 text-[#a3a3a3] font-medium text-[13px] w-12 border-b border-gray-100">No</th>
                        <th class="py-4 px-2 text-[#a3a3a3] font-medium text-[13px] border-b border-gray-100">Nama Pengirim</th>
                        <th class="py-4 px-2 text-[#a3a3a3] font-medium text-[13px] border-b border-gray-100">Nama Penerima</th>
                        <th class="py-4 px-2 text-[#a3a3a3] font-medium text-[13px] border-b border-gray-100">Nominal Transfer</th>
                        <th class="py-4 px-2 text-[#a3a3a3] font-medium text-[13px] border-b border-gray-100">Nomor Telepon</th>
                        <th class="py-4 px-2 text-[#a3a3a3] font-medium text-[13px] border-b border-gray-100">Status</th>
                        <th class="py-4 px-2 text-[#a3a3a3] font-medium text-[13px] text-center w-[140px] border-b border-gray-100">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-[14px] text-gray-800 font-medium">
                    @forelse ($bukti_tf as $index => $item)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="py-4 px-2 border-b border-gray-50 text-gray-600 pl-4">
                            {{ $bukti_tf->firstItem() + $index }}
                        </td>
                        <td class="py-4 px-2 border-b border-gray-50">{{ $item->nama_pengirim }}</td>
                        <td class="py-4 px-2 border-b border-gray-50">{{ $item->nama_penerima }}</td>
                        <td class="py-4 px-2 border-b border-gray-50"> Rp{{ number_format($item->jumlah_transfer, 0, ',', '.') }}</td>
                        <td class="py-4 px-2 border-b border-gray-50">{{ $item->no_hp_pengirim }}</td>
                        <td class="py-4 px-2 border-b border-gray-50 status-col">
                            @if ($item->status_verifikasi == 'pending')
                            <span class="w-7 h-7 rounded-full bg-yellow-100 text-yellow-800 flex items-center justify-center transition-colors shadow-sm">
                                <i class="ph ph-clock text-[20px]" title="Pending"></i>
                            </span>
                            @elseif($item->status_verifikasi == 'berhasil')
                            <span class="w-7 h-7 rounded-full bg-green-100 text-green-800 flex items-center justify-center transition-colors shadow-sm">
                                <i class="ph ph-check-circle text-[20px]" title="Berhasil"></i>
                            </span>
                            @else
                            <span class="w-7 h-7 rounded-full bg-red-100 text-red-800 flex items-center justify-center transition-colors shadow-sm">
                                <i class="ph ph-x-circle text-[20px]" title="Tolak"></i>
                            </span>
                            @endif
                        </td>
                        <td class="py-4 px-2 border-b border-gray-50 action-col">
                            <div class="flex items-center justify-center gap-2">
                                <!-- Tombol Lihat Detail (Menggunakan Data Attributes) -->
                                <button type="button" 
                                    data-pengirim="{{ $item->nama_pengirim }}"
                                    data-penerima="{{ $item->nama_penerima }}"
                                    data-nominal="Rp{{ number_format($item->jumlah_transfer, 0, ',', '.') }}"
                                    data-rek="{{ $item->id_rekening }}"
                                    data-admin="Rp{{ number_format($item->nominal_admin, 0, ',', '.') }}"
                                    data-telp="{{ $item->no_hp_pengirim }}"
                                    data-catatan="{{ $item->catatan }}"
                                    data-tanggal="{{ $item->datetime_tgl }}"
                                    data-bukti="{{ asset(str_starts_with($item->bukti_foto, 'uploads/') || str_starts_with($item->bukti_foto, 'storage/') ? $item->bukti_foto : 'uploads/' . $item->bukti_foto) }}"
                                    onclick="handleViewDetail(this)" 
                                    class="w-[30px] h-[30px] rounded-full bg-[#e2e8f0] text-brand-blue flex items-center justify-center hover:bg-gray-300 transition-colors" 
                                    title="Lihat Detail">
                                    <i class="ph-fill ph-eye text-[16px]"></i>
                                </button>

                                @if($item->status_verifikasi == 'pending')
                                    <!-- Form Setujui -->
                                    <form id="form-approve-tf-{{ $item->id }}" action="{{ route('admin.supervisor.verifikasiTf', $item->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status_verifikasi" value="berhasil">
                                        <button type="button" onclick="confirmAction('form-approve-tf-{{ $item->id }}', 'Setujui Transaksi?', 'Apakah Anda yakin ingin menyetujui verifikasi transfer ini?', 'success', 'Ya, Setujui')" class="w-[30px] h-[30px] rounded-full bg-[#d1fae5] text-[#10a163] flex items-center justify-center hover:bg-green-200 transition-colors" title="Setujui">
                                            <i class="ph-bold ph-check-circle text-[16px]"></i>
                                        </button>
                                    </form>

                                    <!-- Form Tolak -->
                                    <form id="form-reject-tf-{{ $item->id }}" action="{{ route('admin.supervisor.verifikasiTf', $item->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status_verifikasi" value="gagal">
                                        <button type="button" onclick="confirmAction('form-reject-tf-{{ $item->id }}', 'Tolak Transaksi?', 'Apakah Anda yakin ingin membatalkan verifikasi transfer ini?', 'danger', 'Ya, Tolak')" class="w-[30px] h-[30px] rounded-full bg-[#fee2e2] text-red-500 flex items-center justify-center hover:bg-red-200 transition-colors" title="Tolak">
                                            <i class="ph-bold ph-x-circle text-[16px]"></i>
                                        </button>
                                    </form>
                                @else
                                    <span class="text-[9px] text-gray-400 font-italic">
                                        <i class="ph ph-lock"></i> Telah Diverifikasi
                                    </span>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="py-4 px-2 border-b text-gray-500 border-gray-50 text-center" colspan="7">Maaf Data Kosong</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <x-pagination :paginator="$bukti_tf" />

    </div>
</div>

<!-- ================= CRUD VIEWS ================= -->
@include('admin.supervisor.verifikasi.transfer.detail')
@endsection

@section('scripts')
<script>
    // Handler Detail menggunakan Data Attributes
    function handleViewDetail(button) {
        const d = button.dataset;
        viewDetail(d.pengirim, d.penerima, d.nominal, d.rek, d.admin, d.telp, d.catatan, d.tanggal, d.bukti);
    }

    function viewDetail(pengirim, penerima, nominal, rek, admin, telp, catatan, tgl_pengirim, bukti) {
        document.getElementById('detail_pengirim').value = pengirim || '';
        document.getElementById('detail_penerima').value = penerima || '';
        document.getElementById('detail_nominal').value = nominal || '';
        document.getElementById('detail_rek_penerima').value = rek || '';
        document.getElementById('detail_telepon').value = telp || '';
        document.getElementById('detail_admin').value = admin || '';

        document.getElementById('detail_tanggal').value = tgl_pengirim || '';
        document.getElementById('detail_catatan').value = catatan || '';

        const placeholder = document.getElementById('detail_bukti_container');
        const imgTag = document.getElementById('detail_bukti_img');
        const pdfTag = document.getElementById('detail_bukti_pdf');

        placeholder.classList.add('hidden');
        imgTag.classList.add('hidden');
        pdfTag.classList.add('hidden');
        imgTag.src = '';
        pdfTag.src = '';

        if (bukti && bukti.trim() !== '' && !bukti.endsWith('/')) {
            const ekstensi = bukti.split('?')[0].split('.').pop().toLowerCase();

            if (ekstensi === 'pdf') {
                pdfTag.src = bukti;
                pdfTag.classList.remove('hidden');
            } else {
                imgTag.src = bukti;
                imgTag.classList.remove('hidden');
            }
        } else {
            placeholder.classList.remove('hidden');
        }

        switchView('detail');
    }

    // Handler Konfirmasi Modal yang Aman
    function confirmAction(formId, title, message, type, confirmText) {
        if (typeof openConfirmModal === 'function') {
            openConfirmModal({
                title: title,
                message: message,
                type: type,
                confirmText: confirmText,
                onConfirm: () => {
                    const form = document.getElementById(formId);
                    if (form) form.submit();
                }
            });
        } else {
            // Fallback jika modal custom tidak ada
            if (confirm(message)) {
                const form = document.getElementById(formId);
                if (form) form.submit();
            }
        }
    }

    function switchView(viewName) {
        const views = {
            'tabel': document.getElementById('viewTabelData'),
            'detail': document.getElementById('viewDetailData')
        };

        Object.values(views).forEach(v => {
            if (v) {
                v.classList.add('hidden');
                v.classList.remove('flex', 'block');
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

        const mainElem = document.querySelector('main');
        if (mainElem) {
            mainElem.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        }
    }

    function toggleFilterDropdown() {
        const menu = document.getElementById('filterDropdownMenu');
        menu.classList.toggle('hidden');
    }

    window.addEventListener('click', function(e) {
        const menu = document.getElementById('filterDropdownMenu');
        const container = document.getElementById('filterDropdownContainer');
        if (container && !container.contains(e.target) && menu) {
            menu.classList.add('hidden');
        }
    });

    function changePerPage(value) {
        const url = new URL(window.location.href);
        url.searchParams.set('per_page', value);
        url.searchParams.set('page', 1);
        window.location.href = url.toString();
    }
</script>
@endsection