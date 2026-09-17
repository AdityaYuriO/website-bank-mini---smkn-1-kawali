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
        Schema::create('rekening', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary();
            $table->foreignId('nasabah_id')->constrained('nasabah');
            $table->integer('saldo_saat_ini');

            // Kolom penanda jika buku tabungan pernah hilang/ganti baru
            $table->unsignedBigInteger('buku_baru_transaksi_id')->nullable();

            $table->enum('status_akun', ['aktif', 'non-aktif', 'pending', 'revisi']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rekening');
    }
};
