<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Bank Mini')</title>
    <link rel="icon" href="{{ asset('img/Logo Bank Mini K-one.jpeg') }}" type="image/jpeg">

    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Phosphor Icons -->
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
                    colors: {
                        background: '#f8f9fc',
                        primary: '#15395b',
                        primaryLight: '#436585',
                        brand: {
                            blue: '#1c3a5a',
                            green: '#10a163',
                            gold: '#bd8607',
                            bg: '#f8f9fb',
                            textDark: '#1e293b',
                            textMuted: '#94a3b8',
                            sidebarIcon: '#a1a1aa'
                        },
                        cardLight: '#eef2f6',
                        warningBg: '#fcefc7',
                        textDark: '#1f2937',
                        textGray: '#8892a0',
                        formBorder: '#e5e7eb',
                        btnGreen: '#1aa061',
                        accentYellow: '#c88d22',
                    },

                    backgroundImage: {
                        'primary-gradient': 'linear-gradient(to bottom, #143657, #1E5081, #143657)',
                        'secondary-gradient': 'linear-gradient(to bottom, #E1E9F2, #F9FCFF, #E1E9F2)',
                        'success-gradient': 'linear-gradient(to right, #008959, #1E9F71, #008959)',
                    }
                }
            }
        }
    </script>
    <style>
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        ::-webkit-scrollbar-thumb {
            background: #d1d5db;
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #9ca3af;
        }

        /* Remove number input arrows */
        input[type="number"]::-webkit-inner-spin-button,
        input[type="number"]::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
    </style>
    @yield('styles')
</head>

