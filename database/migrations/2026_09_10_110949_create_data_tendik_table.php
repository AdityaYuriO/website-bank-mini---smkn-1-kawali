<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('data_tendik', function (Blueprint $table) {
            $table->id();
            $table->String('nama_lengkap')->nullable();
            $table->String('jabatan')->nullable();
            $table->String('nuptk')->nullable();
            $table->String('nip')->nullable();
            $table->String('nik')->nullable();
            $table->String('jenis_kelamin')->nullable();
            $table->String('tempat_lahir')->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->String('agama')->nullable();
            $table->String('kode_pos')->nullable();
            $table->String('rt')->nullable();
            $table->String('rw')->nullable();
            $table->String('dusun')->nullable();
            $table->String('kelurahan_id')->nullable();
            $table->String('kecamatan_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_tendik');
    }
};
