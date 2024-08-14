<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Imports\UserImport;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;

class BendaharaController extends Controller
{
    public function bendahara(Request $request)
    {
        return view('halaman.user');
    }

    public function destroy($id)
    {
        try {
            $user = User::find($id);

            if ($user) {
                $user->forceDelete(); // Menghapus data secara permanen
                return redirect('/user')->with('success', 'Data berhasil dihapus secara permanen');
            } else {
                return redirect('/user')->with('error', 'Data tidak ditemukan.');
            }
        } catch (\Exception $e) {
            return redirect('/user')->with('error', 'Gagal menghapus data. Silakan coba lagi.');
        }
    }

    public function add_user()
    {
        $roles = Role::all();
        return view('tambah.add_user', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'min:3', 'max:30', function ($attribute, $value, $fail) {
                // Check if the name already exists in the database
                if (User::where('name', $value)->exists()) {
                    $fail($attribute . ' is registered.');
                }
            }],
            'email' => 'required|unique:users,email',
            'password' => ['required', 'min:8', 'max:12'],
            'kelamin' => 'required',
            'alamat' => ['required', 'min:3', 'max:30'],
            'roles' => 'required|array',
            'roles.*' => 'exists:roles,name',
        ]);

        // Create the user
        $bendahara = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'alamat' => $request->alamat,
            'kelamin' => $request->kelamin,
        ]);
        $bendahara->assignRole($request->roles);

        return redirect('/user')->with('success', 'Data Berhasil Ditambahkan');
    }

    public function edit($id)
    {
        $roles = Role::all();
        $guruu = User::find($id);

        return view('edit.edit_user', compact('guruu', 'roles'));
    }

    public function update(Request $request, $id)
    {
        $guruu = User::find($id);

        $request->validate([
            'name' => ['required', 'min:3', 'max:30'],
            'email' => 'required|email|unique:users,email,' . $guruu->id,
            'password' => ['nullable', 'min:8', 'max:12'],
            'kelamin' => 'required',
            'alamat' => ['required', 'min:3', 'max:30'],
            'roles' => 'required|array',
            'roles.*' => 'exists:roles,name',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'kelamin' => $request->kelamin,
            'alamat' => $request->alamat,
        ];

        // Menambahkan password ke data hanya jika ada input password
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        // Mengupdate data user
        $guruu->update($data);

        // Mengupdate role yang dimiliki user
        $guruu->syncRoles($request->roles);

        // Mengalihkan pengguna berdasarkan peran baru mereka
        $redirectPath = $guruu->hasRole('admin') ? '/user' : '/home';

        return redirect($redirectPath)->with('update_success', 'Data Berhasil Diupdate');
    }

    public function bendaharaimportexcel(Request $request)
    {
        User::query()->where('level', 'bendahara')->delete();
        $file = $request->file('file');
        $namafile = $file->getClientOriginalName();
        $file->move('DataBendahara', $namafile);

        Excel::import(new UserImport, public_path('/DataBendahara/' . $namafile));
        return redirect('/bendahara')->with('success', 'Data Berhasil Ditambahkan');
    }
}
