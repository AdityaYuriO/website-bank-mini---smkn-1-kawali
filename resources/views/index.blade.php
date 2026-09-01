<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BANK MINI K-ONE</title>
    <meta name="description" content="Aplikasi Bank Mini K-One dari SMKN 1 Kawali. Sistem pengelolaan keuangan, tabungan, dan data nasabah digital untuk sekolah.">

    <!-- Font Google: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Ikon Phosphor -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                    backgroundImage: {
                        'gradient-merek': 'linear-gradient(to bottom, #143657, #1E5081, #143657)',

                        'gradient-tombol': 'linear-gradient(to right, #143657, #1E5081)',

                        'button-gradient': 'linear-gradient(to right, #008959, #1E9F71, #008959)',
                    },
                    colors: {
                        merek: {
                            biru: '#1E3A5F', // Latar belakang biru gelap utama
                            gelap: '#162D4A', // Biru lebih gelap untuk beberapa kartu
                            terang: '#284B75', // Biru lebih terang untuk skeleton
                            kuning: '#D9A036', // Kuning aksen
                            hijau: '#1A8F6A', // Hijau aksen
                            bg: '#F4F6F9' // Latar belakang abu-abu terang utama
                        }
                    }
                }
            }
        }
    </script>
    <link rel="stylesheet" href="{{ asset('css/index.css') }}">
    <link rel="icon" href="{{ asset('img/bankmini2.png') }}" type="image/png">
    <meta name="google-site-verification" content="1pN7zUA8VbuOoD5m9b8Kfq54Cr3elCQ3gDfuqzi9zMw" />
</head>

