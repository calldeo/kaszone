<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Excel</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }
    </style>
</head>
<body>
    <h1>Laporan Pemasukan  {{ $year ? $year : 'Seluruh' }}</h1>

    <h2>Pemasukan</h2>
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
</body>
</html>
