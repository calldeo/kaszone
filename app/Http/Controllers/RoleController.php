<?php

namespace App\Http\Controllers;

use App\Models\Pemasukan;
use App\Models\Pengeluaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    //
     public function role(Request $request)
    {
        
        // Meneruskan data ke tampilan
        return view('halaman.role');
    }


  

     public function edit($id)
    {
         $role = Role::findOrFail($id);
    $permissions = Permission::all(); // Ambil semua permissions
    $rolePermissions = $role->permissions->pluck('id')->toArray(); // Ambil ID permissions yang terkait dengan role

    return view('edit.edit_role', compact('role', 'permissions', 'rolePermissions'));
    }


    
  public function update(Request $request, $id)
{
    $role = Role::findOrFail($id);

    // Validasi input
    $request->validate([
        'name' => ['required', 'min:3', 'max:30'],
        'guard_name' => ['required', 'min:3', 'max:30'],
        'permissions' => ['required', 'array'], // Pastikan permissions adalah array
    ]);

    // Update data role
    $role->update([
        'name' => $request->name,
        'guard_name' => $request->guard_name,
    ]);

    // Sinkronisasi permissions dengan role
    $role->permissions()->sync($request->permissions);

    return redirect('/role')->with('update_success', 'Role dan permissions berhasil diupdate');
}


 public function create()
    {
       
        return view('tambah.add_role');
    }

    public function store(Request $request)
    {
        $request->validate([
        'name' => ['required', 'min:3', 'max:30'],
        'guard_name' => ['required', 'min:3', 'max:30'],
        ]);

     DB::beginTransaction();
     try {
        //code... 
        $role = new Role();
        $role->name = $request->name;
        $role->guard_name = $request->guard_name;
       

        
        // dd($pemasukan);
        $role->save();
        DB::commit();
     } catch (\Throwable $th) {
        DB::rollback();
        return redirect('/role')->with('success', 'Role gagal ditambahkan!' . $th->getMessage());

        //throw $th;
     }
        return redirect('/role')->with('success', 'Role berhasil ditambahkan!');
       
        // Pemasukan::create($request->all());

    }



    public function saldo(Request $request)
    {
        
       $totalPemasukan = Pemasukan::sum('jumlah');
    $totalPengeluaran = Pengeluaran::sum('jumlah');
        
    $saldo = $totalPemasukan - $totalPengeluaran;

    // Passing data ke view
    return view('halaman.saldo', compact('totalPemasukan', 'totalPengeluaran', 'saldo'));
    }


public function editMinimalSaldo()
{
    // Ambil nilai minimal saldo dari database atau file konfigurasi
    // Contoh menggunakan model Setting:
    // $minimalSaldo = Setting::where('key', 'minimal_saldo')->first()->value ?? 0;
    
    $minimalSaldo = 0; // Ganti dengan nilai sebenarnya dari database
    
    // Ambil saldo saat ini
    $totalPemasukan = Pemasukan::sum('jumlah');
    $totalPengeluaran = Pengeluaran::sum('jumlah');
    $saldo = $totalPemasukan - $totalPengeluaran;
    
    return view('edit.edit_saldo', compact('minimalSaldo', 'saldo'));
}
public function updateMinimalSaldo(Request $request)
{
    $request->validate([
        'minimal_saldo' => 'required|numeric|min:0'
    ]);

    $minimalSaldo = $request->minimal_saldo;

    // Simpan minimal saldo ke database atau file konfigurasi
    // Contoh menggunakan model Setting:
    // Setting::updateOrCreate(['key' => 'minimal_saldo'], ['value' => $minimalSaldo]);

    return redirect()->back()->with('success', 'Minimal saldo berhasil diperbarui');
}
}
