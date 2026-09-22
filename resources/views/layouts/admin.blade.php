<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin - Bank Mini')</title>
    <link rel="icon" href="{{ asset('img/Logo Bank Mini K-one.jpeg') }}" type="image/jpeg">
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Phosphor Icons -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            blue: '#1c3a5a',
                            green: '#10a163',
                            gold: '#bd8607',
                            bg: '#f8f9fb',
                            textDark: '#1e293b',
                            textMuted: '#94a3b8',
                            sidebarIcon: '#a1a1aa'
                        }
                    },
                    boxShadow: {
                        'card': '0 4px 20px -2px rgba(0, 0, 0, 0.03)',
                        'sidebar': '4px 0 24px -4px rgba(0,0,0,0.02)'
                    },
                    backgroundImage: {
                        'primary-gradient': 'linear-gradient(to bottom, #143657, #1E5081, #143657)',
                        'success-gradient': 'linear-gradient(to bottom, #008959, #1FB581, #008959)',
                        'warning-gradient': 'linear-gradient(to bottom, #AC7500, #dd9700, #AC7500)',
                        'button-gradient': 'linear-gradient(to right, #008959, #1E9F71, #008959)',
                    }
                }
            }
        }
    </script>
    <style>
        body {
            background-color: #f8f9fb;
        }

        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 10px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        .submenu-open {
            grid-template-rows: 1fr;
        }

        .submenu-closed {
            grid-template-rows: 0fr;
        }

        @yield('styles')
    </style>
</head>

