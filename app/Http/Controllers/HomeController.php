<?php

namespace App\Http\Controllers;

use App\Models\Osis;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\SettingWaktu;
use Carbon\Carbon;
class HomeController extends Controller
{
    public function index()
    {
       
            return view('home');
      
        
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