<?php

namespace App\Exports;

use App\Models\Category;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromArray;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\WithTitle;

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

class DataPengeluaranSheetExport implements FromArray, WithHeadings, WithTitle
{
    public function title(): string
    {
        return Carbon::yesterday()->format('d-m-Y');
    }

    public function array(): array
    {
        return [
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
        ];
    }

    public function headings(): array
    {
        return ['Nama Pengeluaran', 'Deskripsi', 'Jumlah Satuan', 'Nominal(Rp)', 'dll(Rp)', 'Total', 'Kategori'];
    }
}

class DataPengeluaranSheetExport1 implements FromArray, WithHeadings, WithTitle
{
    public function title(): string
    {
        return Carbon::today()->format('d-m-Y');
    }

    public function array(): array
    {
        return [
            ['', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', ''],
        ];
    }

    public function headings(): array
    {
        return ['Nama Pengeluaran', 'Deskripsi', 'Jumlah Satuan', 'Nominal(Rp)', 'dll(Rp)', 'Total', 'Kategori'];
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
        $categories = Category::select('id', 'jenis_kategori', 'name')->get()->toArray();
        return $categories;
    }

    public function headings(): array
    {
        return ['Kode', 'Jenis Kategori', 'Name'];
    }
}
