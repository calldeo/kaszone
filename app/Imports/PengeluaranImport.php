<?php

namespace App\Imports;

use Carbon\Carbon;
use App\Models\Pengeluaran;
use Maatwebsite\Excel\Concerns\ToModel;

class PengeluaranImport implements ToModel
{
  
    public function model(array $row)
    {
        // Lewati baris jika kolom 'name' kosong
        if (empty($row[1])) {
            return null; // Lewati baris
        }

        return new Pengeluaran([
       'name' => $row[0],
        'description'=> $row[1],
        'jumlah'=> $row[2],
        'jumlah_satuan'=> $row[3],
        'nominal'=> $row[4],
        'dll'=> $row[5],
        'image  '=> $row[6],
        'id' => $row[7],
        'id_parent'=> $row[8], // Default ke tanggal sekarang jika tidak ada
        ]);
    }
}
