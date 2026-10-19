<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RekapAbsensi extends Model
{
    protected $table = 'rekap_absensis';

    protected $fillable = [
        'id_pegawai',
        'nama_pegawai',
        'tanggal',
        'hari',
        'jam_masuk',
        'jam_pulang',
        'keterangan',
        'nama_file',
    ];

    protected function casts(): array
    {
        return [
            'tanggal' => 'date',
        ];
    }
}