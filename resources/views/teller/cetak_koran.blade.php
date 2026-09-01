<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Rekening Koran - {{ $rekening->id ?? 'Nasabah' }}</title>
    <style>
        * {
            box-sizing: border-box;
        }

        /* Set margin seragam, 1cm di semua sisi */
        @page {
            size: auto; 
            margin: 10mm; 
        }
        
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #000;
            font-size: 12px;
            line-height: 1.5;
            margin: 0 auto;
            padding: 0;
            background-color: #fff;
            max-width: 100%;
        }
        
        .header {
            text-align: center;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }
        .header h1 {
            margin: 0;
            font-size: 18px;
            color: #000;
            text-transform: uppercase;
        }
        .header p {
            margin: 2px 0;
            font-size: 12px;
        }
        .title {
            text-align: center;
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 15px;
            text-decoration: underline;
        }
        .info-table {
            width: 100%;
            margin-bottom: 15px;
            font-size: 12px;
            padding: 20px;
        }
        .info-table td {
            padding: 2px 5px;
            vertical-align: top;
        }
        .info-label {
            width: 110px;
            font-weight: bold;
        }
        .info-separator {
            width: 10px;
            text-align: center;
        }
        
        /* PERBAIKAN TABEL UTAMA */
        .data-table {
            width: 100%;
            max-width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
            table-layout: fixed;
            word-wrap: break-word;
        }
        
        .data-table th, .data-table td {
            border: 1px solid #000 !important; 
            padding: 5px 3px; 
            text-align: center !important; /* Semua isi tabel rata tengah */
            font-size: 11px;
        }
        
        .data-table th {
            background-color: hsl(0, 0%, 100%) !important; 
            font-weight: bold;
            color: #000 !important;
        }
        
        .text-center { text-align: center !important; }
        
        .footer {
            margin-top: 30px;
            width: 100%;
            page-break-inside: avoid;
        }
        .signature {
            float: right;
            text-align: center;
            width: 200px;
        }
        .signature-space {
            height: 70px;
        }
        
        /* Style Tombol Aksi */
        .action-container {
            margin-bottom: 20px;
            display: flex;
            justify-content: center;
            gap: 10px;
        }
        
        @media print {
            body { 
                background: none; 
                -webkit-print-color-adjust: exact !important; 
                print-color-adjust: exact !important; 
            }
            .no-print { display: none !important; }
            thead { display: table-header-group; }
            tr { page-break-inside: avoid; }
        }
    </style>
</head>
<body onload="window.print()">

    <div class="no-print" style="margin-bottom: 20px;">
        <button onclick="window.print()" style="padding: 10px 20px; font-weight: bold; cursor: pointer; background-color: #007bff; color: white; border: none; border-radius: 5px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">Cetak Sekarang</button>
        <button onclick="window.close()" style="padding: 10px 20px; font-weight: bold; cursor: pointer; background-color: #6c757d; color: white; border: none; border-radius: 5px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); margin-left: 10px;">Tutup Tab</button>
    </div>

    <div class="header">
        <h1>BANK MINI SMKN 1 KAWALI</h1>
        <p>Jl. Talagasari No.35, Kawalimukti, Kec. Kawali, Kab. Ciamis</p>
        <p>Telp: (0265) 123456 | Email: info@smkn1kawali.sch.id</p>
    </div>

    <div class="title">REKENING KORAN</div>

    <table class="info-table">
        <tr>
            <td class="info-label">Nama Nasabah</td>
            <td class="info-separator">:</td>
            <td><strong>{{ $rekening->nasabah->nama_nasabah ?? ($rekening->nasabah->nama_lengkap ?? 'Fulan') }}</strong></td>
            
            <td class="info-label">Periode Transaksi</td>
            <td class="info-separator">:</td>
            <td>{{ \Carbon\Carbon::parse($startDate)->format('d M Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}</td>
        </tr>
        <tr>
            <td class="info-label">No. Rekening</td>
            <td class="info-separator">:</td>
            <td>{{ $rekening->id }}</td>
            
            <td class="info-label">Tanggal Cetak</td>
            <td class="info-separator">:</td>
            <td>{{ \Carbon\Carbon::now()->format('d M Y, H:i') }}</td>
        </tr>
    </table>

    <table class="data-table">
        <thead>
            <tr>
                <th width="5%">NO</th>
                <th width="12%">TANGGAL</th>
                <th width="27%">KETERANGAN</th>
                <th width="15%">DEBIT</th>
                <th width="15%">KREDIT</th>
                <th width="26%">SALDO</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transaksi as $index => $trx)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td class="text-center">{{ \Carbon\Carbon::parse($trx->tanggal)->format('d/m/Y') }}</td>
                <td class="text-center">[{{ $trx->jenis }}] {{ $trx->keterangan ?? '-' }}</td>
                
                <td class="text-center">
                    {{ $trx->debit > 0 ? 'Rp ' . number_format($trx->debit, 0, ',', '.') : '-' }}
                </td>
                
                <td class="text-center">
                    {{ $trx->kredit > 0 ? 'Rp ' . number_format($trx->kredit, 0, ',', '.') : '-' }}
                </td>
                
                <td class="text-center">
                    <strong>Rp {{ number_format($trx->saldo, 0, ',', '.') }}</strong>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center" style="padding: 20px;">Tidak ada transaksi pada periode ini.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <div class="signature">
            <p>Kawali, {{ \Carbon\Carbon::now()->format('d F Y') }}</p>
            <p>Petugas Teller</p>
            <div class="signature-space"></div>
            <p><strong>( {{ auth()->user()->name ?? 'Petugas Bank' }} )</strong></p>
        </div>
        <div style="clear: both;"></div>
    </div>

</body>  
</html> 