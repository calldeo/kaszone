<?php

namespace App\Imports;

use App\Models\Siswa;
use App\Models\Category;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Hash;

class KategoriImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
   public function model(array $row)
    {
        return new Category([
            'id' => $row[0],
            'name' => $row[1],
            'description' => $row[2],
            'jenis_kategori' => $row[3], // Tambahkan jenis_kategori
        ]);
    }
}
