<?php

namespace App\Imports;

use App\Models\Pegawai;
use App\Models\RekapAbsensi;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class RekapAbsensiImport implements WithMultipleSheets
{
    public function sheets(): array
    {
        return [
            0 => new RekapAbsensiSheetImport(),
        ];
    }
}

class RekapAbsensiSheetImport implements ToCollection
{
    private array $pegawaiCache = [];
    private array $tanggalCache = [];
    private int $jumlahBerhasil = 0;
    private array $pegawaiTidakDikenal = [];

    public function collection(Collection $rows)
    {
        $this->pegawaiCache = Pegawai::with([
            'divisi',
            'divisiKedua',
        ])
            ->where('aktif', 1)
            ->get()
            ->keyBy(fn ($pegawai) => $this->normalisasiNama(
                $pegawai->nama_lengkap
            ))
            ->all();

        $this->tanggalCache = $this->deteksiPeriode($rows);

        DB::transaction(function () use ($rows) {
            foreach ($rows as $index => $row) {
                // Dua baris pertama merupakan header.
                if ($index < 2) {
                    continue;
                }

                $nama = trim((string) ($row[0] ?? ''));
                $nomorTanggal = $this->angka($row[1] ?? null);

                if (
                    $nama === '' ||
                    $nomorTanggal === null ||
                    $nomorTanggal < 1 ||
                    $nomorTanggal > 31
                ) {
                    continue;
                }

                $pegawai = $this->pegawaiCache[
                    $this->normalisasiNama($nama)
                ] ?? null;

                if (!$pegawai) {
                    $this->pegawaiTidakDikenal[] = $nama;
                    continue;
                }

                $tanggal = $this->tanggalCache[$nomorTanggal] ?? null;

                if (!$tanggal) {
                    continue;
                }

                /*
                 * Pasangan kolom Excel:
                 * Pagi   : D masuk, F pulang
                 * Siang  : I masuk, K pulang
                 * Lembur : M masuk, O pulang
                 */
                $shift = $this->pilihShift($row);

                $jamMasuk = $shift['masuk'];
                $jamPulang = $shift['pulang'];

                // Periksa kedua divisi pegawai.
                $divisiPertama = strtolower(
                    trim($pegawai->divisi->nama_divisi ?? '')
                );

                $divisiKedua = strtolower(
                    trim($pegawai->divisiKedua->nama_divisi ?? '')
                );

                $hostLive =
                    str_contains($divisiPertama, 'live commerce') ||
                    str_contains($divisiKedua, 'live commerce');

                /*
                 * Host Live:
                 * Jam masuk 08:00 tetap 08:00.
                 * Jika jam masuk 12:00 atau lebih, kurangi 7 jam
                 * dari jam masuk dan jam pulang hasil fingerprint.
                 */
                if (
                    $hostLive &&
                    $jamMasuk !== null &&
                    $jamMasuk >= '12:00:00'
                ) {
                    $jamMasuk = $this->kurangiTujuhJam($jamMasuk);

                    if ($jamPulang !== null) {
                        $jamPulang = $this->kurangiTujuhJam($jamPulang);
                    }
                }

                $keterangan = $this->tentukanKeterangan(
                    $jamMasuk,
                    $jamPulang
                );

                RekapAbsensi::updateOrCreate(
                    [
                        'id_pegawai' => $pegawai->kode_pegawai,
                        'tanggal' => $tanggal,
                    ],
                    [
                        'nama_pegawai' => $pegawai->nama_lengkap,
                        'hari' => $this->namaHariIndonesia($tanggal),
                        'jam_masuk' => $jamMasuk,
                        'jam_pulang' => $jamPulang,
                        'keterangan' => $keterangan,
                        'nama_file' => 'ABSEN MENTAH',
                    ]
                );

                $this->jumlahBerhasil++;
            }
        });

        session()->flash(
            'rekap_import_berhasil',
            $this->jumlahBerhasil
        );

        session()->flash(
            'rekap_import_tidak_dikenal',
            array_values(array_unique($this->pegawaiTidakDikenal))
        );
    }

    /**
     * Ambil jam masuk paling awal dan jam pulang paling akhir
     * dari semua kelompok shift yang memiliki data.
     */
    private function pilihShift($row): array
    {
        $pasanganShift = [
            ['masuk' => $row[3] ?? null, 'pulang' => $row[5] ?? null],
            ['masuk' => $row[8] ?? null, 'pulang' => $row[10] ?? null],
            ['masuk' => $row[12] ?? null, 'pulang' => $row[14] ?? null],
        ];

        $semuaMasuk = [];
        $semuaPulang = [];

        foreach ($pasanganShift as $shift) {
            $masuk = $this->formatJam($shift['masuk']);
            $pulang = $this->formatJam($shift['pulang']);

            if ($masuk !== null) {
                $semuaMasuk[] = $masuk;
            }

            if ($pulang !== null) {
                $semuaPulang[] = $pulang;
            }
        }

        sort($semuaMasuk);
        sort($semuaPulang);

        return [
            'masuk' => $semuaMasuk[0] ?? null,
            'pulang' => !empty($semuaPulang)
                ? end($semuaPulang)
                : null,
        ];
    }

