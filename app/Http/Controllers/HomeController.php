<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Osis;
use App\Models\User;
use App\Models\Pemasukan;
use App\Models\Pengeluaran;
use App\Models\SettingSaldo;
use Illuminate\Http\Request;
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
}
