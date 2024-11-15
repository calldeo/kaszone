<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Excel</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 0 auto;
        }
        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: center;
        }
        .judul {
            text-align: center;
            font-size: 32px;
            font-weight: bold;
            border: none !important;
        }
        .header-row {
            background-color: #f2f2f2;
        }
        .judul h1 {
            margin: 20px 0;
            font-size: 28px;
            text-align: center;
        }
    </style>
</head>
<body>
    <table style="margin: 0 auto;">
        <tr>
            <td colspan="9" class="judul" style="border: none; text-align: center; font-size: 20px;">
                <h1><strong>Laporan Pemasukan dan Pengeluaran {{ $year ? $year : 'Seluruh' }}</strong></h1>
            </td>
        </tr>
    </table>

    <h2>Pemasukan</h2>
    <table>
        <thead>
            <tr class="header-row">
               <th style="text-align: center;">No</th>
                <th style="text-align: center;">Nama</th>
                <th style="text-align: center;">Deskripsi</th>
                <th style="text-align: center;">Kategori</th>
                <th style="text-align: center;">Jumlah</th>
                <th style="text-align: center;">Tanggal</th>
            </tr>
        </thead>
        <tbody>
           @foreach($pemasukan as $key => $item)
            <tr>
                <td style="text-align: center;">{{ $key + 1 }}</td>
                <td style="text-align: center;">{{ $item->name }}</td>
                <td style="text-align: center;">{{ $item->description }}</td>
                <td style="text-align: center;">{{ $item->category ? $item->category->name : 'Kategori tidak ditemukan' }}</td>
                <td style="text-align: center;">Rp{{ number_format($item->jumlah, 0, ',', '.') }}</td>
                <td style="text-align: center;">{{ \Carbon\Carbon::parse($item->date)->format('d-m-Y') }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="4" style="text-align: right;"><strong>Total Pemasukan:</strong></td>
                <td colspan="2" style="text-align: center;"><strong>Rp{{ number_format($pemasukan->sum('jumlah'), 0, ',', '.') }}</strong></td>
            </tr>
        </tfoot>
    </table>

    <h2>Pengeluaran</h2>
    <table>
        <thead>
            <tr class="header-row">
              <th style="text-align: center;">No</th>
                <th style="text-align: center;">Nama</th>
                <th style="text-align: center;">Deskripsi</th>
                <th style="text-align: center;">Kategori</th>
                <th style="text-align: center;">Jumlah Item</th>
                <th style="text-align: center;">Harga (Rp)</th>
                <th style="text-align: center;">Lain - Lain</th>
                <th style="text-align: center;">Total</th>
                <th style="text-align: center;">Tanggal</th>
            </tr>
        </thead>
        <tbody>
               @foreach($pengeluaran as $key => $pgl)
            <tr>
                <td style="text-align: center;">{{ $key + 1 }}</td>
                <td style="text-align: center;">{{ $pgl->name }}</td>
                <td style="text-align: center;">{{ $pgl->description }}</td>
                <td style="text-align: center;">{{ $pgl->category ? $pgl->category->name : 'Kategori tidak ditemukan' }}</td>
                <td style="text-align: center;">{{ $pgl->jumlah_satuan }}</td>
                <td style="text-align: center;">Rp{{ number_format($pgl->nominal, 0, ',', '.') }}</td>
                <td style="text-align: center;">Rp{{ $pgl->dll }}</td>
                <td style="text-align: center;">Rp{{ number_format($pgl->jumlah, 0, ',', '.') }}</td>
                <td style="text-align: center;">{{ $pgl->parentPengeluaran ? \Carbon\Carbon::parse($pgl->parentPengeluaran->tanggal)->format('d-m-Y') : 'Tanggal tidak ditemukan' }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="7" style="text-align: right;"><strong>Total Pengeluaran:</strong></td>
                <td colspan="2" style="text-align: center;"><strong>Rp{{ number_format($pengeluaran->sum('jumlah'), 0, ',', '.') }}</strong></td>
            </tr>
        </tfoot>
    </table>

    <h2>Selisih</h2>
    <table>
        <thead>
            <tr class="header-row">
                <th style="text-align: center;">Total Pemasukan</th>
                <th style="text-align: center;">Total Pengeluaran</th>
                <th style="text-align: center;">Selisih</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="text-align: center;">Rp{{ number_format($pemasukan->sum('jumlah'), 0, ',', '.') }}</td>
                <td style="text-align: center;">Rp{{ number_format($pengeluaran->sum('jumlah'), 0, ',', '.') }}</td>
                <td style="text-align: center;">Rp{{ number_format($pemasukan->sum('jumlah') - $pengeluaran->sum('jumlah'), 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>
</body>
</html>