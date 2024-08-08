<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\CategoriesExport;
use App\Imports\CategoriesImport;
use App\Imports\KategoriImport;


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
        Category::query()->where('name','description')->delete();
        $file=$request->file('file');
        $namafile = $file->getClientOriginalName();
        $file->move('DataKategori', $namafile);

        Excel::import(new KategoriImport, public_path('/DataKategori/'.$namafile));
        return redirect('/kategori')->with('success', 'Data Berhasil Ditambahkan');
        
    }
  public function cetaklaporan()
    {
        // Dapatkan calon dengan jumlah suara terbanyak
       
       $category = Category::all();


    return view('halaman.cetaklaporan',compact('category'));
    }
    
    // Method untuk mendapatkan detail admin
     public function showDetail($id)
     {
         $category = Category::find($id);
         if ($category) {
             return response()->json($category);
         } else {
             return response()->json(['message' => 'Kategori tidak ditemukan.'], 404);
         }
     }
}