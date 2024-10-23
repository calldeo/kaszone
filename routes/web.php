<?php

use App\Models\Category;
use App\Models\Pengeluaran;
use App\Exports\PengeluaranExport;
use App\Exports\PemasukanExport;

use Maatwebsite\Excel\Facades\Excel;
// use Maatwebsite\Excel\Facades\Excel;

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\BendaharaController;
use App\Http\Controllers\PemasukanController;
use App\Http\Controllers\PengeluaranController;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\Auth\RegisterController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


route::get('/',[LoginController::class,'landing'])->name('landing');

route::get('/login',[LoginController::class,'halamanlogin'])->name('login');
route::post('/postlogin',[LoginController::class,'postlogin'])->name('postlogin');


Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Menampilkan form registrasi // Memproses registrasi
Route::get('/register', [RegisterController::class, 'showRegisterForm'])->name('register');

Route::post('/postregister', [RegisterController::class, 'register']);

Route::get('/profile/edit', [UserProfileController::class, 'edit'])->name('profile.edit');
Route::put('/profile/update', [UserProfileController::class, 'update'])->name('profile.update');




Route::group(['middleware' => ['auth', 'permission:Home']], function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('/get-financial-data-yearly', [HomeController::class, 'getFinancialDataYearly'])->name('get.financial.data.yearly');
});





route::get('/table',[AdminController::class,'table'])->name('table');
route::get('/users',[AdminController::class,'users'])->name('users');
route::get('/kategoris',[AdminController::class,'kategoris'])->name('kategoris');
route::get('/report-income',[AdminController::class,'reportIncome'])->name('income');
route::get('/report-production',[AdminController::class,'reportProduction'])->name('production');
route::get('/income',[AdminController::class,'income'])->name('income');
route::get('/production',[AdminController::class,'production'])->name('production');
route::get('/roles',[AdminController::class,'roles'])->name('roles');







Route::group(['middleware' => ['auth','permission:Bendahara']], function (){

    Route::post('/importbendahara', [BendaharaController::class, 'userImportExcel'])->name('import-bendahara');
    route::get('/user',[BendaharaController::class,'bendahara'])->name('bendahara');
    Route::delete('/user/{id}/destroy', [BendaharaController::class, 'destroy'])->name('user.destroy');
    route::get('/add-user',[BendaharaController::class,'create'])->name('add_user');
    Route::post('/user/store',[BendaharaController::class,'store']);
    Route::get('/user/{id}/edit  ',[BendaharaController::class,'edit']);
    Route::put('/guruu/{id}',[BendaharaController::class,'update']);
    Route::get('/user/{id}/detail', [BendaharaController::class, 'showDetail'])->name('user.showDetail');
Route::post('/switch-role', [BendaharaController::class, 'switchRole'])->name('switchRole');

});



Route::group(['middleware' => ['auth','permission:Kategori']], function (){

    Route::get('/kategori', [CategoryController::class, 'index']);
    route::get('/add-kategori',[CategoryController::class,'create'])->name('add_kategori');
    Route::post('/kategori/store',[CategoryController::class,'store']);
    Route::delete('/kategori/{id}/destroy', [CategoryController::class,'destroy'])->name('kategori.destroy');
    Route::get('/kategori/{id}/edit_kategori', [CategoryController::class, 'edit'])->name('kategori.edit');
    Route::put('/kategori/{id}', [CategoryController::class, 'update'])->name('kategori.update');
    Route::post('/importkategori', [CategoryController::class, 'kategoriimportexcel'])->name('import-kategori');
    Route::get('/export-kategori',[CategoryController::class,'exportkategori'])->name('export-kategori');
    Route::get('/download-template-kategori', [CategoryController::class, 'downloadTemplateExcel'])->name('download-template-kategori');
    Route::get('/kategori/{id}/detail', [CategoryController::class, 'showDetail'])->name('kategori.showDetail');

});

