<?php

use App\Http\Controllers\GuardiansController;
use App\Http\Controllers\KidsController;
use App\Http\Controllers\RecordsController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HolidaysController;
use App\Http\Controllers\LessonsController;
use App\Http\Controllers\GradesController;
use App\Http\Controllers\Auth;
use App\Http\Controllers\HomeController;

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



//Auth::routes();  //管理者権限以上でのみユーザー登録
//ログイン関連
Route::get('login',[Auth\LoginController::class,'showLoginForm'])->name('login');
Route::post('login',[Auth\LoginController::class,'login']);
Route::post('logout',[Auth\LoginController::class,'logout'])->name('logout');

Route::get('/password/reset',[Auth\ForgotPasswordController::class,'showLinkRequestForm'])->name('password.request');
Route::post('/password/email',[Auth\ForgotPasswordController::class,'sendResetLinkEmail'])->name('password.email');
Route::get('/password/reset/{token}',[Auth\ResetPasswordController::class,'showResetForm'])->name('password.reset');
Route::post('/password/reset',[Auth\ResetPasswordController::class,'reset']);


//ユーザー以上
Route::group(['middleware' => ['auth','can:user-higher']], function(){

    Route::get('/', [HomeController::class, 'index'])->name('home');

    //レコード登録関連
    Route::resource('records', RecordsController::class)->only(['create','store']);
    //降園記録
    Route::post('records/{record}/leave', [RecordsController::class, 'leave']);
    //園児関連(閲覧のみ)
    Route::get('grades/kids/{grade}', [KidsController::class, 'index'])->name('kids.index');
    //保護者関連
    Route::resource('guardians', GuardiansController::class)->only(['create', 'store', 'update','edit', 'destroy']);
    //習い事関連
    Route::resource('lessons', LessonsController::class)->only(['create', 'store','edit', 'update', 'destroy']);

});

//管理者以上
Route::group(['middleware' => ['auth','can:admin-higher']],function(){

    //レコード閲覧・出力
    Route::resource('records',RecordsController::class)->only(['index', 'edit', 'update', 'destroy']);
    //各種登録画面
    Route::get('/procedure',[HomeController::class,'procedure'])->name('procedure');
    //園児関連
    Route::resource('kids',KidsController::class)->only(['create', 'store', 'show', 'edit', 'update', 'destroy']);
    //休園日登録
    Route::resource('holidays', HolidaysController::class)->only(['index','create', 'store', 'edit', 'update', 'destroy']);
    //進級処理
    Route::post('kids/gradeup',[KidsController::class, 'gradeUp'])->name('gradeUp');

});

//システム管理者のみ
Route::group(['middleware' => ['auth','can:system-only']], function(){

    //ユーザー新規登録
    Route::get('register',[Auth\RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('register',[Auth\RegisterController::class,'register']);

    //初期設定(クラス登録)
    Route::resource('grades', GradesController::class)->only(['update','store','destroy']);
});
