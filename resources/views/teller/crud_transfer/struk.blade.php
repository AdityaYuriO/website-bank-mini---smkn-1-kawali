<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">

<style>
@page {
    /* Mengubah ukuran kertas menjadi lebar 102mm (10,2cm) dan tinggi 159mm (15,9cm) */
    size: 102mm 159mm;
    margin: 0;
}

html, body {
    /* Mengubah ukuran body menyesuaikan ukuran kertas */
    width: 102mm;
    height: 159mm;
    margin: 0;
    padding: 5mm;
    box-sizing: border-box;
    font-family: Arial, sans-serif;
    font-size: 11px;
}

body {
    zoom: 100%;
}

.title {
    text-align: center;
    margin-bottom: 10px;
}

.title h3 {
    margin: 0;
    font-size: 18px;
}

.title strong {
    font-size: 13px;
}

.line {
    border-top: 1px dashed #000;
    margin-top: 8px;    
    margin-bottom: 15px; 
    
    margin-left: 10px;  
    margin-right: 10px; 
}

table {
    width: 100%;
    border-collapse: collapse;
}

td {
    padding: 2px 0;
    vertical-align: top;
    word-break: break-word;
}

.label {
    width: 32%;
}

.separator {
    width: 5%;
    text-align: center;
}

.total {
    font-weight: bold;
    font-size: 12px;
}

.footer {
    margin-top: 15px;
    text-align: center;
    font-size: 11px;
}
</style>

</head>
<body onload="window.print()">

<div class="title">
    <h3 style="margin:0;">BANK MINI K-ONE</h3>
    <div style="font-weight:bold; margin-top:3px;">JL.Talagasari, No.35, Kawalimukti</div>
    <div style="font-weight:bold; margin-top:3px;">STRUK TRANSFER</div>
</div>

<div class="line"></div>

<table>

    <tr>
        <td class="label">No Transaksi</td>
        <td class="separator">:</td>
        <td>{{ 'TF-' . str_pad($transfer->id, 5, '0', STR_PAD_LEFT) }}</td>
    </tr>

    <tr>
        <td class="label">Tanggal</td>
        <td class="separator">:</td>
        <td>{{ $transfer->updated_at->format('d-m-Y H:i') }}</td>
    </tr>

    <tr>
        <td class="label">Nama Pengirim</td>
        <td class="separator">:</td>
        <td>{{ optional($transfer->rekeningPengirim->nasabah)->nama_nasabah ?? '-' }}</td>
    </tr>

    <tr>
        <td class="label">Rek Pengirim</td>
        <td class="separator">:</td>
        <td>{{ $transfer->id_rekening_pengirim }}</td>
    </tr>

    <tr>
        <td class="label">Nama Penerima</td>
        <td class="separator">:</td>
        <td>{{ optional($transfer->rekeningPenerima->nasabah)->nama_nasabah ?? '-' }}</td>
    </tr>

    <tr>
        <td class="label">Rek Penerima</td>
        <td class="separator">:</td>
        <td>{{ $transfer->id_rekening_penerima }}</td>
    </tr>

    <tr>
        <td class="label">Petugas</td>
        <td class="separator">:</td>
        <td>{{ optional($transfer->petugas)->user->name ?? '-' }}</td>
    </tr>
</table>

<div class="line"></div>

<table>
    <tr>
        <td class="label">Nominal</td>
        <td class="separator">:</td>
        <td>
            Rp {{ number_format($transfer->jumlah_transfer,0,',','.') }}
        </td>
    </tr>

    <tr>
        <td class="label">Biaya Admin</td>
        <td class="separator">:</td>
        <td>
            Rp {{ number_format($transfer->total_biaya - $transfer->jumlah_transfer,0,',','.') }}
        </td>
    </tr>

    <tr class="total">
        <td class="label">Total Biaya</td>
        <td class="separator">:</td>
        <td>
            Rp {{ number_format($transfer->total_biaya,0,',','.') }}
        </td>
    </tr>
</table>

<div class="line"></div>

<table>

    <tr>
        <td class="label">Pembayaran</td>
        <td class="separator">:</td>
        <td>
            {{ $transfer->pilihan_biaya_transaksi ?? 'Cash' }}
        </td>
    </tr>

    <tr>
        <td class="label">Catatan</td>
        <td class="separator">:</td>
        <td>{{ $transfer->catatan ?: '-' }}</td>
    </tr>
</table>

<div style="margin-top:20px;">
    <table style="width:100%; text-align:center;">
        <tr>
            <td style="width:50%;">
                Teller
            </td>
            <td style="width:50%;">
                Nasabah
            </td>
        </tr>

        <!-- Ruang tanda tangan -->
        <tr>
            <td style="height:40px;"></td> <!-- Disesuaikan jadi 40px sama kaya setoran -->
            <td></td>
        </tr>

        <!-- Nama -->
        <tr>
            <td>
                ( <strong>{{ optional($transfer->petugas)->user->name ?? '-' }}</strong> )
            </td>

            <td>
                ( <strong>{{ optional($transfer->rekeningPengirim->nasabah)->nama_nasabah ?? '-' }}</strong> )
            </td>
        </tr>
    </table>
</div>

<div class="footer">
    <div class="line"></div>
    Terima Kasih Telah Menggunakan Layanan Kami
    <div style="margin-top: 4px; font-weight: normal; font-size: 10px;">
        SMS/WA 0812-3456-7890
    </div>
</div>

<script>
    window.onafterprint = function() {
        window.close(); // Menutup tab struk ini secara otomatis
    }

    // Jaga-jaga jika onafterprint tidak terpicu di beberapa browser
    setTimeout(function() {
        window.close();
    }, 1000); // Tutup setelah 1 detik jika dialog cetak selesai/batal
</script>

</body>
</html>