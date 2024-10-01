<?php

namespace App\Exports;

use App\Models\Pengeluaran;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class PengeluaranExport implements FromView
{
    protected $pengeluaran;
    protected $year;

    public function __construct( $pengeluaran, $year)
    {
        $this->pengeluaran = $pengeluaran;
        $this->year = $year;
    }

    public function view(): View
    {
        return view('pengeluaran.excel', [
            'pengeluaran' => $this->pengeluaran,
            'year' => $this->year,
        ]);
    }
}