<body class="flex h-screen w-full overflow-hidden text-brand-textDark selection:bg-brand-blue selection:text-white relative">

    <!-- OVERLAY (Mobile) -->
    <div id="sidebarOverlay" class="fixed inset-0 bg-black/50 z-40 hidden lg:hidden" onclick="toggleSidebar()"></div>

    <!-- Sidebar -->
    <aside id="sidebar" class="fixed lg:relative inset-y-0 left-0 w-[250px] h-full py-0 lg:py-5 pl-0 lg:pl-5 pr-0 flex-shrink-0 z-50 lg:z-20 transform -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out">
        <div class="bg-white h-full lg:rounded-[24px] shadow-sidebar flex flex-col pt-8 pb-8 overflow-hidden relative">
            <button class="lg:hidden absolute top-4 right-4 text-gray-400 hover:text-brand-textDark" onclick="toggleSidebar()">
                <i class="ph ph-x text-2xl"></i>
            </button>

            <div class="flex items-center gap-3 px-6 mb-10">
                <img src="{{ asset('img/bankmini2.png') }}" alt="Bank Logo" class="w-12 h-12 object-contain" onerror="this.src='https://via.placeholder.com/48'">
                <div>
                    <span class="block text-xs text-gray-500 font-semibold leading-none">Bank Mini</span>
                    <h1 class="font-extrabold text-[18px] leading-tight tracking-tight text-gray-900 mt-1">K-One</h1>
                </div>
            </div>

            <div class="px-6 mb-3">
                <p class="text-[11px] font-semibold text-brand-sidebarIcon tracking-wider">MENU ADMIN</p>
            </div>

            <nav class="flex-1 flex flex-col gap-1 w-full overflow-y-auto custom-scrollbar">
                @php
                $currentUrl = url()->current();
                $isSupervisor = str_contains($currentUrl, 'supervisor');
                $isKelolaDataSpv = str_contains($currentUrl, 'datapetugas') || str_contains($currentUrl, 'datanasabah') || str_contains($currentUrl, 'biayatransaksi') || str_contains($currentUrl, 'saldominimum') || str_contains($currentUrl, 'datamaster');
                $isVerifikasiSpv = str_contains($currentUrl, 'verifikasi');

                $isCs = str_contains($currentUrl, 'costumerservice') || str_contains($currentUrl, 'customerservice');
                $isCsKelolaData = str_contains($currentUrl, 'costumerservice/keloladata') || str_contains($currentUrl, 'costumerservice/edit') || str_contains($currentUrl, 'costumerservice/import');

                $isTeller = str_contains($currentUrl, 'teller');
                @endphp

                <!-- 1. SUPERVISOR ACCORDION -->
                <div class="flex flex-col w-full">
                    <button onclick="toggleSubmenu('spvSubmenu', 'spvArrow')" class="relative flex items-center justify-between px-6 py-3 {{ $isSupervisor ? 'text-brand-textDark font-bold' : 'text-[#a3a3a3] font-medium' }} hover:bg-gray-50 transition-colors group w-full text-left">
                        <div class="flex items-center gap-3">
                            <i class="ph ph-shield-check text-[22px] {{ $isSupervisor ? 'text-brand-blue' : 'group-hover:text-gray-600' }}"></i>
                            <span class="text-[14px]">Supervisor</span>
                        </div>
                        <i id="spvArrow" class="ph-bold ph-caret-right text-[14px] transition-transform duration-300 {{ $isSupervisor ? 'rotate-90' : '' }}"></i>
                    </button>
                    <div id="spvSubmenu" class="grid transition-all duration-300 {{ $isSupervisor ? 'submenu-open' : 'submenu-closed' }}">
                        <div class="overflow-hidden flex flex-col">
                            <!-- Dashboard -->
                            <a href="{{ route('halaman.utama.admin') }}" class="pl-[48px] pr-6 py-2 {{ str_contains($currentUrl, '/admin/supervisor/dashboard') ? 'text-brand-blue font-bold' : 'text-[#a3a3a3] font-medium' }} text-[13.5px] hover:text-gray-800 hover:bg-gray-50 transition-colors">
                                Dashboard
                            </a>

                            <!-- Kelola Data Sub-dropdown -->
                            <div class="flex flex-col w-full">
                                <button onclick="toggleSubmenu('kelolaDataSubmenu', 'kelolaDataArrow')" class="flex items-center justify-between pl-[48px] pr-6 py-2 {{ $isKelolaDataSpv ? 'text-brand-blue font-bold' : 'text-[#a3a3a3] font-medium' }} text-[13.5px] hover:text-gray-800 hover:bg-gray-50 transition-colors w-full text-left">
                                    <span>Kelola Data</span>
                                    <i id="kelolaDataArrow" class="ph-bold ph-caret-right text-[11px] transition-transform duration-300 {{ $isKelolaDataSpv ? 'rotate-90' : '' }}"></i>
                                </button>
                                <div id="kelolaDataSubmenu" class="grid transition-all duration-300 {{ $isKelolaDataSpv ? 'submenu-open' : 'submenu-closed' }}">
                                    <div class="overflow-hidden flex flex-col">
                                        <a href="{{ route('halaman.petugas.admin') }}" class="pl-[62px] pr-6 py-1.5 {{ str_contains($currentUrl, 'datapetugas') ? 'text-brand-blue font-bold' : 'text-[#a3a3a3] font-medium' }} text-[13px] hover:text-gray-800 hover:bg-gray-50 transition-colors">Data Petugas</a>
                                        <a href="{{ route('halaman.nasabah.admin') }}" class="pl-[62px] pr-6 py-1.5 {{ str_contains($currentUrl, 'datanasabah') ? 'text-brand-blue font-bold' : 'text-[#a3a3a3] font-medium' }} text-[13px] hover:text-gray-800 hover:bg-gray-50 transition-colors">Data Nasabah</a>
                                        <a href="{{ url('/admin/supervisor/biayatransaksi') }}" class="pl-[62px] pr-6 py-1.5 {{ str_contains($currentUrl, 'biayatransaksi') ? 'text-brand-blue font-bold' : 'text-[#a3a3a3] font-medium' }} text-[13px] hover:text-gray-800 hover:bg-gray-50 transition-colors">Biaya Transaksi</a>
                                        <a href="{{ url('/admin/supervisor/saldominimum') }}" class="pl-[62px] pr-6 py-1.5 {{ str_contains($currentUrl, 'saldominimum') || str_contains($currentUrl, 'saldoMinimum') ? 'text-brand-blue font-bold' : 'text-[#a3a3a3] font-medium' }} text-[13px] hover:text-gray-800 hover:bg-gray-50 transition-colors">Saldo Minimum</a>
                                        <a href="{{ route('master.siswa.admin') }}" class="pl-[62px] pr-6 py-1.5 {{ str_contains($currentUrl, 'datamaster/siswa') ? 'text-brand-blue font-bold' : 'text-[#a3a3a3] font-medium' }} text-[13px] hover:text-gray-800 hover:bg-gray-50 transition-colors">Data Siswa</a>
                                        <a href="{{ route('master.gtk.admin') }}" class="pl-[62px] pr-6 py-1.5 {{ str_contains($currentUrl, 'datamaster/gtk') ? 'text-brand-blue font-bold' : 'text-[#a3a3a3] font-medium' }} text-[13px] hover:text-gray-800 hover:bg-gray-50 transition-colors">Data GTK</a>
                                    </div>
                                </div>
                            </div>

                            <!-- Verifikasi Sub-dropdown -->
                            <div class="flex flex-col w-full">
                                <button onclick="toggleSubmenu('verifikasiSubmenu', 'verifikasiArrow')" class="flex items-center justify-between pl-[48px] pr-6 py-2 {{ $isVerifikasiSpv ? 'text-brand-blue font-bold' : 'text-[#a3a3a3] font-medium' }} text-[13.5px] hover:text-gray-800 hover:bg-gray-50 transition-colors w-full text-left">
                                    <span>Verifikasi</span>
                                    <i id="verifikasiArrow" class="ph-bold ph-caret-right text-[11px] transition-transform duration-300 {{ $isVerifikasiSpv ? 'rotate-90' : '' }}"></i>
                                </button>
                                <div id="verifikasiSubmenu" class="grid transition-all duration-300 {{ $isVerifikasiSpv ? 'submenu-open' : 'submenu-closed' }}">
                                    <div class="overflow-hidden flex flex-col">
                                        <a href="{{ url('/admin/supervisor/verifikasi/login') }}" class="pl-[62px] pr-6 py-1.5 {{ str_contains($currentUrl, 'verifikasi/login') ? 'text-brand-blue font-bold' : 'text-[#a3a3a3] font-medium' }} text-[13px] hover:text-gray-800 hover:bg-gray-50 transition-colors">Verifikasi Login</a>
                                        <a href="{{ route('verifikasi.nasabah.admin') }}" class="pl-[62px] pr-6 py-1.5 {{ str_contains($currentUrl, 'registrasi') ? 'text-brand-blue font-bold' : 'text-[#a3a3a3] font-medium' }} text-[13px] hover:text-gray-800 hover:bg-gray-50 transition-colors">Verifikasi Registrasi</a>
                                        <a href="{{ url('/admin/supervisor/verifikasi/transfer') }}" class="pl-[62px] pr-6 py-1.5 {{ str_contains($currentUrl, 'verifikasi/transfer') ? 'text-brand-blue font-bold' : 'text-[#a3a3a3] font-medium' }} text-[13px] hover:text-gray-800 hover:bg-gray-50 transition-colors">Verifikasi Transfer</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 2. CUSTOMER SERVICE ACCORDION -->
                <div class="flex flex-col w-full">
                    <button onclick="toggleSubmenu('csSubmenu', 'csArrow')" class="relative flex items-center justify-between px-6 py-3 {{ $isCs ? 'text-brand-textDark font-bold' : 'text-[#a3a3a3] font-medium' }} hover:bg-gray-50 transition-colors group w-full text-left">
                        <div class="flex items-center gap-3">
                            <i class="ph ph-headset text-[22px] {{ $isCs ? 'text-brand-blue' : 'group-hover:text-gray-600' }}"></i>
                            <span class="text-[14px]">Customer Service</span>
                        </div>
                        <i id="csArrow" class="ph-bold ph-caret-right text-[14px] transition-transform duration-300 {{ $isCs ? 'rotate-90' : '' }}"></i>
                    </button>
                    <div id="csSubmenu" class="grid transition-all duration-300 {{ $isCs ? 'submenu-open' : 'submenu-closed' }}">
                        <div class="overflow-hidden flex flex-col">
                            <a href="{{ route('halaman.utama.cs.admin') }}" class="pl-[48px] pr-6 py-2 {{ str_contains($currentUrl, '/admin/costumerservice/dashboard') ? 'text-brand-blue font-bold' : 'text-[#a3a3a3] font-medium' }} text-[13.5px] hover:text-gray-800 hover:bg-gray-50 transition-colors">
                                Dashboard
                            </a>
                            <a href="{{ route('kelola.data.cs.admin') }}" class="pl-[48px] pr-6 py-2 {{ $isCsKelolaData ? 'text-brand-blue font-bold' : 'text-[#a3a3a3] font-medium' }} text-[13.5px] hover:text-gray-800 hover:bg-gray-50 transition-colors">
                                Kelola Data
                            </a>
                        </div>
                    </div>
                </div>

                <!-- 3. TELLER DIRECT MENU -->
                <div class="relative">
                    @if($isTeller)
                    <div class="absolute left-0 top-1/2 -translate-y-1/2 w-[4px] h-8 bg-brand-blue rounded-r-md"></div>
                    @endif
                    <a href="{{ url('/admin/teller/dashboard') }}" class="flex items-center gap-3 px-6 py-3 {{ $isTeller ? 'text-brand-textDark font-bold bg-gray-50/50' : 'text-[#a3a3a3] font-medium hover:bg-gray-50' }} transition-colors group">
                        <i class="ph{{ $isTeller ? '-fill' : '' }} ph-bank text-[22px] {{ $isTeller ? 'text-brand-blue' : 'group-hover:text-gray-600' }}"></i>
                        <span class="text-[14px]">Teller</span>
                    </a>
                </div>

                <!-- Keluar -->
                <div class="px-6 mt-4">
                    <button
                        type="button"
                        onclick="openLogoutModal()"
                        class="flex items-center gap-3 py-2 text-red-600 font-medium hover:text-red-700 transition-colors w-full text-left">
                        <i class="ph ph-sign-out text-[22px]"></i>
                        <span class="text-[14px]">Keluar</span>
                    </button>
                </div>
            </nav>
        </div>
    </aside>

    <!-- Main Content Area -->
    <main class="flex-1 flex flex-col h-screen overflow-y-auto w-full">
        <!-- MOBILE TOP BAR (Standardized) -->
        <div class="lg:hidden flex items-center justify-between px-6 py-4 bg-white border-b border-gray-100 sticky top-0 z-30">
            <div class="flex items-center gap-2 text-brand-blue">
                <img src="{{ asset('img/bankmini2.png') }}" alt="Bank Logo" class="w-9 h-9 object-contain">
                <div class="flex flex-col">
                    <span class="text-[10px] text-gray-500 font-semibold leading-none">Bank Mini</span>
                    <span class="font-bold text-base tracking-tight text-gray-900 leading-tight mt-0.5">K-One</span>
                </div>
            </div>
            <button class="p-2 bg-gray-50 rounded-lg text-brand-blue" onclick="toggleSidebar()">
                <i class="ph ph-list text-2xl"></i>
            </button>
        </div>

        <div class="max-w-[1050px] mx-auto w-full flex flex-col h-full mt-2 pb-10 px-6 lg:px-10">
            <!-- Header Section -->
            <header class="flex flex-col md:flex-row justify-between items-start md:items-center py-4 lg:py-8 mb-4 gap-4 md:gap-0 hidden lg:flex">
                <div>
                    <h2 class="text-[20px] md:text-[26px] font-bold text-gray-800 mb-0.5">@yield('header_title', 'Selamat Datang!')</h2>
                    <p class="text-gray-500 text-[10px] md:text-[14px]">@yield('header_subtitle', 'Panel Kontrol Administrator Bank Mini.')</p>
                </div>
                <div class="flex items-center gap-6">
                    @yield('header_actions')
                    <div class="flex items-center gap-3">
                        <i class="ph-fill ph-user-circle text-[38px] text-brand-blue"></i>
                        <div class="text-left">
                            <p class="font-bold text-[14px] text-gray-800 leading-tight">{{ $user->name ?? 'Administrator' }}</p>
                            <p class="text-[12px] text-gray-400 mt-0.5">{{ $user->email ?? 'admin@bankminikone.test' }}</p>
                        </div>
                    </div>
                </div>
            </header>

            @yield('content')
        </div>
    </main>

    <!-- Global Toast Notification -->
    <div id="toastAlert" class="fixed top-6 right-6 z-[110] hidden opacity-0 transform translate-y-2 transition-all duration-300">
        <div id="toastBg" class="bg-white text-gray-800 px-5 py-4 rounded-2xl shadow-[0_10px_30px_rgba(0,0,0,0.08)] flex items-center gap-3.5 border border-gray-100/80 min-w-[320px] max-w-[420px]">
            <div id="toastIcon" class="flex items-center justify-center w-10 h-10 rounded-xl">
                <!-- Icon dynamically set -->
            </div>
            <div class="flex-1 flex flex-col text-left">
                <span id="toastTitle" class="font-semibold text-sm text-gray-900 leading-tight">Berhasil</span>
                <span id="toastMessage" class="text-xs text-gray-500 font-medium mt-0.5">Pesan berhasil disimpan!</span>
            </div>
            <button onclick="closeToast()" class="text-gray-400 hover:text-gray-600 transition-colors ml-2 focus:outline-none">
                <i class="ph ph-x text-lg"></i>
            </button>
        </div>
    </div>

    <!-- Global Delete Confirmation Modal -->
    <div id="deleteModal" class="fixed inset-0 z-[100] hidden items-center justify-center p-4">
        <div class="fixed inset-0 bg-black/40 backdrop-blur-sm" onclick="closeDeleteModal()"></div>
        <div class="bg-white rounded-[28px] w-full max-w-[400px] p-8 shadow-2xl relative z-10 transform transition-all scale-95 opacity-0 duration-300" id="deleteModalContent">
            <div class="flex flex-col items-center text-center">
                <div class="w-20 h-20 rounded-full bg-red-50 flex items-center justify-center mb-6">
                    <i class="ph-fill ph-warning-circle text-[48px] text-red-500"></i>
                </div>
                <h3 class="text-[22px] font-bold text-gray-900 mb-2">Hapus Data?</h3>
                <p class="text-gray-500 text-[14px] leading-relaxed mb-8">
                    Apakah Anda yakin ingin menghapus data ini? Tindakan ini <span class="font-bold text-red-500">tidak dapat dibatalkan</span> (Mode Preview).
                </p>
                <div class="flex flex-col sm:flex-row gap-3 w-full">
                    <button onclick="closeDeleteModal()" class="flex-1 px-6 py-3.5 rounded-xl bg-gray-100 text-gray-700 font-bold text-[14px] hover:bg-gray-200 transition-colors">
                        Batal
                    </button>
                    <button id="btnConfirmDelete" class="flex-1 px-6 py-3.5 rounded-xl bg-red-500 text-white font-bold text-[14px] hover:bg-red-600 transition-colors shadow-lg shadow-red-500/30">
                        Ya, Hapus
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Global Logout Confirmation Modal -->
    <div id="logoutModal" class="fixed inset-0 z-[100] hidden items-center justify-center p-4">
        <div class="fixed inset-0 bg-black/40 backdrop-blur-sm" onclick="closeLogoutModal()"></div>
        <div class="bg-white rounded-[28px] w-full max-w-[400px] p-8 shadow-2xl relative z-10 transform transition-all scale-95 opacity-0 duration-300" id="logoutModalContent">
            <div class="flex flex-col items-center text-center">
                <div class="w-20 h-20 rounded-full bg-red-50 flex items-center justify-center mb-6">
                    <i class="ph-fill ph-sign-out text-[44px] text-red-500"></i>
                </div>
                <h3 class="text-[22px] font-bold text-gray-900 mb-2">Konfirmasi Keluar</h3>
                <p class="text-gray-500 text-[14px] leading-relaxed mb-8">
                    Apakah Anda yakin ingin keluar dari akun Anda?
                </p>
                <div class="flex flex-col sm:flex-row gap-3 w-full">
                    <button type="button" onclick="closeLogoutModal()" class="flex-1 px-6 py-3.5 rounded-xl bg-gray-100 text-gray-700 font-bold text-[14px] hover:bg-gray-200 transition-colors">
                        Batal
                    </button>
                    <a href="{{ url('/login') }}" class="flex-1 px-6 py-3.5 rounded-xl bg-red-500 text-white font-bold text-[14px] text-center hover:bg-red-600 transition-colors shadow-lg shadow-red-500/30">
                        Ya, Keluar
                    </a>
                </div>
            </div>
        </div>
    </div>

