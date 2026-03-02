<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProdukCon;
use App\Http\Controllers\PembelianCon;
use App\Http\Controllers\KeranjangController;
use Illuminate\Support\Facades\Auth;


Route::get('/', function () {
    return view('utama');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';


Route::get('/', [ProdukCon::class, 'home'])->name('homeproduk');
Route::post(
    '/pembelian/storeinput',
    [
        PembelianCon::class,
        'storeinput'
    ]
)->name('storeInputpembelian')->middleware('auth');
Route::post('/logout', function () {
    Auth::logout();
    return redirect('/');
})->name('logout');


Route::post('/keranjang/store', [KeranjangController::class, 'store'])
->middleware('auth');
