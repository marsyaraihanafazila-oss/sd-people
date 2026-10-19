<?php

namespace App\Http\Controllers;

use App\Imports\RekapAbsensiImport;
use App\Models\Divisi;
use App\Models\RekapAbsensi;
use App\Exports\RekapAbsensiExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class RekapAbsensiController extends Controller
{
   
    public function index(Request $request)
    {
        $query = RekapAbsensi::query();

        if ($request->filled('tanggal_mulai')) {
            $query->whereDate('tanggal', '>=', $request->tanggal_mulai);
        }

        if ($request->filled('tanggal_selesai')) {
            $query->whereDate('tanggal', '<=', $request->tanggal_selesai);
        }

        if ($request->filled('pegawai')) {
            $query->where(
                'nama_pegawai',
                'like',
                '%' . $request->pegawai . '%'
            );
        }

        // Urutkan nama A-Z, lalu tanggal terbaru untuk tiap pegawai.
        $rekapAbsensis = $query
            ->orderBy('nama_pegawai', 'asc')
            ->orderBy('tanggal', 'desc')
            ->paginate(20)
            ->withQueryString();

        $divisis = Divisi::orderBy('nama_divisi')->get();

        return view('rekap.index', compact(
            'rekapAbsensis',
            'divisis'
        ));
    }


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
                ->with('error', 'Import gagal: ' . $e->getMessage());
        }
    }
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
