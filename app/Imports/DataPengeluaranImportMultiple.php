<?php

namespace App\Imports;

use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class DataPengeluaranImportMultiple implements WithMultipleSheets
{
    
    protected $tanggal;

    public function __construct($tanggal)
    {
        $this->tanggal = $tanggal;
    }

    public function sheets(): array
    {
        return [
            Carbon::yesterday()->format('d-m-Y') => new DataPengeluaranImport($this->tanggal),
            Carbon::today()->format('d-m-Y') => new DataPengeluaranImport($this->tanggal),
        ];
    }
}
