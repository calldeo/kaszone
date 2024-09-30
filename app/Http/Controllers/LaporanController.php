<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LaporanExport;
use App\Models\Pemasukan;
use App\Models\Pengeluaran;
use PDF;
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

       public function laporanKas()
    {
 
        $pemasukan = Pemasukan::with('category')->paginate(10); 
        $totalJumlah = Pemasukan::sum('jumlah');
        

        
     
        return view('laporan.laporan-kas', compact('pemasukan', 'totalJumlah'));
    }

    public function exportLaporanPDF(Request $request)
{
    $year = $request->input('year');
    
    if ($year) {
        $pemasukan = Pemasukan::whereYear('date', $year)->get();
        
         $pengeluaran = Pengeluaran::whereHas('parentPengeluaran', function($query) use ($year) {
            $query->whereYear('tanggal',$year);    
        })->with('category', 'parentPengeluaran')->get();
    } else {
        $pemasukan = Pemasukan::all();
        $pengeluaran = Pengeluaran::with('category', 'ParentPengeluaran')->get();
    }
    
    $totalPemasukan = $pemasukan->sum('jumlah');
    $totalPengeluaran = $pengeluaran->sum('jumlah');
    $selisih = $totalPemasukan - $totalPengeluaran;

    $pdf = PDF::loadView('laporan.pdf', compact('pemasukan', 'pengeluaran', 'totalPemasukan', 'totalPengeluaran', 'selisih', 'year'));
    
    $pdf->setPaper('A4', 'portrait');
    
    return $pdf->stream($year ? "laporan_$year.pdf" : "laporan_seluruh.pdf");
}


public function exportLaporanExcel(Request $request)
{
    $year = $request->input('year');
    
    if ($year) {
        $pemasukan = Pemasukan::whereYear('date', $year)->get();
        
        $pengeluaran = Pengeluaran::whereHas('parentPengeluaran', function($query) use ($year) {
            $query->whereYear('tanggal', $year);    
        })->with('category', 'parentPengeluaran')->get();
    } else {
        $pemasukan = Pemasukan::all();
        $pengeluaran = Pengeluaran::with('category', 'parentPengeluaran')->get();
    }

    return Excel::download(new LaporanExport($pemasukan, $pengeluaran, $year), $year ? "laporan_$year.xlsx" : "laporan_seluruh.xlsx");
}


}
