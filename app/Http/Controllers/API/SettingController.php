<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\SettingSaldo; // Tambahkan ini untuk mengimport model SettingSaldo

class SettingController extends Controller
{
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