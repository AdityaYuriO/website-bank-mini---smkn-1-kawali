<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Supervisor - Bank Mini')</title>
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
                <img src="{{ asset('img/bankmini2.png') }}" alt="Bank Logo" class="w-12 h-12 object-contain">
                <div>
                    <span class="block text-xs text-gray-500 font-semibold leading-none">Bank Mini</span>
                    <h1 class="font-extrabold text-[18px] leading-tight tracking-tight text-gray-900 mt-1">K-One</h1>
                </div>
            </div>

            <div class="px-6 mb-3">
                <p class="text-[11px] font-semibold text-brand-sidebarIcon tracking-wider">MENU</p>
            </div>

            <nav class="flex-1 flex flex-col gap-1 w-full overflow-y-auto custom-scrollbar">
                @php
                $route = Route::currentRouteName();
                $isKelolaData = str_contains($route, 'supervisor.datapetugas') || str_contains($route, 'supervisor.datanasabah') || str_contains($route, 'supervisor.biayatransaksi') || str_contains($route, 'supervisor.saldominimum') || str_contains($route, 'datamaster.siswa');
                @endphp

                <!-- Dashboard -->
                <div class="relative">
                    @if($route == 'supervisor.dashboard')
                    <div class="absolute left-0 top-1/2 -translate-y-1/2 w-[4px] h-8 bg-brand-blue rounded-r-md"></div>
                    @endif
                    <a href="{{ route('supervisor.dashboard') }}" class="flex items-center gap-3 px-6 py-3 {{ $route == 'supervisor.dashboard' ? 'text-brand-textDark font-bold bg-gray-50/50' : 'text-[#a3a3a3] font-medium hover:bg-gray-50' }} transition-colors group">
                        <i class="ph{{ $route == 'supervisor.dashboard' ? '-fill' : '' }} ph-gauge text-[22px] {{ $route == 'supervisor.dashboard' ? 'text-brand-blue' : 'group-hover:text-gray-600' }}"></i>
                        <span class="text-[14px]">Dashboard</span>
                    </a>
                </div>

                <!-- Kelola Data Dropdown -->
                <div class="flex flex-col w-full">
                    <button onclick="toggleSubmenu('kelolaDataSubmenu', 'kelolaDataArrow')" class="relative flex items-center justify-between px-6 py-3 {{ $isKelolaData ? 'text-brand-textDark font-bold' : 'text-[#a3a3a3] font-medium' }} hover:bg-gray-50 transition-colors group w-full">
                        <div class="flex items-center gap-3">
                            <i class="ph ph-list text-[22px] {{ $isKelolaData ? 'text-brand-blue' : 'group-hover:text-gray-600' }}"></i>
                            <span class="text-[14px]">Kelola Data</span>
                        </div>
                        <i id="kelolaDataArrow" class="ph-bold ph-caret-right text-[14px] transition-transform duration-300 {{ $isKelolaData ? 'rotate-90' : '' }}"></i>
                    </button>
                    <div id="kelolaDataSubmenu" class="grid transition-all duration-300 {{ $isKelolaData ? 'submenu-open' : 'submenu-closed' }}">
                        <div class="overflow-hidden flex flex-col">
                            <a href="{{ route('supervisor.datapetugas') }}" class="pl-[52px] pr-6 py-2.5 {{ $route == 'supervisor.datapetugas' ? 'text-brand-blue font-bold' : 'text-[#a3a3a3] font-medium' }} text-[13.5px] hover:text-gray-800 hover:bg-gray-50 transition-colors">Data Petugas</a>
                            <a href="{{ route('supervisor.datanasabah') }}" class="pl-[52px] pr-6 py-2.5 {{ $route == 'supervisor.datanasabah' ? 'text-brand-blue font-bold' : 'text-[#a3a3a3] font-medium' }} text-[13.5px] hover:text-gray-800 hover:bg-gray-50 transition-colors">Data Nasabah</a>
                            <a href="{{ route('supervisor.biayatransaksi') }}" class="pl-[52px] pr-6 py-2.5 {{ $route == 'supervisor.biayatransaksi' ? 'text-brand-blue font-bold' : 'text-[#a3a3a3] font-medium' }} text-[13.5px] hover:text-gray-800 hover:bg-gray-50 transition-colors">Biaya Transaksi</a>
                            <a href="{{ route('supervisor.saldominimum') }}" class="pl-[52px] pr-6 py-2.5 {{ $route == 'supervisor.saldominimum' ? 'text-brand-blue font-bold' : 'text-[#a3a3a3] font-medium' }} text-[13.5px] hover:text-gray-800 hover:bg-gray-50 transition-colors">Saldo Minimum</a>
                            <a href="{{ route('halaman.datamaster.siswa') }}" class="pl-[52px] pr-6 py-2.5 {{ ($route == 'halaman.datamaster.siswa' || $route == 'datamaster.siswa') ? 'text-brand-blue font-bold' : 'text-[#a3a3a3] font-medium' }} text-[13.5px] hover:text-gray-800 hover:bg-gray-50 transition-colors">Data Siswa</a>
                            <a href="{{ route('halaman.datamaster.gtk') }}" class="pl-[52px] pr-6 py-2.5 {{ ($route == 'halaman.datamaster.gtk' || $route == 'datamaster.gtk') ? 'text-brand-blue font-bold' : 'text-[#a3a3a3] font-medium' }} text-[13.5px] hover:text-gray-800 hover:bg-gray-50 transition-colors">Data GTK</a>
                            <div class="h-2"></div>
                        </div>
                    </div>
                </div>

                <!-- Verifikasi -->
                <div class="relative">
                    @if($route == 'supervisor.verifikasi' || $route == 'supervisor.verifikasi.registrasi' || $route == 'supervisor.verifikasi.login')
                    <div class="absolute left-0 top-1/2 -translate-y-1/2 w-[4px] h-8 bg-brand-blue rounded-r-md"></div>
                    @endif
                    <a href="{{ route('supervisor.verifikasi.login') }}" class="flex items-center gap-3 px-6 py-3 {{ ($route == 'supervisor.verifikasi' || $route == 'supervisor.verifikasi.registrasi' || $route == 'supervisor.verifikasi.login') ? 'text-brand-textDark font-bold bg-gray-50/50' : 'text-[#a3a3a3] font-medium hover:bg-gray-50' }} transition-colors group">
                        <i class="ph{{ ($route == 'supervisor.verifikasi' || $route == 'supervisor.verifikasi.registrasi' || $route == 'supervisor.verifikasi.login') ? '-fill' : '' }} ph-folder text-[22px] {{ ($route == 'supervisor.verifikasi' || $route == 'supervisor.verifikasi.registrasi' || $route == 'supervisor.verifikasi.login') ? 'text-brand-blue' : 'group-hover:text-gray-600' }}"></i>
                        <span class="text-[14px]">Verifikasi</span>
                    </a>
                </div>

                <!-- Keluar -->
                <div class="px-6 mt-4">
                    <form id="globalLogoutForm" action="{{ route('logout') }}" method="POST" class="hidden">
                        @csrf
                    </form>
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
            <header class="flex flex-col md:flex-row justify-between items-start md:items-center py-4 lg:py-8 mb-4 gap-4 md:gap-0  hidden lg:flex">
                <div>
                    <h2 class="text-[20px] md:text-[26px] font-bold text-gray-800 mb-0.5">@yield('header_title', 'Selamat Datang!')</h2>
                    <p class="text-gray-500 text-[10px] md:text-[14px]">@yield('header_subtitle', 'Kelola pengawasan Bank Mini.')</p>
                </div>
                <div class="flex items-center gap-6">
                    <!-- Search Bar (Standardized) -->

                    <div class="flex items-center gap-3">
                        <i class="ph-fill ph-user-circle text-[38px] text-brand-blue"></i>
                        <div class="text-left">

                            <p class="font-bold text-[14px] text-gray-800 leading-tight">{{ $user->name }}</p>

                            <p class="text-[12px] text-gray-400 mt-0.5">{{ $user->email }}</p>
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
                    Apakah Anda yakin ingin menghapus data ini? Tindakan ini <span class="font-bold text-red-500">tidak dapat dibatalkan</span> dan data akan hilang permanen.
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
                    <button type="button" onclick="confirmLogout()" class="flex-1 px-6 py-3.5 rounded-xl bg-red-500 text-white font-bold text-[14px] hover:bg-red-600 transition-colors shadow-lg shadow-red-500/30">
                        Ya, Keluar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Global Action Confirmation Modal (Clean & Modern) -->
    <div id="confirmActionModal" class="fixed inset-0 z-[100] hidden items-center justify-center p-4">
        <div class="fixed inset-0 bg-black/40 backdrop-blur-sm" onclick="closeConfirmModal()"></div>
        <div class="bg-white rounded-[28px] w-full max-w-[420px] p-8 shadow-2xl relative z-10 transform transition-all scale-95 opacity-0 duration-300" id="confirmActionModalContent">
            <div class="flex flex-col items-center text-center">
                <div id="confirmIconContainer" class="w-20 h-20 rounded-full flex items-center justify-center mb-6">
                    <!-- Icon inserted dynamically -->
                </div>
                <h3 id="confirmModalTitle" class="text-[22px] font-bold text-gray-900 mb-2">Konfirmasi</h3>
                <p id="confirmModalMessage" class="text-gray-500 text-[14px] leading-relaxed mb-8">
                    Apakah Anda yakin ingin melanjutkan tindakan ini?
                </p>
                <div class="flex flex-col sm:flex-row gap-3 w-full">
                    <button type="button" onclick="closeConfirmModal()" class="flex-1 px-6 py-3.5 rounded-xl bg-gray-100 text-gray-700 font-bold text-[14px] hover:bg-gray-200 transition-colors">
                        Batal
                    </button>
                    <button id="confirmModalBtn" type="button" class="flex-1 px-6 py-3.5 rounded-xl text-white font-bold text-[14px] transition-all shadow-lg">
                        Ya, Lanjutkan
                    </button>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('js/datapetugas/edit.js') }}"></script>
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            sidebar.classList.toggle('-translate-x-full');
            overlay.classList.toggle('hidden');
        }

        function toggleSubmenu(submenuId, arrowId) {
            const submenu = document.getElementById(submenuId);
            const arrow = document.getElementById(arrowId);
            if (submenu.classList.contains('submenu-open')) {
                submenu.classList.replace('submenu-open', 'submenu-closed');
                arrow.style.transform = 'rotate(0deg)';
            } else {
                submenu.classList.replace('submenu-closed', 'submenu-open');
                arrow.style.transform = 'rotate(90deg)';
            }
        }

        let toastTimeout;

        // TOAST SYSTEM
        function showToast(message, type = 'success') {
            clearTimeout(toastTimeout);
            const toast = document.getElementById('toastAlert');
            const toastBg = document.getElementById('toastBg');
            const toastTitle = document.getElementById('toastTitle');
            const toastMsg = document.getElementById('toastMessage');
            const toastIcon = document.getElementById('toastIcon');

            toastMsg.textContent = message;

            if (type === 'error' || type === 'failed') {
                toastTitle.textContent = 'Gagal';
                toastTitle.className = 'font-semibold text-sm text-red-600 leading-tight';
                toastIcon.className = 'flex items-center justify-center w-10 h-10 rounded-xl bg-red-50 text-red-500';
                toastIcon.innerHTML = '<i class="ph-fill ph-x-circle text-[22px]"></i>';
                toastBg.className = 'bg-white text-gray-800 px-5 py-4 rounded-2xl shadow-[0_10px_30px_rgba(0,0,0,0.08)] flex items-center gap-3.5 border border-red-100/50 min-w-[320px] max-w-[420px]';
            } else {
                toastTitle.textContent = 'Berhasil';
                toastTitle.className = 'font-semibold text-sm text-emerald-600 leading-tight';
                toastIcon.className = 'flex items-center justify-center w-10 h-10 rounded-xl bg-emerald-50 text-emerald-500';
                toastIcon.innerHTML = '<i class="ph-fill ph-check-circle text-[22px]"></i>';
                toastBg.className = 'bg-white text-gray-800 px-5 py-4 rounded-2xl shadow-[0_10px_30px_rgba(0,0,0,0.08)] flex items-center gap-3.5 border border-emerald-100/50 min-w-[320px] max-w-[420px]';
            }

            toast.classList.remove('hidden');
            setTimeout(() => {
                toast.classList.remove('opacity-0', 'translate-y-2');
                toast.classList.add('opacity-100', 'translate-y-0');
            }, 10);

            toastTimeout = setTimeout(closeToast, 4000);
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
            }
        }

        // Trigger toast on page load if session exists
        @if(session('success'))
            document.addEventListener('DOMContentLoaded', function() {
                showToast("{{ session('success') }}", 'success');
            });
        @endif

        @if(session('error'))
            document.addEventListener('DOMContentLoaded', function() {
                showToast("{{ session('error') }}", 'error');
            });
        @endif

        @if(session('failed'))
            document.addEventListener('DOMContentLoaded', function() {
                showToast("{{ session('failed') }}", 'error');
            });
        @endif

        // DELETE MODAL LOGIC
        function openDeleteModal(onConfirm) {
            const modal = document.getElementById('deleteModal');
            const content = document.getElementById('deleteModalContent');
            const confirmBtn = document.getElementById('btnConfirmDelete');

            modal.classList.replace('hidden', 'flex');

            // Trigger animation
            setTimeout(() => {
                content.classList.replace('scale-95', 'scale-100');
                content.classList.replace('opacity-0', 'opacity-100');
            }, 10);

            // Set action
            confirmBtn.onclick = () => {
                onConfirm();
                closeDeleteModal();
            };
        }

        function hapusPetugas(id) {

            // Ambil form delete
            const form = document.getElementById('formDeletePetugas');

            // Set action sesuai ID
            form.action = `/datapetugas/delete/${id}`;

            // Submit form
            form.submit();
        }

        function closeDeleteModal() {
            const modal = document.getElementById('deleteModal');
            const content = document.getElementById('deleteModalContent');

            content.classList.replace('scale-100', 'scale-95');
            content.classList.replace('opacity-100', 'opacity-0');

            setTimeout(() => {
                modal.classList.replace('flex', 'hidden');
            }, 300);
        }

        // LOGOUT MODAL LOGIC
        function openLogoutModal() {
            const modal = document.getElementById('logoutModal');
            const content = document.getElementById('logoutModalContent');
            if (!modal || !content) return;

            modal.classList.replace('hidden', 'flex');
            setTimeout(() => {
                content.classList.replace('scale-95', 'scale-100');
                content.classList.replace('opacity-0', 'opacity-100');
            }, 10);
        }

        function closeLogoutModal() {
            const modal = document.getElementById('logoutModal');
            const content = document.getElementById('logoutModalContent');
            if (!modal || !content) return;

            content.classList.replace('scale-100', 'scale-95');
            content.classList.replace('opacity-100', 'opacity-0');
            setTimeout(() => {
                modal.classList.replace('flex', 'hidden');
            }, 300);
        }

        function confirmLogout() {
            const form = document.getElementById('globalLogoutForm');
            if (form) {
                form.submit();
            }
        }

        // GLOBAL ACTION CONFIRMATION MODAL LOGIC
        function openConfirmModal(options) {
            const modal = document.getElementById('confirmActionModal');
            const content = document.getElementById('confirmActionModalContent');
            const titleEl = document.getElementById('confirmModalTitle');
            const msgEl = document.getElementById('confirmModalMessage');
            const iconContainer = document.getElementById('confirmIconContainer');
            const confirmBtn = document.getElementById('confirmModalBtn');

            if (!modal || !content) return;

            titleEl.textContent = options.title || 'Konfirmasi';
            msgEl.innerHTML = options.message || 'Apakah Anda yakin ingin melanjutkan tindakan ini?';
            confirmBtn.textContent = options.confirmText || 'Ya, Lanjutkan';

            if (options.type === 'danger') {
                iconContainer.className = 'w-20 h-20 rounded-full bg-red-50 flex items-center justify-center mb-6';
                iconContainer.innerHTML = '<i class="ph-fill ph-x-circle text-[48px] text-red-500"></i>';
                confirmBtn.className = 'flex-1 px-6 py-3.5 rounded-xl bg-red-500 text-white font-bold text-[14px] hover:bg-red-600 transition-all shadow-lg shadow-red-500/30';
            } else if (options.type === 'warning') {
                iconContainer.className = 'w-20 h-20 rounded-full bg-amber-50 flex items-center justify-center mb-6';
                iconContainer.innerHTML = '<i class="ph-fill ph-warning-circle text-[48px] text-amber-500"></i>';
                confirmBtn.className = 'flex-1 px-6 py-3.5 rounded-xl bg-amber-500 text-white font-bold text-[14px] hover:bg-amber-600 transition-all shadow-lg shadow-amber-500/30';
            } else {
                iconContainer.className = 'w-20 h-20 rounded-full bg-emerald-50 flex items-center justify-center mb-6';
                iconContainer.innerHTML = '<i class="ph-fill ph-check-circle text-[48px] text-emerald-500"></i>';
                confirmBtn.className = 'flex-1 px-6 py-3.5 rounded-xl bg-emerald-600 text-white font-bold text-[14px] hover:bg-emerald-700 transition-all shadow-lg shadow-emerald-600/30';
            }

            confirmBtn.onclick = function() {
                if (typeof options.onConfirm === 'function') {
                    options.onConfirm();
                }
                closeConfirmModal();
            };

            modal.classList.replace('hidden', 'flex');
            setTimeout(() => {
                content.classList.replace('scale-95', 'scale-100');
                content.classList.replace('opacity-0', 'opacity-100');
            }, 10);
        }

        function closeConfirmModal() {
            const modal = document.getElementById('confirmActionModal');
            const content = document.getElementById('confirmActionModalContent');
            if (!modal || !content) return;

            content.classList.replace('scale-100', 'scale-95');
            content.classList.replace('opacity-100', 'opacity-0');
            setTimeout(() => {
                modal.classList.replace('flex', 'hidden');
            }, 300);
        }

        // GLOBAL LIVE SEARCH & AJAX AUTO-SEARCH FOR SUPERVISOR
        document.addEventListener("DOMContentLoaded", function() {
            let searchTimeout = null;

            function filterTableRowsLocally(input, val) {
                const query = val.toLowerCase().trim();
                const container = input.closest('#viewTabel, #viewTabelData, .shadow-card, div') || document;
                const tbody = container.querySelector('table tbody');
                if (!tbody) return;

                const rows = tbody.querySelectorAll('tr');
                let foundAny = false;

                rows.forEach(row => {
                    const text = row.textContent.toLowerCase();
                    if (!query || text.includes(query)) {
                        row.style.display = '';
                        foundAny = true;
                    } else {
                        row.style.display = 'none';
                    }
                });
            }

            function performSupervisorAjaxSearch(form, searchVal) {
                const actionUrl = form.getAttribute('action') || window.location.pathname;
                const urlParams = new URLSearchParams(window.location.search);
                if (searchVal) {
                    urlParams.set('keyword', searchVal);
                } else {
                    urlParams.delete('keyword');
                }
                urlParams.delete('page'); // Reset ke halaman 1 saat pencarian baru

                const targetUrl = `${actionUrl.split('?')[0]}?${urlParams.toString()}`;
                window.history.replaceState({}, '', targetUrl);

                // Sync all keyword search inputs
                document.querySelectorAll('input[name="keyword"]').forEach(inp => {
                    if (inp.value !== searchVal) {
                        inp.value = searchVal;
                    }
                });

                fetch(targetUrl, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.text())
                .then(html => {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');

                    // Replace table container
                    const newTableCard = doc.querySelector('.overflow-x-auto') || doc.querySelector('table');
                    const oldTableCard = document.querySelector('.overflow-x-auto') || document.querySelector('table');

                    if (newTableCard && oldTableCard) {
                        oldTableCard.innerHTML = newTableCard.innerHTML;
                    }

                    // Replace pagination container if present
                    const newPagination = doc.querySelector('x-pagination, .flex.items-center.justify-end');
                    const oldPagination = document.querySelector('x-pagination, .flex.items-center.justify-end');

                    if (newPagination && oldPagination) {
                        oldPagination.outerHTML = newPagination.outerHTML;
                    }
                })
                .catch(err => console.error('Live search error:', err));
            }

            // Bind to all search inputs (input[name="keyword"])
            document.querySelectorAll('input[name="keyword"]').forEach(input => {
                // Instant client-side filter + debounced server fetch
                input.addEventListener('input', function() {
                    const val = this.value;

                    // 1. Filter tabel secara instan (misal ketik 'kip' langsung memunculkan 'kipli')
                    filterTableRowsLocally(this, val);

                    // 2. Query server otomatis untuk pencarian seluruh database
                    clearTimeout(searchTimeout);
                    searchTimeout = setTimeout(() => {
                        const form = input.closest('form');
                        if (form) {
                            performSupervisorAjaxSearch(form, val);
                        }
                    }, 350);
                });

                // Mencegah submit reload halaman saat tekan Enter
                const form = input.closest('form');
                if (form) {
                    form.addEventListener('submit', function(e) {
                        e.preventDefault();
                        clearTimeout(searchTimeout);
                        performSupervisorAjaxSearch(form, input.value);
                    });
                }
            });
        });
    </script>
    @yield('scripts')
</body>

</html>
