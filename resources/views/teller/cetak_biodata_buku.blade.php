<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Biodata Buku Tabungan</title>
    <style>
        /* Pengaturan ukuran kertas untuk passbook printer (sesuaikan dengan ukuran buku) */
        @page {
            size: 15cm 20cm;
            /* Sesuaikan dengan dimensi buku tabungan yang terbuka */
            margin: 0;
        }

        body {
            font-family: 'Courier New', Courier, monospace;
            /* Font monospaced lebih baik untuk printer passbook */
            font-size: 10pt;
            margin: 0;
            padding: 0;
        }
        
        .biodata h3 {
            margin: 0;
            line-height: 1.2; 
        }

        .biodata {
            padding-top: 12px; 
            }
            
        .biodata span{
            margin-right: 27px; 
        }

        /* Sesuaikan jarak margin (top dan left) agar pas tercetak di kolom biodata fisik buku */
        .biodata-container {
            position: absolute;
            left: 1cm;
            /* Jarak dari tepi kiri buku */
        }
    </style> 
</head>

    <div class="biodata-container">
        @php
            $namaAsli = $rekening->nasabah->nama_nasabah ?? '-';
            
            // Rapikan spasi dan buat huruf awal setiap kata menjadi kapital (Title Case)
            $namaAsli = ucwords(strtolower(trim($namaAsli)));
            
            // Pecah menjadi array dan reset indeks (array_values) agar aman jika ada spasi ganda
            $kata = array_values(array_filter(explode(' ', $namaAsli))); 
            
            if (count($kata) > 2) {
                // Ambil 2 kata pertama secara utuh
                $namaFormat = $kata[0] . ' ' . $kata[1];
                
                // Singkat sisa kata berikutnya dan tambahkan titik
                for ($i = 2; $i < count($kata); $i++) {
                    $namaFormat .= ' ' . strtoupper(substr($kata[$i], 0, 1)) . '.';
                }
            } else {
                $namaFormat = $namaAsli;
            }

            // Ambil data nama jurusan dan status jabatan nasabah
            $namaJurusan = $rekening->nasabah?->jurusan?->nama_jurusan;
            
            // Ambil nama jabatan (sesuaikan nama field 'nama_jabatan' atau 'jabatan' dengan kolom DB kamu)
            $jabatan     = $rekening->nasabah?->jabatan?->nama_jabatan ?? $rekening->nasabah?->jabatan;
        @endphp
        
        <div class="biodata">
            <h3><span>Nama</span>: <strong>{{ $namaFormat }}</strong></h3> <!-- Nama Nasabah -->
        </div>
        <div class="biodata">
            <h3>No. Rek: <strong>{{ $rekening->id }}</strong></h3> <!-- Nomor Rekening -->
        </div>

        <!-- Hanya muncul jika jabatannya 'Siswa' ATAU 'siswa' -->
        @if(strtolower(trim($jabatan ?? '')) === 'siswa')
            <div class="biodata">
                <h3>Jurusan: <strong>{{ $namaJurusan ?? '-' }}</strong></h3> <!-- Jurusan Nasabah -->
            </div>
        @endif
    </div>

<script>
    // Langsung memunculkan dialog print saat halaman selesai dimuat
    window.onload = function() {
        window.print();
    };

    // (Opsional) Otomatis menutup tab setelah dialog print ditutup
    window.onafterprint = function() {
        window.close();
    };
</script>
</body>

</html>