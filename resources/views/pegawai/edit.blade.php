
@extends('layout.app')

@section('title', 'Edit Pegawai | S&D People')

@section('content')
<div class="max-w-3xl mx-auto px-4 py-8 md:px-6">
    <div class="bg-white/80 border border-white rounded-3xl p-6 md:p-8 shadow-sm">
        <h2 class="text-3xl font-black text-[#2C352E]">Edit Pegawai</h2>
        <p class="text-[#82776F] mt-2 mb-8">Perbarui informasi pegawai.</p>

        <form action="{{ route('pegawai.update', $pegawai->id) }}" method="POST" class="space-y-5">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-semibold mb-2">Kode Pegawai</label>
                <input type="text" name="kode_pegawai"
                       value="{{ old('kode_pegawai', $pegawai->kode_pegawai) }}"
                       required class="w-full border border-[#EAE4DA] rounded-xl px-4 py-3">
                @error('kode_pegawai')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold mb-2">Nama Lengkap</label>
                <input type="text" name="nama_lengkap"
                       value="{{ old('nama_lengkap', $pegawai->nama_lengkap) }}"
                       required class="w-full border border-[#EAE4DA] rounded-xl px-4 py-3">
                @error('nama_lengkap')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold mb-2">Divisi</label>
                <select name="divisi_id" required class="w-full border border-[#EAE4DA] rounded-xl px-4 py-3 bg-white">
                    @foreach ($divisis as $divisi)
                        <option value="{{ $divisi->id }}"
                            @selected(old('divisi_id', $pegawai->divisi_id) == $divisi->id)>
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
                            @selected(old(
                                'divisi_kedua_id',
                                $pegawai->divisi_kedua_id
                            ) == $divisi->id)
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
                <select name="aktif" required class="w-full border border-[#EAE4DA] rounded-xl px-4 py-3 bg-white">
                    <option value="1" @selected(old('aktif', (string) (int) $pegawai->aktif) == '1')>Aktif</option>
                    <option value="0" @selected(old('aktif', (string) (int) $pegawai->aktif) == '0')>Nonaktif</option>
                </select>
                @error('aktif')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end gap-3 pt-4">
                <a href="{{ route('pegawai.index') }}"
                   class="px-5 py-3 rounded-xl bg-gray-100 text-gray-700 font-bold">
                    Batal
                </a>
                <button type="submit"
                        class="px-5 py-3 rounded-xl bg-[#2C1E17] hover:bg-[#493329] text-white font-bold">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
