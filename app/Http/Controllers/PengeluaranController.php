<?php

namespace App\Http\Controllers;

use App\Models\Category;
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
            'jumlah' => 'required|numeric',
            'category_id' => 'required|exists:categories,id',
        ]);

     DB::beginTransaction();
     try {
        //code... 
        $pengeluaran = new Pengeluaran();
        $pengeluaran->name = $request->name;
        $pengeluaran->description = $request->description;
        $pengeluaran->date = $request->date;
        $pengeluaran->jumlah = $request->jumlah;
        $pengeluaran->id = $request->category_id;
       

        
        // dd($pemasukan);
        $pengeluaran->save();
        DB::commit();
     } catch (\Throwable $th) {
        DB::rollback();
        return redirect('/pengeluaran')->with('success', 'Pemasukan gagal ditambahkan!' . $th->getMessage());

        //throw $th;
     }
        return redirect('/pengeluaran')->with('success', 'Pemasukan berhasil ditambahkan!');
       
        // Pemasukan::create($request->all());

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
//  $pemasukan = DB::table('datapemasukan')->get();

    return view('edit.edit_pengeluaran', compact('id_data','pengeluaran','category'));
}

  public function update(Request $request, $id_data)
    {
        // Validasi input
        $request->validate([
            'name' => ['required', 'min:3', 'max:30'],
            'description' => ['required', 'min:3', 'max:255'],
            'date' => ['required', 'date'],
            'jumlah' => ['required', 'numeric'],
            'category_id' => ['required', 'exists:categories,id'],
        ]);

        // Cari data pemasukan berdasarkan ID
         $pengeluaran = Pengeluaran::find($id_data);

        // Jika data pemasukan tidak ditemukan, arahkan kembali dengan pesan error
        // if (!$pemasukan) {
        //     return redirect('/datapemasukan')->with('error', 'Data pemasukan tidak ditemukan.');
        // }

        // Update data pemasukan
        $pengeluaran->update([
            'name' => $request->name,
            'description' => $request->description,
            'date' => $request->date,
            'jumlah' => $request->jumlah,
            'category_id' => $request->category_id,
        ]);
//  return view('halaman.datapemasukan', compact('id_data','pemasukan'));
        // Arahkan kembali ke halaman pemasukan dengan pesan sukses
        return redirect('/pengeluaran')->with('update_success', 'Data pengeluaran berhasil diperbarui.');
    }
}
