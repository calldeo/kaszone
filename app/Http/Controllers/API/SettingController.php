<?php

namespace App\Http\Controllers\API;

use App\Models\Pemasukan;
use App\Models\Pengeluaran;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\SettingSaldo; // Tambahkan ini untuk mengimport model SettingSaldo

class SettingController extends Controller
{

    public function editMinimalSaldo()
{
    $settingSaldo = SettingSaldo::first();
    $minimalSaldo = $settingSaldo ? $settingSaldo->saldo : 0;
    
    $totalPemasukan = Pemasukan::sum('jumlah');
    $totalPengeluaran = Pengeluaran::sum('jumlah');
    $saldo = $totalPemasukan - $totalPengeluaran;
    
    return response()->json([
        'status' => 200,
        'message' => 'Berhasil menampilkan data',
        'data'  => $settingSaldo,
    ],200);
}

    public function updateMinimalSaldo(Request $request)
    {
        try {
            $request->validate([
                'saldo_hidden' => 'required|numeric|min:0',
            ]);

            $settingSaldo = SettingSaldo::firstOrNew();
            $settingSaldo->saldo = $request->saldo_hidden;
            $settingSaldo->save();

            return response()->json([
                'status' => 200,
                'message' => 'Minimal saldo berhasil diperbarui',
                'data' => $settingSaldo,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 500);
        }
    }
    
}