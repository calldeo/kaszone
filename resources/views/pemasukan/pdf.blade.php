<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Pemasukan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }
        h2, h3 {
            color: #2c3e50;
            text-align: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid #bdc3c7;
        }
        th, td {
            padding: 12px;
            text-align: left;
        }
        th {
            background-color: #3498db;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        .total-row {
            background-color: #ecf0f1;
            font-weight: bold;
        }
        .total-row td {
            border-top: 2px solid #3498db;
        }
        .page-break {
            page-break-before: always;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <h2>Laporan Keuangan Tahun {{ $year }}</h2>

    <h3>Pemasukan</h3>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Deskripsi</th>
                <th>Kategori</th>
                <th>Jumlah</th>
                <th>Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pemasukan as $key => $item)
            <tr>
                <td>{{ $key + 1 }}</td>
                <td>{{ $item->name }}</td>
                <td>{{ $item->description }}</td>
                <td>{{ $item->category ? $item->category->name : 'Kategori tidak ditemukan' }}</td>
                <td>Rp{{ number_format($item->jumlah, 0, ',', '.') }}</td>
                <td>{{ \Carbon\Carbon::parse($item->date)->format('d-m-Y') }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td colspan="4" style="text-align: right;">Total Jumlah:</td>
                <td colspan="2">Rp{{ number_format($pemasukan->sum('jumlah'), 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>
</body>
</html>
