@extends('layouts.admin')

@section('title', 'Revisi Registrasi - Supervisor')

@section('header_title')
    Verifikasi Registrasi
@endsection

@section('header_subtitle', 'Masukkan catatan perbaikan untuk pendaftaran rekening nasabah.')

@section('styles')
<style>
    /* Fade animation */
    .fade-in {
        animation: fadeIn 0.3s ease-in-out;
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>
@endsection

@section('content')
<div class="fade-in flex-1 mt-4">
    <div class="bg-white rounded-[24px] shadow-card p-6 md:p-10 w-full border border-gray-50">
        <h3 class="text-[20px] font-bold text-gray-800 mb-8 flex items-center gap-3">
            <div class="w-[6px] h-7 bg-brand-gold rounded-full"></div>
            Catatan Revisi Registrasi
        </h3>

        <form action="{{ Route('revisi.nasabah.admin', $nasabah->id) }}" method="post">
            @csrf
            @method('PUT')

            <input type="hidden" value="revisi" name="status_akun">
            <input type="hidden" value="Admin" name="nama_perevisi">

            <div class="space-y-6">
                <!-- Info Nasabah yang Direvisi -->
                <div class="bg-amber-50/50 border border-amber-100 rounded-2xl p-5 mb-6 flex items-center gap-4">
                    <div>
                        <span class="text-[11px] font-bold text-brand-gold uppercase tracking-wider block mb-1">Nasabah yang Direvisi</span>
                        <h4 class="text-[18px] font-bold text-gray-800">{{ $nasabah->nama_nasabah ?? 'Dominik' }}</h4>
                    </div>
                </div>

                <!-- Input Catatan Revisi -->
                <div>
                    <label for="pesan" class="block text-[13.5px] font-bold text-gray-500 mb-2">Pesan / Alasan Revisi</label>
                    <textarea
                        name="pesan"
                        id="pesan"
                        rows="8"
                        placeholder="Tulis instruksi perbaikan atau alasan revisi di sini."
                        class="w-full border border-gray-200 rounded-xl px-4 py-3.5 text-[14px] text-gray-800 focus:outline-none focus:border-brand-blue focus:ring-1 focus:ring-brand-blue transition-all bg-white shadow-sm resize-none"
                        required></textarea>
                </div>
            </div>

            <!-- Buttons -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-10">
                <a href="{{ route('supervisor.verifikasi.registrasi') }}" class="w-full bg-[#797979] hover:bg-gray-600 text-white font-bold py-3.5 rounded-xl transition-colors text-[15px] flex items-center justify-center shadow-sm">
                    Kembali
                </a>
                <button type="submit" class="w-full bg-brand-blue hover:opacity-95 text-white font-bold py-3.5 rounded-xl transition-colors text-[15px] shadow-lg shadow-brand-blue/10">
                    Kirim Catatan Revisi
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
