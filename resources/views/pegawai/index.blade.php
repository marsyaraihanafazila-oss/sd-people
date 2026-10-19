
@extends('layout.app')

@section('title', 'Data Pegawai | S&D People')

@section('content')

<div class="max-w-7xl mx-auto px-4 py-6 md:px-6 md:py-8">
    @if (session('success'))
    <div class="mb-5 rounded-xl border border-[#D8E0D8]
                bg-[#E8EDE8] px-5 py-4 text-sm font-semibold
                text-[#35573B]">
        {{ session('success') }}
    </div>
    @endif

    {{-- HEADER --}}
    <div class="flex flex-col md:flex-row md:items-end
                md:justify-between gap-5 mb-8">
        <div>
            <span class="inline-flex items-center gap-2
                         bg-[#F5E6C5] text-[#79591E]
                         px-3 py-1.5 rounded-full
                         text-xs font-bold mb-4">
                <span class="w-2 h-2 rounded-full bg-[#B88A2E]"></span>
                PEOPLE MANAGEMENT
            </span>

            <h2 class="text-3xl md:text-4xl font-black tracking-tight">
                Data Pegawai
            </h2>

            <p class="text-[#82776F] mt-3">
                Kelola informasi pegawai dan divisi dalam satu tempat.
            </p>
        </div>

        <div class="flex flex-wrap items-center gap-3">
            <button
                type="button"
                onclick="document.getElementById('modalTambahDivisi').classList.remove('hidden')"
                class="bg-[#E8EDE8] hover:bg-[#D8E0D8]
                    text-[#35573B] px-5 py-3 rounded-xl
                    text-sm font-bold transition">
                + Kelola Divisi
            </button>

            <button
                type="button"
                onclick="document.getElementById('modalTambahPegawai').classList.remove('hidden')"
                class="bg-[#2C1E17] hover:bg-[#493329]
                    text-white px-5 py-3 rounded-xl
                    text-sm font-bold transition">
                + Tambah Pegawai
            </button>
        </div>
    </div>
    


    {{-- STATISTIK --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 mb-8">

        {{-- TOTAL PEGAWAI --}}
        <div class="bg-white/80 border border-white rounded-2xl p-6 shadow-sm">
            <p class="text-sm text-[#82776F]">Total Pegawai</p>

            <h3 class="text-4xl font-black mt-4">
                {{ $totalPegawai }}
            </h3>

            <p class="text-sm text-[#82776F] mt-2">
                Pegawai terdaftar
            </p>
        </div>

        {{-- TOTAL DIVISI --}}
        <div class="bg-[#E8EDE8] border border-[#D8E0D8] rounded-2xl p-6">
            <p class="text-sm text-[#506854]">Total Divisi</p>

            <h3 class="text-4xl font-black mt-4 text-[#35573B]">
                {{ $totalDivisi }}
            </h3>

            <p class="text-sm text-[#506854] mt-2">
                Divisi terdaftar
            </p>
        </div>

    </div>


    {{-- TABEL PEGAWAI --}}
    <div class="bg-white/80 border border-white
                rounded-2xl shadow-sm overflow-hidden">

        <div class="p-6 border-b border-[#F0ECE5]">
            <h3 class="text-xl font-bold">Daftar Pegawai</h3>
            <p class="text-sm text-[#82776F] mt-2">
                Cari nama, ID, atau saring berdasarkan divisi.
            </p>


            <form action="{{ route('pegawai.index') }}" method="GET"
                class="grid grid-cols-1 md:grid-cols-2 gap-3 mt-5">

                <input
                    type="search"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Cari nama atau ID pegawai..."
                    class="w-full border border-[#EAE4DA] bg-white
                        rounded-xl px-4 py-3 text-sm
                        focus:outline-none focus:ring-2
                        focus:ring-[#E6C66E]">

                <select
                    name="divisi_id"
                    onchange="this.form.submit()"
                    class="w-full border border-[#EAE4DA] bg-white
                        rounded-xl px-4 py-3 text-sm
                        focus:outline-none focus:ring-2
                        focus:ring-[#E6C66E]">

                    <option value="">Semua Divisi</option>

                    @foreach ($divisis as $divisi)
                        <option value="{{ $divisi->id }}"
                            @selected(request('divisi_id') == $divisi->id)>
                            {{ $divisi->nama_divisi }}
                        </option>
                    @endforeach

                </select>

                <button type="submit"
                        class="md:col-span-2 justify-self-start
                            bg-[#E8D08A] hover:bg-[#DFC16A]
                            text-[#2C352E] px-5 py-2.5
                            rounded-xl text-sm font-bold transition">
                    Cari Pegawai
                </button>

            </form>

        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-[#F9F7F2]">
                    <tr class="text-xs uppercase tracking-wider text-[#82776F]">
                        <th class="px-6 py-4">ID Pegawai</th>
                        <th class="px-6 py-4">Nama Pegawai</th>
                        <th class="px-6 py-4">Divisi</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4">Aksi</th>
                    </tr>
                </thead>

                
               <tbody>
                    @forelse ($pegawais as $pegawai)
                        <tr class="border-t border-[#F0ECE5]">

                            {{-- ID PEGAWAI --}}
                            <td class="px-6 py-5">
                                {{ $pegawai->kode_pegawai }}
                            </td>

                            {{-- NAMA PEGAWAI --}}
                            <td class="px-6 py-5 font-bold">
                                {{ $pegawai->nama_lengkap }}
                            </td>

                            
                            {{-- DIVISI --}}
                            <td class="px-6 py-5">
                                <div class="flex flex-col items-start gap-2">
                                    <span class="inline-flex items-center rounded-full
                                                bg-[#E8EDE8] px-3 py-1.5
                                                text-sm font-semibold text-[#35573B]">
                                        {{ $pegawai->divisi->nama_divisi ?? '-' }}
                                    </span>

                                    @if ($pegawai->divisiKedua)
                                        <span class="inline-flex items-center rounded-full
                                                    bg-[#F5E6C5] px-3 py-1.5
                                                    text-sm font-semibold text-[#79591E]">
                                            {{ $pegawai->divisiKedua->nama_divisi }}
                                        </span>
                                    @endif
                                </div>
                            </td>


                            {{-- STATUS --}}
                            <td class="px-6 py-5">
                                @if ($pegawai->aktif)
                                    <span class="bg-[#E8EDE8] text-[#35573B]
                                                px-3 py-1.5 rounded-full text-xs font-bold">
                                        Aktif
                                    </span>
                                @else
                                    <span class="bg-gray-100 text-gray-600
                                                px-3 py-1.5 rounded-full text-xs font-bold">
                                        Nonaktif
                                    </span>
                                @endif
                            </td>

                            {{-- AKSI: EDIT DAN HAPUS --}}
                            <td class="px-6 py-5">
                                <div class="flex items-center gap-2">

                                    <a href="{{ route('pegawai.edit', $pegawai->id) }}"
                                    class="px-3 py-2 rounded-lg bg-[#E8D08A]
                                            hover:bg-[#DFC16A] text-[#2C352E]
                                            text-xs font-bold transition">
                                        Edit
                                    </a>

                                    <form action="{{ route('pegawai.destroy', $pegawai->id) }}"
                                        method="POST"
                                        onsubmit="return confirm('Yakin ingin menghapus pegawai ini?')">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                                class="px-3 py-2 rounded-lg bg-red-100
                                                    hover:bg-red-200 text-red-700
                                                    text-xs font-bold transition">
                                            Hapus
                                        </button>
                                    </form>

                                </div>
                            </td>

                        </tr>

                    @empty
                        <tr>
                            <td colspan="5"
                                class="px-6 py-8 text-center text-gray-500">
                                Belum ada data pegawai.
                            </td>
                        </tr>
                    @endforelse
                </tbody>

            </table>
        </div>

        <div class="px-6 py-4 bg-[#F9F7F2]text-xs text-[#82776F]">
            Menampilkan {{ $pegawais->firstItem() ?? 0 }}
            – {{ $pegawais->lastItem() ?? 0 }}
            dari {{ $pegawais->total() }} pegawai. 
        </div>       

    </div>

</div>

{{-- MODAL TAMBAH PEGAWAI --}}
<div id="modalTambahPegawai"
     class="hidden fixed inset-0 z-50 flex items-center justify-center
            bg-black/40 backdrop-blur-sm p-4">

    <div class="bg-white rounded-3xl shadow-xl w-full max-w-lg p-6 md:p-8">
        <div class="flex items-start justify-between gap-4 mb-6">
            <div>
                <h3 class="text-2xl font-black text-[#2C352E]">
                    Tambah Pegawai
                </h3>
                <p class="text-sm text-[#82776F] mt-2">
                    Lengkapi informasi pegawai baru.
                </p>
            </div>

            <button type="button"
                    onclick="document.getElementById('modalTambahPegawai').classList.add('hidden')"
                    class="text-gray-500 hover:text-gray-800 text-2xl">
                &times;
            </button>
        </div>

        <form action="{{ route('pegawai.store') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label class="block text-sm font-semibold mb-2">Kode Pegawai</label>
                <input type="text" name="kode_pegawai" required
                       value="{{ old('kode_pegawai') }}"
                       placeholder="Contoh: SD001"
                       class="w-full border border-[#EAE4DA] rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#E6C66E]">
                @error('kode_pegawai')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold mb-2">Nama Lengkap</label>
                <input type="text" name="nama_lengkap" required
                       value="{{ old('nama_lengkap') }}"
                       placeholder="Masukkan nama lengkap"
                       class="w-full border border-[#EAE4DA] rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#E6C66E]">
                @error('nama_lengkap')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold mb-2">Divisi</label>
                <select name="divisi_id" required
                        class="w-full border border-[#EAE4DA] rounded-xl px-4 py-3 bg-white focus:outline-none focus:ring-2 focus:ring-[#E6C66E]">
                    <option value="">Pilih Divisi</option>
                    @foreach ($divisis as $divisi)
                        <option value="{{ $divisi->id }}"
                            @selected(old('divisi_id') == $divisi->id)>
                            {{ $divisi->nama_divisi }}
                        </option>
                    @endforeach
                </select>
                @error('divisi_id')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label for="divisi_kedua_id" class="block font-semibold mb-2">
                    Divisi 2 (Opsional)
                </label>

                <select
                    name="divisi_kedua_id"
                    id="divisi_kedua_id"
                    class="w-full border border-[#EAE4DA] bg-white
                        rounded-xl px-4 py-3"
                >
                    <option value="">Tidak ada divisi kedua</option>

                    @foreach ($divisis as $divisi)
                        <option
                            value="{{ $divisi->id }}"
                            {{ old('divisi_kedua_id') == $divisi->id ? 'selected' : '' }}
                        >
                            {{ $divisi->nama_divisi }}
                        </option>
                    @endforeach
                </select>

                @error('divisi_kedua_id')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold mb-2">Status Pegawai</label>
                <select name="aktif" required
                        class="w-full border border-[#EAE4DA] rounded-xl px-4 py-3 bg-white focus:outline-none focus:ring-2 focus:ring-[#E6C66E]">
                    <option value="1" @selected(old('aktif', '1') == '1')>Aktif</option>
                    <option value="0" @selected(old('aktif') == '0')>Nonaktif</option>
                </select>
                @error('aktif')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end gap-3 pt-3">
                <button type="button"
                        onclick="document.getElementById('modalTambahPegawai').classList.add('hidden')"
                        class="px-5 py-3 rounded-xl bg-gray-100 text-gray-700 font-bold">
                    Batal
                </button>
                <button type="submit"
                        class="px-5 py-3 rounded-xl bg-[#2C1E17] hover:bg-[#493329] text-white font-bold">
                    Simpan Pegawai
                </button>
            </div>
        </form>
    </div>
</div>


{{-- MODAL TAMBAH DIVISI --}}
<div id="modalTambahDivisi"
     class="hidden fixed inset-0 z-50 flex items-center justify-center
            bg-black/40 backdrop-blur-sm p-4">

    <div class="bg-white rounded-3xl shadow-xl w-full max-w-md p-6 md:p-8">
        <div class="flex items-start justify-between gap-4 mb-6">
            <div>
                <h3 class="text-2xl font-black text-[#2C352E]">
                    Tambah Divisi
                </h3>
                <p class="text-sm text-[#82776F] mt-2">
                    Tambahkan divisi baru ke sistem.
                </p>
            </div>

            <button type="button"
                    onclick="document.getElementById('modalTambahDivisi').classList.add('hidden')"
                    class="text-gray-500 hover:text-gray-800 text-2xl">
                &times;
            </button>
        </div>

        <form action="{{ route('divisi.store') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label class="block text-sm font-semibold mb-2">Nama Divisi</label>
                <input type="text" name="nama_divisi" required
                       value="{{ old('nama_divisi') }}"
                       placeholder="Contoh: Human Resources"
                       class="w-full border border-[#EAE4DA] rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#E6C66E]">
                @error('nama_divisi')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="rounded-xl bg-[#F9F7F2] p-4">
                <p class="text-sm text-[#82776F]">
                    Divisi yang berhasil ditambahkan akan tersedia pada pilihan divisi di form pegawai.
                </p>
            </div>

            <div class="flex justify-end gap-3 pt-3">
                <button type="button"
                        onclick="document.getElementById('modalTambahDivisi').classList.add('hidden')"
                        class="px-5 py-3 rounded-xl bg-gray-100 text-gray-700 font-bold">
                    Batal
                </button>
                <button type="submit"
                        class="px-5 py-3 rounded-xl bg-[#35573B] hover:bg-[#29452E] text-white font-bold">
                    Simpan Divisi
                </button>
            </div>
        </form>
    </div>
</div>


@endsection
