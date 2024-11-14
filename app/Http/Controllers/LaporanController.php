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

        return view('laporan.laporan', compact('pemasukan', 'pengeluaran', 'totalJumlah', 'totalJumlah1'));
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
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $pemasukanQuery = Pemasukan::query();
        $pengeluaranQuery = Pengeluaran::with('category', 'parentPengeluaran');

        if ($year) {
            $pemasukanQuery->whereYear('date', $year);
            $pengeluaranQuery->whereHas('parentPengeluaran', function ($query) use ($year) {
                $query->whereYear('tanggal', $year);
            });
        }

        if ($startDate && $endDate) {
            $pemasukanQuery->whereBetween('date', [$startDate, $endDate]);
            $pengeluaranQuery->whereHas('parentPengeluaran', function ($query) use ($startDate, $endDate) {
                $query->whereBetween('tanggal', [$startDate, $endDate]);
            });
        }

        $pemasukan = $pemasukanQuery->get();
        $pengeluaran = $pengeluaranQuery->get();

        $totalPemasukan = $pemasukan->sum('jumlah');
        $totalPengeluaran = $pengeluaran->sum('jumlah');
        $selisih = $totalPemasukan - $totalPengeluaran;

        $pdf = PDF::loadView('laporan.pdf', compact('pemasukan', 'pengeluaran', 'totalPemasukan', 'totalPengeluaran', 'selisih', 'year', 'startDate', 'endDate'));

        $pdf->setPaper('A4', 'portrait');

        $filename = "laporan";
        if ($year) {
            $filename .= "_$year";
        }
        if ($startDate && $endDate) {
            $startDateFormatted = date('d-m-Y', strtotime($startDate));
            $endDateFormatted = date('d-m-Y', strtotime($endDate));
            $filename .= "_" . $startDateFormatted . "_" . $endDateFormatted;
        }
        $filename .= ".pdf";

        return $pdf->stream($filename);
    }

    public function exportLaporanExcel(Request $request)
    {
        $year = $request->input('year');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $pemasukanQuery = Pemasukan::query();
        $pengeluaranQuery = Pengeluaran::with('category', 'parentPengeluaran');

        if ($year) {
            $pemasukanQuery->whereYear('date', $year);
            $pengeluaranQuery->whereHas('parentPengeluaran', function ($query) use ($year) {
                $query->whereYear('tanggal', $year);
            });
        }

        if ($startDate && $endDate) {
            $pemasukanQuery->whereBetween('date', [$startDate, $endDate]);
            $pengeluaranQuery->whereHas('parentPengeluaran', function ($query) use ($startDate, $endDate) {
                $query->whereBetween('tanggal', [$startDate, $endDate]);
            });
        }

        $pemasukan = $pemasukanQuery->get();
        $pengeluaran = $pengeluaranQuery->get();

        $filename = "laporan";
        if ($year) {
            $filename .= "_$year";
        }
        if ($startDate && $endDate) {
            $startDateFormatted = date('d-m-Y', strtotime($startDate));
            $endDateFormatted = date('d-m-Y', strtotime($endDate));
            $filename .= "_" . $startDateFormatted . "_" . $endDateFormatted;
        }
        $filename .= ".xlsx";

        return Excel::download(new LaporanExport($pemasukan, $pengeluaran, $year, $startDate, $endDate), $filename);
    }

    
}
