<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Imports\KategoriImport;
use App\Exports\CategoriesExport;
use App\Imports\CategoriesImport;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;


class CategoryController extends Controller
{
    // Mendapatkan daftar kategori
    public function index()
    {
      
           $categories = Category::paginate(10); // Menggunakan pagination
        return view('halaman.kategori', compact('categories'));
    }

    public function add_kategori()
    {
        // Meneruskan data ke tampilan
        return view('tambah.add_kategori');
    }

    public function store(Request $request)
{
    $request->validate([
        'name' => ['required', 'min:3', 'max:30'],
         'description' => ['required', 'min:3', 'max:30'],
    ]);


       Category::create([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        // Redirect dengan pesan sukses
        return redirect('/kategori')->with('success', 'Data Berhasil Ditambahkan');

}
public function destroy($id)
{
    try {
        $user = Category::find($id);
        
        if ($user) {
            $user->forceDelete(); // Menghapus data secara permanen
            return redirect('/kategori')->with('success', 'Data berhasil dihapus secara permanen');
        } else {
            return redirect('/kategori')->with('error', 'Data tidak ditemukan.');
        }
    } catch (\Exception $e) {
        return redirect('/kategori')->with('error', 'Gagal menghapus data. Silakan coba lagi.');
    }
}

 public function edit($id)
{
    $category= Category::find($id);
    // Jangan mengirimkan password ke tampilan
    

         

    return view('edit.edit_kategori', compact('category'));
}

public function update(Request $request, $id)
{
    $category = Category::find($id);

    $request->validate([
        'name' => ['required', 'min:3', 'max:30'],
        'description' => ['required', 'min:3', 'max:30'],

         
    ]);

    $data = [
        'name' => $request->name,
        'description' => $request->description,

       
    ];

   
    $category->update($data);

    return redirect('/kategori')->with('update_success', 'Data Berhasil Diupdate');
}

public function kategoriimportexcel(Request $request) {

        // DB::table('users')->where('level','guru')->delete();
       
    // Mulai transaksi database
    DB::beginTransaction();
    
    try {
        // Hapus semua data lama dari tabel Category
        Category::query()->delete();
        
        // Pindahkan file ke folder DataKategori
        $file = $request->file('file');
        $namafile = $file->getClientOriginalName();
        $file->move('DataKategori', $namafile);

        // Impor data dari file Excel
        Excel::import(new KategoriImport, public_path('/DataKategori/'.$namafile));

        // Commit transaksi jika semua operasi berhasil
        DB::commit();

        // Hapus file setelah impor selesai
        Storage::delete($namafile);

        return redirect('/kategori')->with('success', 'Data Berhasil Ditambahkan');
    } catch (\Exception $e) {
        // Rollback transaksi jika terjadi kesalahan
        DB::rollBack();

        // Log error jika diperlukan
        \Log::error('Import kategori failed: ' . $e->getMessage());

        return redirect('/kategori')->with('error', 'Terjadi kesalahan saat mengimpor data');
    }
        
    }
  public function cetaklaporan()
    {
        // Dapatkan calon dengan jumlah suara terbanyak
       
       $category = Category::all();


    return view('halaman.cetaklaporan',compact('category'));
    }
    
    // Method untuk mendapatkan detail kategori
public function showDetail($id)
{
    $category = Category::find($id);
    if ($category) {
        return response()->json($category);
    } else {
        return response()->json(['message' => 'Kategori tidak ditemukan.'], 404);
    }
}
public function downloadTemplate()
{
    $filePath = public_path('templates/template-category.xlsx'); // Path ke file template
    return response()->download($filePath);
}
}