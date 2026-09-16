@extends('layouts.admin')

@section('title', 'Kelola Biaya Transaksi')
@section('header_title')
Selamat Datang, {{ $user->name ?? 'Administrator' }}!
@endsection
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
<div id="viewMain" class="fade-in flex flex-1 flex-col justify-start">
    
    <!-- Header Page: Title & Save Button -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6 px-1">
        <h3 class="text-[20px] md:text-[22px] font-bold text-gray-800">Kelola Biaya Transaksi</h3>
        <button id="btnSaveFees" onclick="saveFees()" class="bg-[#1c3a5a] text-white px-7 py-2.5 rounded-[10px] text-[14px] font-semibold hover:bg-[#1a3552] transition-all shadow-md w-full sm:w-auto text-center flex items-center justify-center gap-2">
            <span>Simpan</span>
        </button>
    </div>

    <!-- Cards Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        
        <!-- Card 1: Setoran -->
        <div class="bg-white rounded-[24px] shadow-card p-6 md:p-8 flex flex-col border border-gray-100/50 min-h-[300px]">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-[14px] bg-[#1c3a5a] flex items-center justify-center text-white shadow-md shadow-brand-blue/10">
                    <i class="ph-fill ph-credit-card text-[22px]"></i>
                </div>
                <h4 class="text-[18px] md:text-[20px] font-bold text-[#1e293b]">Setoran</h4>
            </div>
            
            <div class="mt-6 flex flex-col">
                <span class="text-gray-400 font-semibold text-[13px] tracking-wide mb-2">Biaya Admin</span>
                <div class="relative flex items-center">
                    <span class="absolute left-4 text-gray-800 font-bold text-[14px]">Rp.</span>
                    <input
                    type="number"
                    id="biaya_setoran"
                    value="{{ $setoran->nominal ?? 0 }}"
                    class="w-full pl-11 pr-4 py-3 bg-white border border-gray-200 rounded-[12px] text-[14px] font-bold focus:outline-none focus:border-brand-blue focus:ring-1 focus:ring-brand-blue text-gray-800 placeholder-gray-400 shadow-sm transition-all"
                    placeholder="0">
                </div>
            </div>

            <div class="border-l-[3px] border-amber-500 pl-3.5 py-0.5 mt-8 mb-1">
                <p class="text-[11px] text-gray-500 leading-relaxed font-normal">
                    Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed.
                </p>
            </div>
        </div>

        <!-- Card 2: Penarikan -->
        <div class="bg-white rounded-[24px] shadow-card p-6 md:p-8 flex flex-col border border-gray-100/50 min-h-[300px]">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-[14px] bg-[#1c3a5a] flex items-center justify-center text-white shadow-md shadow-brand-blue/10">
                    <i class="ph-fill ph-credit-card text-[22px]"></i>
                </div>
                <h4 class="text-[18px] md:text-[20px] font-bold text-[#1e293b]">Penarikan</h4>
            </div>
            
            <div class="mt-6 flex flex-col">
                <span class="text-gray-400 font-semibold text-[13px] tracking-wide mb-2">Biaya Admin</span>
                <div class="relative flex items-center">
                    <span class="absolute left-4 text-gray-800 font-bold text-[14px]">Rp.</span>
                    <input
                        type="number"
                        id="biaya_penarikan"
                        value="{{ $penarikan->nominal ?? 0 }}"
                        class="w-full pl-11 pr-4 py-3 bg-white border border-gray-200 rounded-[12px] text-[14px] font-bold focus:outline-none focus:border-brand-blue focus:ring-1 focus:ring-brand-blue text-gray-800 placeholder-gray-400 shadow-sm transition-all"
                        placeholder="0">
                </div>
            </div>

            <div class="border-l-[3px] border-amber-500 pl-3.5 py-0.5 mt-8 mb-1">
                <p class="text-[11px] text-gray-500 leading-relaxed font-normal">
                    Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed.
                </p>
            </div>
        </div>

        <!-- Card 3: Transfer -->
        <div class="bg-white rounded-[24px] shadow-card p-6 md:p-8 flex flex-col border border-gray-100/50 min-h-[300px]">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-[14px] bg-[#1c3a5a] flex items-center justify-center text-white shadow-md shadow-brand-blue/10">
                    <i class="ph-fill ph-credit-card text-[22px]"></i>
                </div>
                <h4 class="text-[18px] md:text-[20px] font-bold text-[#1e293b]">Transfer</h4>
            </div>
            
            <div class="mt-6 flex flex-col">
                <span class="text-gray-400 font-semibold text-[13px] tracking-wide mb-2">Biaya Admin</span>
                <div class="relative flex items-center">
                    <span class="absolute left-4 text-gray-800 font-bold text-[14px]">Rp.</span>
                    <input
                        type="number"
                        id="biaya_transfer"
                        value="{{ $transfer->nominal ?? 0 }}"
                        class="w-full pl-11 pr-4 py-3 bg-white border border-gray-200 rounded-[12px] text-[14px] font-bold focus:outline-none focus:border-brand-blue focus:ring-1 focus:ring-brand-blue text-gray-800 placeholder-gray-400 shadow-sm transition-all"
                        placeholder="0">
                </div>
            </div>

            <div class="border-l-[3px] border-amber-500 pl-3.5 py-0.5 mt-8 mb-1">
                <p class="text-[11px] text-gray-500 leading-relaxed font-normal">
                    Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed.
                </p>
            </div>
        </div>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <!-- card: 4 -->
        <div class="bg-white rounded-[24px] shadow-card p-6 md:p-8 flex flex-col border border-gray-100/50 min-h-[200px]">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-[14px] bg-[#1c3a5a] flex items-center justify-center text-white shadow-md shadow-brand-blue/10">
                    <i class="ph-fill ph-credit-card text-[22px]"></i>
                </div>
                <h4 class="text-[18px] md:text-[20px] font-bold text-[#1e293b]">Transfer Luar</h4>
            </div>
            
            <div class="mt-6 flex flex-col">
                <span class="text-gray-400 font-semibold text-[13px] tracking-wide mb-2">Biaya Admin</span>
                <div class="relative flex items-center">
                    <span class="absolute left-4 text-gray-800 font-bold text-[14px]">Rp.</span>
                    <input
                        type="number"
                        id="biaya_transfer_luar"
                        value="{{ $transfer_luar->nominal ?? 0 }}"
                        class="w-full pl-11 pr-4 py-3 bg-white border border-gray-200 rounded-[12px] text-[14px] font-bold focus:outline-none focus:border-brand-blue focus:ring-1 focus:ring-brand-blue text-gray-800 placeholder-gray-400 shadow-sm transition-all"
                        placeholder="0">
                </div>
            </div>

            <div class="border-l-[3px] border-amber-500 pl-3.5 py-0.5 mt-8 mb-1">
                <p class="text-[11px] text-gray-500 leading-relaxed font-normal">
                    Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed.
                </p>
            </div>
        </div>
        <!-- card: 5 -->
        <div class="bg-white rounded-[24px] shadow-card p-6 md:p-8 flex flex-col border border-gray-100/50 min-h-[200px]">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-[14px] bg-[#1c3a5a] flex items-center justify-center text-white shadow-md shadow-brand-blue/10">
                    <i class="ph-fill ph-credit-card text-[22px]"></i>
                </div>
                <h4 class="text-[18px] md:text-[20px] font-bold text-[#1e293b]">Transfer Nasabah</h4>
            </div>
            
            <div class="mt-6 flex flex-col">
                <span class="text-gray-400 font-semibold text-[13px] tracking-wide mb-2">Biaya Admin</span>
                <div class="relative flex items-center">
                    <span class="absolute left-4 text-gray-800 font-bold text-[14px]">Rp.</span>
                    <input
                        type="number"
                        id="biaya_transfer_nasabah"
                        value="{{ $transfer_nasabah->nominal ?? 0 }}"
                        class="w-full pl-11 pr-4 py-3 bg-white border border-gray-200 rounded-[12px] text-[14px] font-bold focus:outline-none focus:border-brand-blue focus:ring-1 focus:ring-brand-blue text-gray-800 placeholder-gray-400 shadow-sm transition-all"
                        placeholder="0">
                </div>
            </div>

            <div class="border-l-[3px] border-amber-500 pl-3.5 py-0.5 mt-8 mb-1">
                <p class="text-[11px] text-gray-500 leading-relaxed font-normal">
                    Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed.
                </p>
            </div>
        </div>

    </div>
</div>
@endsection

@section('scripts')
<script>
function saveFees() {
    const btn = document.getElementById('btnSaveFees');
    const originalContent = btn ? btn.innerHTML : '<span>Simpan</span>';

    if (btn) {
        btn.disabled = true;
        btn.innerHTML = `<i class="ph ph-spinner animate-spin text-lg"></i> <span>Menyimpan...</span>`;
    }

    setTimeout(() => {
        showToast('Biaya transaksi berhasil disimpan (Mode Preview)!', 'success');
        if (btn) {
            btn.disabled = false;
            btn.innerHTML = originalContent;
        }
    }, 600);
}
</script>
@endsection
