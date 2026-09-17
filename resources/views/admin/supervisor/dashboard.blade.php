@extends('layouts.admin')

@section('title','Supervisor Dashboard')
@section('header_title')
Selamat Datang, {{ $user->name ?? 'Administrator' }}!
@endsection
@section('header_subtitle', 'Lorem Ipsum is simply dummy text of the printing.')

@section('content')
<div id="viewMain" class="fade-in block">
    <!-- Statistics Cards -->
    <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
        <!-- Total Saldo -->
        <div class="bg-primary-gradient rounded-2xl p-6 text-white relative overflow-hidden shadow-lg h-[130px] flex flex-col justify-center sm:col-span-2 lg:col-span-2">
            <div class="relative z-10">
                <p class="text-[11px] font-semibold tracking-[0.08em] text-blue-100/80 mb-2.5 uppercase">Total Saldo Tabungan</p>
                <h3 class="text-[24px] md:text-[28px] font-bold">Rp. {{ number_format($totalSaldoTabungan, 0, ',', '.') }}</h3>
            </div>
            <div class="absolute -right-2 -bottom-2 flex opacity-10"><i class="ph-fill ph-bank text-[120px] translate-y-6 translate-x-4"></i></div>
        </div>

        <div class="bg-gradient-to-br from-[#3b82f6] to-[#4f46e5] rounded-2xl p-6 text-white relative overflow-hidden shadow-lg h-[130px] flex flex-col justify-center">
            <div class="relative z-10">
                <p class="text-[11px] font-semibold tracking-[0.08em] text-blue-100/80 mb-2.5 uppercase">Total Saldo Admin Tf Luar</p>
                <h3 class="text-[24px] md:text-[28px] font-bold">Rp. {{ number_format($adminTotal, 0, ',', '.') }}</h3>
            </div>
            <div class="absolute -right-2 -bottom-2 flex opacity-10"><i class="ph-fill ph-money text-[120px] translate-y-6 translate-x-4"></i></div>
        </div>

        <div class="bg-gradient-to-br from-[#0ea5e9] to-[#0284c7] rounded-2xl p-6 text-white relative overflow-hidden shadow-lg h-[130px] flex flex-col justify-center">
            <div class="relative z-10">
                <p class="text-[11px] font-semibold tracking-[0.08em] text-blue-100/80 mb-2.5 uppercase">Total Saldo Admin Antar Nasabah</p>
                <h3 class="text-[24px] md:text-[28px] font-bold">Rp. {{ number_format($adminAntarNasabah, 0, ',', '.') }}</h3>
            </div>
            <div class="absolute -right-2 -bottom-2 flex opacity-10"><i class="ph-fill ph-coins text-[120px] translate-y-6 translate-x-4"></i></div>
        </div>

        <!-- Total Nasabah -->
        <div class="bg-success-gradient rounded-2xl p-6 text-white relative overflow-hidden shadow-lg h-[130px] flex flex-col justify-center sm:col-span-1 lg:col-span-2">
            <div class="relative z-10">
                <p class="text-[11px] font-semibold tracking-[0.08em] text-green-100/80 mb-2.5 uppercase">Total Nasabah</p>
                <h3 class="text-[28px] md:text-[32px] font-bold">{{ $totalNasabah }}</h3>
            </div>
            <div class="absolute -right-2 -bottom-2 flex opacity-10"><i class="ph-fill ph-users-three text-[120px] translate-y-6 translate-x-4"></i></div>
        </div>

        <!-- Pending Verifikasi -->
        <div class="bg-warning-gradient rounded-2xl p-6 text-white relative overflow-hidden shadow-lg h-[130px] flex flex-col justify-center sm:col-span-1 lg:col-span-2">
            <div class="relative z-10">
                <p class="text-[11px] font-semibold tracking-[0.08em] text-yellow-100/80 mb-2.5 uppercase">Pending Verifikasi</p>
                <h3 class="text-[28px] md:text-[32px] font-bold">{{ $totalPending }}</h3>
            </div>
            <div class="absolute -right-2 -bottom-2 flex opacity-10"><i class="ph-fill ph-hourglass-high text-[120px] translate-y-6 translate-x-4"></i></div>
        </div>
    </section>

    <!-- Bottom Content -->
    <section class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start mb-5">
        <div class="lg:col-span-2 flex flex-col gap-3.5">
            <div class="flex justify-between items-end mb-1 px-1">
                <h3 class="text-[20px] font-bold text-gray-800">Pending Verifikasi</h3>
                @if ($nasabahPending->isNotEmpty() || $nasabahTfPending->isNotEmpty())
                <button onclick="switchView('history')"
                    class="text-[13px] font-bold text-gray-500 hover:text-brand-blue transition-colors">
                    Lihat Semua
                </button>
                @endif
            </div>

            <div class="flex flex-col gap-3">
            @if($nasabahPending->isNotEmpty() || $nasabahTfPending->isNotEmpty())
                @foreach ($nasabahPending as $item)
                <!-- Jika data ADA, bagian ini yang dijalankan (Looping) -->
                <div class="bg-white rounded-[20px] p-4 px-6 flex items-center justify-between shadow-[0_4px_20px_rgba(0,0,0,0.02)] border border-gray-50">
                    <div class="flex items-center gap-4">
                        <i class="ph-fill ph-user-circle text-[40px] text-brand-blue"></i>
                        <div>
                            <h4 class="font-bold text-[16px] text-gray-800">{{ $item->nama_nasabah }}</h4>
                            <p class="text-[12px] text-gray-400 font-medium">Registrasi Akun</p>
                        </div>
                    </div>
                    <a href="{{ url('/admin/supervisor/verifikasi/registrasirekening') }}">
                    <button class="w-[36px] h-[36px] rounded-full bg-[#f1f5f9] text-[#1e293b] flex items-center justify-center hover:bg-[#e2e8f0] transition-colors border border-gray-100" title="Lihat">
                        <i class="ph-fill ph-eye text-[18px]"></i>
                    </button>
                    </a>
                </div>
                @endforeach
                @foreach ($nasabahTfPending as $item)
                <!-- Jika data ADA, bagian ini yang dijalankan (Looping) -->
                <div class="bg-white rounded-[20px] p-4 px-6 flex items-center justify-between shadow-[0_4px_20px_rgba(0,0,0,0.02)] border border-gray-50">
                    <div class="flex items-center gap-4">
                        <i class="ph-fill ph-user-circle text-[40px] text-brand-blue"></i>
                        <div>
                            <h4 class="font-bold text-[16px] text-gray-800">{{ $item->nama_penerima }}</h4>
                            <p class="text-[12px] text-gray-400 font-medium">Transfer Masuk</p>
                        </div>
                    </div>
                    <button onclick="window.location.href='{{ url('/admin/supervisor/verifikasi/transfer') }}'" class="w-[36px] h-[36px] rounded-full bg-[#f1f5f9] text-[#1e293b] flex items-center justify-center hover:bg-[#e2e8f0] transition-colors border border-gray-100" title="Lihat">
                        <i class="ph-fill ph-eye text-[18px]"></i>
                    </button>
                </div>
                @endforeach
            @else
                <!-- Jika data KOSONG, bagian ini yang dijalankan -->
                <div class="bg-white rounded-[20px] p-4 px-6 flex items-center justify-between shadow-[0_4px_20px_rgba(0,0,0,0.02)] border border-gray-50">
                    <div class="flex items-center gap-4">
                        <div>
                            <p class="text-[12px] text-gray-400 font-medium">Data Verifikasi Kosong</p>
                        </div>
                    </div>
                </div>
            @endif
            </div>
        </div>

        <!-- Red Card: Segera Periksa! -->
        @if ($totalPending > 0)
        <div class="bg-[#fee2e2] rounded-[24px] p-7 shadow-sm border border-red-100 flex flex-col gap-8 mt-1">
            <div class="flex items-center gap-3">
                <i class="ph-fill ph-warning text-[26px] text-[#dc2626]"></i>
                <h3 class="text-[14px] font-bold text-[#dc2626] tracking-wide">Segera Periksa!</h3>
            </div>

            <div class="flex flex-col gap-7">
                @if ($totalPendingRegistrasi > 0)
                <!-- Item 1 -->
                <div class="flex items-center justify-between group cursor-pointer" onclick="window.location.href='{{ url('/admin/supervisor/verifikasi/registrasirekening') }}'">
                    <div class="flex items-center gap-5">
                        <div class="w-2.5 h-2.5 rounded-full bg-[#dc2626] shadow-[0_0_8px_rgba(220,38,38,0.4)]"></div>
                        <div>
                            <h4 class="font-bold text-[16px] text-[#991b1b] leading-tight tracking-tight">Registrasi Akun</h4>
                            <p class="text-[10px] text-[#dc2626] mt-1">{{ $totalPendingRegistrasi }} Rekening baru harus diperiksa</p>
                        </div>
                    </div>
                    <i class="ph ph-caret-right text-[22px] text-[#dc2626]/40 group-hover:text-[#dc2626] transition-all"></i>
                </div>
                @endif

                @if ($totalPendingTransfer > 0)
                <!-- Item 2 -->
                <div class="flex items-center justify-between group cursor-pointer" onclick="window.location.href='{{ url('/admin/supervisor/verifikasi/transfer') }}'">
                    <div class="flex items-center gap-5">
                        <div class="w-2.5 h-2.5 rounded-full bg-[#dc2626] shadow-[0_0_8px_rgba(220,38,38,0.4)]"></div>
                        <div>
                            <h4 class="font-bold text-[16px] text-[#991b1b] leading-tight tracking-tight">Transfer Pihak Luar</h4>
                            <p class="text-[10px] text-[#dc2626] mt-1">{{ $totalPendingTransfer }} Transfer harus diperiksa</p>
                        </div>
                    </div>
                    <i class="ph ph-caret-right text-[22px] text-[#dc2626]/40 group-hover:text-[#dc2626] transition-all"></i>
                </div>
                @endif
            </div>
        </div>
        @endif
    </section>
