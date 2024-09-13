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
        return view('pengeluaran.data_pengeluaran', compact('pengeluaran'));
    }
public function create()
    {
        // $categories = Category::all(); // Mengambil semua kategori
        // $categories = Category::where('jenis_kategori', 'pengeluaran')->get();
        return view('pengeluaran.add_pengeluaran');
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
            // // Menghitung total pemasukan dan pengeluaran
            // $totalPemasukanTersedia = Pemasukan::sum('jumlah');
            // $totalPengeluaran = array_sum($request->input('jumlah', []));
    
            // if ($totalPengeluaran > $totalPemasukanTersedia) {
            //     return redirect()->back()->with('error', 'Jumlah pengeluaran melebihi pemasukan yang tersedia.');
            // }
    
            
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
    // Temukan pengeluaran berdasarkan ID
    $pengeluaran = Pengeluaran::findOrFail($id_data);

    // Cek jika pengeluaran memiliki parentPengeluaran
    if ($pengeluaran->parentPengeluaran) {
        // Dapatkan parentPengeluaran dari pengeluaran
        $parentPengeluaran = $pengeluaran->parentPengeluaran;
        
        // Cek apakah pengeluaran parent memiliki lebih dari satu entri
        if ($pengeluaran->parantPengeluaran->count() <= 1) {
            return redirect()->back()->with('error', 'Tidak dapat menghapus data. Minimal harus ada satu pengeluaran pada parent pengeluaran.');
        }
    }

    // Lakukan penghapusan data
    $pengeluaran->delete();
    
    return redirect()->back()->with('success', 'Data pengeluaran berhasil dihapus.');
}

public function destroyAll($parentId)
{
   $parentPengeluaran = ParentPengeluaran::findOrFail($parentId);

    // Hapus semua pengeluaran yang terkait
    $parentPengeluaran->pengeluaran()->delete();

    // Hapus parent pengeluaran
    $parentPengeluaran->delete();

    return redirect()->back()->with('success', 'Semua data pengeluaran berhasil dihapus.');
}




//     public function destroy($id_data)
// {
//    {
//         try {
//             $pengeluaran = Pengeluaran::find($id_data);

//             if ($pengeluaran) {
//                 $pengeluaran->forcedelete(); // Use delete() for soft deletes or forceDelete() if you need permanent deletion
//                 return redirect('/pengeluaran')->with('success', 'Data berhasil dihapus.');
//             } else {
//                 return redirect('/pengeluaran')->with('error', 'Data tidak ditemukan.');
//             }
//         } catch (\Exception $e) {
//             return redirect('/pengeluaran')->with('error', 'Gagal menghapus data. Silakan coba lagi.');
//         }
//     }}

    public function edit($id_data)
    {
        $pengeluaran = Pengeluaran::findOrFail($id_data);
        $categories = Category::where('jenis_kategori', 'pengeluaran')->get();

        return view('pengeluaran.edit', compact('id_data','pengeluaran', 'categories'));
    }

    // Memperbarui data pengeluaran
    public function update(Request $request, $id_data)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'jumlah_satuan' => 'required|numeric',
            'nominal' => 'required|numeric',
            'category_id' => 'nullable|exists:categories,id',
            'jumlah' => 'required|numeric',
            'dll' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Mulai transaksi database
        DB::beginTransaction();

        try {
            $pengeluaran = Pengeluaran::findOrFail($id_data);
            $pengeluaran->name = $request->input('name');
            $pengeluaran->description = $request->input('description');
            $pengeluaran->jumlah_satuan = $request->input('jumlah_satuan');
            $pengeluaran->nominal = $request->input('nominal');
            $pengeluaran->id = $request->input('category_id');
            $pengeluaran->jumlah = $request->input('jumlah');
            $pengeluaran->dll = $request->input('dll');

            // Handle image upload
            if ($request->hasFile('image')) {
                // Hapus gambar lama jika ada
                if ($pengeluaran->image) {
                    Storage::delete('public/' . $pengeluaran->image);
                }

                $path = $request->file('image')->store('images', 'public');
                $pengeluaran->image = $path;
            }

            $pengeluaran->save();

            // Commit transaksi
            DB::commit();

            return redirect()->route('pengeluaran.index')->with('success', 'Pengeluaran updated successfully');
        } catch (\Exception $e) {
            // Rollback transaksi jika ada kesalahan
            DB::rollBack();

            return redirect()->route('pengeluaran.index')->with('error', 'Failed to update pengeluaran: ' . $e->getMessage());
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
    return view('pengeluaran.detail', compact('parentPengeluaran'));


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