<body class="bg-background text-textDark flex h-screen overflow-hidden relative">

    <!-- OVERLAY (Mobile) -->
    <div id="sidebarOverlay" class="fixed inset-0 bg-black/50 z-40 hidden lg:hidden" onclick="toggleSidebar()"></div>

    <!-- SIDEBAR -->
    <aside id="sidebar" class="fixed lg:relative inset-y-0 left-0 w-[260px] h-full py-0 lg:py-5 pl-0 lg:pl-5 pr-0 flex-shrink-0 z-50 lg:z-20 transform -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out">
        <div class="bg-white h-full lg:rounded-[24px] shadow-[4px_0_24px_rgba(0,0,0,0.02)] flex flex-col pt-8 pb-8 overflow-hidden relative border-r border-gray-100 lg:border-none">
            <button class="lg:hidden absolute top-4 right-4 text-gray-400 hover:text-textDark" onclick="toggleSidebar()">
                <i class="ph ph-x text-2xl"></i>
            </button>

            <div class="px-8 mb-10 flex items-center gap-3">
                <img src="{{ asset('img/bankmini2.png') }}" alt="Bank Logo" class="w-14 h-14 object-contain">
                <div>
                    <span class="block text-xs text-textGray font-semibold leading-none">Bank Mini</span>
                    <h1 class="font-extrabold text-xl leading-tight tracking-tight text-textDark mt-1">K-One</h1>
                </div>
            </div>

            <div class="px-8 flex-1 overflow-y-auto">
                <p class="text-xs font-semibold text-gray-400 mb-4 tracking-wider">MENU</p>
                <ul class="space-y-1 relative">
                    @php $route = Route::currentRouteName(); @endphp
                    @if ($rekening->status_akun == 'aktif')               
                    <li class="relative">
                        @if($route == 'nasabah.dashboard')
                            <div class="absolute left-[-32px] top-1/2 -translate-y-1/2 w-[5px] h-8 bg-primary rounded-r-lg"></div>
                        @endif
                        <a href="{{ route('nasabah.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all {{ $route == 'nasabah.dashboard' ? 'bg-gray-50 text-textDark font-bold' : 'text-[#a0aab8] font-medium hover:bg-gray-50 hover:text-primary' }}">
                            <img src="{{ asset('img/icon/sidebar/dashboard.png') }}" alt="Dashboard" class="w-5 h-5 {{ $route == 'nasabah.dashboard' ? '' : 'opacity-40' }}">
                            <span class="text-[14px]">Dashboard</span>
                        </a>
                    </li>
                    <li class="relative">
                        @if($route == 'nasabah.transfer')
                            <div class="absolute left-[-32px] top-1/2 -translate-y-1/2 w-[5px] h-8 bg-primary rounded-r-lg"></div>
                        @endif
                        <a href="{{ route('nasabah.transfer') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all {{ $route == 'nasabah.transfer' ? 'bg-gray-50 text-textDark font-bold' : 'text-[#a0aab8] font-medium hover:bg-gray-50 hover:text-primary' }}">
                            <img src="{{ asset('img/icon/sidebar/transfer.png') }}" alt="Transfer" class="w-5 h-5 {{ $route == 'nasabah.transfer' ? '' : 'opacity-40' }}">
                            <span class="text-[14px]">Transfer</span>
                        </a>
                    </li>
                    @else 
                    <li class="relative">
                        @if($route == 'nasabah.dashboard')
                            <div class="absolute left-[-32px] top-1/2 -translate-y-1/2 w-[5px] h-8 bg-primary rounded-r-lg"></div>
                        @endif
                        <a href="{{ route('nasabah.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all {{ $route == 'nasabah.dashboard' ? 'bg-gray-50 text-textDark font-bold' : 'text-[#a0aab8] font-medium hover:bg-gray-50 hover:text-primary' }}">
                            <img src="{{ asset('img/icon/sidebar/dashboard.png') }}" alt="Dashboard" class="w-5 h-5 {{ $route == 'nasabah.dashboard' ? '' : 'opacity-40' }}">
                            <span class="text-[14px]">Dashboard</span>
                        </a>
                    </li>
                    @endif
                    <li class="pt-4 px-4">
                        <form id="globalLogoutForm" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                        <a href="#" onclick="event.preventDefault(); openLogoutModal();" class="flex items-center gap-3 text-red-500 font-medium py-2 hover:text-red-600 transition-colors">
                            <i class="ph ph-sign-out text-[22px]"></i>
                            <span class="text-[14px]">Keluar</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </aside>

    <!-- MAIN CONTENT -->
    <main class="flex-1 flex flex-col h-screen overflow-y-auto w-full">
        <!-- MOBILE TOP BAR -->
        <div class="lg:hidden flex items-center justify-between px-6 py-4 bg-white border-b border-gray-100 sticky top-0 z-30">
            <div class="flex items-center gap-2">
                <img src="{{ asset('img/bankmini2.png') }}" alt="Bank Logo" class="w-9 h-9 object-contain">
                <div class="flex flex-col">
                    <span class="text-[10px] text-textGray font-semibold leading-none">Bank Mini</span>
                    <span class="font-bold text-base tracking-tight text-textDark leading-tight mt-0.5">K-One</span>
                </div>
            </div>
            <button class="p-2 bg-gray-50 rounded-lg text-primary" onclick="toggleSidebar()">
                <i class="ph ph-list text-2xl"></i>
            </button>
        </div>

        <div class="max-w-[1050px] mx-auto w-full flex flex-col h-full mt-2 pb-10 px-6 lg:px-10">
            <!-- HEADER (Desktop) -->
            <header class="flex flex-col md:flex-row justify-between items-start md:items-center py-6 lg:py-8 mb-4 gap-4 md:gap-0 hidden lg:flex">
                <div>
                    <h2 class="text-[22px] md:text-[26px] font-bold text-gray-800 mb-0.5">@yield('header_title', 'Selamat Datang!')</h2>
                    <p class="text-gray-500 text-[12px] md:text-[14px]">@yield('header_subtitle', 'Kelola transaksi nasabah.')</p>
                </div>


                <div class="flex items-center gap-6">
                    @if ($rekening->status_akun == 'aktif')             
                    <div class="flex items-center gap-2 bg-[#d1f4e0] text-[#0d7d42] px-4 py-2 rounded-full text-sm font-semibold shadow-sm">
                        <i class="ph-fill ph-check-circle text-[16px]"></i>
                        Rekening Aktif
                    </div>
                    @else
                    <div class="flex items-center gap-2 bg-[#f4d1d1] text-[#7d0d0d] px-4 py-2 rounded-full text-sm font-semibold shadow-sm">
                        <i class="ph-fill ph-check-circle text-[16px]"></i>
                        Rekening Non-aktif
                    </div>
                    @endif
                    <div class="flex items-center gap-3">
                        <i class="ph-fill ph-user-circle text-[40px] text-primary"></i>
                        <div class="text-left">

                            <p class="text-sm font-bold text-textDark leading-tight">{{ $nasabah->nama_nasabah }}</p>

                            <p class="text-xs text-textGray mt-0.5">{{ $user->email }}</p>
                        </div>
                    </div>
                </div>
            </header>

            <!-- CONTENT AREA -->
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

    @yield('modals')

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

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            sidebar.classList.toggle('-translate-x-full');
            overlay.classList.toggle('hidden');
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
    </script>
    @yield('scripts')
</body>

</html>