</div>

<!-- VIEW HISTORY: Semua Pending Verifikasi (10 Terakhir) -->
<div id="viewHistory" class="fade-in hidden">
    <div class="bg-white rounded-[24px] shadow-[0_4px_24px_rgba(0,0,0,0.02)] border border-gray-50 p-8 lg:p-10">
        <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4 mb-8 md:mb-10">
            <button onclick="switchView('main')" class="text-[14px] font-bold text-gray-800 hover:text-brand-blue transition-colors flex items-center gap-2 w-fit">
                <i class="ph ph-arrow-left"></i> Kembali
            </button>
            <h3 class="text-[18px] md:text-[22px] font-bold text-gray-800">Semua Pending Verifikasi</h3>
        </div>

        <div class="lg:space-y-5">
            @if($nasabahPending->count() || $nasabahTfPending->count())
                @foreach ($nasabahPending as $item)
                <div class="flex justify-between items-center bg-white p-4 px-6 rounded-[20px] border border-gray-50 hover:border-gray-100 hover:shadow-sm transition-all">
                    <div class="flex items-center gap-4">
                        <i class="ph-fill ph-user-circle text-[30px] lg:text-[40px] text-brand-blue"></i>
                        <div>
                            <h4 class="font-bold text-[13px] lg:text-[15px] text-gray-800">{{ $item->nama_nasabah }}</h4>
                            <p class="text-[13px] lg:text-[15px] text-gray-500 mt-0.5">Registrasi Akun • {{ $item->nis_nip }}</p>
                        </div>
                    </div>
                    <a href="{{ url('/admin/supervisor/verifikasi/registrasirekening') }}">
                    <button class="w-[32px] h-[32px] rounded-full bg-[#f1f5f9] text-brand-blue flex items-center justify-center hover:bg-gray-200 transition-colors">
                        <i class="ph-fill ph-eye text-[16px]"></i>
                    </button>
                    </a>
                </div>
                @endforeach

                @foreach ($nasabahTfPending as $item)
                <div class="flex justify-between items-center bg-white p-4 px-6 rounded-[20px] border border-gray-50 hover:border-gray-100 hover:shadow-sm transition-all">
                    <div class="flex items-center gap-4">
                        <i class="ph-fill ph-user-circle text-[30px] lg:text-[40px] text-brand-blue"></i>
                        <div>
                            <h4 class="font-bold text-[13px] lg:text-[15px] text-gray-800">{{ $item->nama_penerima }}</h4>
                            <p class="text-[13px] lg:text-[15px] text-gray-500 mt-0.5">Transfer Masuk • {{ $item->datetime_tgl }}</p>
                        </div>
                    </div>
                    <button onclick="window.location.href='{{ url('/admin/supervisor/verifikasi/transfer') }}'" class="w-[32px] h-[32px] rounded-full bg-[#f1f5f9] text-brand-blue flex items-center justify-center hover:bg-gray-200 transition-colors">
                        <i class="ph-fill ph-eye text-[16px]"></i>
                    </button>
                </div>
                @endforeach
            @else
                <div class="bg-white rounded-[20px] p-4 px-6 flex items-center justify-between shadow-[0_4px_20px_rgba(0,0,0,0.02)] border border-gray-50">
                    <div class="flex items-center gap-4">
                        <div>
                            <p class="text-[12px] text-gray-400 font-medium">Data Verifikasi Kosong</p>
                        </div>
                    </div>
                </div>
            @endif
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

        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    }
</script>
@endsection