    private function formatJam($value): ?string
    {
        if ($value === null || $value === '') {
            return null;
        }

        if ($value instanceof \DateTimeInterface) {
            return $value->format('H:i:s');
        }

        if (is_numeric($value)) {
            $seconds = (int) round(((float) $value) * 86400);
            $seconds %= 86400;

            return gmdate('H:i:s', $seconds);
        }

        $value = trim((string) $value);

        // Mendukung format teks seperti 14.42 dan 14:42.
        if (preg_match('/^(\d{1,2})[.:](\d{2})(?:[.:](\d{2}))?$/', $value, $m)) {
            $jam = (int) $m[1];
            $menit = (int) $m[2];
            $detik = isset($m[3]) ? (int) $m[3] : 0;

            if (
                $jam > 23 ||
                $menit > 59 ||
                $detik > 59
            ) {
                return null;
            }

            return sprintf('%02d:%02d:%02d', $jam, $menit, $detik);
        }

        try {
            return Carbon::parse($value)->format('H:i:s');
        } catch (\Throwable $e) {
            return null;
        }
    }

    private function kurangiTujuhJam(string $jam): string
    {
        return Carbon::createFromFormat('H:i:s', $jam)
            ->subHours(7)
            ->format('H:i:s');
    }

    private function tentukanKeterangan(
        ?string $jamMasuk,
        ?string $jamPulang
    ): string {
        if (!$jamMasuk && !$jamPulang) {
            return 'Tidak ada data fingerprint';
        }

        if (!$jamMasuk) {
            return 'Jam masuk tidak tercatat';
        }

        if (!$jamPulang) {
            return 'Jam pulang tidak tercatat';
        }

        return $jamMasuk > '08:00:00'
            ? 'Terlambat'
            : 'Hadir';
    }

    private function normalisasiNama(string $nama): string
    {
        $nama = mb_strtolower(trim($nama));

        return preg_replace('/\s+/', ' ', $nama);
    }

    private function angka($value): ?int
    {
        if (!is_numeric($value)) {
            return null;
        }

        return (int) $value;
    }

    private function namaHariIndonesia(string $tanggal): string
    {
        return [
            1 => 'Sen',
            2 => 'Sel',
            3 => 'Rab',
            4 => 'Kam',
            5 => 'Jum',
            6 => 'Sab',
            7 => 'Min',
        ][Carbon::parse($tanggal)->dayOfWeekIso];
    }

    /**
     * Mencocokkan angka tanggal dan nama hari dari file mentah
     * dengan kalender tanpa meminta input bulan/tahun.
     */
    private function deteksiPeriode(Collection $rows): array
    {
        $tanggalHari = [];

        foreach ($rows->slice(2) as $row) {
            $nomor = $this->angka($row[1] ?? null);
            $namaHari = strtolower(trim((string) ($row[2] ?? '')));

            if (
                $nomor !== null &&
                $nomor >= 1 &&
                $nomor <= 31 &&
                $namaHari !== ''
            ) {
                $tanggalHari[$nomor] = $namaHari;
            }
        }

        $mappingHari = [
            'sen' => 1,
            'sel' => 2,
            'rab' => 3,
            'kam' => 4,
            'jum' => 5,
            'sab' => 6,
            'min' => 7,
        ];

        $awal = Carbon::now()->startOfMonth();

        for ($mundur = 0; $mundur < 24; $mundur++) {
            $kandidat = $awal->copy()->subMonths($mundur);
            $cocok = true;

            foreach ($tanggalHari as $nomor => $hari) {
                if ($nomor > $kandidat->daysInMonth) {
                    $cocok = false;
                    break;
                }

                $hariKandidat = $kandidat->copy()
                    ->day($nomor)
                    ->dayOfWeekIso;

                if (
                    ($mappingHari[substr($hari, 0, 3)] ?? null)
                    !== $hariKandidat
                ) {
                    $cocok = false;
                    break;
                }
            }

            if ($cocok) {
                $hasil = [];

                foreach (array_keys($tanggalHari) as $nomor) {
                    $hasil[$nomor] = $kandidat->copy()
                        ->day($nomor)
                        ->format('Y-m-d');
                }

                return $hasil;
            }
        }

        throw new \RuntimeException(
            'Periode absensi tidak dapat ditentukan dari angka tanggal dan nama hari.'
        );
    }
}
