<?php
namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class LaporanExport implements FromView
{
    protected $pemasukan;
    protected $pengeluaran;
    protected $year;

    public function __construct($pemasukan, $pengeluaran, $year)
    {
        $this->pemasukan = $pemasukan;
        $this->pengeluaran = $pengeluaran;
        $this->year = $year;
    }

    public function view(): View
    {
        return view('laporan.excel', [
            'pemasukan' => $this->pemasukan,
            'pengeluaran' => $this->pengeluaran,
            'year' => $this->year,
        ]);
    }
}

