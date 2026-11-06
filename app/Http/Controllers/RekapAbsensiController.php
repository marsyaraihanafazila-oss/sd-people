<?php

namespace App\Http\Controllers;

use App\Imports\RekapAbsensiImport;
use App\Models\Divisi;
use App\Models\RekapAbsensi;
use App\Exports\RekapAbsensiExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;

class RekapAbsensiController extends Controller
{
    /**
     * Query filter yang dipakai bersama oleh halaman rekap
     * dan fitur hapus hasil filter.
     */
    private function filteredQuery(Request $request)
    {
        $query = RekapAbsensi::query();

        if ($request->filled('tanggal_mulai')) {
            $query->whereDate(
                'tanggal',
                '>=',
                $request->tanggal_mulai
            );
        }

        if ($request->filled('tanggal_selesai')) {
            $query->whereDate(
                'tanggal',
                '<=',
                $request->tanggal_selesai
            );
        }

        if ($request->filled('pegawai')) {
            $query->where(
                'nama_pegawai',
                'like',
                '%' . $request->pegawai . '%'
            );
        }

        return $query;
    }

    /**
     * Menampilkan halaman rekap absensi.
     */
    public function index(Request $request)
    {
        $query = $this->filteredQuery($request);

        $jumlahHasilFilter = (clone $query)->count();

        $rekapAbsensis = $query
            ->orderBy('nama_pegawai', 'asc')
            ->orderBy('tanggal', 'desc')
            ->paginate(20)
            ->withQueryString();

        $divisis = Divisi::orderBy('nama_divisi')->get();

        return view('rekap.index', compact(
            'rekapAbsensis',
            'divisis',
            'jumlahHasilFilter'
        ));
    }

    /**
     * Menampilkan form edit satu data absensi.
     */
    public function edit($id)
    {
        $rekap = RekapAbsensi::findOrFail($id);

        return view('rekap.edit', compact('rekap'));
    }

    /**
     * Menyimpan perubahan data absensi.
     */
    public function update(Request $request, $id)
    {
        $rekap = RekapAbsensi::findOrFail($id);

        $validated = $request->validate([
            'tanggal' => [
                'required',
                'date',
            ],
            'jam_masuk' => [
                'nullable',
                'date_format:H:i',
            ],
            'jam_pulang' => [
                'nullable',
                'date_format:H:i',
            ],
            'keterangan' => [
                'nullable',
                'string',
                'max:255',
            ],
        ]);

        // Menentukan nama hari berdasarkan tanggal.
        $namaHari = [
            0 => 'Min',
            1 => 'Sen',
            2 => 'Sel',
            3 => 'Rab',
            4 => 'Kam',
            5 => 'Jum',
            6 => 'Sab',
        ];

        $hari = Carbon::parse($validated['tanggal'])->dayOfWeek;

        $rekap->update([
            'tanggal' => $validated['tanggal'],
            'hari' => $namaHari[$hari],
            'jam_masuk' => $validated['jam_masuk'] ?? null,
            'jam_pulang' => $validated['jam_pulang'] ?? null,
            'keterangan' => $validated['keterangan'] ?? null,
        ]);

        return redirect()
            ->route('rekap.index')
            ->with('success', 'Data absensi berhasil diperbarui.');
    }

    /**
     * Menghapus satu data absensi.
     */
    public function destroy($id)
    {
        $rekap = RekapAbsensi::findOrFail($id);

        $rekap->delete();

        return redirect()
            ->route('rekap.index')
            ->with('success', 'Satu data absensi berhasil dihapus.');
    }

    /**
     * Menghapus seluruh data sesuai filter aktif.
     */
    public function destroyFiltered(Request $request)
    {
        $validated = $request->validate([
            'tanggal_mulai' => [
                'nullable',
                'date',
            ],
            'tanggal_selesai' => [
                'nullable',
                'date',
            ],
            'pegawai' => [
                'nullable',
                'string',
                'max:255',
            ],
        ]);
        if (
            !empty($validated['tanggal_mulai']) &&
            !empty($validated['tanggal_selesai']) &&
            $validated['tanggal_selesai'] < $validated['tanggal_mulai']
        ) {
            return back()
                ->withInput()
                ->with('error', 'Tanggal selesai tidak boleh lebih awal dari tanggal mulai.');
        }

        // Mencegah penghapusan seluruh database jika filter kosong.
        if (
            empty($validated['tanggal_mulai']) &&
            empty($validated['tanggal_selesai']) &&
            empty(trim($validated['pegawai'] ?? ''))
        ) {
            return redirect()
                ->route('rekap.index')
                ->with('error', 'Penghapusan massal harus menggunakan minimal satu filter.');
        }

        $jumlah = 0;

        DB::transaction(function () use ($request, &$jumlah) {
            $query = $this->filteredQuery($request);

            $jumlah = (clone $query)->count();

            if ($jumlah > 0) {
                $query->delete();
            }
        });

        if ($jumlah === 0) {
            return redirect()
                ->route('rekap.index')
                ->with('error', 'Tidak ada data yang sesuai dengan filter.');
        }

        return redirect()
            ->route('rekap.index')
            ->with('success', $jumlah . ' data absensi berhasil dihapus.');
    }

    /**
     * Import Excel.
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => [
                'required',
                'file',
                'mimes:xlsx,xls,csv',
                'max:10240',
            ],
        ]);

        try {
            Excel::import(
                new RekapAbsensiImport(),
                $request->file('file')
            );

            return redirect()
                ->route('rekap.index')
                ->with('success', 'Data absensi berhasil diimpor.');

        } catch (\Throwable $e) {
            report($e);

            return redirect()
                ->route('rekap.index')
                ->with('error', 'Import gagal. Periksa format dan isi file Excel.');
        }
    }

    /**
     * Export Excel sesuai filter.
     */
    public function export(Request $request)
    {
        return Excel::download(
            new RekapAbsensiExport($request->only([
                'tanggal_mulai',
                'tanggal_selesai',
                'pegawai',
            ])),
            'rekap-absensi.xlsx'
        );
    }
}
