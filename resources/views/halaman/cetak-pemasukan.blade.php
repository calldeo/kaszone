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
    <title>Data pemasukan</title>
</head>
<body>
    <div class="form-group">
        <div class="kop-surat">
            {{-- <img src="/foto_calon/print.png" alt="Logo" style="width: 115%;"> --}}
        </div>


        <table class="static" align="center" rules="all" border="1px" style="width: 95%;">
            <thead>
                <tr>
                    <th>No. </th>
                    <th>Nama pemasukan</th>
                    <th>Deskripsi</th>
                    <th>Kategori</th>
                    <th>Tanggal</th>
                    <th>Jumlah</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($pemasukan as $index => $ct)
                <tr>
                    <td style="text-align: center;">{{ $index + 1 }}</td>
                    <td style="text-align: center;">{{ $ct->name }}</td>
                    <td style="text-align: center;">{{ $ct->description }}</td>
                    <td style="text-align: center;">{{ $ct->category->name}}</td>
                    <td style="text-align: center;">{{ $ct->date}}</td>
                    <td style="text-align: center;">{{ number_format($ct->jumlah, 0, ',', '.') }}</td>
                </tr>
                @endforeach
                <tr>
                    <td colspan="5" style="text-align: right; font-weight: bold;">Total Jumlah:</td>
                    <td style="text-align: center; font-weight: bold;">
                        {{ number_format($pemasukan->sum('jumlah'), 0, ',', '.') }}
                    </td>
                </tr>
            </tbody>
        </table>
        
        {{-- <div class="tanda-tangan">
            <p>Mengetahui,</p> --}}
            {{-- <img src="/foto_kepala_sekolah/tanda_tangan.png" alt="Tanda Tangan Kepala Sekolah" style="width: 150px;"> --}}
            {{-- <p style="margin-bottom: 20px;">Kepala Sekolah</p>
            <p style="margin-top: 70px;">...................</p>
        </div> --}}
    </div> 
    <script>
        window.print();
    </script>
</body>
</html>