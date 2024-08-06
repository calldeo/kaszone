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
    <title>Laporan Hasil Polling</title>
</head>
<body>
    <div class="form-group">
        <div class="kop-surat">
            <img src="/foto_calon/print.png" alt="Logo" style="width: 115%;">
        </div>
        <div class="berita-acara">
            <h2>Berita Acara</h2>
            <p style="margin-bottom: 10px; text-indent: 20px;">Pada hari ini, tanggal <?php echo date('d F Y'); ?>, telah dilaksanakan pemilihan Ketua dan Wakil Ketua Organisasi Siswa Intra Sekolah (OSIS) di SMKN 1 TAPEN. Setelah proses pemungutan dan penghitungan suara, {{ $calonTerpilih->nama_calon }} terpilih sebagai Ketua OSIS periode 2024/2025 dengan jumlah suara {{ $calonTerpilih->jumlah_vote }} suara.</p>
            <p style="margin-bottom: 10px;text-indent: 20px;">Dokumen ini menjadi catatan resmi hasil pemilihan Ketua dan Wakil Ketua OSIS. Demikianlah berita acara ini dibuat dengan sebenarnya untuk menjadi catatan resmi hasil pemilihan Ketua dan Wakil Ketua OSIS SMKN 1 TAPEN.</p>
        </div>

        <table class="static" align="center" rules="all" border="1px" style="width: 95%;">
            <thead>
                <tr>
                    <th>No. Urut</th>
                    <th>Nama Calon</th>
                    <th>Jumlah</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($cosis as $calon)
                <tr>
                    <td style="text-align: center;">{{ $calon->id }}</td>
                    <td style="text-align: center;">{{ $calon->nama_calon }}</td>
                    <td style="text-align: center;">{{ $calon->jumlah_vote }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        
        <div class="tanda-tangan">
            <p>Mengetahui,</p>
            {{-- <img src="/foto_kepala_sekolah/tanda_tangan.png" alt="Tanda Tangan Kepala Sekolah" style="width: 150px;"> --}}
            <p style="margin-bottom: 20px;">Kepala Sekolah</p>
            <p style="margin-top: 70px;">...................</p>
        </div>
    </div>
    <script>
        window.print();
    </script>
</body>
</html>
