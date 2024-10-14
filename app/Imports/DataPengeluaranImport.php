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
        \Log::info('Memproses baris: ' . json_encode($row));

        // Validasi kolom name
        if (empty($row['nama_pengeluaran'])) {
            \Log::warning('Nama tidak ada pada baris: ' . json_encode($row));
            return null; // Jika name kosong, lewati baris ini
        }

        // Hitung jumlah otomatis
        $jumlah_satuan = $row['jumlah_satuan'] ?? 0;
        $nominal = $row['nominalrp'] ?? 0;
        $dll = $row['dllrp'] ?? 0;
        $jumlah = ($jumlah_satuan * $nominal) + $dll;

        return new Pengeluaran([
            'name' => $row['nama_pengeluaran'],
            'description' => $row['deskripsi'] ?? null,
            'jumlah_satuan' => $jumlah_satuan,
            'nominal' => $nominal,
            'dll' => $dll,
            'jumlah' => $jumlah,
            'id' => $row['kategori'] ?? null, // Ganti dengan nama kolom yang sesuai
            'id_parent' => $row['id_parent'] ?? null, // Ganti dengan nama kolom yang sesuai
        ]);
    }
}
