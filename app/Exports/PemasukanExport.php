<?php

namespace App\Exports;

use App\Models\Pemasukan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PemasukanExport implements FromCollection, WithHeadings, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Pemasukan::all();
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
     * @param Pemasukan $pemasukan
     * @return array
     * Mapping untuk menambah nomor urut secara otomatis
     */
    public function map($pemasukan): array
    {
        static $rowNumber = 1;
        return [
            $rowNumber++, // Nomor urut
            $pemasukan->name, // Pastikan nama kolom sesuai dengan di database
            $pemasukan->description,
            $pemasukan->category->name,
            $pemasukan->date,
            $pemasukan->jumlah,
        ];
    }
}