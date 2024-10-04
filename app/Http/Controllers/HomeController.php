<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Osis;
use App\Models\User;
use App\Models\Pemasukan;
use App\Models\Pengeluaran;
use App\Models\SettingWaktu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
  public function index()
  {
    // dd(auth()->user()->getAllPermissions());
    // Hitung saldo yang tersedia
    // Ambil total pemasukan dan total pengeluaran
    $totalPemasukan = Pemasukan::sum('jumlah');
    $totalPengeluaran = Pengeluaran::sum('jumlah');

    $saldo = $totalPemasukan - $totalPengeluaran;

    // Passing data ke view
    return view('home', compact('totalPemasukan', 'totalPengeluaran', 'saldo'));
  }
}
