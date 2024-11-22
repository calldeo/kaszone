<?php
    
namespace App\Http\Controllers;

use App\Models\User;
use App\Imports\UserImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Session;

class BendaharaController extends Controller
{
    public function bendahara(Request $request)
    {
        return view('user.user');
    }

    public function destroy($id)
    {
        try {
            $user = User::find($id);

            if ($user) {
                $user->forceDelete();
                return redirect('/user')->with('success', 'Data berhasil dihapus secara permanen');
            } else {
                return redirect('/user')->with('error', 'Data tidak ditemukan.');
            }
        } catch (\Exception $e) {
            return redirect('/user')->with('error', 'Gagal menghapus data. Silakan coba lagi.');
        }
    }

    public function create()
    {
        $roles = Role::all();
        return view('user.add', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'min:3', 'max:30', 'regex:/^[a-zA-Z\s]+$/', function ($attribute, $value, $fail) {
                if (User::where('name', $value)->exists()) {
                    $fail($attribute . ' is registered.');
                }
            }],
            'email' => 'required|unique:users,email',
            'password' => ['required', 'min:8', 'max:12'],
            'kelamin' => 'required',
            'alamat' => ['required', 'min:3', 'max:30'],
            'level' => 'required|array'
        ]);

        DB::begintransaction();
        try {
            $bendahara = new User();
            $bendahara->name = $request->name;
            $bendahara->email = $request->email;
            $bendahara->password = Hash::make($request->password);
            $bendahara->kelamin = $request->kelamin;
            $bendahara->alamat = $request->alamat;
            $bendahara->save();

            foreach ($request->level as $role) {
                $bendahara->assignRole($role);
            }

            DB::commit();

            return redirect('/user')->with('success', 'User berhasil ditambahkan.');
        } catch (\Throwable $th) {
            DB::rollback();

            return redirect('/user')->with('error', 'User gagal ditambahkan! ' . $th->getMessage());
        }
    }

    public function edit($id)
    {
        $roles = Role::all();
        $guruu = User::find($id);

        return view('user.edit', compact('guruu', 'roles'));
    }

    public function update(Request $request, $id)
    {
        $guruu = User::find($id);

        $request->validate([
            'name' => ['required', 'min:3', 'max:30', 'regex:/^[a-zA-Z\s]+$/'],
            'email' => 'required|email|unique:users,email,' . $guruu->id,
            'password' => ['nullable', 'min:8', 'max:12'],
            'kelamin' => 'required',
            'alamat' => ['required', 'min:3', 'max:30'],
            'roles' => 'required|array', 
            'roles.*' => 'exists:roles,name',
        ]);

        DB::beginTransaction();
        try {
            $guruu->name = $request->name;
            $guruu->email = $request->email;
            $guruu->kelamin = $request->kelamin;
            $guruu->alamat = $request->alamat;

            if ($request->filled('password')) {
                $guruu->password = Hash::make($request->password);
            }

            $guruu->save();

            $guruu->syncRoles($request->roles);

            DB::commit();

            return redirect('/user')->with('success', 'Data user berhasil diperbarui.');
        } catch (\Throwable $th) {
            DB::rollback();

            return redirect('/user')->with('error', 'User gagal diperbarui! ' . $th->getMessage());
        }
    }

    public function switchRole(Request $request)
    {
        $user = auth()->user();

        if (!session()->has('activeRole')) {
            if ($user->roles->count() === 1) {
                session(['activeRole' => $user->roles->first()->name]);
            }
        }

        Session::put('activeRole', $request->role);

        $hasRole = $user->hasRole(Session::get('activeRole'));

        if ($hasRole) {
            $activeRole = Session::get('activeRole');

            if ($activeRole) {
                $activeRole = \Spatie\Permission\Models\Role::where('name', $activeRole)->first();

                if ($activeRole) {
                    $permissions = $activeRole->permissions->pluck('name')->toArray();
                } else {
                    $permissions = [];
                }
            } else {
                $permissions = [];
            }

            Session::put('permissions', $permissions);

            $redirectPath = $activeRole === 'admin' ? '/user' : '/home';

            return redirect($redirectPath)->with('success', 'Role dan permissions berhasil diubah.');
        } else {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses ke peran ini.');
        }
    }

    public function showDetail($id)
    {
        $user = User::with('roles')->find($id);

        if (!$user) {
            return response()->json(['message' => 'Pengeluaran not found'], 404);
        }

        return response()->json([
            'name' => $user->name,
            'email' => $user->email,
            'kelamin' => $user->kelamin,
            'alamat' => $user->alamat,
        ]);
    }
}
