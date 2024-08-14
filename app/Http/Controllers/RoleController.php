<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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

}
