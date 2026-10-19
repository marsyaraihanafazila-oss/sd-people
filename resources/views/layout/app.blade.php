
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'S&D People')</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        * {
            box-sizing: border-box;
        }

        html {
            -webkit-text-size-adjust: 100%;
        }

        body {
            font-family: Arial, sans-serif;
            background: #E8EDF0;
            overflow-x: hidden;
        }

        .glass-sidebar {
            background: rgba(43, 48, 45, 0.88);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border: 1px solid rgba(255, 255, 255, 0.16);
            box-shadow: 0 20px 50px rgba(37, 44, 40, 0.12);
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.72);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.8);
        }

        #mobileSidebar,
        #mobileOverlay {
            transition: transform .25s ease, opacity .25s ease;
        }

        @media (max-width: 767px) {
            input,
            select,
            textarea {
                min-width: 0;
                max-width: 100%;
            }

            table {
                min-width: 650px;
            }
        }
    </style>
</head>

<body class="min-h-screen text-[#29372F] bg-[#E8EDF0]">

    <!-- MOBILE HEADER: hanya tampil di HP -->
    <header class="md:hidden sticky top-0 z-30 p-3">
        <div class="flex items-center justify-between gap-3
                    rounded-2xl border border-white/80
                    bg-white/80 backdrop-blur-xl p-3 shadow-sm">

            <a href="/" class="flex items-center gap-3 min-w-0">
                <div class="w-10 h-10 shrink-0 rounded-xl bg-[#E6C66E]
                            flex items-center justify-center font-black">
                    S&D
                </div>

                <div class="min-w-0">
                    <p class="font-bold text-[#29372F]">S&D People</p>
                    <p class="text-xs text-[#82776F]">People Management</p>
                </div>
            </a>

            <button
                id="mobileMenuButton"
                type="button"
                aria-label="Buka navigasi"
                aria-controls="mobileSidebar"
                aria-expanded="false"
                class="w-11 h-11 shrink-0 rounded-xl bg-[#2C352E]
                       text-white flex items-center justify-center"
            >
                <svg viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2"
                     class="w-6 h-6">
                    <path d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
        </div>
    </header>

    <!-- OVERLAY MOBILE -->
    <div id="mobileOverlay"
         class="hidden fixed inset-0 z-40 bg-black/40 md:hidden">
    </div>

    <div class="min-h-screen md:flex">

        <!-- FLOATING GLASS SIDEBAR -->
        <aside id="mobileSidebar"
            class="fixed inset-y-0 left-0 z-50
                   w-[280px] max-w-[85vw] p-4
                   -translate-x-full overflow-y-auto
                   md:relative md:translate-x-0
                   md:w-[260px] md:max-w-none md:shrink-0">

            <div class="glass-sidebar sticky top-4
                        min-h-[calc(100vh-32px)]
                        rounded-[28px] p-5 flex flex-col">

                <!-- BRAND -->
                <div class="flex items-center justify-between gap-2
                            px-2 py-3 mb-8">
                    <a href="/" class="flex items-center gap-3 min-w-0">
                        <div class="w-11 h-11 shrink-0 rounded-2xl
                                    bg-[#E6C66E] flex items-center
                                    justify-center text-[#29372F] font-black text-sm">
                            S&D
                        </div>

                        <div>
                            <h1 class="text-lg font-bold text-white">
                                S&D <span class="text-[#E6C66E]">People</span>
                            </h1>
                            <p class="text-[11px] text-white/60">
                                People Management
                            </p>
                        </div>
                    </a>

                    <button id="mobileCloseButton"
                        type="button"
                        aria-label="Tutup navigasi"
                        class="md:hidden text-white text-2xl px-2">
                        &times;
                    </button>
                </div>

                <p class="text-[10px] font-bold tracking-[0.2em]
                          text-white/40 px-3 mb-3">
                    MAIN MENU
                </p>

                <nav class="flex flex-col gap-2">

                  <a href="{{ route('dashboard') }}"
                    class="flex items-center gap-3 rounded-2xl px-3 py-3 transition
                    {{ request()->routeIs('dashboard')
                            ? 'bg-[#E6C66E] text-[#29372F]'
                            : 'text-white/75 hover:bg-white/10 hover:text-white' }}">
                        <span class="w-9 h-9 shrink-0 rounded-xl
                                     bg-white/50 flex items-center justify-center">
                            <svg viewBox="0 0 24 24" fill="none"
                                 stroke="currentColor" stroke-width="1.8"
                                 class="w-5 h-5">
                                <rect x="3" y="3" width="7" height="7" rx="2"/>
                                <rect x="14" y="3" width="7" height="7" rx="2"/>
                                <rect x="3" y="14" width="7" height="7" rx="2"/>
                                <rect x="14" y="14" width="7" height="7" rx="2"/>
                            </svg>
                        </span>
                        <span class="text-sm font-bold">Overview</span>
                    </a>

                    <a href="{{ route('pegawai.index') }}"
                    class="flex items-center gap-3 rounded-2xl px-3 py-3 transition
                    {{ request()->routeIs('pegawai.*')
                            ? 'bg-[#E6C66E] text-[#29372F]'
                            : 'text-white/75 hover:bg-white/10 hover:text-white' }}">
                        <span class="w-9 h-9 shrink-0 rounded-xl bg-white/10
                                     flex items-center justify-center">
                            <svg viewBox="0 0 24 24" fill="none"
                                 stroke="currentColor" stroke-width="1.8"
                                 class="w-5 h-5">
                                <circle cx="9" cy="8" r="3"/>
                                <path d="M3.5 19a5.5 5.5 0 0 1 11 0"/>
                                <path d="M16 5.5a3 3 0 0 1 0 5.8M17 14a5 5 0 0 1 3.5 4.8"/>
                            </svg>
                        </span>
                        <span class="text-sm font-medium">Data Pegawai</span>
                    </a>

                   <a href="{{ route('rekap.index') }}"
                    class="flex items-center gap-3 rounded-2xl px-3 py-3 transition
                    {{ request()->routeIs('rekap.*')
                            ? 'bg-[#E6C66E] text-[#29372F]'
                            : 'text-white/75 hover:bg-white/10 hover:text-white' }}">
                        <span class="w-9 h-9 shrink-0 rounded-xl bg-white/10
                                     flex items-center justify-center">
                            <svg viewBox="0 0 24 24" fill="none"
                                 stroke="currentColor" stroke-width="1.8"
                                 class="w-5 h-5">
                                <path d="M4 19V5M4 19h17"/>
                                <rect x="7" y="11" width="3" height="5" rx="1"/>
                                <rect x="13" y="7" width="3" height="9" rx="1"/>
                                <rect x="19" y="3" width="2" height="13" rx="1"/>
                            </svg>
                        </span>
                        <span class="text-sm font-medium">Rekap Laporan</span>
                    </a>

                </nav>

                <!-- SIDEBAR FOOTER -->
                <div class="mt-auto pt-6">
                    <div class="rounded-2xl border border-white/10 bg-white/5 p-3">
                        <p class="text-xs font-semibold text-white">S&D Workspace</p>
                        <p class="text-[11px] text-white/50 mt-1">
                            Employee Attendance
                        </p>
                    </div>

                    <p class="text-[10px] text-white/30 text-center mt-4">
                        S&D People · 2026
                    </p>
                </div>
            </div>
        </aside>

        <!-- MAIN CONTENT -->
        <main class="flex-1 min-w-0 w-full p-3 sm:p-4 md:p-6 lg:p-8">
            <div class="min-h-[calc(100vh-32px)]
                        rounded-2xl md:rounded-[28px]
                        border border-white/70 bg-white/30
                        backdrop-blur-xl
                        shadow-[0_12px_40px_rgba(41,55,47,0.06)]
                        p-3 sm:p-5 md:p-8">
                @yield('content')
            </div>
        </main>

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const sidebar = document.getElementById('mobileSidebar');
            const overlay = document.getElementById('mobileOverlay');
            const openButton = document.getElementById('mobileMenuButton');
            const closeButton = document.getElementById('mobileCloseButton');

            function openMenu() {
                sidebar.classList.remove('-translate-x-full');
                overlay.classList.remove('hidden');
                document.body.classList.add('overflow-hidden');
                openButton.setAttribute('aria-expanded', 'true');
            }

            function closeMenu() {
                if (window.innerWidth < 768) {
                    sidebar.classList.add('-translate-x-full');
                }

                overlay.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
                openButton.setAttribute('aria-expanded', 'false');
            }

            openButton.addEventListener('click', openMenu);
            closeButton.addEventListener('click', closeMenu);
            overlay.addEventListener('click', closeMenu);

            document.addEventListener('keydown', function (event) {
                if (event.key === 'Escape') closeMenu();
            });

            sidebar.querySelectorAll('nav a').forEach(function (link) {
                link.addEventListener('click', closeMenu);
            });

            window.addEventListener('resize', function () {
                if (window.innerWidth >= 768) {
                    sidebar.classList.remove('-translate-x-full');
                    overlay.classList.add('hidden');
                    document.body.classList.remove('overflow-hidden');
                    openButton.setAttribute('aria-expanded', 'false');
                } else {
                    sidebar.classList.add('-translate-x-full');
                }
            });
        });
    </script>

</body>
</html>