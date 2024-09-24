<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Pemasukan;
use App\Models\Pengeluaran;

class LaporanController extends Controller
{
    //

    public function index()
    {
 
        $pemasukan = Pemasukan::with('category')->paginate(10); 
        $totalJumlah = Pemasukan::sum('jumlah');
        

        $pengeluaran = Pengeluaran::with('category')->paginate(10); 
        $totalJumlah1 = Pengeluaran::sum('jumlah');
     
        return view('laporan.laporan', compact('pemasukan', 'pengeluaran','totalJumlah','totalJumlah1'));
    }
}
