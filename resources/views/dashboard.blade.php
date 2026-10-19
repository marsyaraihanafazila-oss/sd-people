@extends('layout.app')

@section('title', 'Overview | S&D People')

@section('content')

<main class="max-w-7xl mx-auto px-4 py-8 md:px-6 md:py-10">

    {{-- HEADER --}}
    <section class="mb-9">
        <div class="flex flex-col sm:flex-row
                    sm:items-center sm:justify-between gap-4">

            <div>
                <span class="inline-flex items-center gap-2
                             bg-[#F5E6C5] text-[#79591E]
                             px-3 py-1.5 rounded-full
                             text-xs font-bold mb-4">
                    <span class="w-2 h-2 rounded-full bg-[#B88A2E]"></span>
                    PEOPLE & CULTURE
                </span>

                <h2 class="text-3xl md:text-4xl font-black
                           tracking-tight text-[#2C352E]">
                    Hey, HR S&D!
                </h2>

                <p class="text-[#82776F] mt-3">
                    Your people, your progress.
                    Semua informasi tim dalam satu ruang.
                </p>
            </div>

            <div class="bg-white/80 border border-white
                        rounded-2xl px-5 py-4 shadow-sm">
                <p class="text-xs font-bold uppercase
                          tracking-wider text-[#82776F]">
                    Hari ini
                </p>

                <p class="text-sm font-bold text-[#2C352E] mt-2">
                    {{ $tanggalHariIni }}
                </p>
            </div>

        </div>
    </section>


    {{-- STATISTIK UTAMA --}}
    <section class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-8">        
    {{-- TOTAL PEGAWAI --}}
        <div class="relative overflow-hidden
                    bg-white/80 border border-white
                    rounded-2xl p-6 md:p-7 shadow-sm">

            <div class="flex items-start justify-between gap-4">
                <div>
                    <p class="text-sm font-medium text-[#82776F]">
                        Total Pegawai
                    </p>

                    <h3 class="text-4xl md:text-5xl font-black
                               text-[#2C352E] mt-5">
                        {{ number_format($totalPegawai, 0, ',', '.') }}
                    </h3>

                    <p class="text-sm text-[#82776F] mt-3">
                        Pegawai terdaftar dalam sistem
                    </p>
                </div>

                <div class="bg-[#F5E6C5] rounded-2xl
                            p-3 text-[#79591E]">
                    <svg xmlns="http://www.w3.org/2000/svg"
                         width="27" height="27" viewBox="0 0 24 24"
                         fill="none" stroke="currentColor"
                         stroke-width="1.8" stroke-linecap="round"
                         stroke-linejoin="round">
                        <path d="M16 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                        <circle cx="10" cy="7" r="4"/>
                        <path d="M20 21v-2a4 4 0 0 0-3-3.87"/>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                    </svg>
                </div>
            </div>

            <div class="absolute -right-8 -bottom-10
                        w-32 h-32 rounded-full
                        bg-[#F5E6C5]/40"></div>
        </div>


        {{-- TOTAL DIVISI --}}
        <div class="relative overflow-hidden
                    bg-[#E8EDE8] border border-[#D8E0D8]
                    rounded-2xl p-6 md:p-7">

            <div class="flex items-start justify-between gap-4">
                <div>
                    <p class="text-sm font-medium text-[#506854]">
                        Total Divisi
                    </p>

                    <h3 class="text-4xl md:text-5xl font-black
                               text-[#35573B] mt-5">
                        {{ number_format($totalDivisi, 0, ',', '.') }}
                    </h3>

                    <p class="text-sm text-[#506854] mt-3">
                        Divisi terdaftar dalam sistem
                    </p>
                </div>

                <div class="bg-white/80 rounded-2xl
                            p-3 text-[#35573B]">
                    <svg xmlns="http://www.w3.org/2000/svg"
                         width="27" height="27" viewBox="0 0 24 24"
                         fill="none" stroke="currentColor"
                         stroke-width="1.8" stroke-linecap="round"
                         stroke-linejoin="round">
                        <rect x="3" y="7" width="18" height="14" rx="2"/>
                        <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/>
                    </svg>
                </div>
            </div>

            <div class="absolute -right-8 -bottom-10
                        w-32 h-32 rounded-full
                        bg-white/40"></div>
        </div>

    </section>


    {{-- RINGKASAN BULANAN --}}
    <section class="relative overflow-hidden
                    bg-[#2C352E] rounded-3xl
                    p-6 md:p-8 text-white">

        <div class="absolute -right-12 -top-16
                    w-64 h-64 rounded-full
                    border border-white/10"></div>

        <div class="absolute -right-2 -top-8
                    w-44 h-44 rounded-full
                    border border-white/10"></div>

        <div class="relative z-10
                    flex flex-col md:flex-row
                    md:items-center md:justify-between gap-6">

            <div class="max-w-xl">
                <span class="inline-flex items-center
                             bg-white/10 text-[#E8D08A]
                             px-3 py-1.5 rounded-full
                             text-xs font-bold tracking-wider">
                    MONTHLY OVERVIEW
                </span>

                <h3 class="text-2xl md:text-3xl
                           font-black mt-4">
                    Ringkasan Bulanan
                </h3>

                <p class="text-white/65 text-sm mt-3 leading-relaxed">
                    Periode laporan yang sedang berjalan.
                    Ringkasan kehadiran akan tersedia setelah
                    modul absensi dan rekap terhubung.
                </p>
            </div>

            <div class="bg-white/10 border border-white/10
                        rounded-2xl p-5 min-w-48">

                <p class="text-xs text-white/60
                          uppercase tracking-wider font-bold">
                    Periode saat ini
                </p>

                <p class="text-xl font-bold mt-3">
                    {{ $bulanIni }}
                </p>

                <div class="flex items-center gap-2 mt-4">
                    <span class="w-2 h-2 rounded-full
                                 bg-[#E8D08A]"></span>
                    <span class="text-xs text-white/70">
                        Menunggu data rekap
                    </span>
                </div>

            </div>

        </div>
    </section>


    {{-- FOOTER --}}
    <footer class="text-center text-xs
                   text-[#A69B91] mt-8">
        S&D People · Employee Management System
    </footer>

</main>

@endsection