<body class="font-sans text-gray-800 bg-merek-bg antialiased selection:bg-merek-kuning selection:text-white">

    <!-- Global Notification Modal Center -->
    <div id="notificationModal" class="fixed inset-0 z-[150] hidden items-center justify-center p-4">
        <!-- Overlay background blur -->
        <div class="fixed inset-0 bg-black/40 backdrop-blur-sm transition-opacity duration-300" onclick="closeNotificationModal()"></div>
        <!-- Modal Content (Kotak Sedang) -->
        <div class="bg-white rounded-[24px] w-full max-w-[420px] p-6 shadow-2xl relative z-10 transform transition-all scale-95 opacity-0 duration-300" id="notificationModalContent">
            <div class="flex flex-col items-center text-center">
                <!-- Icon Container -->
                <div id="notificationIconBg" class="w-16 h-16 rounded-full flex items-center justify-center mb-4">
                    <!-- SVG Icon dynamically set -->
                </div>
                
                <!-- Judul -->
                <h3 class="text-lg font-bold text-gray-900 mb-2" id="notificationModalTitle">Notifikasi</h3>
                <!-- Pesan/Detail -->
                <div class="text-gray-500 text-xs md:text-sm leading-relaxed mb-6 w-full text-left bg-gray-50 p-4 rounded-xl border border-gray-100 max-h-[180px] overflow-y-auto" id="notificationModalMessage">
                    Detail notifikasi akan tampil di sini.
                </div>
                
                <!-- Tombol Aksi -->
                <button id="notificationModalBtn" onclick="closeNotificationModal()" class="w-full px-6 py-3.5 rounded-xl text-white font-bold text-[14px] transition-colors shadow-lg active:scale-95 focus:outline-none">
                    Mengerti
                </button>
            </div>
        </div>
    </div>

    <!-- Bilah Navigasi -->
    <!-- Ditambahkan id="navbar" dan class transition untuk efek animasi -->
    <nav id="navbar" class="bg-white border-b border-gray-100 sticky top-0 z-50 transition-all duration-300 ease-in-out">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20 items-center transition-all duration-300" id="navbar-container">
                <!-- Logo -->
                <div class="flex-shrink-0 flex items-center">
                    <a href="#beranda" class="flex items-center gap-2 group">
                        <img src="{{ asset('img/bankmini2.png') }}" alt="Bank Logo" class="h-8 lg:h-12 w-auto object-contain transition-transform group-hover:scale-105">
                        <span class="font-bold text-sm  lg:text-xl tracking-tight text-gray-900">BANK MINI K-ONE</span>
                    </a>
                </div>

                <!-- Menu Desktop -->
                <div class="hidden md:flex space-x-8 items-center">
                    <a href="#beranda" class="desktop-nav-link text-sm font-semibold text-gray-900 border-b-2 border-merek-kuning pb-1 transition-all duration-200">Beranda</a>
                    <a href="#tentang" class="desktop-nav-link text-sm font-medium text-gray-500 hover:text-gray-900 border-b-2 border-transparent hover:border-gray-200 pb-1 transition-all duration-200">Tentang</a>
                    <a href="#alur-layanan" class="desktop-nav-link text-sm font-medium text-gray-500 hover:text-gray-900 border-b-2 border-transparent hover:border-gray-200 pb-1 transition-all duration-200">Alur Layanan</a>
                    <a href="#form-transfer" class="desktop-nav-link text-sm font-medium text-gray-500 hover:text-gray-900 border-b-2 border-transparent hover:border-gray-200 pb-1 transition-all duration-200">Form Transfer</a>
                </div>

                <!-- Tombol Autentikasi -->
                <div class="hidden md:flex items-center space-x-4">
                    <a href="{{ route('login') }}" class="text-sm font-medium text-white bg-gradient-tombol px-6 py-2 rounded-[10px] hover:bg-opacity-90 hover:shadow-md transition">Masuk</a>
                </div>

                <!-- Tombol menu seluler -->
                <div class="flex items-center md:hidden">
                    <button type="button" onclick="toggleMobileMenu()" class="text-gray-400 hover:text-white focus:outline-none p-2 transition-transform duration-200 active:scale-95">
                        <i class="ph ph-list text-2xl transition-all" id="menu-icon"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Panel Menu Seluler -->
        <div id="mobile-menu" class="hidden md:hidden bg-white border-b border-gray-100 shadow-lg absolute w-full transition-all origin-top">
            <div class="px-4 pt-2 pb-6 space-y-2">
                <a href="#beranda" onclick="toggleMobileMenu()" class="mobile-nav-link block px-3 py-2 text-base font-medium text-merek-kuning bg-gray-50 rounded-md transition-colors">Beranda</a>
                <a href="#tentang" onclick="toggleMobileMenu()" class="mobile-nav-link block px-3 py-2 text-base font-medium text-gray-600 hover:bg-gray-50 hover:text-gray-900 rounded-md transition-colors">Tentang</a>
                <a href="#alur-layanan" onclick="toggleMobileMenu()" class="mobile-nav-link block px-3 py-2 text-base font-medium text-gray-600 hover:bg-gray-50 hover:text-gray-900 rounded-md transition-colors">Alur Layanan</a>
                <a href="#form-transfer" onclick="toggleMobileMenu()" class="mobile-nav-link block px-3 py-2 text-base font-medium text-gray-600 hover:bg-gray-50 hover:text-gray-900 rounded-md transition-colors">Form Transfer</a>
                <div class="mt-4 flex flex-col gap-2 px-3">
                    <a href="{{ route('login') }}" class="text-center text-base font-medium text-white bg-gradient-tombol py-2 rounded-[10px] hover:bg-opacity-90 transition-colors">Masuk</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Bagian Utama (Hero) -->
    <header id="beranda" class="bg-gradient-merek pt-16 pb-20 lg:pt-24 lg:pb-48 relative scroll-mt-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-2 gap-12 items-center">
                <!-- Konten Teks -->
                <div class="text-white z-10">
                    <h1 class="text-[28px] sm:text-4xl md:text-5xl lg:text-[40px] xl:text-5xl font-bold leading-tight mb-6">
                        <span class="whitespace-nowrap">Tabungan <span class="text-merek-kuning">Masa Depan</span></span><br>
                        Dimulai dari Sini
                    </h1>
                    <p class="text-base md:text-lg text-gray-300 mb-8 max-w-lg leading-relaxed">
                        Membangun kebiasaan finansial yang cerdas melalui pengalaman perbankan nyata di lingkungan sekolah yang aman dan edukatif.
                    </p>
                    <a href="#form-transfer" class="inline-block bg-merek-kuning text-white font-semibold px-8 py-3 rounded-md hover:bg-opacity-90 transition shadow-lg hover:shadow-xl hover:-translate-y-1 transform duration-200">
                        Buka Tabungan
                    </a>
                </div>

                <!-- Gambar Utama -->
                <div class="relative z-10 rounded-2xl overflow-hidden shadow-2xl max-w-lg mx-auto lg:ml-auto -mt-4 lg:mt-0">
                    <img src="{{ asset('img/konefix.png') }}" alt="Smkn 1 Kawali" class="w-full h-full object-cover sm:h-[300px] lg:h-[400px] hidden lg:flex">
                    <!-- Lapisan bayangan untuk meniru efek desain -->
                    <div class="absolute inset-0 bg-gradient-to-tr from-[#143657]/60 to-transparent"></div>
                </div>
            </div>
        </div>
    </header>

    <!-- Bagian Tentang / Fitur (Tumpang Tindih) -->
    <section id="tentang" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative -mt-12 lg:-mt-40 z-20 mb-10 lg:mb-20 scroll-mt-24">
        <div class="bg-white rounded-[20px] shadow-xl overflow-hidden p-8 md:p-12">
            <div class="text-center mb-10">
                <h2 class="text-2xl lg:text-3xl font-bold text-merek-biru">Apa Itu Bank Mini Sekolah?</h2>
            </div>

            <div class="grid md:grid-cols-12 gap-12 items-center">
                <!-- Konten Kiri -->
                <div class="md:col-span-7 flex flex-col justify-center">
                    <div class="flex items-center gap-4 mb-6">
                        <img src="{{ asset('img/bankmini2.png') }}" alt="Bank Icon" class="h-10 lg:h-14 w-auto object-contain">
                        <h3 class="text-1xl lg:text-2xl font-bold text-merek-hijau">Fasilitas Belajar Praktis</h3>
                    </div>
                    <p class="text-gray-600 mb-4 leading-relaxed text-justify text-sm lg:text-base">
                        Bank mini K-One adalah sarana pembelajaran praktik yang terdapat di SMK Negeri 1 Kawali, khususnya pada kompetensi keahlian Akuntansi dan Keuangan Lembaga, untuk mensimulasikan kegiatan operasional perbankan secara sederhana di lingkungan sekolah.
                    </p>
                    <p class="text-gray-600 mb-2 leading-relaxed text-sm lg:text-base font-semibold">
                        Pelayanan yang dilakukan di Bank Mini K-One diantaranya:
                    </p>
                    <ul class="list-disc pl-5 text-gray-600 mb-8 text-sm lg:text-base space-y-1">
                        <li>Pembukaan rekening tabungan</li>
                        <li>Penerimaan setoran tabungan</li>
                        <li>Penarikan tabungan</li>
                        <li>Top Up E-Wallet, dll.</li>
                    </ul>
                    <div>
                        <span class="inline-block bg-merek-hijau text-white text-xs lg:text-sm font-bold px-5 py-2 rounded-full shadow-sm">
                            100% Transaksi Aman
                        </span>
                    </div>
                </div>

                <!-- Konten Kanan (Kartu Gelap) -->
                <div class="md:col-span-5">
                    <div class="bg-gradient-merek rounded-[20px] p-8 lg:p-10 text-white shadow-2xl border border-white/5 relative overflow-hidden group">
                        <!-- Dekorasi Gradient Halus -->
                        <div class="absolute top-0 right-0 -mr-16 -mt-16 w-32 h-32 bg-white/5 rounded-full blur-3xl group-hover:bg-white/10 transition-colors duration-500"></div>

                        <div class="relative z-10">
                            <img src="{{ asset('img/icon/landingpage/shield.png') }}" alt="Shield Icon" class="w-14 h-14 object-contain mb-4">
                            <h3 class="text-2xl font-bold">Keamanan Terjamin</h3>
                        </div>
                        <p class="text-gray-300 text-justify text-xs lg:text-base mb-8 leading-relaxed">
                            Setiap transaksi diawasi langsung oleh guru pembimbing dan sistem tercatat secara digital untuk transparansi penuh.
                        </p>

                        <!-- Garis Pemisah -->
                        <div class="border-t border-white/10 mb-8"></div>

                        <ul class="space-y-4">
                            <li class="flex items-center gap-4 text-xs lg:text-sm font-medium text-gray-200">
                                <img src="{{ asset('img/icon/landingpage/check-circle.png') }}" alt="Check Icon" class="w-3 h-3 lg:w-5 lg:h-5 object-contain">
                                Audit Bulanan Berkala
                            </li>
                            <li class="flex items-center gap-4 text-xs lg:text-sm font-medium text-gray-200">
                                <img src="{{ asset('img/icon/landingpage/check-circle.png') }}" alt="Check Icon" class="w-3 h-3 lg:w-5 lg:h-5 object-contain">
                                Sistem Terenkripsi
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </section>

    <!-- Pembungkus Bagian Proses untuk Alur Layanan -->
    <div id="alur-layanan" class="scroll-mt-20">
        <!-- Bagian Proses -->
        <section class="py-10 lg:py-24 bg-merek-bg">
            <div class="max-w-8xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="mb-20 text-left">
                    <h2 class="text-2xl lg:text-4xl font-bold text-merek-biru mb-4">Proses Buka Buku Tabungan</h2>
                    <p class="text-gray-600 text-sm lg:text-lg">Proses pendaftaran yang mudah dan cepat.</p>
                </div>

                <!-- Langkah-langkah -->
                <div class="relative">
                    <!-- Garis Penghubung (Desktop) -->
                    <div class="block absolute top-5 lg:top-10 left-[12%] right-[12%] h-[2px] bg-gray-300 -translate-y-1/2 z-0"></div>

                    <div class="grid grid-cols-3 gap-12 text-center relative z-10">
                        <!-- Langkah 1 -->
                        <div class="flex flex-col items-center">
                            <div class="w-10 h-10 lg:w-20 lg:h-20 rounded-full bg-[#1e3a5f] text-white flex items-center justify-center text-1xl lg:text-3xl font-bold mb-8 ring-[10px] ring-merek-bg shadow-md hover:scale-110 transition-transform duration-300 cursor-default">
                                1
                            </div>
                            <h3 class="text-sm lg:text-2xl font-bold text-merek-biru mb-3">Registrasi</h3>
                            <p class="text-gray-500 text-xs lg:text-base max-w-xs leading-relaxed">Nasabah mengisi formulir pendaftaran dan menyerahkan dokumen persyaratan kepada petugas CS.</p>
                        </div>

                        <!-- Langkah 2 -->
                        <div class="flex flex-col items-center">
                            <div class="w-10 h-10 lg:w-20 lg:h-20 rounded-full bg-[#1A5B53] text-white flex items-center justify-center text-1xl lg:text-3xl font-bold mb-8 ring-[10px] ring-merek-bg shadow-md hover:scale-110 transition-transform duration-300 cursor-default">
                                2
                            </div>
                            <h3 class="text-sm lg:text-2xl font-bold text-merek-biru mb-3">Verifikasi</h3>
                            <p class="text-gray-500 text-xs lg:text-base max-w-xs leading-relaxed">Petugas CS memeriksa keaslian dokumen, memvalidasi NIK, lalu menginput data ke sistem</p>
                        </div>

                        <!-- Langkah 3 -->
                        <div class="flex flex-col items-center">
                            <div class="w-10 h-10 lg:w-20 lg:h-20 rounded-full bg-[#34B38A] text-white flex items-center justify-center text-1xl lg:text-3xl font-bold mb-8 ring-[10px] ring-merek-bg shadow-md hover:scale-110 transition-transform duration-300 cursor-default">
                                3
                            </div>
                            <h3 class="text-sm lg:text-2xl font-bold text-merek-biru mb-3">Terima Buku</h3>
                            <p class="text-gray-500 text-xs lg:text-base max-w-xs leading-relaxed">Sistem otomatis memproses pembuatan rekening, lalu nasabah menerima buku tabungan resmi.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Bagian Alur Transaksi -->
        <section class="py-10 lg:py-24 bg-merek-bg">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="mb-16 text-center md:text-left">
                    <h2 class="text-2xl lg:text-4xl font-bold text-merek-biru mb-4">Alur Transaksi Bank Mini</h2>
                    <p class="text-gray-600 text-xs lg:text-base max-w-2xl">Langkah-langkah proses transaksi di Bank Mini, mulai dari menabung hingga transfer, yang disusun secara jelas dan mudah dipahami.</p>
                </div>

                <div class="grid lg:grid-cols-2 gap-8 items-stretch">
                    <!-- Alur Nabung Card -->
                    <div class="bg-white rounded-[20px] p-8 lg:p-12 shadow-xl hover:shadow-2xl transition-all duration-300 border border-gray-100">
                        <div class="flex items-center gap-4 mb-10">
                            <img src="{{ asset('img/icon/landingpage/cash-stack.png') }}" alt="Cash Icon" class="w-10 h-10 object-contain">
                            <h3 class="text-lg lg:text-2xl font-bold text-[#1e3a5f]">Alur Nabung</h3>
                        </div>

                        <div class="relative space-y-12 ml-4">
                            <!-- Garis Vertikal -->
                            <div class="absolute top-5 left-5 w-[1.5px] h-[calc(100%-40px)] bg-gray-100 -translate-x-1/2 z-0"></div>

                            <!-- Langkah 1 -->
                            <div class="relative flex items-center gap-8 z-10">
                                <div class="w-10 h-10 rounded-full bg-[#1e3a5f] text-white flex items-center justify-center font-bold text-lg shadow-md shrink-0">1</div>
                                <p class="text-gray-600 font-medium text-sm lg:text-lg leading-relaxed">Nasabah memberikan buku tabungan dan uang secara langsung kepada petugas teller.</p>
                            </div>

                            <!-- Langkah 2 -->
                            <div class="relative flex items-center gap-8 z-10">
                                <div class="w-10 h-10 rounded-full bg-[#1A5B53] text-white flex items-center justify-center font-bold text-lg shadow-md shrink-0">2</div>
                                <p class="text-gray-600 font-medium text-sm lg:text-lg leading-relaxed">Teller menerima uang tersebut, menghitungnya, lalu mengetik data transaksi ke dalam sistem.</p>
                            </div>

                            <!-- Langkah 3 -->
                            <div class="relative flex items-center gap-8 z-10">
                                <div class="w-10 h-10 rounded-full bg-[#34B38A] text-white flex items-center justify-center font-bold text-lg shadow-md shrink-0">3</div>
                                <p class="text-gray-600 font-medium text-sm lg:text-lg leading-relaxed">Sistem otomatis mengubah uang tunai menjadi saldo digital dan mencetak struk.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Alur Tarik Tunai Card -->
                    <div class="bg-gradient-merek rounded-[20px] p-8 lg:p-12 shadow-xl hover:shadow-2xl transition-all duration-300 relative overflow-hidden group">
                        <!-- Dekorasi Halus -->
                        <div class="absolute bottom-0 right-0 -mr-16 -mb-16 w-48 h-48 bg-white/5 rounded-full blur-3xl group-hover:bg-white/10 transition-colors duration-500"></div>

                        <div class="relative z-10">
                            <div class="flex items-center gap-4 mb-10 text-white">
                                <img src="{{ asset('img/icon/landingpage/cash-stack-invert.png') }}" alt="Cash Icon" class="w-10 h-10 object-contain brightness-0 invert">
                                <h3 class="text-lg lg:text-2xl font-bold">Alur Tarik Tunai</h3>
                            </div>

                            <div class="relative space-y-12 ml-4">
                                <!-- Garis Vertikal -->
                                <div class="absolute top-5 left-5 w-[1.5px] h-[calc(100%-40px)] bg-white/10 -translate-x-1/2 z-0"></div>

                                <!-- Langkah 1 -->
                                <div class="relative flex items-center gap-8 z-10">
                                    <div class="w-10 h-10 rounded-full bg-[#B87333] text-white flex items-center justify-center font-bold text-lg shadow-lg shrink-0 border border-white/10">1</div>
                                    <p class="text-gray-100 font-medium text-sm lg:text-lg leading-relaxed">Nasabah memberikan buku tabungan kepada petugas teller.</p>
                                </div>

                                <!-- Langkah 2 -->
                                <div class="relative flex items-center gap-8 z-10">
                                    <div class="w-10 h-10 rounded-full bg-[#D4A017] text-white flex items-center justify-center font-bold text-lg shadow-lg shrink-0 border border-white/10">2</div>
                                    <p class="text-gray-100 font-medium text-sm lg:text-lg leading-relaxed">Teller memeriksa dokumen, saldo, lalu menginput data penarikan ke sistem.</p>
                                </div>

                                <!-- Langkah 3 -->
                                <div class="relative flex items-center gap-8 z-10">
                                    <div class="w-10 h-10 rounded-full bg-[#FFD700] text-[#1e3a5f] flex items-center justify-center font-bold text-lg shadow-lg shrink-0 border border-white/10">3</div>
                                    <p class="text-gray-100 font-medium text-sm lg:text-lg leading-relaxed">Sistem memproses data, lalu nasabah menerima uang tunai dan struk.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- Bagian Formulir Transfer -->
    <section id="form-transfer" class="py-10 lg:py-20 bg-gradient-merek relative scroll-mt-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-12 gap-8 items-stretch">
                <!-- Info Kiri: Header & Alur Card -->
                <div class="lg:col-span-4 flex flex-col justify-between">
                    <!-- Header Bagian Form -->
                    <div class="mb-10 text-left">
                        <h2 class="text-2xl lg:text-4xl font-bold text-white mb-6">
                            Form Bukti <span class="text-merek-kuning">Transfer</span>
                        </h2>
                        <p class="text-gray-300 text-xs lg:text-sm leading-relaxed">
                            Kirim bukti transaksi Anda atau periksa status secara instan. Sistem kami memproses unggahan dalam beberapa menit selama jam operasional.
                        </p>
                    </div>

                    <div class="bg-gradient-merek rounded-[20px] p-10 shadow-xl relative overflow-hidden group flex-grow">
                        <!-- Dekorasi -->
                        <div class="absolute top-0 left-0 -ml-12 -mt-12 w-32 h-32 bg-white/5 rounded-full blur-3xl"></div>

                        <div class="relative z-10">
                            <div class="flex items-center gap-4 mb-10 -mt-2 text-white">
                                <img src="{{ asset('img/icon/landingpage/cash-stack-invert.png') }}" alt="Transfer Icon" class="w-10 h-10 object-contain brightness-0 invert">
                                <h3 class="text-lg lg:text-2xl font-bold">Alur Transfer</h3>
                            </div>

                            <div class="space-y-4">
                                <div class="bg-white/10 rounded-xl p-4 text-xs lg:text-sm text-gray-200">
                                    Pihak luar mengirim uang terlebih dahulu lewat ATM atau bank umum.
                                </div>
                                <div class="bg-white/10 rounded-xl p-4 text-xs lg:text-sm text-gray-200">
                                    Pihak luar mengisi nama pengirim, penerima, dan nominal di formulir ini.
                                </div>
                                <div class="bg-white/10 rounded-xl p-4 text-xs lg:text-sm text-gray-200">
                                    Pihak luar mengunggah foto struk atau screenshot bukti transfernya.
                                </div>
                                <div class="bg-white/10 rounded-xl p-4 text-xs lg:text-sm text-gray-200">
                                    Pihak luar menekan tombol "Kirim" agar data diperiksa oleh sistem.
                                </div>
                                <div class="bg-white/10 rounded-xl p-4 text-xs lg:text-sm text-gray-200">
                                    Setelah bukti dikonfirmasi, saldo tabungan Bank Mini bertambah.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Formulir Kanan -->
                <div class="lg:col-span-8 bg-white rounded-[20px] p-8 sm:p-12 shadow-xl border border-gray-100">


                    <!--
                        BAGIAN BACKEND: FORM TRANSFER (GUEST/UMUM)
                        - Form ini digunakan untuk mengirim data permohonan transfer tanpa login.
                        - action="#": Saat ini action kosong, backend dev perlu mengarahkannya ke route yang tepat (misal: route('guest.transfer.store')).
                        - method="POST": Menggunakan POST untuk mengirim data sensitif secara aman.
                        - enctype="multipart/form-data": WAJIB ada karena form ini mengunggah file gambar (bukti transfer).
                    -->
                    <form action="{{ route('bukti_tf.transfer_luar') }}" method="POST" enctype="multipart/form-data" class="space-y-6" id="form-transfer">

                        <!--
                            BAGIAN BACKEND: CSRF TOKEN
                            - Mencegah serangan CSRF. Wajib ada di setiap form POST di Laravel.
                        -->
                        @csrf
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <!-- Kolom: Nama Pengirim -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Nama Pengirim</label>
                                <!--
                                    BAGIAN BACKEND: INPUT NAMA PENGIRIM
                                    - name="nama_pengirim": Key yang akan ditangkap oleh $request di backend.
                                    - value="{{ old('nama_pengirim') }}": Mempertahankan nilai input jika terjadi error validasi.
                                -->
                                <input type="text" name="nama_pengirim" value="{{ old('nama_pengirim') }}"
                                    class="w-full px-4 py-3 bg-white border rounded-lg focus:ring-2 focus:ring-merek-biru focus:border-transparent outline-none transition {{ $errors->has('nama_pengirim') ? 'border-red-500' : 'border-gray-200' }}"
                                    placeholder="Masukkan nama lengkap">
                                @error('nama_pengirim')
                                    <!-- BAGIAN BACKEND: ERROR NAMA PENGIRIM -->
                                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Kolom: Nomor Telepon -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Nomor Rekening Penerima</label>
                                <!-- BAGIAN BACKEND: INPUT REKENING PENERIMA -->
                                <input type="text" id="id_rekening" name="id_rekening" value="{{ old('id_rekening') }}"
                                    class="w-full px-4 py-3 bg-white border rounded-lg focus:ring-2 focus:ring-merek-biru focus:border-transparent outline-none transition {{ $errors->has('id_rekening') ? 'border-red-500' : 'border-gray-200' }}"
                                    placeholder="Masukkan nomor rekening">
                                @error('id_rekening')
                                    <!-- BAGIAN BACKEND: ERROR REKENING PENERIMA -->
                                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>


                            <!-- Kolom: Nama Penerima -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Nama Penerima</label>
                                <!-- BAGIAN BACKEND: INPUT NAMA PENERIMA -->
                                <input type="text" name="nama_penerima" id="nama_penerima" value="{{ old('nama_penerima') }}"
                                    class="w-full px-4 py-3 bg-white border rounded-lg focus:ring-2 focus:ring-merek-biru focus:border-transparent outline-none transition {{ $errors->has('nama_penerima') ? 'border-red-500' : 'border-gray-200' }}"
                                    placeholder="Nama lengkap penerima" readonly>
                                @error('nama_penerima')
                                    <!-- BAGIAN BACKEND: ERROR NAMA PENERIMA -->
                                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Kolom: Biaya Admin (Otomatis ID 2) -->
                            <div class="relative">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Biaya Admin</label>
                                
                                <!-- 1. INPUT DISPLAY (Hanya untuk dilihat user, dikunci dengan 'readonly') -->
                                <input type="text" 
                                    value="Rp {{ number_format($biaya_admin->nominal, 0, ',', '.') }}" 
                                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg outline-none transition text-gray-500 cursor-not-allowed" 
                                    readonly>

                                <!-- 2. INPUT HIDDEN (Ini yang bertugas mengirimkan ID '2' ke backend Laravel saat disubmit) -->
                                <input type="hidden" name="transaksi_id" value="{{ $biaya_admin->id }}">

                                <!-- Karena ini readonly dan otomatis, kita tidak butuh pesan error validation di sini 
                                    karena nilainya sudah pasti valid di-set dari database -->
                            </div>

                            <!-- Kolom: Nomor Rekening Penerima -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Nomor Telepon</label>
                                <!-- BAGIAN BACKEND: INPUT NOMOR TELEPON -->
                                <input type="tel" name="no_hp_pengirim" id="no_hp_pengirim" value="{{ old('no_hp_pengirim') }}"
                                    class="w-full px-4 py-3 bg-white border rounded-lg focus:ring-2 focus:ring-merek-biru focus:border-transparent outline-none transition {{ $errors->has('no_hp_pengirim') ? 'border-red-500' : 'border-gray-200' }}"
                                    placeholder="Contoh: 08123456789">
                                @error('no_hp_pengirim')
                                    <!-- BAGIAN BACKEND: ERROR NOMOR TELEPON -->
                                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Kolom: Catatan (Optional) spans 2 rows -->
                            <div class="sm:row-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Catatan (Opsional)</label>
                                <!-- BAGIAN BACKEND: INPUT CATATAN -->
                                <textarea name="catatan" rows="5" class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg focus:ring-2 focus:ring-merek-biru focus:border-transparent outline-none transition resize-none" placeholder="Tambahkan pesan jika perlu">{{ old('catatan') }}</textarea>
                            </div>

                            <!-- Kolom: Jumlah Transfer -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Jumlah Transfer</label>
                                <!-- BAGIAN BACKEND: INPUT JUMLAH TRANSFER (Diubah ke type="text" & ditambah id) -->
                                <input type="text" name="jumlah_transfer" id="jumlah_transfer" value="{{ old('jumlah_transfer') }}"
                                    class="w-full px-4 py-3 bg-white border rounded-lg focus:ring-2 focus:ring-merek-biru focus:border-transparent outline-none transition {{ $errors->has('jumlah_transfer') ? 'border-red-500' : 'border-gray-200' }}"
                                    placeholder="0">
                                @error('jumlah_transfer')
                                    <!-- BAGIAN BACKEND: ERROR JUMLAH TRANSFER -->
                                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Kolom: Upload Bukti -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Unggah Bukti Transfer</label>
                            <div onclick="document.getElementById('file-upload').click()"
                                class="mt-1 min-h-[160px] relative flex justify-center items-center px-6 pt-5 pb-6 border-2 border-dashed rounded-lg bg-white hover:bg-gray-50 transition cursor-pointer group overflow-hidden {{ $errors->has('bukti_foto') ? 'border-red-300' : 'border-gray-300' }}">
                                
                                <!-- Konten Default (Ikon & Teks) -->
                                <div id="upload-placeholder" class="space-y-2 text-center transition-all duration-300">
                                    <img src="{{ asset('img/icon/landingpage/upload-cloud.png') }}" alt="Upload Icon" class="w-12 h-12 mx-auto object-contain">
                                    <div class="flex text-sm text-gray-600 justify-center">
                                        <span class="font-medium text-merek-biru">Klik untuk unggah bukti</span>
                                    </div>
                                    <!-- UBAH DISINI: Tambahkan informasi PDF -->
                                    <p class="text-xs text-gray-400">JPG, PNG, atau PDF hingga 5MB</p>
                                </div>

                                <!-- Konten Pratinjau (Tersembunyi Awalnya) -->
                                <!-- UBAH DISINI: preview-container diubah flex-col agar layout aman -->
                                <div id="preview-container" class="hidden absolute inset-0 w-full h-full bg-white z-10 flex flex-col items-center justify-center p-2">
                                    
                                    <!-- UBAH DISINI: Ganti <img> jadi <object> serbaguna -->
                                    <object id="file-preview-object" data="" type="" class="w-full h-full object-contain rounded-md shadow-sm"></object>
                                    
                                    <!-- Overlay untuk ganti file -->
                                    <div class="absolute inset-0 bg-merek-biru/10 opacity-0 hover:opacity-100 transition-opacity flex items-center justify-center backdrop-blur-[2px]">
                                        <!-- UBAH DISINI: Teks diganti jadi "Ganti File" karena bisa jadi bukan gambar -->
                                        <div class="bg-merek-biru text-white text-xs font-bold px-4 py-2 rounded-full shadow-lg transform translate-y-4 group-hover:translate-y-0 transition-transform">
                                            Ganti File
                                        </div>
                                    </div>
                                </div>

                                <!-- UBAH DISINI: accept ditambah application/pdf -->
                                <input id="file-upload" name="bukti_foto" type="file" class="sr-only" accept="image/*,application/pdf" onchange="previewImage(this)">
                            </div>
                            
                            @error('bukti_foto')
                                <!-- BAGIAN BACKEND: ERROR BUKTI TRANSFER -->
                                <p class="text-xs text-red-500 mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Tombol Kirim -->
                        <div>
                            <!--
                                BAGIAN BACKEND: TOMBOL SUBMIT
                                - Memicu pengiriman form ke server.
                            -->
                            <button type="submit" id="btn-kirim" name="btn_kirim" class="w-full flex justify-center py-4 px-4 border border-transparent rounded-xl shadow-md text-lg font-bold text-white bg-button-gradient hover:bg-opacity-95 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-merek-hijau transition transform active:scale-[0.98]">
                                Kirim
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- Bagian Bawah (Footer) -->
    <footer class="bg-white py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-[#f3f6f9] rounded-[20px] p-8 md:p-12 shadow-sm border border-gray-100">
                <!-- Brand Title -->
                <div class="flex items-center gap-3 mb-10">
                    <img src="{{ asset('img/bankmini2.png') }}" alt="Bank Icon" class="h-8 lg:h-10 w-auto object-contain">
                    <span class="font-bold text-base lg:text-2xl text-[#1e3a5f]">Bank Mini K-One</span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-12 items-start">

                    <!-- Kolom Alamat -->
                    <div>
                        <h5 class="font-bold text-[#1e3a5f] mb-4 text-base lg:text-lg">Alamat</h5>
                        <p class="text-gray-500 text-xs lg:text-sm leading-relaxed">
                            SMKN 1 Kawali
                            Jalan. Talagasari, No. 35, Kawalimukti, Kawali Ciamis 46253
                        </p>
                    </div>

                    <!-- Media Sosial -->
                    <div>
                        <h5 class="font-bold text-[#1e3a5f] mb-4 text-base lg:text-lg">Media Digital</h5>
                        <ul class="space-y-2 text-xs lg:text-sm text-gray-500">
                            <li>
                                <span class="font-medium">Instagram :</span>
                                <a href="https://instagram.com/smkn1kawali" target="_blank" class="hover:text-[#1e3a5f] hover:underline transition">@smkn1kawali</a>
                            </li>
                            <li>
                                <span class="font-medium">Tiktok :</span>
                                <a href="https://tiktok.com/@smkn1kawali" target="_blank" class="hover:text-[#1e3a5f] hover:underline transition">@smkn1kawali</a>
                            </li>
                            <li>
                                <span class="font-medium">Youtube :</span>
                                <a href="https://youtube.com/@SMKN1KawaliOfficial" target="_blank" class="hover:text-[#1e3a5f] hover:underline transition">@SMKN1KawaliOfficial</a>
                            </li>
                        </ul>
                    </div>

                    <!-- Logo Sekolah -->
                    <div class="flex justify-center lg:justify-end hidden lg:flex">
                        <div class="w-32 h-32 ">
                            <img src="{{ asset('img/logosmk.png') }}" alt="Logo SMKN 1 Kawali" class="w-full h-full object-contain ">
                        </div>
                    </div>
                </div>
            </div>


        </div>
    </footer>

    <!-- Script untuk animasi navbar dan interaktivitas menu -->
    <script>
        // Fungsi pratinjau gambar bukti transfer
