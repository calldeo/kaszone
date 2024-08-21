<?php

namespace App\Http\Controllers;

use DB;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Category;
use App\Models\Pemasukan;
use App\Models\Pengeluaran;
use App\Models\SettingWaktu;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;


class AdminController extends Controller
{
    public function admin(Request $request)
    {
        $search = $request->search; 
        // $users = User::where('name', 'LIKE', '%' . $request->search . '%')->paginate(10);

        // // Mengambil semua data user dengan level admin
        // $users = User::where('level', 'admin')->paginate(10);
       
        // Meneruskan data ke tampilan
        return view('halaman.admin');
    }
   
   public function table(Request $request)
{
    if ($request->ajax()) {
        // Query users with the 'admin' role
        $admins = User::role('admin') // Filter users by the 'admin' role
                      ->with('roles') // Eager load roles
                      ->select(['id', 'name', 'email',  'kelamin', 'alamat'])
                      ->get();

        return DataTables::of($admins)
            ->addIndexColumn() // Menambahkan indeks otomatis
            ->addColumn('roles', function ($row) {
                // Mengambil nama role dan menggabungkannya menjadi string
                return $row->roles->pluck('name')->implode(', ');
            })
            ->addColumn('opsi', function ($row) {
                return '
                    <div class="d-flex align-items-center">
                        <form action="/admin/' . $row->id . '/edit_admin" method="GET" class="mr-1">
                            <button type="submit" class="btn btn-warning btn-xs"><i class="fa fa-pencil"></i></button>
                        </form>
                        <form action="/admin/' . $row->id . '/destroy" method="POST">
                            ' . csrf_field() . '
                            ' . method_field('DELETE') . '
                            <button type="submit" class="btn btn-danger btn-xs"><i class="fa fa-trash"></i></button>
                        </form>
                    </div>
                ';
            })
            ->rawColumns(['roles', 'opsi']) // Pastikan kolom ini dianggap sebagai HTML
            ->make(true);
    }
}


    public function destroy($id)
    {
        try {
            $user = User::find($id);
            
            if ($user) {
                $user->forceDelete(); // Menghapus data secara permanen
                return redirect('/admin')->with('success', 'Data berhasil dihapus secara permanen');
            } else {
                return redirect('/admin')->with('error', 'Data tidak ditemukan.');
            }
        } catch (\Exception $e) {
            return redirect('/admin')->with('error', 'Gagal menghapus data. Silakan coba lagi.');
        }
    }

