<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Formulir Nasabah - Bank Mini K-One</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            body { background: white !important; color: black !important; font-size: 12px !important; }
            @page { size: A4; margin: 0; }
            body .print-container {
                width: 210mm !important; height: auto !important;
                padding: 1.2cm 1.6cm !important;
                box-sizing: border-box !important; margin: 0 auto !important;
                box-shadow: none !important; border-radius: 0 !important; background-color: white !important;
            }
            .no-print { display: none !important; }
        }
        body { font-family: 'Arial', sans-serif; background-color: #f3f4f6; }
    </style>
</head>
<body class="bg-gray-100 py-6 text-slate-800">

    <div class="max-w-[800px] mx-auto mb-4 flex justify-between items-center px-2 no-print">
        <button onclick="window.history.back()" class="px-4 py-2 bg-gray-200 text-gray-700 text-xs font-bold rounded-lg hover:bg-gray-300">Kembali</button>
        <button onclick="window.print()" class="px-4 py-2 bg-blue-600 text-white text-xs font-bold rounded-lg hover:bg-blue-700">Cetak Dokumen</button>
    </div>

    <div class="print-container max-w-[800px] mx-auto bg-white p-8 shadow-md rounded-xl">
        <!-- Kop Surat -->
        <div class="flex items-center justify-between pb-3 border-b-4 border-slate-900 mb-6">
            <div class="w-16 h-16 shrink-0 flex items-center justify-center">
                <img src="{{ asset('img/logosmk.png') }}" alt="Logo SMK" class="w-full h-full object-contain" onerror="this.style.display='none'">
            </div>
            <div class="text-center flex-1 px-4">
                <h1 class="text-xl font-extrabold tracking-wider text-slate-900 leading-tight">BANK MINI K-ONE</h1>
                <h2 class="text-sm font-bold text-slate-800 mt-0.5">SMK NEGERI 1 KAWALI</h2>
                <p class="text-[10px] text-slate-600 italic mt-0.5">Jalan Raya Kawali No. 65, Ciamis, Jawa Barat | Kode Pos: 46253</p>
            </div>
            <div class="w-16 h-16 shrink-0 flex items-center justify-center">
                <img src="{{ asset('img/bankmini2.png') }}" alt="Logo Bank Mini" class="w-full h-full object-contain" onerror="this.style.display='none'">
            </div>
        </div>

        <h3 class="text-center font-bold text-base mb-6 underline tracking-wide">FORMULIR BIODATA NASABAH</h3>

        <div class="space-y-3 text-xs leading-relaxed">
            <div class="grid grid-cols-3 gap-2 py-1 border-b border-gray-100">
                <span class="font-bold text-gray-600">Nomor Rekening</span>
                <span class="col-span-2 font-mono font-bold text-blue-900">: 320701892001</span>
            </div>
            <div class="grid grid-cols-3 gap-2 py-1 border-b border-gray-100">
                <span class="font-bold text-gray-600">Nama Nasabah</span>
                <span class="col-span-2 font-semibold">: Muhammad Rizky Pratama</span>
            </div>
            <div class="grid grid-cols-3 gap-2 py-1 border-b border-gray-100">
                <span class="font-bold text-gray-600">NIS / NIP</span>
                <span class="col-span-2">: 212210045</span>
            </div>
            <div class="grid grid-cols-3 gap-2 py-1 border-b border-gray-100">
                <span class="font-bold text-gray-600">Jurusan / Status</span>
                <span class="col-span-2">: Rekayasa Perangkat Lunak</span>
            </div>
            <div class="grid grid-cols-3 gap-2 py-1 border-b border-gray-100">
                <span class="font-bold text-gray-600">Status Akun</span>
                <span class="col-span-2 text-emerald-700 font-bold">: Aktif</span>
            </div>
        </div>

        <div class="mt-16 grid grid-cols-2 text-center text-xs">
            <div>
                <p class="mb-14 text-gray-600">Nasabah,</p>
                <p class="font-bold underline">Muhammad Rizky Pratama</p>
            </div>
            <div>
                <p class="mb-14 text-gray-600">Petugas Bank Mini,</p>
                <p class="font-bold underline">Administrator Bank Mini</p>
            </div>
        </div>
    </div>
</body>
</html>
