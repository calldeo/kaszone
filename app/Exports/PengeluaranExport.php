<?php

namespace App\Exports;

use App\Models\Pengeluaran;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PengeluaranExport implements FromCollection, WithHeadings, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Pengeluaran::all();
    }

    /**
     * @return array
     * Menentukan heading kolom
     */
    public function headings(): array
    {
        return [
            'No',
            'Nama',
            'Deskripsi',
            'Kategori',
            'Tanggal',
            'Jumlah (Rp)',
        ];
    }

    /**
     * @param Pengeluaran $pengeluaran
     * @return array
     * Mapping untuk menambah nomor urut secara otomatis
     */
    public function map($pengeluaran): array
    {
        static $rowNumber = 1;
        return [
            $rowNumber++, // Nomor urut
            $pengeluaran->name, // Pastikan nama kolom sesuai dengan di database
            $pengeluaran->description,
            $pengeluaran->category->name,
            $pengeluaran->date,
            $pengeluaran->jumlah,
        ];
    }
}