<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            line-height: 1.4;
            color: #333;
            margin: 0;
            padding: 10px;
            font-size: 12px;
        }
        .kop-surat {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            margin-bottom: 30px;
        }
        .form-group {
            width: 90%;
            margin: 0 auto;
        }
        h1 {
            color: #2c3e50;
            text-align: center;
            margin-bottom: 20px;
            font-size: 18px;
        }
        table.static {
            width: 100%;
            border-collapse: collapse;
            margin: 20px auto;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        table.static th {
            background-color: #3498db;
            color: white;
            font-weight: bold;
            padding: 12px;
            font-size: 14px;
            border: 1px solid #ddd;
        }
        table.static td {
            padding: 10px;
            border: 1px solid #ddd;
            font-size: 12px;
        }
        table.static tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        .tanda-tangan {
            text-align: right;
            margin-top: 30px;
            padding-right: 50px;
        }
        .tanda-tangan p {
            margin: 5px 0;
        }
        @media print {
            body {
                padding: 20px;
            }
            table.static {
                page-break-inside: auto;
            }
            tr {
                page-break-inside: avoid;
                page-break-after: auto;
            }
        }
    </style>
    <title>Laporan Data Kategori</title>
</head>
<body>
    <div class="form-group">
        <div class="kop-surat">
            <h1>LAPORAN DATA KATEGORI</h1>
            <p>Tanggal: {{ date('d F Y') }}</p>
        </div>

        <table class="static">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Nama Kategori</th>
                    <th>Jenis Kategori</th>
                    <th>Deskripsi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($category as $key => $ct)
                <tr>
                    <td style="text-align: center;">{{ $key + 1 }}</td>
                    <td>{{ $ct->name }}</td>
                    <td style="text-align: center;">
                        @if($ct->jenis_kategori == 1)
                            Pemasukan
                        @elseif($ct->jenis_kategori == 2)
                            Pengeluaran
                        @else
                            Tidak Diketahui
                        @endif
                    </td>
                    <td>{{ $ct->description }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        {{-- <div class="tanda-tangan">
            <p>{{ date('d F Y') }}</p>
            <br><br><br>
            <p>(_________________)</p>
            <p>Admin</p>
        </div> --}}
    </div>

    <script>
        window.print();
    </script>
</body>
</html>
