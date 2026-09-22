<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Kecamatan;
use App\Models\Desa;
use App\Models\Jurusan;

class data_siswa extends Model
{
    protected $table = 'data_siswa';

    public $timestamps = false;

    public $fillable = ([
        'nama_lengkap',
        'nis',
        'jabatan',
        'nisn',
        'jurusan_id',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'agama',
        'rt',
        'rw',
        'dusun',
        'kelurahan_id',
        'kecamatan_id',
        'kode_pos',
    ]);


    public function jurusan() {
        return $this->belongsTo(jurusan::class);
    }
}
