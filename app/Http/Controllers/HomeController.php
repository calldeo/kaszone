<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Osis;
use App\Models\User;
use App\Models\Pemasukan;
use App\Models\Pengeluaran;
use App\Models\SettingWaktu;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
       
          // Ambil total pemasukan dan total pengeluaran
        $totalPemasukan = Pemasukan::sum('jumlah');
        $totalPemasukan1 = Pemasukan::sum('jumlah');
        $totalPengeluaran = Pengeluaran::sum('jumlah');

        // Hitung saldo yang tersedia
        $saldo = $totalPemasukan - $totalPengeluaran;

        // Passing data ke view
        return view('home', compact('totalPemasukan', 'totalPengeluaran', 'saldo','totalPemasukan1'));
      
        
    }
 public function petunjuk()
    {
        // return view('tambah.add_guruu');
         $settings = SettingWaktu::all();

            $expired = false;
    foreach ($settings as $setting) {
        if (Carbon::now()->greaterThanOrEqualTo($setting->waktu)) {
            $expired = true;
            break;
        }
    }
        // Meneruskan data ke tampilan
        return view('halaman.petunjuk', compact('expired','settings'));
    }

}