
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rekap_absensis', function (Blueprint $table) {
            $table->id();

            // Identitas pegawai dari file Excel
            $table->string('id_pegawai', 50);
            $table->string('nama_pegawai');

            // Informasi tanggal dan waktu absensi
            $table->date('tanggal');
            $table->string('hari', 20)->nullable();
            $table->time('jam_masuk')->nullable();
            $table->time('jam_pulang')->nullable();

            // Keterangan dari file rekap Excel
            $table->string('keterangan')->nullable();

            // Menandai sumber dan periode data impor
            $table->string('nama_file')->nullable();

            $table->timestamps();

            // Mencegah baris yang sama tersimpan dua kali
            $table->unique(
                ['id_pegawai', 'tanggal'],
                'rekap_absensis_pegawai_tanggal_unique'
            );

            $table->index('tanggal');
            $table->index('nama_pegawai');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rekap_absensis');
    }
};
