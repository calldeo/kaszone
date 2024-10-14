<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Pengeluaran</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        h2, h3 {
            color: #2c3e50;
            text-align: center;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 12px;
            text-align: left;
        }
        th {
            background-color: #3498db;
            color: white;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        tr:hover {
            background-color: #e9ecef;
        }
        .total-row {
            background-color: #e9ecef;
            font-weight: bold;
        }
        .total-row td {
            border-top: 2px solid #3498db;
        }
        .jumlah-item {
            font-size: 0.95em;
            text-align: center;
        }
        .currency {
            text-align: right;
        }
        .date {
            text-align: center;
        }
    </style>
</head>
<body>
    <h2>Laporan Keuangan Tahun {{ $year }}</h2>

    <h3>Pengeluaran</h3>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Kategori</th>
                <th>Jumlah Item</th>
                <th>Harga</th>
                <th>Total</th>
                <th>Tanggal</th>
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
                <td class="currency" colspan="2">Rp{{ number_format($totalPengeluaran, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>
</body>
</html>
