<?php

namespace App\Http\Controllers;

use App\Models\Pemasukan;
use App\Models\Pengeluaran;
use App\Models\SettingSaldo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class SettingController extends Controller
{
     public function role(Request $request)
    {
        return view('role.role');
    }

     public function edit($id)
    {
         $role = Role::findOrFail($id);
    $permissions = Permission::all();
    $rolePermissions = $role->permissions->pluck('id')->toArray();

    return view('role.edit', compact('role', 'permissions', 'rolePermissions'));
    }
    
  public function update(Request $request, $id)
{
    $role = Role::findOrFail($id);

    $request->validate([
        'name' => ['required', 'min:3', 'max:30'],
        'permissions' => ['required', 'array'],
    ]);

    $role->update([
        'name' => $request->name,
    ]);

    $role->permissions()->sync($request->permissions);

    return redirect('/role')->with('update_success', 'Role dan permissions berhasil diupdate');
}

 public function create()
    {
       return view('role.add');
    }

    public function store(Request $request)
    {
        $request->validate([
        'name' => ['required', 'min:3', 'max:30'],
        'guard_name' => ['required', 'min:3', 'max:30'],
        ]);

     DB::beginTransaction();
     try {
        $role = new Role();
        $role->name = $request->name;
        $role->guard_name = $request->guard_name;
       
        $role->save();
        DB::commit();
     } catch (\Throwable $th) {
        DB::rollback();
        return redirect('/role')->with('success', 'Role gagal ditambahkan!' . $th->getMessage());
     }
        return redirect('/role')->with('success', 'Role berhasil ditambahkan!');
    }



    public function saldo(Request $request)
    {
        $totalPemasukan = Pemasukan::sum('jumlah');
        $totalPengeluaran = Pengeluaran::sum('jumlah');
        
        $saldo = $totalPemasukan - $totalPengeluaran;

        $minimalSaldo = SettingSaldo::first()->saldo ?? 0;

        return view('saldo.saldo', compact('totalPemasukan', 'totalPengeluaran', 'saldo', 'minimalSaldo'));
    }

public function editMinimalSaldo()
{
    $settingSaldo = SettingSaldo::first();
    $minimalSaldo = $settingSaldo ? $settingSaldo->saldo : 0;
    
    $totalPemasukan = Pemasukan::sum('jumlah');
    $totalPengeluaran = Pengeluaran::sum('jumlah');
    $saldo = $totalPemasukan - $totalPengeluaran;
    
    return view('saldo.edit_saldo', compact('minimalSaldo', 'saldo'));
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

        return redirect()->route('saldo')->with('success', 'Minimal saldo berhasil diperbarui');
    } catch (\Exception $e) {
        return redirect()->route('saldo')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
    }
}
}
