<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Pengeluaran</title>
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            line-height: 1.4;
            color: #333;
            margin: 0;
            padding: 10px;
            font-size: 10px;
        }
        h2, h3 {
            color: #2c3e50;
            text-align: center;
            margin-bottom: 15px;
            font-size: 14px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 8px;
            font-size: 11px;
        }
        th {
            background-color: #3498db;
            color: white;
            font-weight: bold;
            text-align: center;
        }
        td {
            text-align: left;
        }
        td.currency {
            text-align: right;
        }
        td.jumlah-item, td.date {
            text-align: center;
        }
        tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        .total-row {
            background-color: #e9ecef;
            font-weight: bold;
        }
        .total-row td {
            border-top: 2px solid #3498db;
        }
    </style>
</head>
    <body>
        <h1 style="text-align: center;">Laporan Keuangan 
        @if($year) 
            Tahun {{ $year }}
        @endif
    </h1>
    @if($startDate && $endDate)
    <p style="text-align: center;">Periode: {{ \Carbon\Carbon::parse($startDate)->isoFormat('D MMMM Y') }} - {{ \Carbon\Carbon::parse($endDate)->isoFormat('D MMMM Y') }}</p>
    @endif
    <h3>Pengeluaran</h3>
    <table>
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 20%;">Nama</th>
                <th style="width: 15%;">Kategori</th>
                <th style="width: 10%;">Jumlah Satuan</th>
                <th style="width: 15%;">Nominal</th>
                <th style="width: 15%;">Jumlah</th>
                <th style="width: 20%;">Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pengeluaran as $key => $pgl)
            <tr>
                <td>{{ $key + 1 }}</td>
                <td>{{ $pgl->name }}</td>
                <td>{{ $pgl->category ? $pgl->category->name : 'Kategori tidak ditemukan' }}</td>
                <td class="jumlah-item">{{ $pgl->jumlah_satuan }}</td>
                <td class="currency">Rp{{ number_format($pgl->nominal, 0, ',', '.') }}</td>
                <td class="currency">Rp{{ number_format($pgl->jumlah, 0, ',', '.') }}</td>
                <td class="date">{{ $pgl->parentPengeluaran ? \Carbon\Carbon::parse($pgl->parentPengeluaran->tanggal)->format('d/m/Y') : 'Tanggal tidak ditemukan' }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td colspan="5" style="text-align: right;">Total Pengeluaran:</td>
                <td class="currency">Rp{{ number_format($totalPengeluaran, 0, ',', '.') }}</td>
                <td></td>
            </tr>
        </tfoot>
    </table>
</body>
</html>
