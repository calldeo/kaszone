<?php

namespace App\Http\Controllers;

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

}
