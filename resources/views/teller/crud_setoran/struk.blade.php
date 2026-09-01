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

body{
    zoom: 100%;
}

.title{
    text-align: center;
    margin-bottom: 10px;
}

.title h3{
    margin: 0;
    font-size: 18px;
}

.title strong{
    font-size: 13px;
}

.line {
    border-top: 1px dashed #000;
    margin-top: 8px;    
    margin-bottom: 15px; 
    
    margin-left: 10px;  
    margin-right: 10px; 
}

table{
    width: 100%;
    border-collapse: collapse;
}

td{
    padding: 2px 0;
    vertical-align: top;
    word-break: break-word;
}

.label{
    width: 32%;
}

.separator{
    width: 5%;
    text-align: center;
}

.total{
    font-weight: bold;
    font-size: 12px;
}

.footer{
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
    <div style="font-weight:bold; margin-top:3px;">STRUK SETORAN</div>
</div>

<div class="line"></div>

<table>
    <tr>
        <td class="label">No Transaksi</td>
        <td class="separator">:</td>
        <td>ST-{{ str_pad($setoran->id, 5, '0', STR_PAD_LEFT) }}</td>
    </tr>

    <tr>
        <td class="label">Tanggal</td>
        <td class="separator">:</td>
        <td>{{ $setoran->updated_at->format('d-m-Y H:i') }}</td>
    </tr>

    <tr>
        <td class="label">No Rekening</td>
        <td class="separator">:</td>
        <td>{{ $setoran->id_rekening }}</td>
    </tr>

    <tr>
        <td class="label">Nasabah</td>
        <td class="separator">:</td>
        <td>{{ $setoran->nama_lengkap }}</td>
    </tr>

    <tr>
        <td class="label">Penyetor</td>
        <td class="separator">:</td>
        <td>{{ $setoran->nama_penyetor }}</td>
    </tr>

    <tr>
        <td class="label">Petugas</td>
        <td class="separator">:</td>
        <td>{{ optional($setoran->petugas)->user->name ?? '-' }}</td>
    </tr>
</table>

<div class="line"></div>

<table>
    <tr>
        <td class="label">Nominal Setoran</td>
        <td class="separator">:</td>
        <td>
            Rp {{ number_format($setoran->jumlah_penyetoran, 0, ',', '.') }}
        </td>
    </tr>

    <tr>
        <td class="label">Biaya Admin</td>
        <td class="separator">:</td>
        <td>
            Rp {{ number_format(optional($setoran->transaksi)->nominal ?? 0, 0, ',', '.') }}
        </td>
    </tr>

    <tr class="total">
        <td class="label">Total</td>
        <td class="separator">:</td>
        <td>
            Rp {{ number_format($setoran->total_biaya, 0, ',', '.') }}
        </td>
    </tr>
</table>

<div class="line"></div>

<table>

    <tr>
        <td class="label">Pembayaran</td>
        <td class="separator">:</td>
        <td>
            {{ $setoran->pilihan_biaya_transaksi ?? 'Cash' }}
        </td>
    </tr>

    <tr>
        <td class="label">Terbilang</td>
        <td class="separator">:</td>
        <td>{{ $setoran->uang_terbilang }}</td>
    </tr>

    <tr>
        <td class="label">Catatan</td>
        <td class="separator">:</td>
        <td>{{ $setoran->catatan ?? '-' }}</td>
    </tr>
</table>

<div style="height: 15px;"></div>

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
            <td style="height:40px;"></td>
            <td></td>
        </tr>

        <!-- Nama -->
        <tr>
            <td>
                ( <strong>{{ optional($setoran->petugas)->user->name ?? '-' }}</strong> )
            </td>

            <td>
                ( <strong>{{ $setoran->nama_lengkap }}</strong> )
            </td>
        </tr>
    </table>
</div>

<div class="footer">
    <div class="line"></div>
    Terima Kasih Telah Menggunakan Layanan Kami
</div>

<script>
    window.onafterprint = function() {
        window.close(); 
    }

    setTimeout(function() {
        window.close(); 
    }, 1000); 
</script>

</body>
</html>