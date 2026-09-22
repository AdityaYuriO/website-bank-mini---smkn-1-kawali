

@extends('layouts.admin')

@section('title', 'admin - Import Data Nasabah')
@section('header_title', 'Import Data Nasabah')
@section('header_subtitle', 'Unggah file Excel untuk mengimpor data nasabah secara massal.')

@section('content')
<div class="fade-in flex-1 mt-4">
    <div class="bg-white rounded-[24px] shadow-card p-6 md:p-10 w-full border border-gray-50 max-w-[800px] mx-auto">

        <!-- Kembali Ke Kelola Data -->
        <div class="mb-6">
            <a href="{{ route('kelola.data.cs.admin') }}" class="inline-flex items-center gap-2 text-[13px] font-bold text-brand-blue hover:opacity-80 transition-all">
                <i class="ph ph-arrow-left text-base"></i> Kembali ke Kelola Data
            </a>
        </div>

        <div class="flex items-center gap-3 mb-8">
            <div class="w-[5px] h-6 bg-brand-blue rounded-full"></div>
            <h3 class="text-[20px] font-bold text-gray-800">Unggah File Excel</h3>
        </div>

        <!-- Form Import -->
        <form action="{{ route('import.nasabah.admin') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <!-- Area Upload File -->
            <div class="border-2 border-dashed border-gray-200 hover:border-brand-blue rounded-2xl p-8 transition-colors flex flex-col items-center justify-center cursor-pointer group relative" id="dropArea">
                <input type="file" name="file" id="fileInput" required class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" accept=".xlsx,.xls">

                <div class="w-16 h-16 rounded-full bg-blue-50 text-brand-blue flex items-center justify-center mb-4 transition-transform group-hover:scale-105 duration-200">
                    <i class="ph ph-file-xls text-3xl"></i>
                </div>

                <h4 class="text-sm font-bold text-gray-800 mb-1" id="fileStatusTitle">Pilih file Excel Anda</h4>
                <p class="text-xs text-gray-400 mb-4 text-center leading-relaxed">Seret file di sini atau klik untuk mencari.<br>Format yang didukung: <span class="font-bold text-brand-blue">.xlsx, .xls</span></p>

                <div class="hidden bg-slate-50 border border-slate-100 rounded-xl px-4 py-2 text-xs font-semibold text-slate-600 flex items-center gap-2 animate-fade-in" id="fileNameContainer">
                    <i class="ph ph-file-text text-base text-brand-blue"></i>
                    <span id="fileName">nama_file.xlsx</span>
                </div>
            </div>

            <!-- Tombol Action -->
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
                <a href="{{ route('kelola.data.cs.admin') }}" class="px-6 py-2.5 rounded-[12px] text-[13px] font-bold text-gray-500 hover:bg-gray-50 transition-colors">
                    Batal
                </a>
                <button type="submit" class="bg-brand-blue text-white px-6 py-2.5 rounded-[12px] text-[13px] font-bold flex items-center gap-2 hover:opacity-95 transition-all shadow-md active:scale-95">
                    <i class="ph ph-upload-simple text-base"></i> Mulai Import
                </button>
            </div>
        </form>

    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const fileInput = document.getElementById('fileInput');
        const fileName = document.getElementById('fileName');
        const fileNameContainer = document.getElementById('fileNameContainer');
        const fileStatusTitle = document.getElementById('fileStatusTitle');
        const dropArea = document.getElementById('dropArea');

        fileInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                const name = this.files[0].name;
                fileName.textContent = name;
                fileNameContainer.classList.remove('hidden');
                fileStatusTitle.textContent = 'File terpilih!';
                dropArea.classList.add('border-brand-blue', 'bg-blue-50/20');
            } else {
                fileNameContainer.classList.add('hidden');
                fileStatusTitle.textContent = 'Pilih file Excel Anda';
                dropArea.classList.remove('border-brand-blue', 'bg-blue-50/20');
            }
        });
    });
</script>
@endsection