function previewImage(input) {
    const placeholder = document.getElementById('upload-placeholder');
    const previewContainer = document.getElementById('preview-container');
    const previewObject = document.getElementById('file-preview-object');

    // Pastikan user benar-benar memilih file
    if (input.files && input.files[0]) {
        const file = input.files[0];
        const fileType = file.type; // Mengambil mime-type file (contoh: "image/jpeg" atau "application/pdf")
        
        // Buat URL sementara (blob) agar browser bisa membaca file lokal tersebut
        const fileUrl = URL.createObjectURL(file);

        // Atur tipe data dan sumber dokumen pada tag <object>
        if (fileType === 'application/pdf') {
            previewObject.setAttribute('data', fileUrl);
            previewObject.setAttribute('type', 'application/pdf');
        } else {
            previewObject.setAttribute('data', fileUrl);
            previewObject.setAttribute('type', 'image/jpeg'); // Standar untuk semua jenis gambar lokal
        }

        // Jalankan transisi tampilan (sembunyikan info upload, munculkan preview)
        placeholder.classList.add('hidden');
        previewContainer.classList.remove('hidden');
        previewContainer.classList.add('flex'); // Memastikan flexbox aktif saat tampil
    } else {
        // Jika batal memilih file, reset ke tampilan awal
        previewObject.removeAttribute('data');
        previewObject.removeAttribute('type');
        previewContainer.classList.add('hidden');
        previewContainer.classList.remove('flex');
        placeholder.classList.remove('hidden');
    }
}

        // Logika untuk menampilkan/menyembunyikan menu mobile
        function toggleMobileMenu() {
            const menu = document.getElementById('mobile-menu');
            const icon = document.getElementById('menu-icon');

            if (menu.classList.contains('hidden')) {
                menu.classList.remove('hidden');
                menu.classList.add('animate-fade-in-down');
                icon.classList.remove('ph-list');
                icon.classList.add('ph-x');
            } else {
                menu.classList.add('hidden');
                menu.classList.remove('animate-fade-in-down');
                icon.classList.remove('ph-x');
                icon.classList.add('ph-list');
            }
        }

        // Logika Animasi Scroll Navbar & Penyorotan Status Menu Aktif
        document.addEventListener('DOMContentLoaded', () => {
            const navbar = document.getElementById('navbar');
            const sections = document.querySelectorAll('header, section, div[id="alur-layanan"]');
            const desktopLinks = document.querySelectorAll('.desktop-nav-link');
            const mobileLinks = document.querySelectorAll('.mobile-nav-link');

            window.addEventListener('scroll', () => {
                // 1. Efek bayangan pada Navbar saat menggulir
                if (window.scrollY > 20) {
                    navbar.classList.add('shadow-md', 'bg-opacity-95', 'backdrop-blur-sm');
                    navbar.classList.remove('border-gray-100');
                } else {
                    navbar.classList.remove('shadow-md', 'bg-opacity-95', 'backdrop-blur-sm');
                    navbar.classList.add('border-gray-100');
                }

                // 2. Deteksi bagian halaman yang aktif untuk menyoroti menu terkait
                let current = 'beranda';
                sections.forEach(section => {
                    const sectionTop = section.offsetTop;
                    const sectionId = section.getAttribute('id');

                    // Hanya perbarui 'current' jika bagian ini memiliki ID yang valid
                    if (window.scrollY >= sectionTop - 250 && sectionId) {
                        current = sectionId;
                    }
                });

                // 3. Perbarui tampilan tautan di menu Desktop
                desktopLinks.forEach(link => {
                    // Reset semua tautan ke status tidak aktif
                    link.classList.remove('text-gray-900', 'border-merek-kuning', 'font-semibold');
                    link.classList.add('text-gray-500', 'border-transparent', 'font-medium');

                    // Jika href link cocok dengan ID section saat ini, berikan status aktif
                    if (link.getAttribute('href') === `#${current}`) {
                        link.classList.remove('text-gray-500', 'border-transparent', 'font-medium');
                        link.classList.add('text-gray-900', 'border-merek-kuning', 'font-semibold');
                    }
                });

                // 4. Perbarui tampilan tautan di menu Mobile
                mobileLinks.forEach(link => {
                    // Reset status
                    link.classList.remove('text-merek-kuning', 'bg-gray-50');
                    link.classList.add('text-gray-600');

                    // Beri status aktif
                    if (link.getAttribute('href') === `#${current}`) {
                        link.classList.remove('text-gray-600');
                        link.classList.add('text-merek-kuning', 'bg-gray-50');
                    }
                });
            });
        });

        const inputTransfer = document.getElementById('jumlah_transfer');

        // Fungsi untuk memformat angka menjadi format ribuan dengan titik
        function formatRupiah(angka) {
            // Hapus semua karakter selain angka
            let numberString = angka.replace(/[^,\d]/g, '').toString();
            let split = numberString.split(',');
            let sisa = split[0].length % 3;
            let rupiah = split[0].substr(0, sisa);
            let ribuan = split[0].substr(sisa).match(/\d{3}/gi);

            if (ribuan) {
                let separator = sisa ? '.' : '';
                rupiah += separator + ribuan.join('.');
            }

            return split[1] !== undefined ? rupiah + ',' + split[1] : rupiah;
        }

        // Event saat pengguna mengetik
        inputTransfer.addEventListener('keyup', function(e) {
            this.value = formatRupiah(this.value);
        });

        // Jalankan fungsi saat halaman pertama kali dimuat (jika ada nilai old dari backend)
        window.addEventListener('DOMContentLoaded', function() {
            if (inputTransfer.value) {
                inputTransfer.value = formatRupiah(inputTransfer.value);
            }
        });
        
        // Inputan No Hp
        const inputNoHp = document.getElementById('no_hp_pengirim');

        // Fungsi untuk membersihkan input agar HANYA berisi angka
        function cleanNonDigits(angka) {
            // Hapus semua karakter selain angka (0-9)
            return angka.replace(/[^0-9]/g, '');
        }

        // Event saat pengguna mengetik/menginput nilai
        inputNoHp.addEventListener('input', function(e) {
            this.value = cleanNonDigits(this.value);
        });

        // Jalankan saat halaman pertama kali dimuat (jika ada nilai awal)
        window.addEventListener('DOMContentLoaded', function() {
            if (inputNoHp.value) {
                inputNoHp.value = cleanNonDigits(inputNoHp.value);
            }
        });

        // Inputan No Rekening
        const inputNoRek = document.getElementById('id_rekening');

        // Fungsi untuk membersihkan input agar HANYA berisi angka
        function cleanNonDigits(angka) {
            // Hapus semua karakter selain angka (0-9)
            return angka.replace(/[^0-9]/g, '');
        }

        // Event saat pengguna mengetik/menginput nilai
        inputNoRek.addEventListener('input', function(e) {
            this.value = cleanNonDigits(this.value);
        });

        // Jalankan saat halaman pertama kali dimuat (jika ada nilai awal)
        window.addEventListener('DOMContentLoaded', function() {
            if (inputNoRek.value) {
                inputNoRek.value = cleanNonDigits(inputNoRek.value);
            }
        });

    // Buat variabel timer di luar agar bisa di-reset setiap kali mengetik
    let delayTimer;

    document.getElementById('id_rekening').addEventListener('input', function() {
        let noRekening = this.value;
        let inputNoRekening = this;
        let inputNama = document.getElementById('nama_penerima');

        // 1. KELOLA INPUT KOSONG
        if (noRekening.trim() === '') {
            clearTimeout(delayTimer); // batalkan pencarian jika dihapus semua
            inputNama.value = '';
            inputNoRekening.style.borderColor = '#e5e7eb';
            inputNama.style.borderColor = '#e5e7eb';
            return;
        }

        // Tampilkan status "Mencari..." agar user tahu sistem sedang bekerja
        inputNama.value = 'Mencari data...';

        // 2. TEKNIK DEBOUNCE: Hapus timer yang lama jika user masih mengetik
        clearTimeout(delayTimer);

        // Set timer baru: Tunggu 500ms (0.5 detik) setelah ketikan terakhir berhenti
        delayTimer = setTimeout(function() {
            
            // Panggil Route API Laravel setelah user BERHENTI mengetik
            fetch(window.location.origin + '/cek-rekening/' + noRekening)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // REKENING ADA
                        inputNama.value = data.nama;
                        inputNoRekening.style.borderColor = '#10b981'; // Hijau halus
                        inputNama.style.borderColor = '#10b981';
                    } else {
                        // REKENING TIDAK ADA
                        inputNama.value = 'Rekening Tidak Ditemukan!';
                        inputNoRekening.style.borderColor = '#ef4444'; // Merah halus
                        inputNama.style.borderColor = '#ef4444';
                    }
                })
                .catch(error => {
                    console.error('Error fetch:', error);
                    inputNama.value = 'Gagal memuat data internet';
                });

        }, 500); // <-- 500 milidetik (0.5 detik). Silakan dipercepat ke 300 jika dirasa kurang kilat
    });

    let notificationTimeout;

    // GLOBAL NOTIFICATION MODAL CONTROL
    function showNotificationModal(title, messageHTML, type = 'success') {
        clearTimeout(notificationTimeout);
        const modal = document.getElementById('notificationModal');
        const content = document.getElementById('notificationModalContent');
        const titleEl = document.getElementById('notificationModalTitle');
        const msgEl = document.getElementById('notificationModalMessage');
        const iconBg = document.getElementById('notificationIconBg');
        const btn = document.getElementById('notificationModalBtn');

        titleEl.textContent = title;
        msgEl.innerHTML = messageHTML;

        if (type === 'error' || type === 'failed') {
            iconBg.className = 'w-16 h-16 rounded-full bg-red-50 flex items-center justify-center mb-4';
            iconBg.innerHTML = '<svg class="w-8 h-8 text-red-500" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path></svg>';
            btn.className = 'w-full px-6 py-3.5 rounded-xl bg-red-500 hover:bg-red-600 text-white font-bold text-[14px] transition-colors shadow-lg shadow-red-500/20 active:scale-95 focus:outline-none';
        } else {
            iconBg.className = 'w-16 h-16 rounded-full bg-emerald-50 flex items-center justify-center mb-4';
            iconBg.innerHTML = '<svg class="w-8 h-8 text-emerald-500" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>';
            btn.className = 'w-full px-6 py-3.5 rounded-xl bg-emerald-500 hover:bg-emerald-600 text-white font-bold text-[14px] transition-colors shadow-lg shadow-emerald-500/20 active:scale-95 focus:outline-none';
        }

        modal.classList.remove('hidden');
        modal.classList.add('flex');
        setTimeout(() => {
            content.classList.remove('scale-95', 'opacity-0');
            content.classList.add('scale-100', 'opacity-100');
        }, 10);
    }

    function closeNotificationModal() {
        const modal = document.getElementById('notificationModal');
        const content = document.getElementById('notificationModalContent');
        if (!modal) return;

        content.classList.remove('scale-100', 'opacity-100');
        content.classList.add('scale-95', 'opacity-0');
        setTimeout(() => {
            modal.classList.remove('flex');
            modal.classList.add('hidden');
        }, 300);
    }

    // FORM SUBMIT VALIDATOR
    document.getElementById('form-transfer').addEventListener('submit', function(e) {
        let errors = [];

        const namaPengirim = document.getElementsByName('nama_pengirim')[0].value.trim();
        const idRekening = document.getElementById('id_rekening').value.trim();
        const namaPenerima = document.getElementById('nama_penerima').value.trim();
        const noHpPengirim = document.getElementsByName('no_hp_pengirim')[0].value.trim();
        const jumlahTransfer = document.getElementById('jumlah_transfer').value.trim();
        const fileUpload = document.getElementById('file-upload').files;

        if (!namaPengirim) {
            errors.push('Nama Pengirim wajib diisi.');
        }
        if (!idRekening) {
            errors.push('Nomor Rekening Penerima wajib diisi.');
        } else if (namaPenerima === 'Rekening Tidak Ditemukan!' || namaPenerima === 'Mencari data...' || !namaPenerima) {
            errors.push('Rekening Penerima tidak valid atau tidak ditemukan.');
        }
        if (!noHpPengirim) {
            errors.push('Nomor Telepon wajib diisi.');
        }
        if (!jumlahTransfer || jumlahTransfer === '0') {
            errors.push('Jumlah Transfer wajib diisi.');
        }
        if (fileUpload.length === 0) {
            errors.push('Bukti Transfer wajib diunggah.');
        }

        if (errors.length > 0) {
            e.preventDefault(); // Mencegah submit form
            
            // Susun list error dalam format HTML yang rapi
            let errorListHTML = '<ul class="list-disc list-inside space-y-1.5 text-red-600 font-medium">';
            errors.forEach(function(err) {
                errorListHTML += `<li>${err}</li>`;
            });
            errorListHTML += '</ul>';

            showNotificationModal('Gagal Mengirim Data', errorListHTML, 'error');
        }
    });

    // TRIGGER BACKEND SESSION MESSAGES
    @if(session('success'))
        document.addEventListener('DOMContentLoaded', function() {
            showNotificationModal('Transfer Berhasil', "{{ session('success') }}", 'success');
        });
    @endif

    @if(session('error'))
        document.addEventListener('DOMContentLoaded', function() {
            showNotificationModal('Gagal', "{{ session('error') }}", 'error');
        });
    @endif

    @if(session('failed'))
        document.addEventListener('DOMContentLoaded', function() {
            showNotificationModal('Gagal', "{{ session('failed') }}", 'error');
        });
    @endif

    // TRIGGER BACKEND ERRORS ON LOAD
    @if($errors->any())
        document.addEventListener('DOMContentLoaded', function() {
            let backendErrors = '<ul class="list-disc list-inside space-y-1.5 text-red-600 font-medium">';
            @foreach($errors->all() as $error)
                backendErrors += `<li>{{ $error }}</li>`;
            @endforeach
            backendErrors += '</ul>';
            
            showNotificationModal('Validasi Gagal', backendErrors, 'error');
        });
    @endif
    </script>
</body>
</html>
