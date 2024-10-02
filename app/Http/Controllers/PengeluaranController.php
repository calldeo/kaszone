<?php

namespace App\Http\Controllers;

use PDF;
use Carbon\Carbon;
use App\Models\Category;
use App\Models\Pemasukan;
use App\Models\Pengeluaran;
use Illuminate\Http\Request;
use App\Exports\TemplateExport;
use App\Models\ParentPengeluaran;
use App\Exports\PengeluaranExport;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;
use App\Imports\DataPengeluaranImportMultiple;

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
        'file.*' => 'required|mimes:xls,xlsx|max:2048',
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
      
        $files = $request->file('file', []);
        foreach ($files as $file) {
            Excel::import(new PengeluaranImport, $file); 
        }

        $parentPengeluaran = new ParentPengeluaran();
        $parentPengeluaran->tanggal = $request->input('tanggal');
        $parentPengeluaran->save();

        $names = $request->input('name', []);
        foreach ($names as $i => $name) {
          
            $existingPengeluaran = Pengeluaran::where('name', $name)
                ->where('id_parent', $parentPengeluaran->id)
                ->first();

            if ($existingPengeluaran) {
               
                $existingPengeluaran->jumlah += $request->input('jumlah')[$i] ?? 0;
                $existingPengeluaran->nominal += $request->input('nominal')[$i] ?? 0; 
                $existingPengeluaran->save();
            } else {
       
                $pengeluaran = new Pengeluaran();
                $pengeluaran->name = $name;
                $pengeluaran->description = $request->input('description')[$i] ?? null;
                $pengeluaran->jumlah_satuan = $request->input('jumlah_satuan')[$i] ?? 0;
                $pengeluaran->nominal = $request->input('nominal')[$i] ?? 0;
                $pengeluaran->dll = $request->input('dll')[$i] ?? 0;
                $pengeluaran->jumlah = $request->input('jumlah')[$i] ?? 0;

          
                $pengeluaran->id = $request->input('category_id')[$i];
                $pengeluaran->id_parent = $parentPengeluaran->id; 

               
                if ($request->hasFile("image.$i")) {
                    $path = $request->file("image.$i")->store('image', 'public');
                    $pengeluaran->image = $path;
                }

                $pengeluaran->save(); 
            }
        }

        DB::commit();
       
        return redirect()->route('pengeluaran.index')->with('success', 'Data berhasil diimpor dan diproses.');
    } catch (\Exception $e) {
        DB::rollBack();
        return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
    }
}


    public function delete($id_data)
    {
 
        $pengeluaran = Pengeluaran::find($id_data);
        
        if ($pengeluaran) {
   
            $sameParentCount = Pengeluaran::where('id_parent', $pengeluaran->id_parent)->count();
    
            if ($sameParentCount > 1) {
   
                $pengeluaran->delete();
                return redirect()->back()->with('success', 'Item berhasil dihapus.');
            } else {

                return redirect()->back()->with('error', 'Tidak dapat menghapus item. karena hanya ada 1 data.');
            }
        }
        
        return redirect()->back()->with('error', 'Item tidak ditemukan.');
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

public function edit($id)
{
    // Ambil ParentPengeluaran berdasarkan ID
    $parentPengeluaran = ParentPengeluaran::with('pengeluaran')->find($id);
       $categories = Category::where('jenis_kategori', 'pengeluaran')->get();

    if (!$parentPengeluaran) {
        return redirect()->route('pengeluaran.index')->with('error', 'Data tidak ditemukan.');
    }

    return view('pengeluaran.edit', compact('parentPengeluaran','categories'));
}

public function update(Request $request, $id)
{
    // Validasi input
    $request->validate([
        'tanggal' => 'required|date',
        'name.*' => 'required|string|max:255',
        'description.*' => 'nullable|string|max:255',
        'jumlah_satuan.*' => 'required|numeric|min:0',
        'nominal.*' => 'required|numeric|min:0',
        'jumlah.*' => 'required|numeric|min:0',
        'dll.*' => 'nullable|string|max:255',
        'category_id.*' => 'required|exists:categories,id', // Memastikan kategori ada
        'image.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    // Temukan ParentPengeluaran
    $parentPengeluaran = ParentPengeluaran::find($id);
    if (!$parentPengeluaran) {
        return redirect()->route('pengeluaran.index')->with('error', 'Data tidak ditemukan.');
    }

    // Mulai transaksi
    DB::beginTransaction();
    try {
        // Update tanggal
        $parentPengeluaran->tanggal = $request->tanggal;
        $parentPengeluaran->save(); // Simpan tanggal

        // Loop melalui data pengeluaran dan perbarui masing-masing
        foreach ($request->name as $key => $name) {
            // Ambil data pengeluaran dari relasi
            $pengeluaran = $parentPengeluaran->pengeluaran[$key]; 

            // Update data pengeluaran
            $pengeluaran->name = $name;
            $pengeluaran->description = $request->description[$key];
            $pengeluaran->jumlah_satuan = $request->jumlah_satuan[$key];
            $pengeluaran->nominal = $request->nominal[$key];
            $pengeluaran->jumlah = $request->jumlah[$key];
            $pengeluaran->dll = $request->dll[$key];
            $pengeluaran->id = $request->id[$key]; // Perbarui ID kategori

            // Menangani gambar
            if ($request->hasFile('image.' . $key)) {
                // Hapus gambar lama jika ada
                if ($pengeluaran->image) {
                    \Storage::disk('public')->delete($pengeluaran->image);
                }
                // Simpan gambar baru
                $pengeluaran->image = $request->file('image.' . $key)->store('pengeluaran_images', 'public');
            }

            // Simpan pengeluaran
            $pengeluaran->save();
        }

        // Commit transaksi
        DB::commit();

        return redirect()->route('pengeluaran.index')->with('success', 'Data berhasil diperbarui.');
    } catch (\Exception $e) {
        // Rollback jika terjadi kesalahan
        DB::rollback();
        return redirect()->back()->with('error', 'Terjadi kesalahan saat memperbarui data: ' . $e->getMessage());
    }
}



    
    

public function cetakpgl(Request $request)
    {
        // Dapatkan calon dengan jumlah suara terbanyak
       
     $year = $request->input('year');
    
    if ($year) {
        
         $pengeluaran = Pengeluaran::whereHas('parentPengeluaran', function($query) use ($year) {
            $query->whereYear('tanggal',$year);    
        })->with('category', 'parentPengeluaran')->get();
    } else {
        
        $pengeluaran = Pengeluaran::with('category', 'ParentPengeluaran')->get();
    }
    
    $totalPengeluaran = $pengeluaran->sum('jumlah');

    $pdf = PDF::loadView('pengeluaran.pdf', compact( 'pengeluaran', 'totalPengeluaran', 'year'));
    
    $pdf->setPaper('A4', 'portrait');
    
    return $pdf->stream($year ? "laporan_$year.pdf" : "laporan_seluruh.pdf");

    // return view('halaman.cetakpgl',compact('pengeluaran'));
    }
 public function showDetail($id)
    {


    // Mengambil data ParentPengeluaran beserta pengeluaran terkait dan kategori
    $parentPengeluaran = ParentPengeluaran::with('pengeluaran.category')->findOrFail($id);
        
    // Melempar data ke view
    return view('pengeluaran.detail', compact('parentPengeluaran'));


    }

    public function exportPengeluaranExcel(Request $request)
{
    $year = $request->input('year');
    
    if ($year) {
        
        $pengeluaran = Pengeluaran::whereHas('parentPengeluaran', function($query) use ($year) {
            $query->whereYear('tanggal', $year);    
        })->with('category', 'parentPengeluaran')->get();
    } else {
        $pengeluaran = Pengeluaran::with('category', 'parentPengeluaran')->get();
    }

    return Excel::download(new PengeluaranExport( $pengeluaran, $year), $year ? "laporan_$year.xlsx" : "laporan_seluruh.xlsx");
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
    
public function importPengeluaran(Request $request)
{
    $request->validate([
        'file' => 'required', // File harus diupload
        'file.*' => 'mimes:xls,xlsx|max:2048', // Validasi untuk setiap file
    ]);

    DB::beginTransaction(); // Mulai transaksi

    try {
        if ($request->hasFile('file')) {
            foreach ($request->file('file') as $file) {
                // Ambil tanggal dari judul sheet atau dari request input
                $tanggal = Carbon::now()->format('Y-m-d'); // Sesuaikan jika perlu mengambil tanggal dari sheet atau input
                
                // Import multiple file menggunakan class yang sama
                Excel::import(new DataPengeluaranImportMultiple($tanggal), $file); // Proses impor
            }
        }

        DB::commit(); // Commit transaksi jika tidak ada kesalahan
        return redirect()->back()->with('success', 'Data pengeluaran berhasil diimpor!');
    } catch (\Exception $e) {
        DB::rollBack(); // Rollback jika ada kesalahan
        \Log::error('Import failed: ' . $e->getMessage()); // Log kesalahan
        return redirect()->back()->with('error', 'Terjadi kesalahan saat mengimpor data: ' . $e->getMessage());
    }
}




    public function downloadTemplate()
    {

   return Excel::download(new TemplateExport(), 'template_pengeluaran.xlsx');}
    }
    
