<?php

namespace App\Http\Controllers;

use PDF;
use App\Models\Category;
use App\Models\Pemasukan;
use App\Models\Pengeluaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PengeluaranController extends Controller
{
    //
    public function index()
    {
       $pengeluaran = Pengeluaran::with('category')->get();
         // Menggunakan pagination
        return view('halaman.datapengeluaran', compact('pengeluaran'));
    }
public function create()
    {
        $categories = Category::all(); // Mengambil semua kategori
        return view('tambah.add_pengeluaran', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'date' => 'required|date',
            'jumlah' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
        ]);

        // Memulai transaksi database
        DB::beginTransaction();

        try {
            // Ambil total pemasukan yang tersedia dari semua record pemasukan
            $totalPemasukanTersedia = Pemasukan::sum('jumlah');

            // Mengecek apakah jumlah pengeluaran melebihi pemasukan yang tersedia
            if ($request->jumlah > $totalPemasukanTersedia) {
                return redirect()->back()->with('error', 'Jumlah pengeluaran melebihi pemasukan yang tersedia.');
            }

            // Menambahkan data pengeluaran baru
            $pengeluaran = new Pengeluaran();
            $pengeluaran->name = $request->name;
            $pengeluaran->description = $request->description;
            $pengeluaran->date = $request->date;
            $pengeluaran->jumlah = $request->jumlah;
            $pengeluaran->id = $request->category_id;
            $pengeluaran->save();

            // Kurangi total pemasukan yang tersedia sesuai dengan jumlah pengeluaran
            // $this->kurangiPemasukan($request->jumlah);

            // Commit transaksi jika semua operasi berhasil
            DB::commit();

            return redirect('/pengeluaran')->with('success', 'Pengeluaran berhasil ditambahkan.');
        } catch (\Throwable $th) {
            // Rollback transaksi jika terjadi error
            DB::rollback();

            return redirect('/pengeluaran')->with('error', 'Pemasukan gagal ditambahkan! ' . $th->getMessage());
        }
    }


    public function destroy($id_data)
{
   {
        try {
            $pengeluaran = Pengeluaran::find($id_data);

            if ($pengeluaran) {
                $pengeluaran->forcedelete(); // Use delete() for soft deletes or forceDelete() if you need permanent deletion
                return redirect('/pengeluaran')->with('success', 'Data berhasil dihapus.');
            } else {
                return redirect('/pengeluaran')->with('error', 'Data tidak ditemukan.');
            }
        } catch (\Exception $e) {
            return redirect('/pengeluaran')->with('error', 'Gagal menghapus data. Silakan coba lagi.');
        }
    }}
public function edit($id_data)
{
    $pengeluaran = Pengeluaran::find($id_data);
    $category = Category::all();

    return view('edit.edit_pengeluaran', compact('id_data', 'pengeluaran', 'category'));
}


  public function update(Request $request, $id_data)
    {
        // Validasi input
        $request->validate([
            'name' => ['required', 'min:3', 'max:30'],
            'description' => ['required', 'min:3', 'max:255'],
            'date' => ['required', 'date'],
            'jumlah' => ['required', 'numeric'],
             'id' => ['nullable', 'exists:categories,id'],

        ]);

        // Cari data pemasukan berdasarkan ID
         $pengeluaran = Pengeluaran::find($id_data);

        // Jika data pengeluaran tidak ditemukan, arahkan kembali dengan pesan error
        // if (!$pengeluaran) {
        //     return redirect('/datapengeluaran')->with('error', 'Data pengeluaran tidak ditemukan.');
        // }

        // Update data pengeluaran
        $pengeluaran->update([
            'name' => $request->name,
            'description' => $request->description,
            'date' => $request->date,
            'jumlah' => $request->jumlah,
            'id' => $request->id ?? $pengeluaran->id, // Gunakan nilai kategori yang ada jika tidak ada yang baru

        ]);
//  return view('halaman.datapengeluaran', compact('id_data','pengeluaran'));
        // Arahkan kembali ke halaman pengeluaran dengan pesan sukses
        return redirect('/pengeluaran')->with('update_success', 'Data pengeluaran berhasil diperbarui.');
}

public function cetakpgl()
    {
        // Dapatkan calon dengan jumlah suara terbanyak
       
       $pengeluaran = Pengeluaran::all();
       $pdf = PDF::loadview('halaman.cetakpgl',compact('pengeluaran'));
       $pdf->setPaper('A4','potrait');
       return $pdf->stream('pengeluaran.pdf');


    // return view('halaman.cetakpgl',compact('pengeluaran'));
    }
 public function showDetail($id_data)
    {
        $pengeluaran = Pengeluaran::with('category')->find($id_data);

    if (!$pengeluaran) {
        return response()->json(['message' => 'Pengeluaran not found'], 404);
    }

    return response()->json([
        'id_data' => $pengeluaran->id,
        'name' => $pengeluaran->name,
        'description' => $pengeluaran->description,
        'date' => $pengeluaran->date,
        'jumlah' => $pengeluaran->jumlah,
        'category_name' => $pengeluaran->category->name, // Ambil nama kategori
    ]);
    }

    public function data()
    {
        $pengeluaran = Pengeluaran::query();

        return User::of($pengeluaran)
            ->addColumn('checkbox', function ($row) {
                return '<input type="checkbox" class="custom-control-input" id="check_' . $row->id . '">';
            })
            ->addColumn('action', function ($row) {
                return '<a href="/pengeluaran/' . $row->id . '/edit" class="btn btn-warning btn-sm">Edit</a>
                        <form action="/pengeluaran/' . $row->id . '" method="POST" style="display:inline;">
                            ' . csrf_field() . method_field('DELETE') . '
                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                        </form>';
            })
            ->make(true);
    }
}
