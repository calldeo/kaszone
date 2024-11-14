<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan PDF</title>
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
            font-size: 12px;
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
        .page-break {
            page-break-after: always;
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


    <h2>Pemasukan</h2>
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
                <td>Rp{{ number_format($item->jumlah, 0, ',', '.') }}</td>
                <td>{{ \Carbon\Carbon::parse($item->date)->format('d/m/Y') }}</td>
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

    <div class="page-break"></div>

    <h2>Pengeluaran</h2>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Kategori</th>
                <th>Jumlah Item</th>
                <th>Harga (Rp)</th>
                <th>Lain-lain (Rp)</th>
                <th>Jumlah (Rp)</th>
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
                <td>Rp{{ number_format($pgl->nominal, 0, ',', '.') }}</td>
                <td>Rp{{ number_format($pgl->dll, 0, ',', '.') }}</td>
                <td>Rp{{ number_format($pgl->jumlah, 0, ',', '.') }}</td>
                <td>{{ $pgl->parentPengeluaran ? \Carbon\Carbon::parse($pgl->parentPengeluaran->tanggal)->format('d/m/Y') : 'Tanggal tidak ditemukan' }}</td>
            </tr>
            @endforeach
        </tbody>
         <tfoot>
            <tr class="total-row">
                <td colspan="6" style="text-align: right;">Total Jumlah:</td>
                <td colspan="2">Rp{{ number_format($pengeluaran->sum('jumlah'), 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>

    <div class="selisih" style="font-size: 13px; font-weight: bold; margin-top: 20px;">
        Selisih Antara Pemasukan dan Pengeluaran: Rp{{ number_format($selisih, 0, ',', '.') }}
    </div>
</body>
</html>
