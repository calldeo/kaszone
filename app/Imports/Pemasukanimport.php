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
          $jumlah = isset($row[3]) && is_numeric($row[3]) ? (float) $row[3] : 0;

    return new Pemasukan([
        'name' => $row[0],
        'description' => $row[1],
        'date' => \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[2]), // Konversi tanggal dari Excel
        'jumlah' => $jumlah, // Nilai yang sudah dipastikan valid
        'id' => $row[4],
        ]);
    }
}