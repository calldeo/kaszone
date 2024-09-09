<?php

namespace App\Http\Controllers;

use App\Imports\PemasukanImport;
use App\Models\Category;
use App\Models\Pemasukan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use PDF;


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
        // $categories = Category::all(); // Mengambil semua kategori
        // $categories = Category::where('jenis_kategori', '1')->get();
        return view('tambah.add_pemasukan');
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
// $category = Category::all();
//  $pemasukan = DB::table('datapemasukan')->get();
// $category = Category::where('jenis_kategori', '1')->get();
    return view('edit.edit_pemasukan', compact('id_data','pemasukan'));
}

  public function update(Request $request, $id_data)
{
    // Validasi input
    $request->validate([
        'name' => ['required', 'min:3', 'max:30'],
        'description' => ['required', 'min:3', 'max:255'],
        'date' => ['required', 'date'],
       'jumlah' => 'required|numeric|min:0',
        'id' => ['nullable', 'exists:categories,id'],
    ]);

    // Mulai transaksi database
    DB::beginTransaction();

    try {
        // Temukan data pemasukan berdasarkan ID
        $pemasukan = Pemasukan::find($id_data);

        if (!$pemasukan) {
            // Jika data tidak ditemukan, kembalikan error
            return redirect('/pemasukan')->with('error', 'Data pemasukan tidak ditemukan.');
        }

        // Update data pemasukan
        $pemasukan->name = $request->name;
        $pemasukan->description = $request->description;
        $pemasukan->date = $request->date;
        $pemasukan->jumlah = $request->jumlah;
        $pemasukan->id = $request->id ?? $pemasukan->id; // Gunakan kategori yang ada jika tidak ada yang baru

        // Simpan perubahan
        $pemasukan->save();

        // Commit transaksi jika tidak ada kesalahan
        DB::commit();

        // Redirect dengan pesan sukses
        return redirect('/pemasukan')->with('update_success', 'Data pemasukan berhasil diperbarui.');
    } catch (\Throwable $th) {
        // Rollback transaksi jika terjadi kesalahan
        DB::rollback();
        
        // Redirect dengan pesan gagal
        return redirect('/pemasukan')->with('error', 'Pemasukan gagal diperbarui! ' . $th->getMessage());
    }
}


// Method untuk mendapatkan detail kategori
   public function showDetail($id_data)
    {
        $pemasukan = Pemasukan::with('category')->find($id_data);

    if (!$pemasukan) {
        return response()->json(['message' => 'Pengeluaran not found'], 404);
    }

    return response()->json([
        'id_data' => $pemasukan->id,
        'name' => $pemasukan->name,
        'description' => $pemasukan->description,
        'date' => $pemasukan->date,
        'jumlah' => $pemasukan->jumlah,
        'category_name' => $pemasukan->category->name, // Ambil nama kategori
    ]);
    }
   
public function pemasukanImportExcel(Request $request) {

        // DB::table('users')->where('level','guru')->delete();
       
    // Mulai transaksi database
    DB::beginTransaction();
    
    try {
        // Hapus semua data lama dari tabel Category
        Pemasukan::query()->delete();
        
        // Pindahkan file ke folder DataKategori
        $file = $request->file('file');
        $namafile = $file->getClientOriginalName();
        $file->move('DataPemasukan', $namafile);

        // Impor data dari file Excel
        Excel::import(new PemasukanImport, public_path('/DataPemasukan/'.$namafile));

        // Commit transaksi jika semua operasi berhasil
        DB::commit();

        // Hapus file setelah impor selesai
        Storage::delete($namafile);

        return redirect('/pemasukan')->with('success', 'Data Berhasil Ditambahkan');
    } catch (\Exception $e) {
        // Rollback transaksi jika terjadi kesalahan
        DB::rollBack();

        // Log error jika diperlukan
        \Log::error('Import Pemasukan failed: ' . $e->getMessage());

        return redirect('/pemasukan')->with('error', 'Terjadi kesalahan saat mengimpor data' . $e->getMessage());
    }
        
    }
public function downloadTemplate()
{
    // Path ke template Excel untuk pemasukan
    $pathToFile = public_path('templates/template_pemasukan.xlsx');

    return response()->download($pathToFile);


}

public function cetakPemasukan()
    {
        // Dapatkan calon dengan jumlah suara terbanyak
       
       $pemasukan = Pemasukan::all();
       $pdf = PDF::loadview('halaman.cetak-pemasukan',compact('pemasukan'));
       $pdf->setPaper('A4','potrait');
       return $pdf->stream('pemasukan.pdf');


    // return view('halaman.cetakpgl',compact('pengeluaran'));
    }


public function getCategories($jenisKategori)
    {
        // dd($jenisKategori);
        // Ambil data dari database
        $options = Category::where('jenis_kategori', $jenisKategori)->get(); // Atau sesuaikan query sesuai kebutuhan

        // Format data untuk dropdown
        $formattedOptions = $options->map(function ($item) {
            return [
                'id' => $item-> id,
                'name' => $item->name, // Ganti dengan field yang sesuai
            ];
        });

        // Kembalikan sebagai JSON
        return response()->json($formattedOptions);
    }



}
