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
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\IOFactory;

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


    public function deleteAll($id)
    {
        // Temukan ParentPengeluaran berdasarkan ID
        $parentPengeluaran = ParentPengeluaran::find($id);

        if ($parentPengeluaran) {
            // Hapus semua pengeluaran terkait
            $parentPengeluaran->pengeluaran()->delete();

            // Hapus ParentPengeluaran itu sendiri
            $parentPengeluaran->delete();

            // Redirect ke halaman pengeluaran
            return redirect()->route('pengeluaran.index')->with('success', 'Semua pengeluaran  berhasil dihapus.');
        } else {
            return redirect()->back()->with('error', 'Data tidak ditemukan.');
        }
    }








    public function edit($id)
    {
        // Ambil ParentPengeluaran berdasarkan ID
        $parentPengeluaran = ParentPengeluaran::with('pengeluaran')->find($id);
        $categories = Category::where('jenis_kategori', Category::PengeluaranCode)->get();

        if (!$parentPengeluaran) {
            return redirect()->route('pengeluaran.index')->with('error', 'Data tidak ditemukan.');
        }
        return view('pengeluaran.edit', compact('parentPengeluaran', 'categories'));
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
        'category_id.*' => 'required|exists:categories,id',
        'image.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);


    $parentPengeluaran = ParentPengeluaran::with('pengeluaran')->find($id);
    if (!$parentPengeluaran) {
        return redirect()->route('pengeluaran.index')->with('error', 'Data tidak ditemukan.');
    }

  
    DB::beginTransaction();
    try {
   
        $parentPengeluaran->tanggal = $request->tanggal;
        $parentPengeluaran->save(); 

        // dd($request,$parentPengeluaran);
        foreach ($request->name as $key => $name) {
            if (isset($parentPengeluaran->pengeluaran[$key])) {
                $pengeluaran = $parentPengeluaran->pengeluaran[$key];

                $pengeluaran->name = $name;
                $pengeluaran->description = $request->description[$key];
                $pengeluaran->jumlah_satuan = $request->jumlah_satuan[$key];
                $pengeluaran->nominal = $request->nominal[$key];
                $pengeluaran->jumlah = $request->jumlah[$key];
                $pengeluaran->dll = $request->dll[$key];
                $pengeluaran->id = $request->category_id[$key];

         
                if ($request->hasFile('image.' . $key)) {
                
                    if ($pengeluaran->image) {
                        \Storage::disk('public')->delete($pengeluaran->image);
                    }
              
                    $pengeluaran->image = $request->file('image.' . $key)->store('pengeluaran_images', 'public');
                }

                $pengeluaran->save();
                // dd($request,$pengeluaran);
            } else {
              
                $pengeluaranBaru = new Pengeluaran();
                $pengeluaranBaru->name = $name;
                $pengeluaranBaru->description = $request->description[$key] ?? null;
                $pengeluaranBaru->jumlah_satuan = $request->jumlah_satuan[$key];
                $pengeluaranBaru->nominal = $request->nominal[$key];
                $pengeluaranBaru->jumlah = $request->jumlah[$key];
                $pengeluaranBaru->dll = $request->dll[$key] ?? null;
                $pengeluaranBaru->id = $request->id[$key];

          
                if ($request->hasFile('image.' . $key)) {
                    $pengeluaranBaru->image = $request->file('image.' . $key)->store('pengeluaran_images', 'public');
                }

            
                $parentPengeluaran->pengeluaran()->save($pengeluaranBaru);
            }
        }

        DB::commit();

        return redirect()->route('pengeluaran.index')->with('success', 'Data berhasil diperbarui.');
    } catch (\Exception $e) {
     
        DB::rollback();
        return redirect()->back()->with('error', 'Terjadi kesalahan saat memperbarui data: ' . $e->getMessage());
    }
}







    public function cetakpgl(Request $request)
    {
        // Dapatkan calon dengan jumlah suara terbanyak

        $year = $request->input('year');

        if ($year) {

            $pengeluaran = Pengeluaran::whereHas('parentPengeluaran', function ($query) use ($year) {
                $query->whereYear('tanggal', $year);
            })->with('category', 'parentPengeluaran')->get();
        } else {

            $pengeluaran = Pengeluaran::with('category', 'ParentPengeluaran')->get();
        }

        $totalPengeluaran = $pengeluaran->sum('jumlah');

        $pdf = PDF::loadView('pengeluaran.pdf', compact('pengeluaran', 'totalPengeluaran', 'year'));

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

            $pengeluaran = Pengeluaran::whereHas('parentPengeluaran', function ($query) use ($year) {
                $query->whereYear('tanggal', $year);
            })->with('category', 'parentPengeluaran')->get();
        } else {
            $pengeluaran = Pengeluaran::with('category', 'parentPengeluaran')->get();
        }

        return Excel::download(new PengeluaranExport($pengeluaran, $year), $year ? "laporan_$year.xlsx" : "laporan_seluruh.xlsx");
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
        'file' => 'required',
        'file.*' => 'mimes:xls,xlsx|max:2048',
    ]);

    DB::beginTransaction(); 

    try {
        if ($request->hasFile('file')) {
            foreach ($request->file('file') as $file) {
                $spreadsheet = IOFactory::load($file);
                $sheetNames = $spreadsheet->getSheetNames();

                foreach ($sheetNames as $sheetIndex => $sheetName) {
                   
                    try {
                        $tanggal = \Carbon\Carbon::createFromFormat('d-m-Y', $sheetName);
                    } catch (\Exception $e) {
                        Log::error('Format tanggal tidak valid: ' . $sheetName);
                        continue; 
                    }

                    Log::alert('Mengimpor dari sheet: ' . $sheetName);
                    
                   
                    $parentPengeluaran = new ParentPengeluaran();
                    $parentPengeluaran->tanggal = $tanggal; 
                    $parentPengeluaran->save();

                    $sheet = $spreadsheet->getSheet($sheetIndex);
                    $highestRow = $sheet->getHighestRow();
                    $highestColumn = $sheet->getHighestColumn();

                    for ($row = 2; $row <= $highestRow; $row++) {
                        $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE);

                     
                        Log::alert('Row ' . $row . ' Data: ' . json_encode($rowData));

                        
                        if (empty($rowData[0][0])) {
                            Log::warning('Row ' . $row . ' is empty or invalid, skipping.');
                            continue; 
                        }

                        
                        $pengeluaran = new Pengeluaran();
                        $pengeluaran->name = $rowData[0][0]; 
                        $pengeluaran->description = $rowData[0][1]  ?? null;
                        $pengeluaran->jumlah_satuan = $rowData[0][2] ?? 0; 
                        $pengeluaran->nominal = $rowData[0][3] ?? 0; 
                        $pengeluaran->dll = $rowData[0][4] ?? 0; 
                        $pengeluaran->jumlah = $rowData[0][5] ?? 0; 

                  
                        $pengeluaran->id = $rowData[0][6] ?? null; 
                        $pengeluaran->id_parent = $parentPengeluaran->id;

                      
                        $pengeluaran->save();

                        Log::alert('Data row ' . $row . ' disimpan: ' . json_encode($pengeluaran));
                    }
                }
            }
        }

        DB::commit(); 
        return redirect()->back()->with('success', 'Data pengeluaran berhasil diimpor!');
    } catch (\PhpOffice\PhpSpreadsheet\Reader\Exception $e) {
        DB::rollBack();
        \Log::error('Import failed: ' . $e->getMessage()); 
        return redirect()->back()->with('error', 'Terjadi kesalahan saat membaca file: ' . $e->getMessage());
    } catch (\Exception $e) {
        DB::rollBack();
        \Log::error('Import failed: ' . $e->getMessage()); 
        return redirect()->back()->with('error', 'Terjadi kesalahan saat mengimpor data: ' . $e->getMessage());
    }
}






    public function downloadTemplate()
    {

        return Excel::download(new TemplateExport(), 'template_pengeluaran.xlsx');
    }
}