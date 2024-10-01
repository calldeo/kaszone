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
            'id_data'=> $row[0],
            'name' => $row[1],
            'description' => $row[2],
            'date' => Date::excelToDateTimeObject($row[3]),// Konversi tanggal dari Excel
            'jumlah' => $jumlah, // Nilai yang sudah dipastikan valid
            'id'=> $row[5],
        ]);
    }
}
