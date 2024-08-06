<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\CategoriesExport;
use App\Imports\CategoriesImport;

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
            'description' => $request->name,
        ]);

        // Redirect dengan pesan sukses
        return redirect('/kategori')->with('success', 'Data Berhasil Ditambahkan');

}
}