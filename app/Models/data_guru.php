<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class data_guru extends Model
{
    public $table = 'data_guru';

    public $fillable = ([
        'nama_lengkap',
        'nuptk',
        'nip',
        'nik',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'agama',
        'kode_pos',
        'jabatan'

    ]);

    public $timestamps = false;
}