    public function add_admin()
    {
      
    $roles = Role::all();
        return view('tambah.add_admin',compact('roles'));
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
    ]);

    // Create the user
   $admin =  User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            // 'level' => $request->level,  // Menambahkan kolom level
            'alamat'=> $request->alamat,
            'kelamin'=> $request->kelamin,

        ]);
        $admin->assignRole($request->level);

    return redirect('/admin')->with('success', 'Data Berhasil Ditambahkan');
}


    public function edit($id)
    {
        $admin = User::find($id);
        unset($admin->password); // Jangan mengirimkan password ke tampilan


        return view('edit.edit_admin', compact( 'admin'));
    }

    public function update(Request $request, $id)
    {
        $admin = User::find($id);

        $request->validate([
            'name' => ['required', 'min:3', 'max:30'],
            'email' => 'required|email|unique:users,email,' . $admin->id,
            'password' => ['nullable', 'min:8', 'max:12'], // Mengubah menjadi nullable
            'kelamin' => 'required',
            'alamat' => ['required', 'min:3', 'max:30'],

        ]);

        $data = [
            'name' => $request->name,
        
            'email' => $request->email,
            'kelamin' => $request->kelamin,
            'alamat' => $request->alamat,

        ];
         $admin->assignRole($request->level);

        // Menambahkan password ke data hanya jika ada input password
        if ($request->filled('password')) {
            $data['password'] = bcrypt($request->password);
        }

        $admin->update($data);

        return redirect('/admin')->with('update_success', 'Data Berhasil Diupdate');
    }

 
    







    public function users(Request $request) //bendahara
{
    if ($request->ajax()) {
        // Ambil pengguna dengan peran 'bendahara' dan 'admin'
        $bendahara = User::role(['bendahara', 'admin']) // Mengambil pengguna dengan peran 'bendahara' atau 'admin'
                      ->with('roles') // Eager load roles
                      ->select(['id', 'name', 'email', 'kelamin', 'alamat'])
                      ->get();

        return DataTables::of($bendahara)
            ->addIndexColumn() // Menambahkan indeks otomatis
            ->addColumn('roles', function ($row) {
                // Mengambil nama role dan menggabungkannya menjadi string
                return $row->roles->pluck('name')->implode(', ');
            })
            ->addColumn('opsi', function ($row) {
                return '
                    <div class="d-flex align-items-center">
                        <form action="/user/' . $row->id . '/edit_user" method="GET" class="mr-1">
                            <button type="submit" class="btn btn-warning btn-xs"><i class="fa fa-pencil"></i></button>
                        </form>
                        <form action="/user/' . $row->id . '/destroy" method="POST">
                            ' . csrf_field() . '
                            ' . method_field('DELETE') . '
                            <button type="submit" class="btn btn-danger btn-xs"><i class="fa fa-trash"></i></button>
                        </form>
                    </div>
                ';
            })
            ->rawColumns(['roles', 'opsi']) // Pastikan kolom ini dianggap sebagai HTML
            ->make(true);
    }
}



   public function kategoris(Request $request) // KATEGORI
{
    if ($request->ajax()) {
        $categories = Category::select(['id', 'name', 'description'])->get();

        return DataTables::of($categories)
            ->addIndexColumn() 
            ->addColumn('opsi', function ($row) {
                return '
                    <div class="d-flex align-items-center">
                    
                        <form action="/kategori/' . $row->id . '/edit_kategori" method="GET" class="mr-1">
                            <button type="submit" class="btn btn-warning btn-xs"><i class="fa fa-pencil"></i></button>
                        </form>
                        <button type="button" class="btn btn-info btn-xs mr-1" data-toggle="modal" data-target="#adminDetailModal" data-url="/kategori/' . $row->id . '/detail">
                    <i class="fa fa-eye"></i>
                    </button>
                        <form action="/kategori/' . $row->id . '/destroy" method="POST">
                            ' . csrf_field() . '
                            ' . method_field('DELETE') . '
                            <button type="submit" class="btn btn-danger btn-xs"><i class="fa fa-trash"></i></button>
                        </form>
                        
                    </div>
                ';
            })
            ->rawColumns(['opsi'])
            ->make(true);
    }
}



    public function income(Request $request) // PEMASUKAN
    {
        if ($request->ajax()) {
            $pemasukan = Pemasukan::with('category')->select(['id_data', 'name', 'description', 'date', 'jumlah', 'id']);

            return DataTables::of($pemasukan)
                ->addIndexColumn()
                ->addColumn('category', function ($row) {
                    return $row->category ? $row->category->name : 'Tidak ada kategori';
                })
                ->addColumn('opsi', function ($row) {
                    return '
                    <div class="d-flex align-items-center">
                        <a href="/pemasukan/' . $row->id_data . '/edit_pemasukan" class="btn btn-warning btn-xs mr-1"><i class="fa fa-pencil"></i></a>
                          <button type="button" class="btn btn-info btn-xs mr-1" data-toggle="modal" data-target="#adminDetailModal" data-url="/pemasukan/' . $row->id_data . '/detail">
                    <i class="fa fa-eye"></i>
                    </button>
                        <form action="/pemasukan/' . $row->id_data . '/destroy" method="POST" style="display:inline;">
                            ' . csrf_field() . '
                            ' . method_field('DELETE') . '
                            <button type="submit" class="btn btn-danger btn-xs"><i class="fa fa-trash"></i></button>
                        </form>
                    </div>';
                })
                ->rawColumns(['opsi'])
                ->make(true);
        }
    }




 public function production(Request $request) // PENGELUARAN
    {
        if ($request->ajax()) {
            $pengeluaran = Pengeluaran::with('category')->select(['id_data', 'name', 'description', 'date', 'jumlah', 'id']);

            return DataTables::of($pengeluaran)
                ->addIndexColumn()
                ->addColumn('category', function ($row) {
                    return $row->category ? $row->category->name : 'Tidak ada kategori';
                })
                ->addColumn('opsi', function ($row) {
                    return '
                    <div class="d-flex align-items-center">
                   
                    <a href="/pengeluaran/' . $row->id_data . '/edit_pengeluaran" class="btn btn-warning btn-xs mr-1"><i class="fas fa-pencil-alt"></i>
</a>
   <button type="button" class="btn btn-info btn-xs mr-1" data-toggle="modal" data-target="#adminDetailModal" data-url="/pengeluaran/' . $row->id_data . '/detail" >
                    <i class="fa fa-eye"></i>
                    </button>
                        <form action="/pengeluaran/' . $row->id_data . '/destroy" method="POST" style="display:inline;">
                            ' . csrf_field() . '
                            ' . method_field('DELETE') . '
                            <button type="submit" class="btn btn-danger btn-xs"><i class="fa fa-trash"></i></button>
                        </form>
                    </div>';
                })
                ->rawColumns(['opsi'])
                ->make(true);
        }
    }

public function roles(Request $request) // BENDAHARA
{
    if ($request->ajax()) {
        $role = Role::select(['id', 'name', 'guard_name'])->get();

        return DataTables::of($role)
            ->addIndexColumn() // Menambahkan indeks otomatis
            ->addColumn('opsi', function ($row) {
                return '
                    <div class="d-flex align-items-center">
                        <form action="/role/' . $row->id . '/edit_role" method="GET" class="mr-1">
                            <button type="submit" class="btn btn-warning btn-xs"><i class="fa fa-pencil"></i></button>
                        </form>
                      
                    </div>
                ';
            })
            ->rawColumns(['opsi']) // Pastikan kolom ini dianggap sebagai HTML
            ->make(true);
    }

 // Sesuaikan dengan view yang Anda miliki
}

}
