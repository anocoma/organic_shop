<?php

use App\Http\Controllers\Admins\DanhMucController;
use App\Http\Controllers\Admins\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Middleware\CheckRoleAdminMiddleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admins\BinhLuanController;
use App\Http\Controllers\Admins\DonHangController;
use App\Http\Controllers\Admins\SanPhamController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
/*Route::get('/admin', function () {
    return view('layouts.admin');
})->name('admin');*/





//Auth::routes();
//Route::get('/home', [HomeController::class, 'index'])->name('home');

Route::get('login', [AuthController::class, 'showLogin']);
Route::post('login', [AuthController::class, 'login'])->name('login');
Route::get('register', [AuthController::class, 'showRegister']);
Route::post('register', [AuthController::class, 'register'])->name('register');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


Route::get('/product/detail/{id}', [ProductController::class, 'detailProduct'])->name('product.detail');
Route::get('/list-cart', [CartController::class, 'listCart'])->name('cart.list');
Route::post('/add-to-cart', [CartController::class, 'addCart'])->name('cart.add');
Route::post('/update-cart', [CartController::class, 'updateCart'])->name('cart.update');


Route::middleware(['auth',])->prefix('donhangs')->as('donhangs.')->group(function (){
    Route::get('/', [OrderController::class, 'index'])->name('index');
    Route::get('/create', [OrderController::class, 'create'])->name('create');
    Route::post('/store', [OrderController::class, 'store'])->name('store');
    Route::get('/show/{id}', [OrderController::class, 'show'])->name('show');
    Route::put('{id}/update', [OrderController::class, 'update'])->name('update');
});


Route::middleware(['auth','auth.admin'])->prefix('admins')->as('admins.')->group(function (){

    Route::get('/dashboard', function (){
       return view('layouts.admin');
    })->name('dashboard');

    Route::resource('danhmuc',DanhMucController::class);
    Route::resource('sanpham', SanPhamController::class);
    Route::resource('donhang', DonHangController::class);
    Route::resource('user', UserController::class);

});


