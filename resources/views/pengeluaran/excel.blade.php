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
    <h1>Laporan  Pengeluaran {{ $year ? $year : 'Seluruh' }}</h1>

 

    <h2>Pengeluaran</h2>
    <table>
        <thead>
            <tr>
              <th>No</th>
                <th>Nama</th>
                <th>Deskripsi</th>
                <th>Kategori</th>
                <th>Jumlah Item</th>
                <th>Harga (Rp)</th>
                <th>Lain - Lain</th>
                <th>Total</th>
                {{-- <th>Bukti Pembayaran</th> --}}
                <th>Tanggal</th>
            </tr>
        </thead>
        <tbody>
               @foreach($pengeluaran as $key => $pgl)
            <tr>
                <td>{{ $key + 1 }}</td>
                <td>{{ $pgl->name }}</td>
                <td>{{ $pgl->description }}</td>
                <td>{{ $pgl->category ? $pgl->category->name : 'Kategori tidak ditemukan' }}</td>
                <td>{{ $pgl->jumlah_satuan }}</td>
                <td>{{ $pgl->nominal }}</td>
                <td>{{ $pgl->dll }}</td>
                <td>{{ number_format($pgl->jumlah, 0, ',', '.') }}</td>
                {{-- <td>
                    @if($item->image) 
                        <img src="{{ public_path('public/str/images/' . $item->image) }}" class="image" alt="Bukti Pembayaran">
                    @else
                        Tidak ada gambar
                    @endif --}}
                <td>{{ $pgl->parentPengeluaran ? \Carbon\Carbon::parse($pgl->parentPengeluaran->tanggal)->format('d-m-Y') : 'Tanggal tidak ditemukan' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
