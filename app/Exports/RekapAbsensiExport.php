<?php

namespace App\Exports;

use App\Models\RekapAbsensi;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class RekapAbsensiExport implements FromCollection, WithHeadings, WithMapping
{
    public function __construct(
        private array $filters = []
    ) {}

    public function collection()
    {
        return RekapAbsensi::query()
            ->when(
                $this->filters['tanggal_mulai'] ?? null,
                fn ($query, $tanggal) => $query->whereDate('tanggal', '>=', $tanggal)
            )
            ->when(
                $this->filters['tanggal_selesai'] ?? null,
                fn ($query, $tanggal) => $query->whereDate('tanggal', '<=', $tanggal)
            )
            ->when(
                $this->filters['pegawai'] ?? null,
                fn ($query, $nama) => $query->where('nama_pegawai', 'like', "%{$nama}%")
            )
            ->orderBy('tanggal')
            ->orderBy('nama_pegawai')
            ->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Nama',
            'Tanggal',
            'Hari',
            'Jam Masuk',
            'Jam Pulang',
            'Keterangan',
        ];
    }

    public function map($rekap): array
    {
        return [
            $rekap->id_pegawai,
            $rekap->nama_pegawai,
            $rekap->tanggal?->format('Y-m-d'),
            $rekap->hari,
            $rekap->jam_masuk,
            $rekap->jam_pulang,
            $rekap->keterangan,
        ];
    }
}
