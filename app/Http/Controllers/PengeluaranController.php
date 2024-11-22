<?php

namespace App\Http\Controllers;

use PDF;
use Carbon\Carbon;
use App\Models\Category;
use App\Models\Pemasukan;
use App\Models\Pengeluaran;
use App\Models\SettingSaldo;
use Illuminate\Http\Request;
use App\Exports\TemplateExport;
use App\Models\ParentPengeluaran;
use App\Exports\PengeluaranExport;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\Validator;
use App\Imports\DataPengeluaranImportMultiple;

class PengeluaranController extends Controller
{
    public function index()
    {
        $pengeluaran = Pengeluaran::with('category')->get();
        
        return view('pengeluaran.data_pengeluaran', compact('pengeluaran'));
    }
    
    public function create()
    {
        return view('pengeluaran.add');
    }

    public function store(Request $request)
    {
        $request->validate([
            'file.*' => 'required|mimes:xls,xlsx|max:2048',
            'name.*' => 'required|string|max:255',
            'description.*' => 'nullable|string',
            'jumlah_satuan.*' => 'required|numeric|min:0',
            'nominal.*' => 'required',
            'dll.*' => 'required',
            'jumlah.*' => 'required',
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

                    $existingPengeluaran->jumlah += $this->convertToNumeric($request->input('jumlah')[$i]) ?? 0;
                    $existingPengeluaran->nominal += $this->convertToNumeric($request->input('nominal')[$i]) ?? 0;
                    $existingPengeluaran->dll += $this->convertToNumeric($request->input('dll')[$i]) ?? 0;
                    $existingPengeluaran->save();
                } else {

                    $pengeluaran = new Pengeluaran();
                    $pengeluaran->name = $name;
                    $pengeluaran->description = $request->input('description')[$i] ?? null;
                    $pengeluaran->jumlah_satuan = $request->input('jumlah_satuan')[$i] ?? 0;
                    $pengeluaran->nominal = $this->convertToNumeric($request->input('nominal')[$i]) ?? 0;
                    $pengeluaran->dll = $this->convertToNumeric($request->input('dll')[$i]) ?? 0;
                    $pengeluaran->jumlah = $this->convertToNumeric($request->input('jumlah')[$i]) ?? 0;


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

    private function convertToNumeric($value)
    {
        $numericValue = preg_replace('/[^0-9]/', '', $value);
        return $numericValue;
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
        $parentPengeluaran = ParentPengeluaran::find($id);

        if ($parentPengeluaran) {
            $parentPengeluaran->pengeluaran()->delete();

            $parentPengeluaran->delete();

            return redirect()->route('pengeluaran.index')->with('success', 'Semua pengeluaran  berhasil dihapus.');
        } else {
            return redirect()->back()->with('error', 'Data tidak ditemukan.');
        }
    }








    public function edit($id)
    {
        $parentPengeluaran = ParentPengeluaran::with('pengeluaran')->find($id);
        $categories = Category::where('jenis_kategori', Category::PengeluaranCode)->get();

        if (!$parentPengeluaran) {
            return redirect()->route('pengeluaran.index')->with('error', 'Data tidak ditemukan.');
        }
        return view('pengeluaran.edit', compact('parentPengeluaran', 'categories'));
    }

    
public function update(Request $request, $id)
{
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
            } else {
              
                $pengeluaranBaru = new Pengeluaran();
                $pengeluaranBaru->name = $name;
                $pengeluaranBaru->description = $request->description[$key] ?? null;
                $pengeluaranBaru->jumlah_satuan = $request->jumlah_satuan[$key];
                $pengeluaranBaru->nominal = $request->nominal[$key];
                $pengeluaranBaru->jumlah = $request->jumlah[$key];
                $pengeluaranBaru->dll = $request->dll[$key] ?? null;
                $pengeluaranBaru->id = $request->category_id[$key];

          
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






  public function exportPengeluaranPdf(Request $request)
{
    $year = $request->input('year');
    $startDate = $request->input('start_date');
    $endDate = $request->input('end_date');

    $query = Pengeluaran::query()->with('category', 'parentPengeluaran');

    if ($year) {
        $query->whereHas('parentPengeluaran', function ($q) use ($year) {
            $q->whereYear('tanggal', $year);
        });
    }

    if ($startDate && $endDate) {
        $query->whereHas('parentPengeluaran', function ($q) use ($startDate, $endDate) {
            $q->whereBetween('tanggal', [$startDate, $endDate]);
        });
    }

    $pengeluaran = $query->get();

    $totalPengeluaran = $pengeluaran->sum('jumlah');

    $pdf = PDF::loadView('pengeluaran.pdf', compact('pengeluaran', 'totalPengeluaran', 'year', 'startDate', 'endDate'));

    $pdf->setPaper('A4', 'portrait');

    if ($startDate && $endDate) {
        $startDateFormatted = date('d-m-Y', strtotime($startDate));
        $endDateFormatted = date('d-m-Y', strtotime($endDate));
        $filename = "pengeluaran_{$startDateFormatted}_sampai_{$endDateFormatted}.pdf";
    } elseif ($year) {
        $filename = "pengeluaran_tahun_{$year}.pdf";
    } else {
        $filename = "pengeluaran_seluruh.pdf";
    }

    return $pdf->stream($filename);
}


  

    
    public function exportPengeluaranExcel(Request $request)
    {
        $year = $request->input('year');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $query = Pengeluaran::query()->with('category', 'parentPengeluaran');

        if ($year) {
            $query->whereHas('parentPengeluaran', function ($q) use ($year) {
                $q->whereYear('tanggal', $year);
            });
        }

        if ($startDate && $endDate) {
            $query->whereHas('parentPengeluaran', function ($q) use ($startDate, $endDate) {
                $q->whereBetween('tanggal', [$startDate, $endDate]);
            });
        }

        $pengeluaran = $query->get();

        if ($startDate && $endDate) {
            $startDateFormatted = date('d-m-Y', strtotime($startDate));
            $endDateFormatted = date('d-m-Y', strtotime($endDate));
            $filename = "laporan_pengeluaran_{$startDateFormatted}_sampai_{$endDateFormatted}.xlsx";
        } elseif ($year) {
            $filename = "laporan_pengeluaran_tahun_{$year}.xlsx";
        } else {
            $filename = "laporan_pengeluaran_seluruh.xlsx";
        }

        return Excel::download(new PengeluaranExport($pengeluaran, $year, $startDate, $endDate), $filename);
    }

  public function show($id)
    {
        $parentPengeluaran = ParentPengeluaran::with('pengeluaran.category')->findOrFail($id);

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
                $namafile = $file->getClientOriginalName();
                $file->move(public_path('DataPengeluaran'), $namafile);

                $spreadsheet = IOFactory::load(public_path('DataPengeluaran/' . $namafile));
                $sheetNames = $spreadsheet->getSheetNames();

                foreach ($sheetNames as $sheetIndex => $sheetName) {
                    try {
                        $tanggal = \Carbon\Carbon::createFromFormat('d-m-Y', $sheetName);
                    } catch (\Exception $e) {
                        continue;
                    }

                    $parentPengeluaran = new ParentPengeluaran();
                    $parentPengeluaran->tanggal = $tanggal;
                    $parentPengeluaran->save();

                    $sheet = $spreadsheet->getSheet($sheetIndex);
                    $highestRow = $sheet->getHighestRow();
                    $highestColumn = $sheet->getHighestColumn();

                    for ($row = 3; $row <= $highestRow; $row++) {
                        $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE);

                        if (empty($rowData[0][0])) {
                            continue;
                        }

                        // Validasi data
                        if (!is_numeric($rowData[0][2]) || !is_numeric($rowData[0][3]) || !is_numeric($rowData[0][4])) {
                            throw new \Exception('Kolom Jumlah Satuan, Nominal, dan dll harus berupa angka pada baris ' . $row);
                        }

                        // Validasi kategori
                        if (empty($rowData[0][5])) {
                            throw new \Exception('Kode Kategori tidak boleh kosong pada baris ' . $row);
                        }

                        $pengeluaran = new Pengeluaran();
                        $pengeluaran->name = $rowData[0][0];
                        $pengeluaran->description = $rowData[0][1] ?? null;
                        $pengeluaran->jumlah_satuan = (float)$rowData[0][2];
                        $pengeluaran->nominal = (float)$rowData[0][3];
                        $pengeluaran->dll = (float)$rowData[0][4];
                        $pengeluaran->id = $rowData[0][5];
                        $pengeluaran->jumlah = ($pengeluaran->jumlah_satuan * $pengeluaran->nominal) + $pengeluaran->dll;
                        $pengeluaran->id_parent = $parentPengeluaran->id;
                        
                        $pengeluaran->save();
                    }
                }

                @unlink(public_path('DataPengeluaran/' . $namafile));
            }
        }

        DB::commit();
        return redirect()->back()->with('success', 'Data pengeluaran berhasil diimpor!');

    } catch (\Exception $e) {
        DB::rollBack();
        
        if(isset($namafile) && file_exists(public_path('DataPengeluaran/' . $namafile))) {
            @unlink(public_path('DataPengeluaran/' . $namafile));
        }

        return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
    }
}



    public function downloadTemplate()
    {

        return Excel::download(new TemplateExport(), 'template_pengeluaran.xlsx');
    }
}