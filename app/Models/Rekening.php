<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rekening extends Model
{
    protected $table = 'rekening';

    protected $primaryKey = 'id';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'nasabah_id',
        'saldo_saat_ini',
        'buku_baru_transaksi_id', // <--- Tambahin kolom ini bray
        'status_akun'
    ];

    public function buktiTf()
    {
        return $this->hasMany(Bukti_Tf::class, 'id_rekening', 'id');
    }

    // Mengambil semua riwayat dana yang MASUK ke rekening ini
    public function transferMasuk()
    {
        return $this->hasMany(RiwayatTf::class, 'id_penerima', 'id');
    }

    // Mengambil semua riwayat dana yang KELUAR dari rekening ini
    public function transferKeluar()
    {
        return $this->hasMany(RiwayatTf::class, 'id_pengirim', 'id');
    }

    public function user()
    {
        // Parameter ke-2: nama kolom di tabel rekening kamu ('nasabah_id')
        // Parameter ke-3: primary key di tabel users ('id')
        return $this->belongsTo(User::class, 'nasabah_id', 'id');
    }

    // relasi ke tabel nasabah
    public function nasabah()
    {
        return $this->belongsTo(Nasabah::class, 'nasabah_id', 'id');
    }

    public function setoran()
    {
        return $this->hasMany(Setoran::class, 'id_rekening', 'id');
    }
}
