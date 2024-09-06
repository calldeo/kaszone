<?php

namespace App\Imports;

use App\Models\Pemasukan;
use Maatwebsite\Excel\Concerns\ToModel;

class PemasukanImport implements ToModel
{
    public function model(array $row)
{
    $jumlah = isset($row[4]) ? (float)$row[4] : 0;

    return new Pemasukan([
        'nama' => $row[0],
        'deskripsi' => $row[1],
        'kategori' => $row[2],
        'tanggal' => \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[3]),
        'jumlah' => $jumlah,
    ]);
}
}
