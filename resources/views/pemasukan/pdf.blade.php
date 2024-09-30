<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pemasukan PDF</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        /* CSS untuk memulai halaman baru */
        .page-break {
            page-break-before: always;
            margin: 20px 0; /* optional: memberi jarak */
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
                <td>{{ number_format($item->jumlah, 0, ',', '.') }}</td>
                <td>{{ \Carbon\Carbon::parse($item->date)->format('d-m-Y') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>


</body>
</html>
