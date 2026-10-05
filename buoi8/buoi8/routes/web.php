<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return redirect()->route('admin.dashboard');
})->middleware(['auth'])->name('dashboard');

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', fn() => redirect()->route('admin.dashboard'))->name('home');
    Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');

    Route::resource('danhmuc', \App\Http\Controllers\Admin\DanhMucController::class);

    // Đặt route custom trước resource để tránh xung đột id/{id}
    Route::post('tin/{id}/restore', [\App\Http\Controllers\Admin\TinTucAdminController::class, 'restore'])->name('tin.restore');
    Route::delete('tin/{id}/force', [\App\Http\Controllers\Admin\TinTucAdminController::class, 'forceDelete'])->name('tin.force-delete');
    Route::delete('tin/gallery/{id}', [\App\Http\Controllers\Admin\TinTucAdminController::class, 'deleteGalleryImage'])->name('tin.delete-gallery');
    Route::resource('tin', \App\Http\Controllers\Admin\TinTucAdminController::class);
});

require __DIR__.'/auth.php';
