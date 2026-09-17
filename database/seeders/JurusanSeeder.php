<?php

namespace Database\Seeders;

use App\Models\Jurusan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class JurusanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Jurusan::create([
            'id' => 01,
            'nama_jurusan' => 'TKRO',
            'singkatan' => 'Teknik Kendaraan Ringan Otomotif'
        ]);

        Jurusan::create([
            'id' => 02,
            'nama_jurusan' => 'TJKT',
            'singkatan' => 'Teknik Jaringan Komputer dan Telekomunikasi'
        ]);

        Jurusan::create([
            'id' => 03,
            'nama_jurusan' => 'PPLG',
            'singkatan' => 'Pengembangan Perangkat Lunak dan Gim',
        ]);

        Jurusan::create([
            'id' => 04,
            'nama_jurusan' => 'DPIB',
            'singkatan' => 'Desain Pemodelan dan Informasi Bangunan',
        ]);

        Jurusan::create([
            'id' => 05,
            'nama_jurusan' => 'MPLB',
            'singkatan' => 'Manajemen Perkantoran dan Layanan Bisnis',
        ]);


        Jurusan::create([
            'id' => 06,
            'nama_jurusan' => 'Akl',
            'singkatan' => 'Akuntansi dan Keuangan Lembaga',
        ]);

        Jurusan::create([
            'id' => 07,
            'nama_jurusan' => 'Sk',
            'singkatan' => 'Seni Karawitan',
        ]);

        Jurusan::create([
            'id' => 8,
            'nama_jurusan' => 'TU',
            'singkatan' => 'Tenaga Kependidikan',
        ]);
    }
}
