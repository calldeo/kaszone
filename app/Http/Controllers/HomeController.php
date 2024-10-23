<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Osis;
use App\Models\User;
use App\Models\Pemasukan;
use App\Models\Pengeluaran;
use App\Models\SettingSaldo;
use Illuminate\Http\Request;
use App\Models\ParentPengeluaran;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
  public function index()
  {
  
    $totalPemasukan = Pemasukan::sum('jumlah');
    $totalPengeluaran = Pengeluaran::sum('jumlah');

    $saldo = $totalPemasukan - $totalPengeluaran;

    $minimalSaldo = SettingSaldo::first()->saldo ?? 0;

    
    return view('home', compact('totalPemasukan', 'totalPengeluaran', 'saldo', 'minimalSaldo'));
  }


   public function getFinancialDataYearly(Request $request)
    {
        $year = $request->input('year');

        $pemasukanBulanan = array_fill(0, 12, 0);
        $pengeluaranBulanan = array_fill(0, 12, 0);

        $pemasukan = Pemasukan::whereYear('date', $year)->get();
        foreach ($pemasukan as $data) {
            $month = Carbon::parse($data->date)->month - 1;
            $pemasukanBulanan[$month] += $data->jumlah;
        }

        $parentPengeluaran = ParentPengeluaran::with('pengeluaran')
            ->whereYear('tanggal', $year)
            ->get();

        foreach ($parentPengeluaran as $parent) {
            $month = Carbon::parse($parent->tanggal)->month - 1;

            foreach ($parent->pengeluaran as $pengeluaran) {
                $pengeluaranBulanan[$month] += $pengeluaran->jumlah;
            }
        }

        return response()->json([
            'pemasukanBulanan' => $pemasukanBulanan,
            'pengeluaranBulanan' => $pengeluaranBulanan
        ]);
    }
}
