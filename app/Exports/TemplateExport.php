<?php

namespace App\Exports;

use App\Models\Category;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromArray;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class TemplateExport implements WithMultipleSheets
{
    public function sheets(): array
    {
        return [
            new DataPengeluaranSheetExport(),
            new DataPengeluaranSheetExport1(),
            new CategorySheetExport(),
        ];
    }
}

class DataPengeluaranSheetExport implements FromArray, WithHeadings, WithTitle, WithStyles
{
    public function title(): string
    {
        return Carbon::yesterday()->format('d-m-Y');
    }

    public function array(): array
    {
        return [
            ['Nama Pengeluaran', 'Deskripsi', 'Jumlah Satuan', 'Nominal (Rp)', 'dll (Rp)', 'Kategori'],
        ];
    }

    public function headings(): array
    {
        return ['Nama Pengeluaran', 'Deskripsi', 'Jumlah Satuan', 'Nominal (Rp)', 'dll (Rp)', 'Kode Kategori'];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->setCellValue('A1', 'Import Data Pengeluaran');
        $sheet->mergeCells('A1:F1');
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A1')->getFont()->setBold(true);

        $sheet->setCellValue('H2', 'Keterangan');
        $sheet->getStyle('H2')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('FFFF00');
        
        $sheet->setCellValue('H3', '1. Pengisian data dimulai dari baris ke-3');
        $sheet->setCellValue('H4', '2. Kolom F (Kode Kategori) diisi sesuai kode pada sheet Jenis Kategori');
        $sheet->setCellValue('H5', '3. Kolom C (Jumlah Satuan) hanya boleh diisi angka');
        $sheet->setCellValue('H6', '4. Kolom D (Nominal (Rp)) hanya boleh diisi angka tanpa Rp, titik (.), atau koma (,)');
        $sheet->setCellValue('H7', '5. Kolom E (dll (Rp)) hanya boleh diisi angka tanpa Rp, titik (.), atau koma (,)');
    }
}

class DataPengeluaranSheetExport1 implements FromArray, WithHeadings, WithTitle, WithStyles  
{
    public function title(): string
    {
        return Carbon::today()->format('d-m-Y');
    }

    public function array(): array
    {
        return [
            ['Nama Pengeluaran', 'Deskripsi', 'Jumlah Satuan', 'Nominal (Rp)', 'dll (Rp)', 'Kategori'],
        ];
    }

    public function headings(): array
    {
        return ['Nama Pengeluaran', 'Deskripsi', 'Jumlah Satuan', 'Nominal (Rp)', 'dll (Rp)', 'Kode Kategori'];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->setCellValue('A1', 'Import Data Pengeluaran');
        $sheet->mergeCells('A1:F1');
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A1')->getFont()->setBold(true);

        $sheet->setCellValue('H2', 'Keterangan');
        $sheet->getStyle('H2')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('FFFF00');
        
        $sheet->setCellValue('H3', '1. Pengisian data dimulai dari baris ke-3');
        $sheet->setCellValue('H4', '2. Kolom F (Kode Kategori) diisi sesuai kode pada sheet Jenis Kategori');
        $sheet->setCellValue('H5', '3. Kolom C (Jumlah Satuan) hanya boleh diisi angka');
        $sheet->setCellValue('H6', '4. Kolom D (Nominal (Rp)) hanya boleh diisi angka tanpa Rp, titik (.), atau koma (,)');
        $sheet->setCellValue('H7', '5. Kolom E (dll (Rp)) hanya boleh diisi angka tanpa Rp, titik (.), atau koma (,)');
    }
}

class CategorySheetExport implements FromArray, WithHeadings, WithTitle
{
    public function title(): string
    {
        return 'Jenis Kategori';
    }

    public function array(): array
    {
        // Mengambil kategori dengan jenis kategori 'pengeluaran'
        $categories = Category::select('id', 'name')
            ->where('jenis_kategori', '2') // Tambahkan kondisi untuk filter
            ->get()
            ->toArray();

        return $categories;
    }

    public function headings(): array
    {
        return ['Kode', 'Name'];
    }
}
