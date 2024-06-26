<?php

use App\Http\Controllers\ProductsController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

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

// Homepage
Route::redirect('/', 'login');

// Dashboard
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Profile
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Products page
Route::middleware('auth')->group(function () {
    Route::get('/products/index', [ProductsController::class, 'index'])->name('products.index');

    Route::middleware('is_admin')->group(function() {
        // Create
        Route::get('/products/create', [ProductsController::class, 'create'])->name('products.create');
        Route::post('/products/store', [ProductsController::class, 'store'])->name('products.store');

        // Edit
        Route::get('/products/edit/{product}', [ProductsController::class, 'edit'])->name('products.edit');
        Route::put('/products/update/{product}', [ProductsController::class, 'update'])->name('products.update');

        Route::delete('/products/delete/{product}',[ProductsController::class, 'delete'])->name('products.delete');
    });
});

require __DIR__.'/auth.php';
