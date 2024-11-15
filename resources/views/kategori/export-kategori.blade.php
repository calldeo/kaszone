<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        .kop-surat {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            margin-bottom: 20px;
        }
        .form-group {
            width: 80%;
            margin: 0 auto;
            text-align: center;
        }
        .berita-acara {
            text-align: justify;
            padding: 10px;
            line-height: 1.5;
        }
        .berita-acara h2 {
            text-align: center;
        }
        table.static {
            width: 95%;
            margin: 20px auto;
            border-collapse: collapse;
            border: 1px solid #543535;
        }
        table.static th, table.static td {
            border: 1px solid #543535;
            padding: 8px;
            text-align: center;
        }
        .tanda-tangan {
            text-align: right;
            margin-top: 20px;
        }
        .tanda-tangan p {
            margin: 0;
        }
        @media print {
            .kop-surat {
                display: flex;
                flex-direction: column;
                align-items: center;
                text-align: center;
                margin: 0 auto;
            }
        }
    </style>
    <title>Data Kategori</title>
</head>
<body>
    <div class="form-group">
        <div class="kop-surat">
        </div>
       

        <table class="static" align="center" rules="all" border="1px" style="width: 95%;">
            <thead>
                <tr>
                    <th>No. </th>
                    <th>Nama Kategori</th>
                    <th>Jenis Kategori</th>
                    <th>Deksripsi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($category as $key => $ct)
                <tr>
                     <td style="text-align: center;">{{ $key + 1 }}</td>
                    <td style="text-align: center;">{{ $ct->name }}</td>
                    <td style="text-align: center;">
                        @if($ct->jenis_kategori == 1)
                            Pemasukan
                        @elseif($ct->jenis_kategori == 2)
                            Pengeluaran
                        @else
                            Tidak Diketahui
                        @endif
                    </td>
                    <td style="text-align: center;">{{ $ct->description }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        
       
    <script>
        window.print();
    </script>
</body>
</html>
