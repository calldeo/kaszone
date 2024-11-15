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
    <h1 style="text-align: center;">Laporan Pemasukan dan Pengeluaran Seluruh</h1>

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
                <td>Rp{{ number_format($item->jumlah, 0, ',', '.') }}</td>
                <td>{{ \Carbon\Carbon::parse($item->date)->format('d-m-Y') }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="4" style="text-align: right;"><strong>Total Pemasukan:</strong></td>
                <td colspan="2"><strong>Rp{{ number_format($pemasukan->sum('jumlah'), 0, ',', '.') }}</strong></td>
            </tr>
        </tfoot>
    </table>

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
                <td>Rp{{ number_format($pgl->nominal, 0, ',', '.') }}</td>
                <td>Rp{{ $pgl->dll }}</td>
                <td>Rp{{ number_format($pgl->jumlah, 0, ',', '.') }}</td>
                <td>{{ $pgl->parentPengeluaran ? \Carbon\Carbon::parse($pgl->parentPengeluaran->tanggal)->format('d-m-Y') : 'Tanggal tidak ditemukan' }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="7" style="text-align: right;"><strong>Total Pengeluaran:</strong></td>
                <td colspan="2"><strong>Rp{{ number_format($pengeluaran->sum('jumlah'), 0, ',', '.') }}</strong></td>
            </tr>
        </tfoot>
    </table>

    <h2>Selisih</h2>
    <table>
        <thead>
            <tr>
                <th>Total Pemasukan</th>
                <th>Total Pengeluaran</th>
                <th>Selisih</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Rp{{ number_format($pemasukan->sum('jumlah'), 0, ',', '.') }}</td>
                <td>Rp{{ number_format($pengeluaran->sum('jumlah'), 0, ',', '.') }}</td>
                <td>Rp{{ number_format($pemasukan->sum('jumlah') - $pengeluaran->sum('jumlah'), 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>
</body>
</html>
