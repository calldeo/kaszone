<?php

namespace App\Imports;

use App\Models\Category;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class KategoriImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {

           if (empty($row['nama'])) {
            \Log::warning('Name is missing in row: ' . json_encode($row));
            return null; // Jika name kosong, lewati baris ini
        }
        return new Category([
            'name' => $row['nama'],
            'jenis_kategori' => $row['jenis_kategori'],
            'description' => $row['deskripsi'],
        ]);
    }
}
