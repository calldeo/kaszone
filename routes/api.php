<?php
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;


use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\SettingController;
use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\PemasukanController;
use App\Http\Controllers\API\PengeluaranController;
// use Auth; 

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/login', [AuthController::class, 'login']);
Route::get('/unautorized', function () {
    return response()->json([
        "status" => 401,
        "message" => "Unautorized"
    ]);
})->name('unautorized');

Route::group(['middleware' => 'auth:api'], function () {
    Route::get('/validate', function (Request $request) {
        return response()->json([
            "status" => 200,
            "message" => "Authorized"
        ]);
    });
    Route::get('/logout', [AuthController::class, 'logout']);




    
      // User Route
    Route::prefix('user')->group(function () {
        Route::get('/', [UserController::class, 'dashboard']);
        Route::get('/all', [UserController::class, 'getAllUsers']);
        Route::get('/create', [UserController::class, 'create']);
        Route::post('/store', [UserController::class, 'store']);
        Route::get('/show/{id}', [UserController::class, 'show']);
        Route::put('/update/{id}', [UserController::class, 'update']);
        Route::delete('/destroy/{id}', [UserController::class, 'destroy']);
        Route::get('/profile', [UserController::class, 'profile']);
        Route::get('/detail/{id}', [UserController::class, 'showDetail']);
        Route::post('/update-profile', [UserController::class, 'updateProfile']);
        Route::post('/update-password', [UserController::class, 'updatePassword']);
        Route::get('/profile-picture', [UserController::class, 'showProfilePicture']);


    });


       // Category Route
    Route::prefix('category')->group(function () {
        Route::get('/all', [CategoryController::class, 'getAllCategory']);
        Route::get('/create', [CategoryController::class, 'create']);
        Route::post('/store', [CategoryController::class, 'store']);
        Route::get('/show/{id}', [CategoryController::class, 'show']);
        Route::put('/update/{id}', [CategoryController::class, 'update']);
        Route::delete('/destroy/{id}', [CategoryController::class, 'destroy']);
        Route::get('/detail/{id}', [CategoryController::class, 'showDetail']);
        Route::post('/import', [CategoryController::class, 'kategoriImportExcel']);
        Route::get('/export', [CategoryController::class, 'exportKategori']);
        Route::get('/template', [CategoryController::class, 'downloadTemplateExcel']);

    });


     Route::prefix('income')->group(function () {
        Route::get('/all', [PemasukanController::class, 'getAllIncome']);
        Route::get('/create', [PemasukanController::class, 'create']);
        Route::get('/saldo', [PemasukanController::class, 'saldo']);
        Route::post('/store', [PemasukanController::class, 'store']);
        Route::get('/show/{id}', [PemasukanController::class, 'show']);
        Route::put('/update/{id}', [PemasukanController::class, 'update']);
        Route::delete('/destroy/{id}', [PemasukanController::class, 'destroy']);
        Route::get('/detail/{id}', [PemasukanController::class, 'showDetail']);
        Route::post('/import', [PemasukanController::class, 'pemasukanImportExcel']);
        Route::get('/export/pdf', [PemasukanController::class, 'exportPemasukanPDF']);
        Route::post('/export/excel', [PemasukanController::class, 'exportPemasukanExcel']);
        Route::get('/template', [PemasukanController::class, 'downloadTemplate']);


    });


    Route::prefix('outcome')->middleware('auth:api')->group(function () {
        Route::get('/all', [PengeluaranController::class, 'getAllOutcome']);
        Route::get('/create', [PengeluaranController::class, 'create']);
        Route::post('/store', [PengeluaranController::class, 'store']);
        Route::get('/show/{id}', [PengeluaranController::class, 'show']);
        Route::post('/update/{id}', [PengeluaranController::class, 'update']); 
        Route::delete('/destroy/{id}', [PengeluaranController::class, 'destroy']);
        Route::delete('/pengeluaran/{id}', [PengeluaranController::class, 'delete']); 
        Route::delete('/pengeluaran/parent/{id}', [PengeluaranController::class, 'deleteAll']);
        Route::get('/export/pdf', [PengeluaranController::class, 'exportPengeluaranPDF']);
        Route::post('/export/excel', [PengeluaranController::class, 'exportPengeluaranExcel']);
        Route::get('/detail/{id}', [PengeluaranController::class, 'showDetail']);
        Route::post('/import', [PengeluaranController::class, 'importPengeluaran']);
        Route::get('/template', [PengeluaranController::class, 'downloadTemplateAPI']);
        Route::get('/image/{id}', [PengeluaranController::class, 'showImage']);


    });

    Route::prefix('setting')->middleware('auth:api')->group(function(){
    Route::get('/edit-minimal-saldo', [SettingController::class, 'editMinimalSaldo'])->name('edit.minimal.saldo');
    Route::post('/update-minimal-saldo', [SettingController::class, 'updateMinimalSaldo']);
    });

});























































// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();  

// });
// route::get('siswa',[SiswaController::class,'SiswaAPI']);
// route::post('siswa/update/{id_siswa}',[SiswaController::class,'updateAPI']);

// route::get('pelanggaran/show',[PelanggaranController::class,'show']);
// route::get('penghargaan/show',[PenghargaanController::class,'show']);
// route::get('penanganan/show', [PenangananController::class, 'show']);



// route::post('siswa',function(Request $request){
//     $valid = Auth::attempt($request->all());

//     if($valid){
//         $siswa = Auth::Siswa();
//         $siswa->api_token = Str::random(100);
//         $siswa->save();

//         // $user->makeVisible('api_token');

//         return $siswa;
//     }
//     return response()->json([
//         'message'=> 'email & password doesn\'t match'
//     ],404);

// });
// route::get('siswa',[SiswaController::class,'SiswaAPI']);
// route::post('login',[SiswaController::class,'loginapi']);

// route::post('siswa',function(Request $request){
//     $valid = Auth::attempt($request->all());

//     if($valid){
//         $siswa = Auth::Siswa();
//         $siswa->api_token = Str::random(100);
//         $siswa->save();

//         // $user->makeVisible('api_token');

//         return $siswa;
//     }
//     return response()->json([
//         'message'=> 'email & password doesn\'t match'
//     ],404);
// });
