<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Pemasukan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PemasukanController extends Controller
{
      public function index()
    {
       $pemasukan = Pemasukan::with('category')->get();
         // Menggunakan pagination
        return view('halaman.datapemasukan', compact('pemasukan'));
    }

    public function create()
    {
        $categories = Category::all(); // Mengambil semua kategori
        return view('tambah.add_pemasukan', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'date' => 'required|date',
            'jumlah' => 'required|numeric',
            'category_id' => 'required|exists:categories,id',
        ]);

     DB::beginTransaction();
     try {
        //code... 
        $pemasukan = new Pemasukan();
        $pemasukan->name = $request->name;
        $pemasukan->description = $request->description;
        $pemasukan->date = $request->date;
        $pemasukan->jumlah = $request->jumlah;
        $pemasukan->id = $request->category_id;
       

        
        // dd($pemasukan);
        $pemasukan->save();
        DB::commit();
     } catch (\Throwable $th) {
        DB::rollback();
        return redirect('/pemasukan')->with('success', 'Pemasukan gagal ditambahkan!' . $th->getMessage());

        //throw $th;
     }
        return redirect('/pemasukan')->with('success', 'Pemasukan berhasil ditambahkan!');
       
        // Pemasukan::create($request->all());

    }

public function destroy($id_data)
{
   {
        try {
            $pemasukan = Pemasukan::find($id_data);

            if ($pemasukan) {
                $pemasukan->forcedelete(); // Use delete() for soft deletes or forceDelete() if you need permanent deletion
                return redirect('/pemasukan')->with('success', 'Data berhasil dihapus.');
            } else {
                return redirect('/pemasukan')->with('error', 'Data tidak ditemukan.');
            }
        } catch (\Exception $e) {
            return redirect('/pemasukan')->with('error', 'Gagal menghapus data. Silakan coba lagi.');
        }
    }}

public function edit($id_data)
{
    $pemasukan = Pemasukan::find($id_data);
$category = Category::all();
//  $pemasukan = DB::table('datapemasukan')->get();

    return view('edit.edit_pemasukan', compact('id_data','pemasukan','category'));
}

  public function update(Request $request, $id_data)
    {
        // Validasi input
        $request->validate([
            'name' => ['required', 'min:3', 'max:30'],
            'description' => ['required', 'min:3', 'max:255'],
            'date' => ['required', 'date'],
            'jumlah' => ['required', 'numeric'],
             'category_id' => ['nullable', 'exists:categories,id'],

        ]);

        // Cari data pemasukan berdasarkan ID
         $pemasukan = Pemasukan::find($id_data);

        // Jika data pemasukan tidak ditemukan, arahkan kembali dengan pesan error
        // if (!$pemasukan) {
        //     return redirect('/datapemasukan')->with('error', 'Data pemasukan tidak ditemukan.');
        // }

        // Update data pemasukan
        $pemasukan->update([
            'name' => $request->name,
            'description' => $request->description,
            'date' => $request->date,
            'jumlah' => $request->jumlah,
                   'category_id' => $request->category_id ?? $pemasukan->category_id, // Gunakan nilai kategori yang ada jika tidak ada yang baru

        ]);
//  return view('halaman.datapemasukan', compact('id_data','pemasukan'));
        // Arahkan kembali ke halaman pemasukan dengan pesan sukses
        return redirect('/pemasukan')->with('update_success', 'Data pemasukan berhasil diperbarui.');
    }
// Method untuk mendapatkan detail kategori
    public function showDetail($id)
    {
        $pemasukan = Pemasukan::find($id);
        if ($pemasukan) {
            return response()->json($pemasukan);
        } else {
            return response()->json(['message' => 'pemasukan tidak ditemukan.'], 404);
        }
    }
}
