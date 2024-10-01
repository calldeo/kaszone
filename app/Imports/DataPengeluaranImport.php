<?php

namespace App\Imports;

use App\Models\Pengeluaran; // Model untuk tabel pengeluaran
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class DataPengeluaranImport implements ToModel, WithHeadingRow
{
    protected $tanggal;

    public function __construct($tanggal)
    {
        $this->tanggal = $tanggal;
    }

    public function model(array $row)
    {
        // Log row yang sedang diproses
        \Log::info('Processing row: ' . json_encode($row));

        // Validasi kolom name
        if (empty($row['name'])) {
            \Log::warning('Name is missing in row: ' . json_encode($row));
            return null; // Jika name kosong, lewati baris ini
        }

        return new Pengeluaran([
            'name' => $row['name'],
            'description' => $row['description'] ?? null,
            'jumlah_satuan' => $row['jumlah_satuan'] ?? null,
            'nominal' => $row['nominal'] ?? null,
            'dll' => $row['dll'] ?? null,
            'jumlah' => $row['jumlah'] ?? null,
            'id' => $row['id'] ?? null, // Ganti dengan nama kolom yang sesuai
            'tanggal' => $this->tanggal, // Menambahkan tanggal dari constructor
        ]);
    }
}
