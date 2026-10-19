<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pegawai extends Model
{
    protected $fillable = [
        'kode_pegawai',
        'nama_lengkap',
        'divisi_id',
        'divisi_kedua_id',
        'aktif',
    ];

    protected function casts(): array
    {
        return [
            'aktif' => 'boolean',
        ];
    }

    public function divisi(): BelongsTo
    {
        return $this->belongsTo(Divisi::class);
        
    }
    public function divisiKedua()
    {
        return $this->belongsTo(
            \App\Models\Divisi::class,
            'divisi_kedua_id'
        );
    }
}
