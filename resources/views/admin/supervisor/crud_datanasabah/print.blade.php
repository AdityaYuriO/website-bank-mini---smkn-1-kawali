<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> </title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            body {
                background: white !important;
                color: black !important;
                font-size: 12px !important;
            }
            @page {
                size: A4;
                margin: 0; /* Hides default browser header (title, date) and footer (URL, page numbers) */
            }
            body .print-container {
                width: 210mm !important;
                height: auto !important;
                padding: 1.2cm 1.6cm 1.2cm 1.6cm !important; /* Top, Right, Bottom, Left */
                box-sizing: border-box !important;
                margin: 0 auto !important;
                box-shadow: none !important;
                border-radius: 0 !important;
                background-color: white !important;
            }
            /* Menghindari halaman terbelah */
            .page-break-avoid {
                page-break-inside: avoid;
            }
        }
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f3f4f6;
        }
    </style>
</head>
<body class="bg-gray-100 py-4 print:py-0 print:bg-white text-slate-800">

    <!-- Container Utama -->
    <div class="print-container max-w-[800px] mx-auto bg-white p-6 print:p-0 shadow-md print:shadow-none rounded-xl print:rounded-none">

        <!-- Kop Surat -->
        <div class="flex items-center justify-between pb-3 border-b-4 border-slate-900 mb-5">
            <div class="w-20 h-20 shrink-0 flex items-center justify-center">
                <img src="{{ asset('img/logosmk.png') }}" alt="Logo SMK" class="w-full h-full object-contain">
            </div>
            <div class="text-center flex-1 px-6">
                <h1 class="text-2xl print:text-[26px] font-extrabold tracking-wider text-slate-900 leading-tight">BANK MINI K-ONE</h1>
                <h2 class="text-sm print:text-base font-bold text-slate-800 leading-normal mt-0.5">SMK NEGERI 1 KAWALI</h2>
                <p class="text-[10px] print:text-xs text-slate-600 italic mt-1">Jalan Raya Kawali No. 65, Ciamis, Jawa Barat | Kode Pos: 46253</p>
                <p class="text-[9px] print:text-[10px] text-slate-500 mt-0.5">Telp: (0265) 791707 | Email: bankmini.kone@smkn1kawali.sch.id</p>
            </div>
            <div class="w-20 h-20 shrink-0 flex items-center justify-center">
                <img src="{{ asset('img/bankmini2.png') }}" alt="Logo Bank Mini" class="w-full h-full object-contain">
            </div>
        </div>

        <!-- Judul Dokumen -->
        <div class="text-center mb-5 print:mb-6">
            <h3 class="text-base print:text-lg font-black uppercase tracking-widest text-slate-900 border-b-2 border-slate-300 inline-block pb-1">FORMULIR DATA PROFIL NASABAH</h3>
            <p class="text-xs print:text-sm text-slate-500 mt-1.5">Nomor Rekening: <span class="font-bold text-slate-900 text-xs print:text-base tracking-wider">{{ $nasabah->rekening->id ?? '-' }}</span></p>
        </div>

        <!-- Blok Data Nasabah -->
        <div class="space-y-4 print:space-y-5 text-xs print:text-[12.5px] text-slate-800">
            <!-- 1. Informasi Akun & Pekerjaan -->
            <div class="page-break-avoid">
                <h4 class="font-bold text-slate-900 border-b border-slate-200 pb-1 mb-2 uppercase tracking-wider text-[11px] print:text-xs flex items-center gap-2">
                    <span class="w-1.5 h-3 bg-blue-600 rounded-sm"></span> Informasi Akun & Kepegawaian
                </h4>
                <div class="grid grid-cols-2 gap-x-8 gap-y-1.5 print:gap-y-2">
                    <div class="flex pb-1">
                        <span class="w-36 shrink-0 font-medium text-slate-500">No. Rekening</span>
                        <span class="font-bold text-slate-900">: {{ $nasabah->rekening->id ?? 'Belum dibuat' }}</span>
                    </div>
                    <div class="flex pb-1">
                        <span class="w-36 shrink-0 font-medium text-slate-500">Status Akun</span>
                        <span class="font-bold uppercase text-slate-900 text-[11px] print:text-xs">: {{ $nasabah->rekening->status_akun ?? 'Menunggu Verifikasi' }}</span>
                    </div>
                    <div class="flex pb-1">
                        <span class="w-36 shrink-0 font-medium text-slate-500">NIS / NIP</span>
                        <span class="text-slate-900">: {{ $nasabah->nis_nip }}</span>
                    </div>
                    <div class="flex pb-1">
                        <span class="w-36 shrink-0 font-medium text-slate-500">Jabatan / Status</span>
                        <span class="text-slate-900">: {{ $nasabah->jabatan }}</span>
                    </div>
                    <div class="flex pb-1">
                        <span class="w-36 shrink-0 font-medium text-slate-500">Pendidikan</span>
                        <span class="text-slate-900">: {{ $nasabah->pendidikan }}</span>
                    </div>
                    <div class="flex pb-1">
                        <span class="w-36 shrink-0 font-medium text-slate-500">Jurusan</span>
                        <span class="text-slate-900">: {{ $nasabah->jurusan->nama_jurusan ?? '-' }}</span>
                    </div>
                </div>
            </div>

            <!-- 2. Informasi Pribadi -->
            <div class="page-break-avoid">
                <h4 class="font-bold text-slate-900 border-b border-slate-200 pb-1 mb-2 uppercase tracking-wider text-[11px] print:text-xs flex items-center gap-2">
                    <span class="w-1.5 h-3 bg-blue-600 rounded-sm"></span> Data Pribadi Nasabah
                </h4>
                <div class="grid grid-cols-2 gap-x-8 gap-y-1.5 print:gap-y-2">
                    <div class="flex pb-1 col-span-2">
                        <span class="w-36 shrink-0 font-medium text-slate-500">Nama Lengkap</span>
                        <span class="font-bold text-slate-900">: {{ $nasabah->nama_nasabah }}</span>
                    </div>
                    <div class="flex pb-1">
                        <span class="w-36 shrink-0 font-medium text-slate-500">Tempat, Tgl Lahir</span>
                        <span class="text-slate-900">: {{ $nasabah->tempat_lahir }}, {{ $nasabah->tanggal_lahir }}</span>
                    </div>
                    <div class="flex pb-1">
                        <span class="w-36 shrink-0 font-medium text-slate-500">Jenis Kelamin</span>
                        <span class="text-slate-900">: {{ $nasabah->jenis_kelamin }}</span>
                    </div>
                    <div class="flex pb-1">
                        <span class="w-36 shrink-0 font-medium text-slate-500">Agama</span>
                        <span class="text-slate-900">: {{ $nasabah->agama }}</span>
                    </div>
                    <div class="flex pb-1">
                        <span class="w-36 shrink-0 font-medium text-slate-500">Jenis Identitas</span>
                        <span class="text-slate-900">: {{ $nasabah->jenis_identitas }}</span>
                    </div>
                    <div class="flex pb-1">
                        <span class="w-36 shrink-0 font-medium text-slate-500">No. Telepon / HP</span>
                        <span class="text-slate-900">: {{ $nasabah->no_hp }}</span>
                    </div>
                    <div class="flex pb-1">
                        <span class="w-36 shrink-0 font-medium text-slate-500">Alamat Email</span>
                        <span class="text-slate-900">: {{ $nasabah->email }}</span>
                    </div>
                    <div class="flex pb-1 col-span-2">
                        <span class="w-36 shrink-0 font-medium text-slate-500">Alamat Lengkap</span>
                        <span class="text-slate-900 leading-normal">: {{ $nasabah->alamat }}</span>
                    </div>
                    <div class="flex pb-1 col-span-2">
                        <span class="w-36 shrink-0 font-medium text-slate-500">Wilayah</span>
                        <span class="text-slate-900">: Kel. {{ $nasabah->desa->name ?? '-' }}, Kec. {{ $nasabah->kecamatan->name ?? '-' }}, {{ $nasabah->kabupaten->name ?? '-' }}, {{ $nasabah->provinsi->name ?? '-' }} ({{ $nasabah->kode_pos }})</span>
                    </div>
                </div>
            </div>

            <!-- 3. Informasi Kontak Darurat -->
            <div class="page-break-avoid">
                <h4 class="font-bold text-slate-900 border-b border-slate-200 pb-1 mb-2 uppercase tracking-wider text-[11px] print:text-xs flex items-center gap-2">
                    <span class="w-1.5 h-3 bg-blue-600 rounded-sm"></span> Kontak Darurat
                </h4>
                <div class="grid grid-cols-2 gap-x-8 gap-y-1.5 print:gap-y-2">
                    <div class="flex pb-1">
                        <span class="w-36 shrink-0 font-medium text-slate-500">Nama Kontak</span>
                        <span class="font-bold text-slate-900">: {{ $nasabah->nama_kontak_darurat }}</span>
                    </div>
                    <div class="flex pb-1">
                        <span class="w-36 shrink-0 font-medium text-slate-500">Hubungan</span>
                        <span class="text-slate-900">: {{ $nasabah->hubungan_kontak_darurat }}</span>
                    </div>
                    <div class="flex pb-1 col-span-2">
                        <span class="w-36 shrink-0 font-medium text-slate-500">No. Telepon HP</span>
                        <span class="text-slate-900">: {{ $nasabah->no_hp_kontak_darurat }}</span>
                    </div>
                    <div class="flex pb-1 col-span-2">
                        <span class="w-36 shrink-0 font-medium text-slate-500">Alamat Kontak</span>
                        <span class="text-slate-900 leading-normal">: {{ $nasabah->alamat_kontak_darurat }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tanda Tangan -->
        <div class="mt-12 print:mt-18 grid grid-cols-2 gap-6 text-center text-xs print:text-[12.5px] text-slate-800 page-break-avoid">
            <div>
                <p class="text-slate-500 font-normal mb-1">Ciamis, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }} {{ \Carbon\Carbon::now()->format('H.i') }}</p>
                <p class="mb-12 print:mb-14">Petugas Supervisor,</p>
                <p class="font-bold underline text-slate-900">Admin</p>
                <p class="text-[10px] print:text-[11px] text-slate-400 mt-1">Nama Lengkap & Paraf</p>
            </div>
            <div>
                <p class="invisible mb-1 text-slate-500 font-normal">Placeholder</p>
                <p class="mb-12 print:mb-14">Nasabah,</p>
                <p class="font-bold underline text-slate-900">{{ $nasabah->nama_nasabah }}</p>
                <p class="text-[10px] print:text-[11px] text-slate-400 mt-1">Tanda Tangan & Nama Terang</p>
            </div>
        </div>

    </div>

    <!-- Pemicu Cetak Otomatis di Browser -->
    <script>
        window.onload = function() {
            setTimeout(() => {
                window.print();
            }, 300);
        }

        window.onafterprint = function() {
            window.location.href = "{{ route('halaman.nasabah.admin') }}";
        }
    </script>
</body>
</html>
