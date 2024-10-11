<?php

namespace App\Imports;

use App\Models\Pemasukan;
use App\Models\Category;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class PemasukanImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        // Cek apakah jumlah adalah angka, jika tidak set default menjadi 
        \Log::info('Processing row: ' . json_encode($row));

          if (empty($row['nama'])) {
            \Log::warning('Name is missing in row: ' . json_encode($row));
            return null; // Jika name kosong, lewati baris ini
        }
        $jumlah = isset($row['jumlah']) && is_numeric($row['jumlah']) ? (float) $row['jumlah'] : 0;

        // Cari kategori berdasarkan kode dan jenis_kategori = 1


        return new Pemasukan([
            'name' => $row['nama'] ?? '', // Mengubah 'nama' menjadi 'name' dan menambahkan null coalescing operator
            'description' => $row['deskripsi'] ?? '', // Menambahkan null coalescing operator
            'date' => isset($row['tanggal']) ? Date::excelToDateTimeObject($row['tanggal']) : now(), // Menambahkan pengecekan
            'jumlah' => $jumlah,
            'id' => $row['kode_kategori'] ?? null,
        ]);
    }
}
