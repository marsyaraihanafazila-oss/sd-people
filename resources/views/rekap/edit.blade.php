@extends('layout.app')

@section('content')
<div class="max-w-3xl mx-auto px-6 py-8">

    <div class="mb-6">
        <a href="{{ route('rekap.index') }}"
           class="text-sm text-gray-500 hover:text-gray-800">
            ← Kembali ke Rekap Absensi
        </a>

        <h1 class="text-2xl font-bold text-gray-800 mt-3">
            Edit Data Absensi
        </h1>

        <p class="text-sm text-gray-500 mt-1">
            Perbarui informasi kehadiran pegawai.
        </p>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">

        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-500">
                Nama Pegawai
            </label>
            <div class="mt-1 font-semibold text-gray-800">
                {{ $rekap->nama_pegawai }}
            </div>
        </div>

        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-500">
                ID Pegawai
            </label>
            <div class="mt-1 font-semibold text-gray-800">
                {{ $rekap->id_pegawai }}
            </div>
        </div>

        <form action="{{ route('rekap.update', $rekap->id) }}"
              method="POST">
            @csrf
            @method('PUT')

            <div class="space-y-5">

                <div>
                    <label for="tanggal"
                           class="block text-sm font-medium text-gray-700 mb-2">
                        Tanggal
                    </label>

                    <input type="date"
                           id="tanggal"
                           name="tanggal"
                           value="{{ old('tanggal', $rekap->tanggal?->format('Y-m-d')) }}"
                           required
                           class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:outline-none">

                    @error('tanggal')
                        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                    <div>
                        <label for="jam_masuk"
                               class="block text-sm font-medium text-gray-700 mb-2">
                            Jam Masuk
                        </label>

                        <input type="time"
                               id="jam_masuk"
                               name="jam_masuk"
                               value="{{ old('jam_masuk', $rekap->jam_masuk ? substr($rekap->jam_masuk, 0, 5) : '') }}"
                               class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:outline-none">

                        @error('jam_masuk')
                            <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="jam_pulang"
                               class="block text-sm font-medium text-gray-700 mb-2">
                            Jam Pulang
                        </label>

                        <input type="time"
                               id="jam_pulang"
                               name="jam_pulang"
                               value="{{ old('jam_pulang', $rekap->jam_pulang ? substr($rekap->jam_pulang, 0, 5) : '') }}"
                               class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:outline-none">

                        @error('jam_pulang')
                            <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                </div>

                <div>
                    <label for="keterangan"
                           class="block text-sm font-medium text-gray-700 mb-2">
                        Keterangan
                    </label>

                    <input type="text"
                           id="keterangan"
                           name="keterangan"
                           value="{{ old('keterangan', $rekap->keterangan) }}"
                           placeholder="Contoh: Hadir, Terlambat, Izin..."
                           class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:outline-none">

                    @error('keterangan')
                        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

            </div>

            <div class="flex justify-end gap-3 mt-8">
                <a href="{{ route('rekap.index') }}"
                   class="px-5 py-3 rounded-xl border border-gray-300 text-gray-600 hover:bg-gray-50">
                    Batal
                </a>

                <button type="submit"
                        class="px-5 py-3 rounded-xl bg-indigo-600 text-white font-medium hover:bg-indigo-700">
                    Simpan Perubahan
                </button>
            </div>

        </form>
    </div>
</div>
@endsection