<script>
    /*
    |--------------------------------------------------------------------------
    | SIDEBAR
    |--------------------------------------------------------------------------
    */

    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebarOverlay');

        if (!sidebar || !overlay) return;

        sidebar.classList.toggle('-translate-x-full');
        overlay.classList.toggle('hidden');
    }


    /*
    |--------------------------------------------------------------------------
    | SUBMENU
    |--------------------------------------------------------------------------
    */

    function toggleSubmenu(submenuId, arrowId) {
        const submenu = document.getElementById(submenuId);
        const arrow = document.getElementById(arrowId);

        if (!submenu) return;

        const isOpen = submenu.classList.contains('submenu-open');

        if (isOpen) {
            submenu.classList.replace('submenu-open', 'submenu-closed');

            if (arrow) {
                arrow.style.transform = 'rotate(0deg)';
            }
        } else {
            submenu.classList.replace('submenu-closed', 'submenu-open');

            if (arrow) {
                arrow.style.transform = 'rotate(90deg)';
            }
        }
    }


    /*
    |--------------------------------------------------------------------------
    | TOAST
    |--------------------------------------------------------------------------
    */

    let toastTimeout = null;

    function showToast(message, type = 'success') {
        const toast = document.getElementById('toastAlert');
        const toastBg = document.getElementById('toastBg');
        const toastTitle = document.getElementById('toastTitle');
        const toastMsg = document.getElementById('toastMessage');
        const toastIcon = document.getElementById('toastIcon');

        if (!toast || !toastBg || !toastTitle || !toastMsg || !toastIcon) {
            return;
        }

        clearTimeout(toastTimeout);

        toastMsg.textContent = message;

        if (type === 'error' || type === 'failed') {
            // ERROR
            toastTitle.textContent = 'Gagal';
            toastTitle.className =
                'font-semibold text-sm text-red-600 leading-tight';

            toastIcon.className =
                'flex items-center justify-center w-10 h-10 rounded-xl bg-red-50 text-red-500';

            toastIcon.innerHTML =
                '<i class="ph-fill ph-x-circle text-[22px]"></i>';

            toastBg.className =
                'bg-white text-gray-800 px-5 py-4 rounded-2xl shadow-[0_10px_30px_rgba(0,0,0,0.08)] flex items-center gap-3.5 border border-red-100/50 min-w-[320px] max-w-[420px]';

        } else {
            // SUCCESS
            toastTitle.textContent = 'Berhasil';
            toastTitle.className =
                'font-semibold text-sm text-emerald-600 leading-tight';

            toastIcon.className =
                'flex items-center justify-center w-10 h-10 rounded-xl bg-emerald-50 text-emerald-500';

            toastIcon.innerHTML =
                '<i class="ph-fill ph-check-circle text-[22px]"></i>';

            toastBg.className =
                'bg-white text-gray-800 px-5 py-4 rounded-2xl shadow-[0_10px_30px_rgba(0,0,0,0.08)] flex items-center gap-3.5 border border-emerald-100/50 min-w-[320px] max-w-[420px]';
        }

        // Tampilkan toast
        toast.classList.remove('hidden');

        requestAnimationFrame(() => {
            toast.classList.remove('opacity-0', 'translate-y-2');
            toast.classList.add('opacity-100', 'translate-y-0');
        });

        // Auto close 4 detik
        toastTimeout = setTimeout(() => {
            closeToast();
        }, 4000);
    }


    function closeToast() {
        const toast = document.getElementById('toastAlert');

        if (!toast) return;

        toast.classList.remove('opacity-100', 'translate-y-0');
        toast.classList.add('opacity-0', 'translate-y-2');

        setTimeout(() => {
            toast.classList.add('hidden');
        }, 300);

        if (toastTimeout) {
            clearTimeout(toastTimeout);
            toastTimeout = null;
        }
    }


    /*
    |--------------------------------------------------------------------------
    | DELETE PETUGAS
    |--------------------------------------------------------------------------
    */

    function hapusPetugas(id) {
        const form = document.getElementById('formDeletePetugas');

        if (!form) {
            console.error('Form delete petugas tidak ditemukan.');
            return;
        }

        form.action = `/hapus/petugas/admin/${id}`;
        form.submit();
    }


    /*
    |--------------------------------------------------------------------------
    | DELETE MODAL
    |--------------------------------------------------------------------------
    */

    let rowToDelete = null;

    function openDeleteModal(onConfirm) {
        const modal = document.getElementById('deleteModal');
        const content = document.getElementById('deleteModalContent');
        const confirmBtn = document.getElementById('btnConfirmDelete');

        if (!modal || !content || !confirmBtn) {
            return;
        }

        modal.classList.remove('hidden');
        modal.classList.add('flex');

        // Reset posisi awal animasi
        content.classList.remove('scale-100', 'opacity-100');
        content.classList.add('scale-95', 'opacity-0');

        requestAnimationFrame(() => {
            content.classList.remove('scale-95', 'opacity-0');
            content.classList.add('scale-100', 'opacity-100');
        });

        confirmBtn.onclick = function () {
            if (typeof onConfirm === 'function') {
                onConfirm();
            }

            closeDeleteModal();
        };
    }


    function closeDeleteModal() {
        const modal = document.getElementById('deleteModal');
        const content = document.getElementById('deleteModalContent');

        if (!modal || !content) {
            return;
        }

        content.classList.remove('scale-100', 'opacity-100');
        content.classList.add('scale-95', 'opacity-0');

        setTimeout(() => {
            modal.classList.remove('flex');
            modal.classList.add('hidden');
        }, 300);
    }


    function hapusBaris(element) {
        if (!element) return;

        const row =
            element.closest('tr') ||
            element.closest('.data-row') ||
            element.closest('li');

        if (!row) {
            console.warn('Baris data tidak ditemukan.');
            return;
        }

        rowToDelete = row;

        openDeleteModal(() => {
            if (!rowToDelete) return;

            rowToDelete.style.transition = 'all 0.3s ease';
            rowToDelete.style.opacity = '0';

            setTimeout(() => {
                if (rowToDelete) {
                    rowToDelete.remove();
                    rowToDelete = null;
                }

                showToast(
                    'Data berhasil dihapus (Mode Preview)',
                    'success'
                );
            }, 300);
        });
    }


    /*
    |--------------------------------------------------------------------------
    | LOGOUT MODAL
    |--------------------------------------------------------------------------
    */

    function openLogoutModal() {
        const modal = document.getElementById('logoutModal');
        const content = document.getElementById('logoutModalContent');

        if (!modal || !content) {
            return;
        }

        modal.classList.remove('hidden');
        modal.classList.add('flex');

        // Reset animasi
        content.classList.remove('scale-100', 'opacity-100');
        content.classList.add('scale-95', 'opacity-0');

        requestAnimationFrame(() => {
            content.classList.remove('scale-95', 'opacity-0');
            content.classList.add('scale-100', 'opacity-100');
        });
    }


    function closeLogoutModal() {
        const modal = document.getElementById('logoutModal');
        const content = document.getElementById('logoutModalContent');

        if (!modal || !content) {
            return;
        }

        content.classList.remove('scale-100', 'opacity-100');
        content.classList.add('scale-95', 'opacity-0');

        setTimeout(() => {
            modal.classList.remove('flex');
            modal.classList.add('hidden');
        }, 300);
    }


    /*
    |--------------------------------------------------------------------------
    | CONFIRM LOGOUT
    |--------------------------------------------------------------------------
    */

    function confirmLogout() {
        const form = document.getElementById('globalLogoutForm');

        if (form) {
            form.submit();
        }
    }


    /*
    |--------------------------------------------------------------------------
    | SESSION TOAST
    |--------------------------------------------------------------------------
    */

    document.addEventListener('DOMContentLoaded', function () {

        @if(session('success'))
            showToast(
                @json(session('success')),
                'success'
            );
        @endif

        @if(session('error'))
            showToast(
                @json(session('error')),
                'error'
            );
        @endif

        @if(session('failed'))
            showToast(
                @json(session('failed')),
                'error'
            );
        @endif

        @if($errors->any())
            showToast(
                @json($errors->first()),
                'error'
            );
        @endif

    });


    /*
    |--------------------------------------------------------------------------
    | ESC KEY
    |--------------------------------------------------------------------------
    | Menutup modal ketika tombol Escape ditekan.
    */

    document.addEventListener('keydown', function (event) {

        if (event.key !== 'Escape') return;

        const deleteModal = document.getElementById('deleteModal');
        const logoutModal = document.getElementById('logoutModal');

        if (
            deleteModal &&
            !deleteModal.classList.contains('hidden')
        ) {
            closeDeleteModal();
        }

        if (
            logoutModal &&
            !logoutModal.classList.contains('hidden')
        ) {
            closeLogoutModal();
        }

    });
</script>



    @yield('scripts')
</body>
</html>
