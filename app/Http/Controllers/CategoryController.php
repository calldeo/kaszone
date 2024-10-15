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
use PDF;

class CategoryController extends Controller
{
    
    public function index()
    {

        $categories = Category::paginate(10); 
        return view('kategori.kategori', compact('categories'));
    }

    public function create()
    {
        
        return view('kategori.add');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'min:3', 'max:30'],
            'jenis_kategori' => 'required',
            'description' => ['required', 'min:3', 'max:30'],
        ]);

        DB::beginTransaction(); 
        try {
            
            $category = new Category();
            $category->name = $request->name;
            $category->jenis_kategori = $request->jenis_kategori;
            $category->description = $request->description;

            
            $category->save();

            
            DB::commit();

            return redirect('/kategori')->with('success', 'Kategori berhasil ditambahkan.');
        } catch (\Throwable $th) {
            
            DB::rollback();

            return redirect('/kategori')->with('error', 'Kategori gagal ditambahkan! ' . $th->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $user = Category::find($id);

            if ($user) {
                $user->forceDelete(); 
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
        $category = Category::find($id);

    
        if (!$category) {
            return redirect('/kategori')->with('error', 'Kategori tidak ditemukan.');
        }

        return view('kategori.edit', compact('category'));
    }

    public function update(Request $request, $id)
    {
        
        $request->validate([
            'name' => ['required', 'min:3', 'max:30'],
            'jenis_kategori' => 'required',
            'description' => ['nullable', 'min:3', 'max:100'],
        ]);

        
        $category = Category::find($id);

        
        if (!$category) {
            return redirect('/kategori')->with('error', 'Kategori tidak ditemukan.');
        }

        DB::beginTransaction(); 
        try {
            
            $category->name = $request->name;
            $category->jenis_kategori = $request->jenis_kategori;
            $category->description = $request->description;

            
            $category->save();

            
            DB::commit();

            return redirect('/kategori')->with('success', 'Kategori berhasil diperbarui.');
        } catch (\Throwable $th) {
            
            DB::rollback();

            return redirect('/kategori')->with('error', 'Kategori gagal diperbarui! ' . $th->getMessage());
        }
    }


    public function kategoriimportexcel(Request $request)
    {
        
        DB::beginTransaction();

        try {
            
            $request->validate([
                'file' => 'required|file|mimes:xlsx,xls,csv|max:2048', 
            ]);

            
            $file = $request->file('file');
            $namafile = $file->getClientOriginalName();
            $file->move(public_path('DataKategori'), $namafile);

            
            Excel::import(new KategoriImport, public_path('DataKategori/' . $namafile), null, \Maatwebsite\Excel\Excel::XLSX, [
                'startRow' => 2,
                'onlySheets' => [0]
            ]);

            DB::commit();

        
            @unlink(public_path('DataKategori/' . $namafile)); 

            return redirect('/kategori')->with('success', 'Data Berhasil Ditambahkan');
        } catch (\Exception $e) {
            
            DB::rollBack();

            
            \Log::error('Import kategori failed: ' . $e->getMessage());

            return redirect('/kategori')->with('error', 'Terjadi kesalahan saat mengimpor data: ' . $e->getMessage());
        }
    }
    public function exportkategori()
    {
        

        $category = Category::all();
        $pdf = PDF::loadview('kategori.export-kategori', compact('category'));
        $pdf->setPaper('A4', 'potrait');
        return $pdf->stream('category.pdf');


        return view('kategori.export-kategori', compact('category'));
    }

    
    public function showDetail($id)
    {
        $category = Category::find($id);
        if ($category) {
            return response()->json($category);
        } else {
            return response()->json(['message' => 'Kategori tidak ditemukan.'], 404);
        }
    }
    public function downloadTemplateExcel()
    {
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        
        
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Data Kategori');
        $sheet->setCellValue('A1', 'Nama');
        $sheet->setCellValue('B1', 'Jenis Kategori');
        $sheet->setCellValue('C1', 'Deskripsi');
        
        
        $sheet2 = $spreadsheet->createSheet();
        $sheet2->setTitle('Jenis Kategori');
        $sheet2->setCellValue('A1', 'Kode');
        $sheet2->setCellValue('B1', 'Jenis Kategori');
        $sheet2->setCellValue('A2', '1');
        $sheet2->setCellValue('B2', 'Pemasukan');
        $sheet2->setCellValue('A3', '2');
        $sheet2->setCellValue('B3', 'Pengeluaran');
        
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $filename = 'template-category.xlsx';
        $filePath = storage_path('app/public/' . $filename);
        $writer->save($filePath);
        
        return response()->download($filePath, $filename)->deleteFileAfterSend(true);
    }
   
}