Route::group(['middleware' => ['auth','permission:Data Pemasukan']], function (){

    route::get('/pemasukan',[PemasukanController::class,'index'])->name('index');
    Route::get('/add-pemasukan', [PemasukanController::class, 'create']);
    Route::post('/pemasukan/store', [PemasukanController::class, 'store']);
    Route::delete('/pemasukan/{id}/destroy', [PemasukanController::class, 'destroy'])->name('pemasukan.destroy');
    Route::get('/pemasukan/{id_data}/edit', [PemasukanController::class, 'edit'])->name('pemasukan.edit');
    Route::put('/pemasukan/{id_data}', [PemasukanController::class, 'update'])->name('pemasukan.update');
    Route::get('pemasukan/tob', [PemasukanController::class, 'tob'])->name('pemasukan.tob');
    Route::get('/pemasukan/{id_data}/detail', [PemasukanController::class, 'showDetail'])->name('pemasukan.showDetail');
    Route::post('/importpemasukan', [PemasukanController::class, 'pemasukanImportExcel'])->name('import-pemasukan');
    Route::get('/download-template-pemasukan', [PemasukanController::class, 'downloadTemplate'])->name('download.template.pemasukan');
    Route::get('/get-categories/{jenis_kategori}', [PemasukanController::class, 'getCategories']);
    Route::get('/export-pemasukan', [PemasukanController::class, 'exportPemasukanPDF'])->name('export.pemasukan');
    Route::post('/export-pemasukan-excel', [PemasukanController::class, 'exportPemasukanExcel'])->name('export.pemasukan.excel');



});


Route::group(['middleware' => ['auth','permission:Data Pengeluaran']], function (){

    route::get('/pengeluaran',[PengeluaranController::class,'index'])->name('index');
    Route::get('/add-pengeluaran', [PengeluaranController::class, 'create']);
    Route::post('/pengeluaran/store', [PengeluaranController::class, 'store'])->name('pengeluaran.store');
    Route::get('/pengeluaran/edit/{id}', [PengeluaranController::class, 'edit'])->name('pengeluaran.edit');
    Route::put('/pengeluaran/{id}', [PengeluaranController::class, 'update'])->name('pengeluaran.update');
    Route::resource('pengeluaran', PengeluaranController::class);
    Route::get('/export-pengeluaran-pdf',[PengeluaranController::class,'exportPengeluaranPdf'])->name('export.pengeluaran.pdf');
    Route::post('/export-pengeluaran-excel', [PengeluaranController::class, 'exportPengeluaranExcel'])->name('export.pengeluaran.excel');
    Route::get('/pengeluaran', [PengeluaranController::class, 'index'])->name('pengeluaran.index');
    Route::get('/pengeluaran/data', [AdminController::class, 'tabe'])->name('admin.tabe');
    Route::get('pengeluaran/tabe', [PengeluaranController::class, 'tabe'])->name('pengeluaran.tabe');
    Route::get('/pengeluaran/{id_data}/detail', [PengeluaranController::class, 'showDetail'])->name('pengeluaran.showDetail');
    Route::get('/pengeluaran/delete/{id_data}', [PengeluaranController::class, 'delete'])->name('pengeluaran.delete');
    Route::get('/pengeluaran/deleteAll/{id}', [PengeluaranController::class, 'deleteAll'])->name('pengeluaran.deleteAll');
    Route::post('/import-pengeluaran', [PengeluaranController::class, 'importPengeluaran'])->name('import-pengeluaran');
    Route::get('/url-to-get-totals', 'PengeluaranController@getTotals');
    Route::get('/download-template', [PengeluaranController::class, 'downloadTemplate'])->name('download-template');
    Route::post('/export-pengeluaran-excel', [PengeluaranController::class, 'exportPengeluaranExcel'])->name('export.pengeluaran.excel');
    
});
Route::group(['middleware' => ['auth','permission:Laporan']], function (){

    Route::get('/laporan', [LaporanController::class, 'index'])->name('index');
    Route::get('/laporan-kas', [LaporanController::class, 'laporanKas'])->name('laporanKas');
    Route::get('/export-laporan', [LaporanController::class, 'exportLaporanPDF'])->name('export.laporan');
    Route::post('/export-laporan-excel', [LaporanController::class, 'exportLaporanExcel'])->name('export.laporan.excel');

});

Route::group(['middleware' => ['auth','permission:Setting']], function (){

    route::get('/role',[SettingController::class,'role'])->name('role');
    route::get('/setting-saldo',[SettingController::class,'saldo'])->name('saldo');
    Route::get('/edit-minimal-saldo', [SettingController::class, 'editMinimalSaldo'])->name('edit.minimal.saldo');
    Route::put('/update-minimal-saldo', [SettingController::class, 'updateMinimalSaldo'])->name('update.minimal.saldo');

    Route::get('/role/{id}/edit',[SettingController::class,'edit']);
    Route::put('/role/{id}',[SettingController::class,'update']);
    Route::get('/add', [SettingController::class, 'create']);
    Route::post('/role/store', [SettingController::class, 'store']);
});
