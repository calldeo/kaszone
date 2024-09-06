<?php

namespace App\Imports;

use App\Models\Pemasukan;
use Maatwebsite\Excel\Concerns\ToModel;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class PemasukanImport implements ToModel
{
    public function model(array $row)
    {
        // Cek apakah jumlah adalah angka, jika tidak set default menjadi 0
        $jumlah = isset($row[4]) && is_numeric($row[4]) ? (float) $row[4] : 0;

        return new Pemasukan([
            'nama' => $row[0],
            'deskripsi' => $row[1],
            'kategori' => $row[2],
            'tanggal' => Date::excelToDateTimeObject($row[3]), // Konversi tanggal dari Excel
            'jumlah' => $jumlah, // Nilai yang sudah dipastikan valid
        ]);
    }
}
