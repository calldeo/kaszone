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
        if (empty($row['nama_pengeluaran'])) {
            \Log::warning('Name is missing in row: ' . json_encode($row));
            return null; // Jika name kosong, lewati baris ini
        }

        return new Pengeluaran([
            'name' => $row['nama_pengeluaran'],
            'description' => $row['deskripsi'] ?? null,
            'jumlah_satuan' => $row['jumlah_satuan'] ?? null,
            'nominal' => $row['nominalrp'] ?? null,
            'dll' => $row['dllrp'] ?? null,
            'jumlah' => $row['total'] ?? null,
            'id' => $row['kategori'] ?? null, // Ganti dengan nama kolom yang sesuai
            'id_parent' => $row['id_parent'] ?? null, // Ganti dengan nama kolom yang sesuai
        ]);
    }
}
