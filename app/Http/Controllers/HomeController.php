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
    // Hitung saldo yang tersedia
    // Ambil total pemasukan dan total pengeluaran
    $totalPemasukan = Pemasukan::sum('jumlah');
    $totalPengeluaran = Pengeluaran::sum('jumlah');

    $saldo = $totalPemasukan - $totalPengeluaran;

    // Ambil nilai minimal saldo dari SettingSaldo
    $minimalSaldo = SettingSaldo::first()->saldo ?? 0;

    // Passing data ke view
    return view('home', compact('totalPemasukan', 'totalPengeluaran', 'saldo', 'minimalSaldo'));
  }
}
