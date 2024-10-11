<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan PDF</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .page-break {
            page-break-before: always;
            margin: 20px 0;
        }
        h4 {
            margin: 10px 0;
        }
        .selisih {
            margin-top: 20px;
            text-align: right;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <h2 style="text-align: center;">Laporan Keuangan Tahun {{ $year }}</h2>

    <h3>Pemasukan</h3>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Deskripsi</th>
                <th>Kategori</th>
                <th>Jumlah (Rp)</th>
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
                <td>{{ number_format($item->jumlah, 0, ',', '.') }}</td>
                <td>{{ \Carbon\Carbon::parse($item->date)->format('d-m-Y') }}</td>
            </tr>
            @endforeach
        </tbody>
         <tfoot>
            <tr class="total-row">
                <td colspan="4" style="text-align: right;">Total Jumlah:</td>
                <td colspan="2">Rp {{ number_format($pemasukan->sum('jumlah'), 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>

    <div class="page-break"></div>

    <h3>Pengeluaran</h3>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Kategori</th>
                <th>Jumlah Item</th>
                <th>Harga (Rp)</th>
                <th>Lain-lain (Rp)</th>
                <th>Total (Rp)</th>
                <th>Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pengeluaran as $key => $pgl)
            <tr>
                <td>{{ $key + 1 }}</td>
                <td>{{ $pgl->name }}</td>
                <td>{{ $pgl->category ? $pgl->category->name : 'Kategori tidak ditemukan' }}</td>
                <td>{{ $pgl->jumlah_satuan }}</td>
                <td>{{ number_format($pgl->nominal, 0, ',', '.') }}</td>
                <td>{{ number_format($pgl->dll, 0, ',', '.') }}</td>
                <td>{{ number_format($pgl->jumlah, 0, ',', '.') }}</td>
                <td>{{ $pgl->parentPengeluaran ? \Carbon\Carbon::parse($pgl->parentPengeluaran->tanggal)->format('d-m-Y') : 'Tanggal tidak ditemukan' }}</td>
            </tr>
            @endforeach
        </tbody>
         <tfoot>
            <tr class="total-row">
                <td colspan="6" style="text-align: right;">Total Jumlah:</td>
                <td colspan="2">Rp {{ number_format($pengeluaran->sum('jumlah'), 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>

    <div class="selisih">
        Selisih Antara Pemasukan dan Pengeluaran: Rp {{ number_format($selisih, 0, ',', '.') }}
    </div>
</body>
</html>
