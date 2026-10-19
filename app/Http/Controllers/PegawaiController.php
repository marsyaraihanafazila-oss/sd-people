<?php

namespace App\Http\Controllers;

use App\Models\Divisi;
use App\Models\Pegawai;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PegawaiController extends Controller
{
    /**
     * Menampilkan daftar pegawai, pencarian, filter,
     * serta statistik pegawai dan divisi.
     */
    
    public function index(Request $request)
    {
        $query = Pegawai::with(['divisi', 'divisiKedua']);

        // Pencarian berdasarkan kode atau nama pegawai
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('kode_pegawai', 'like', "%{$search}%")
                ->orWhere('nama_lengkap', 'like', "%{$search}%");
            });
        }

        // Filter berdasarkan Divisi 1 atau Divisi 2
        if ($request->filled('divisi_id')) {
            $divisiId = $request->divisi_id;

            $query->where(function ($q) use ($divisiId) {
                $q->where('divisi_id', $divisiId)
                ->orWhere('divisi_kedua_id', $divisiId);
            });
        }

        $pegawais = $query
            ->orderBy('nama_lengkap')
            ->paginate(10)
            ->withQueryString();

        $divisis = Divisi::orderBy('nama_divisi')->get();

        $totalPegawai = Pegawai::count();
        $totalDivisi = Divisi::count();

        return view('pegawai.index', compact(
            'pegawais',
            'divisis',
            'totalPegawai',
            'totalDivisi'
        ));
    }
    /**
     * Menyimpan pegawai baru.
     */
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_pegawai' => [
                'required',
                'string',
                'max:50',
                'unique:pegawais,kode_pegawai',
            ],
            'nama_lengkap' => [
                'required',
                'string',
                'max:255',
            ],
            'divisi_id' => [
                'required',
                'integer',
                'exists:divisis,id',
            ],
            'divisi_kedua_id' => [
                'nullable',
                'integer',
                'different:divisi_id',
                'exists:divisis,id',
            ],
            'aktif' => [
                'required',
                'boolean',
            ],
        ]);

        Pegawai::create($validated);

        return redirect()
            ->route('pegawai.index')
            ->with('success', 'Pegawai berhasil ditambahkan.');
    }

    /**
     * Menampilkan halaman edit pegawai.
     */
    public function edit(Pegawai $pegawai)
    {
        $divisis = Divisi::orderBy('nama_divisi')->get();

        return view('pegawai.edit', compact(
            'pegawai',
            'divisis'
        ));
    }

    /**
     * Memperbarui data pegawai.
     */
    public function update(Request $request, Pegawai $pegawai)
    {
        $validated = $request->validate([
            'kode_pegawai' => [
                'required',
                'string',
                'max:50',
                Rule::unique('pegawais', 'kode_pegawai')
                    ->ignore($pegawai->id),
            ],
            'nama_lengkap' => [
                'required',
                'string',
                'max:255',
                ],
                'divisi_id' => [
                    'required',
                    'integer',
                    'exists:divisis,id',
                    ],
                    'divisi_kedua_id' => [
                    'nullable',
                    'integer',
                    'different:divisi_id',
                    'exists:divisis,id',
                    ],
            'aktif' => [
                'required',
                'boolean',
            ],
        ]);

        $pegawai->update($validated);

        return redirect()
            ->route('pegawai.index')
            ->with('success', 'Data pegawai berhasil diperbarui.');
    }

    /**
     * Menambahkan divisi baru.
     */
    public function storeDivisi(Request $request)
    {
        $validated = $request->validate([
            'nama_divisi' => [
                'required',
                'string',
                'max:255',
                'unique:divisis,nama_divisi',
            ],
        ], [
            'nama_divisi.required' => 'Nama divisi wajib diisi.',
            'nama_divisi.unique' => 'Nama divisi sudah terdaftar.',
        ]);

        Divisi::create($validated);

        return redirect()
            ->route('pegawai.index')
            ->with('success', 'Divisi berhasil ditambahkan.');
    }

    /**
     * Menghapus pegawai.
     */
    public function destroy(Pegawai $pegawai)
    {
        $pegawai->delete();

        return redirect()
            ->route('pegawai.index')
            ->with('success', 'Pegawai berhasil dihapus.');
    }
}
