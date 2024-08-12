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
            $admins = User::where('level', 'admin')->select(['id', 'name', 'email', 'level','kelamin','alamat'])->get();

            return DataTables::of($admins)
                ->addIndexColumn() // Menambahkan indeks otomatis
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
                ->rawColumns(['opsi']) // Pastikan kolom ini dianggap sebagai HTML
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
      

        return view('tambah.add_admin');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'min:3', 'max:30', function ($attribute, $value, $fail) {
                // Memeriksa apakah nama yang dimasukkan sudah ada dalam basis data
                if (User::where('name', $value)->exists()) {
                    $fail($attribute . ' is registered.');
                }
            }],
            'level' => 'required',
            'email' => 'required|unique:users,email',
            'password' => ['required', 'min:8', 'max:12'],
            'kelamin' => 'required',
            'alamat' => ['required', 'min:3', 'max:30'],
        ]);

        $user = User::where('name', $request->name)->orWhere('email', $request->email)->first();
        if ($user) {
            // Jika nama atau email sudah digunakan, tampilkan pesan kesalahan
            return back()->withInput()->with('error', 'Nama atau email sudah digunakan.');
        }

        User::create([
            'name' => $request->name,
            'level' => $request->level,
            'email' => $request->email,
            'password' => bcrypt($request->password),
             'kelamin' => $request->kelamin,
            'alamat' => $request->alamat,
        ]);

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
            'level' => 'required',
            'email' => 'required|email|unique:users,email,' . $admin->id,
            'password' => ['nullable', 'min:8', 'max:12'], // Mengubah menjadi nullable
            'kelamin' => 'required',
            'alamat' => ['required', 'min:3', 'max:30'],

        ]);

        $data = [
            'name' => $request->name,
            'level' => $request->level,
            'email' => $request->email,
            'kelamin' => $request->kelamin,
            'alamat' => $request->alamat,

        ];

        // Menambahkan password ke data hanya jika ada input password
        if ($request->filled('password')) {
            $data['password'] = bcrypt($request->password);
        }

        $admin->update($data);

        return redirect('/admin')->with('update_success', 'Data Berhasil Diupdate');
    }

    public function search(Request $request)
    {
       

        // Dapatkan input pencarian
        $searchTerm = $request->input('search');

        // Lakukan pencarian hanya jika input tidak kosong
        if (!empty($searchTerm)) {
            // Validasi input
            $request->validate([
                'search' => 'string', // Sesuaikan aturan validasi sesuai kebutuhan Anda
            ]);

            // Lakukan pencarian dengan mempertimbangkan validasi input, level 'admin', dan status_pemilihan
            $users = User::where('level', 'admin')
                        ->where(function ($query) use ($searchTerm) {
                            $query->where('name', 'like', "%{$searchTerm}%")
                                ->orWhere('status_pemilihan', 'like', "%{$searchTerm}%"); // Ubah sesuai dengan tipe data status_pemilihan
                        })
                        ->get();
        } else {
            // Jika input kosong, ambil semua data user dengan level 'admin'
            $users = User::where('level', 'admin')->get();
        }

        // Memberikan respons berdasarkan hasil pencarian
        return response()->json($users);
    }

    public function deleteSelected(Request $request)
    {
        try {
            // Ambil ID guru yang dipilih dari request
            $selectedIds = $request->input('id');
    
            // Hapus data guru secara permanen dari database
            $deleted = User::whereIn('id', $selectedIds)->forceDelete();
    
            if ($deleted) {
                // Kirim respons jika berhasil menghapus
                return response()->json(['success' => true, 'message' => 'Berhasil menghapus data guru yang dipilih secara permanen.']);
            } else {
                // Kirim respons jika gagal menghapus
                return response()->json(['success' => false, 'message' => 'Gagal menghapus data guru yang dipilih.']);
            }
        } catch (\Exception $e) {
            // Tangani kesalahan jika terjadi
            return response()->json(['success' => false, 'message' => 'Gagal menghapus data guru yang dipilih. Silakan coba lagi.']);
        }
    }







    public function tab(Request $request) // BENDAHARA
    {
        if ($request->ajax()) {
            $admins = User::where('level', 'bendahara')->select(['id', 'name', 'email', 'level','kelamin','alamat'])->get();

            return DataTables::of($admins)
                ->addIndexColumn() // Menambahkan indeks otomatis
                ->addColumn('opsi', function ($row) {
                    return '
                        <div class="d-flex align-items-center">
                            <form action="/bendahara/' . $row->id . '/edit_bendahara" method="GET" class="mr-1">
                                <button type="submit" class="btn btn-warning btn-xs"><i class="fa fa-pencil"></i></button>
                            </form>
                            <form action="/bendahara/' . $row->id . '/destroy" method="POST">
                                ' . csrf_field() . '
                                ' . method_field('DELETE') . '
                                <button type="submit" class="btn btn-danger btn-xs"><i class="fa fa-trash"></i></button>
                            </form>
                        </div>
                    ';
                })
                ->rawColumns(['opsi']) // Pastikan kolom ini dianggap sebagai HTML
                ->make(true);
        }
    }



   public function teb(Request $request) // KATEGORI
{
    if ($request->ajax()) {
        $categories = Category::select(['id', 'name', 'description'])->get();

        return DataTables::of($categories)
            ->addIndexColumn() 
            ->addColumn('opsi', function ($row) {
                return '
                    <div class="d-flex align-items-center">
                    <button type="button" class="btn btn-info btn-xs mr-1" data-toggle="modal" data-target="#adminDetailModal" data-url="/kategori/' . $row->id . '/detail">
                    <i class="fa fa-eye"></i>
                    </button>
                        <form action="/kategori/' . $row->id . '/edit_kategori" method="GET" class="mr-1">
                            <button type="submit" class="btn btn-warning btn-xs"><i class="fa fa-pencil"></i></button>
                        </form>
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



    public function tob(Request $request) // PEMASUKAN
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
                      <button type="button" class="btn btn-info btn-xs mr-1" data-toggle="modal" data-target="#adminDetailModal" data-url="/pemasukan/' . $row->id_data . '/detail">
                    <i class="fa fa-eye"></i>
                    </button>
                        <a href="/pemasukan/' . $row->id_data . '/edit_pemasukan" class="btn btn-warning btn-xs mr-1"><i class="fa fa-pencil"></i></a>
                        <form action="/pemasukan/' . $row->id . '/destroy" method="POST" style="display:inline;">
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




 public function tabe(Request $request) // PENGELUARAN
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
                      <button type="button" class="btn btn-info btn-xs mr-1" data-toggle="modal" data-target="#adminDetailModal" data-url="/pengeluaran/' . $row->id_data . '/detail">
                    <i class="fa fa-eye"></i>
                    </button>
                    <a href="/pengeluaran/' . $row->id_data . '/edit_pengeluaran" class="btn btn-warning btn-xs mr-1"><i class="fas fa-pencil-alt"></i>
</a>
                        <form action="/pengeluaran/' . $row->id . '/destroy" method="POST" style="display:inline;">
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


}
