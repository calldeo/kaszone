<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;

use App\Http\Controllers\SiswaController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\BendaharaController;
use App\Http\Controllers\SiswaaController;
use App\Http\Controllers\CategoryController;


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

// Route::get('/', function () {
//     return view('welcome');
// });
route::get('/',[LoginController::class,'landing'])->name('landing');

route::get('/login',[LoginController::class,'halamanlogin'])->name('login');
route::post('/postlogin',[LoginController::class,'postlogin'])->name('postlogin');
route::get('/logout',[LoginController::class,'logout'])->name('logout');








Route::group(['middleware' => ['auth','ceklevel:admin,guru,siswa']], function (){
    route::get('/home',[HomeController::class,'index'])->name('home');
    // Route::get('/home', [HomeController::class, 'home'])->name('home');
Route::get('/jumlah_suara', [HomeController::class, 'showJumlahSuara'])->name('jumlah_suara');
    
    // route::get('/home',[HomeController::class,'penghargaan'])->name('home');
    
});








route::get('/admin/search',[AdminController::class,'search'])->name('admin.search');
route::get('/admin',[AdminController::class,'admin'])->name('admin');
Route::delete('/admin/{id}', [AdminController::class,'destroy'])->name('admin.destroy');
route::get('/add_admin',[AdminController::class,'add_admin'])->name('add_admin');
Route::post('/admin/store',[AdminController::class,'store']);
Route::get('/admin/{id}/edit_admin  ',[AdminController::class,'edit']);
Route::put('/admin/{id}',[AdminController::class,'update']);



Route::post('/importguru', [BendaharaController::class, 'guruimportexcel'])->name('import-guru');
route::get('/guru/search',[BendaharaController::class,'search'])->name('guru.search');
route::get('/bendahara',[BendaharaController::class,'bendahara'])->name('bendahara');
Route::delete('/guruu/{id}', [BendaharaController::class,'destroy'])->name('guruu.destroy');
route::get('/add_bendahara',[BendaharaController::class,'add_bendahara'])->name('add_bendahara');
Route::post('/guruu/store',[BendaharaController::class,'store']);
Route::get('/bendahara/{id}/edit_bendahara  ',[BendaharaController::class,'edit']);
Route::put('/guruu/{id}',[BendaharaController::class,'update']);




Route::post('/importsiswa', [SiswaaController::class, 'siswaimportexcel'])->name('import-siswa');
route::get('/siswa/search',[SiswaaController::class,'search'])->name('siswa.search');
route::get('/siswaa',[SiswaaController::class,'siswaa'])->name('siswaa');
Route::delete('/siswaa/{id}', [SiswaaController::class,'destroy'])->name('siswaa.destroy');
route::get('/add_siswaa',[siswaaController::class,'add_siswaa'])->name('add_siswaa');
Route::post('/siswaa/store',[SiswaaController::class,'store']);
Route::get('/siswaa/{id}/edit_siswaa  ',[SiswaaController::class,'edit']);
Route::put('/siswaa/{id}',[SiswaaController::class,'update']);


Route::get('/kategori', [CategoryController::class, 'index']);
route::get('/add_kategori',[CategoryController::class,'add_kategori'])->name('add_kategori');
Route::post('/kategori/store',[CategoryController::class,'store']);
// Route::get('/categories/{id}', [CategoryController::class, 'show']);
// Route::post('/categories', [CategoryController::class, 'store']);
// Route::put('/categories/{id}', [CategoryController::class, 'update']);
// Route::delete('/categories/{id}', [CategoryController::class, 'destroy']);
// Route::get('/categories/search', [CategoryController::class, 'search']);
// Route::post('/categories/import', [CategoryController::class, 'import']);
// Route::get('/categories/export', [CategoryController::class, 'export']);
