@extends('layout.app')

@section('title', 'Rekap Absensi | S&D People')

@section('content')
<div class="min-h-full space-y-6 text-[#26352D]">

    {{-- NOTIFIKASI --}}
    @if (session('success'))
        <div class="rounded-xl border border-green-200 bg-green-50 px-5 py-4 text-sm font-semibold text-green-800">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="rounded-xl border border-red-200 bg-red-50 px-5 py-4 text-sm font-semibold text-red-700">
            {{ session('error') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="rounded-xl border border-red-200 bg-red-50 px-5 py-4 text-sm text-red-700">
            <p class="mb-2 font-bold">Data belum bisa diproses:</p>
            <ul class="list-inside list-disc space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- HEADER --}}
    <section class="flex flex-col gap-5 rounded-3xl border border-[#D7DED5] bg-[#F7F5EC] p-6 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="text-xs font-bold uppercase tracking-[0.18em] text-[#68776A]">
                S&D People / Human Resources
            </p>
            <h1 class="mt-2 text-3xl font-extrabold tracking-tight text-[#26352D]">
                Rekap Absensi
            </h1>
            <p class="mt-2 max-w-xl text-sm leading-6 text-[#68776A]">
                Pantau dan kelola ringkasan kehadiran seluruh pegawai dalam satu tempat.
            </p>
        </div>

        <div class="flex flex-wrap gap-3">

            {{-- IMPORT EXCEL --}}
            <form action="{{ route('rekap.import') }}"
                  method="POST"
                  enctype="multipart/form-data">
                @csrf

                <label class="inline-flex cursor-pointer items-center gap-2 rounded-xl border border-[#CBD4C9] bg-white px-4 py-3 text-sm font-bold text-[#34483A] transition hover:bg-[#EEF1E8]">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="1.8">
                        <path d="M12 3v12m0 0 4-4m-4 4-4-4M4 17v3h16v-3"
                              stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>

                    Import Excel

                    <input type="file"
                           name="file"
                           accept=".xlsx,.xls,.csv"
                           class="hidden"
                           required
                           onchange="if(this.files.length){this.form.submit();}">
                </label>
            </form>

            {{-- EXPORT EXCEL --}}
            <a href="{{ route('rekap.export', request()->query()) }}"
               class="inline-flex items-center gap-2 rounded-xl bg-[#E3C46D] px-4 py-3 text-sm font-extrabold text-[#26352D] transition hover:bg-[#D9B856]">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="1.8">
                    <path d="M12 3v12m0 0 4-4m-4 4-4-4M4 17v3h16v-3"
                          stroke-linecap="round" stroke-linejoin="round"/>
                </svg>

                Export Rekap
            </a>
        </div>
    </section>

    {{-- FILTER --}}
    <section class="rounded-2xl border border-[#D9E0D6] bg-white p-5 sm:p-6">
        <div class="mb-5">
            <h2 class="text-lg font-extrabold text-[#26352D]">
                Filter Rekap
            </h2>
            <p class="mt-1 text-sm text-[#68776A]">
                Persempit data berdasarkan periode atau nama pegawai.
            </p>
        </div>

        <form action="{{ route('rekap.index') }}"
              method="GET"
              class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-3">

            <div>
                <label for="tanggal_mulai"
                       class="mb-2 block text-sm font-bold text-[#3D4B40]">
                    Dari Tanggal
                </label>

                <input type="date"
                       id="tanggal_mulai"
                       name="tanggal_mulai"
                       value="{{ request('tanggal_mulai') }}"
                       class="w-full rounded-xl border border-[#D2DAD0] bg-[#FAFBF8] px-3 py-3 text-sm text-[#26352D] outline-none transition focus:border-[#A88B37] focus:ring-2 focus:ring-[#E3C46D]/30">
            </div>

            <div>
                <label for="tanggal_selesai"
                       class="mb-2 block text-sm font-bold text-[#3D4B40]">
                    Sampai Tanggal
                </label>

                <input type="date"
                       id="tanggal_selesai"
                       name="tanggal_selesai"
                       value="{{ request('tanggal_selesai') }}"
                       class="w-full rounded-xl border border-[#D2DAD0] bg-[#FAFBF8] px-3 py-3 text-sm text-[#26352D] outline-none transition focus:border-[#A88B37] focus:ring-2 focus:ring-[#E3C46D]/30">
            </div>

            <div>
                <label for="pegawai"
                       class="mb-2 block text-sm font-bold text-[#3D4B40]">
                    Nama Pegawai
                </label>

                <input type="text"
                       id="pegawai"
                       name="pegawai"
                       value="{{ request('pegawai') }}"
                       placeholder="Cari nama pegawai..."
                       class="w-full rounded-xl border border-[#D2DAD0] bg-[#FAFBF8] px-3 py-3 text-sm text-[#26352D] outline-none placeholder:text-[#89948A] transition focus:border-[#A88B37] focus:ring-2 focus:ring-[#E3C46D]/30">
            </div>

            <div class="flex flex-wrap gap-3 md:col-span-2 xl:col-span-3">
                <button type="submit"
                        class="rounded-xl bg-[#304536] px-5 py-3 text-sm font-bold text-white transition hover:bg-[#243629]">
                    Terapkan Filter
                </button>

                <a href="{{ route('rekap.index') }}"
                   class="rounded-xl border border-[#D2DAD0] bg-white px-5 py-3 text-sm font-bold text-[#425246] transition hover:bg-[#F1F4EE]">
                    Reset
                </a>
            </div>
        </form>
    </section>

    {{-- TABEL REKAP --}}
    <section class="overflow-hidden rounded-2xl border border-[#D9E0D6] bg-white">

        <div class="flex flex-col gap-3 border-b border-[#E5E9E2] px-5 py-5 sm:flex-row sm:items-center sm:justify-between sm:px-6">
            <div>
                <h2 class="text-lg font-extrabold text-[#26352D]">
                    Data Rekap Kehadiran
                </h2>
                <p class="mt-1 text-sm text-[#68776A]">
                    Rincian jam masuk, jam pulang, dan status kehadiran.
                </p>
            </div>

            <span class="inline-flex w-fit items-center rounded-full border border-[#D7DED5] bg-[#F0F3ED] px-3 py-1.5 text-xs font-bold text-[#536154]">
                Data Database
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full min-w-[950px] text-left text-sm">

                <thead class="bg-[#F0F3ED] text-xs uppercase tracking-wider text-[#536154]">
                    <tr>
                        <th class="px-5 py-4 font-extrabold">ID Pegawai</th>
                        <th class="px-5 py-4 font-extrabold">Nama Pegawai</th>
                        <th class="px-5 py-4 font-extrabold">Hari</th>
                        <th class="px-5 py-4 font-extrabold">Tanggal</th>
                        <th class="px-5 py-4 font-extrabold">Jam Masuk</th>
                        <th class="px-5 py-4 font-extrabold">Jam Pulang</th>
                        <th class="px-5 py-4 font-extrabold">Keterangan</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-[#EDF0EA] text-[#344238]">
                    @forelse ($rekapAbsensis as $rekap)
                        <tr class="transition hover:bg-[#FAFBF8]">

                            <td class="whitespace-nowrap px-5 py-4 font-bold text-[#80651A]">
                                {{ $rekap->id_pegawai }}
                            </td>

                            <td class="whitespace-nowrap px-5 py-4 font-semibold">
                                {{ $rekap->nama_pegawai }}
                            </td>

                            <td class="whitespace-nowrap px-5 py-4">
                                {{ $rekap->hari ?? '-' }}
                            </td>

                            <td class="whitespace-nowrap px-5 py-4">
                                {{ $rekap->tanggal?->format('d M Y') ?? '-' }}
                            </td>

                            <td class="whitespace-nowrap px-5 py-4">
                                {{ $rekap->jam_masuk ? substr((string) $rekap->jam_masuk, 0, 5) : '-' }}
                            </td>

                            <td class="whitespace-nowrap px-5 py-4">
                                {{ $rekap->jam_pulang ? substr((string) $rekap->jam_pulang, 0, 5) : '-' }}
                            </td>

                            <td class="whitespace-nowrap px-5 py-4">
                                {{ $rekap->keterangan ?: '-' }}
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="8"
                                class="px-5 py-12 text-center text-sm text-[#758176]">
                                <div class="font-bold text-[#536154]">
                                    Belum ada data absensi
                                </div>
                                <p class="mt-1">
                                    Import file Excel untuk menampilkan data kehadiran di sini.
                                </p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- PAGINATION --}}
        <div class="flex flex-col gap-3 border-t border-[#E5E9E2] px-5 py-4 sm:flex-row sm:items-center sm:justify-between sm:px-6">
            <p class="text-xs font-medium text-[#758176]">
                Menampilkan {{ $rekapAbsensis->firstItem() ?? 0 }}
                sampai {{ $rekapAbsensis->lastItem() ?? 0 }}
                dari {{ $rekapAbsensis->total() }} data absensi.
            </p>

            <div>
                {{ $rekapAbsensis->links() }}
            </div>
        </div>

    </section>
</div>
@endsection