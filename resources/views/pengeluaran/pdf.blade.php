<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan PDF</title>
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


    <h3>Pengeluaran</h3>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama</th>
                {{-- <th>Deskripsi</th> --}}
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
                {{-- <td>{{ $pgl->description }}</td> --}}
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

    <h4>Total Pengeluaran: Rp{{ number_format($totalPengeluaran, 0, ',', '.') }}</h4>
</body>
</html>
