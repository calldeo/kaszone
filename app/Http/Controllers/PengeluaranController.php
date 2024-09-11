<?php

namespace App\Http\Controllers;

use PDF;
use App\Models\Category;
use App\Models\Pemasukan;
use App\Models\Pengeluaran;
use Illuminate\Http\Request;
use App\Models\ParentPengeluaran;
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
        // $categories = Category::all(); // Mengambil semua kategori
        // $categories = Category::where('jenis_kategori', 'pengeluaran')->get();
        return view('tambah.add_pengeluaran');
    }

public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'name.*' => 'required|string|max:255',
            'description.*' => 'nullable|string',
            'jumlah_satuan.*' => 'required|numeric|min:0',
            'nominal.*' => 'required|numeric|min:0',
            'dll.*' => 'required|numeric|min:0',
            'jumlah.*' => 'required|numeric|min:0',
            'category_id.*' => 'required|exists:categories,id',
            'image.*' => 'nullable|mimes:jpg,jpeg,png|max:2048',
            'tanggal.*' => 'required|date_format:Y-m-d|exists:pengeluaran_parent,tanggal'
        ]);
    
        DB::beginTransaction();
    
        try {
            // Menghitung total pemasukan dan pengeluaran
            $totalPemasukanTersedia = Pemasukan::sum('jumlah');
            $totalPengeluaran = array_sum($request->input('jumlah', []));
    
            if ($totalPengeluaran > $totalPemasukanTersedia) {
                return redirect()->back()->with('error', 'Jumlah pengeluaran melebihi pemasukan yang tersedia.');
            }
    
            
            $parentPengeluaran = new ParentPengeluaran();
            $parentPengeluaran->tanggal = $request->input('tanggal');
            $parentPengeluaran->save();

            // Proses data untuk setiap pengeluaran
            foreach ($request->input('name') as $i => $name) {
                $pengeluaran = new Pengeluaran();
                $pengeluaran->name = $name;
                $pengeluaran->description = $request->input('description')[$i] ?? null;
                $pengeluaran->jumlah_satuan = $request->input('jumlah_satuan')[$i];
                $pengeluaran->nominal = $request->input('nominal')[$i];
                $pengeluaran->dll = $request->input('dll')[$i];
                $pengeluaran->jumlah = $request->input('jumlah')[$i];
                $pengeluaran->id = $request->input('category_id')[$i];
                $pengeluaran->id_parent = $parentPengeluaran->id; // Menggunakan ID yang benar
    
                // Simpan gambar jika ada file yang diupload
                if ($request->hasFile("image.$i")) {
                    $path = $request->file("image.$i")->store('image', 'public');
                    $pengeluaran->image = $path;
                }
    
                $pengeluaran->save(); // Simpan setiap pengeluaran
            }
    
            DB::commit();
            return redirect('/pengeluaran')->with('success', 'Pengeluaran berhasil ditambahkan.');
        } catch (\Throwable $th) {
            DB::rollback();
            return redirect('/pengeluaran')->with('error', 'Pengeluaran gagal ditambahkan! ' . $th->getMessage());
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
    $category = Category::where('jenis_kategori', 'pengeluaran')->get();

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

    // Mulai transaksi database
    DB::beginTransaction();

    try {
        // Temukan data pengeluaran berdasarkan ID
        $pengeluaran = Pengeluaran::find($id_data);

        if (!$pengeluaran) {
            // Jika data tidak ditemukan, kembalikan error
            return redirect('/pengeluaran')->with('error', 'Data pengeluaran tidak ditemukan.');
        }

        // Update data pengeluaran
        $pengeluaran->name = $request->name;
        $pengeluaran->description = $request->description;
        $pengeluaran->date = $request->date;
        $pengeluaran->jumlah = $request->jumlah;
        $pengeluaran->id = $request->id ?? $pengeluaran->id; // Gunakan kategori yang ada jika tidak ada yang baru

        // Simpan perubahan
        $pengeluaran->save();

        // Commit transaksi jika tidak ada kesalahan
        DB::commit();

        // Redirect dengan pesan sukses
        return redirect('/pengeluaran')->with('update_success', 'Data pengeluaran berhasil diperbarui.');
    } catch (\Throwable $th) {
        // Rollback transaksi jika terjadi kesalahan
        DB::rollback();
        
        // Redirect dengan pesan gagal
        return redirect('/pengeluaran')->with('error', 'Pengeluaran gagal diperbarui! ' . $th->getMessage());
    }
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
 public function showDetail($id)
    {


    // Mengambil data ParentPengeluaran beserta pengeluaran terkait dan kategori
    $parentPengeluaran = ParentPengeluaran::with('pengeluaran.category')->findOrFail($id);

    // Melempar data ke view
    return view('halaman.detail', compact('parentPengeluaran'));


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