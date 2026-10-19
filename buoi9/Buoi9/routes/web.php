<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BicycleController;

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

// Admin Routes
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/', function () {
        return redirect()->route('admin.bicycles.index');
    });

    // Route admin cho bicycle
    // ===== TRASH =====
    Route::get('/bicycles/trash', [BicycleController::class, 'trash'])
        ->name('bicycles.trash');
    Route::get('/bicycles/{id}/restore', [BicycleController::class, 'restore'])
        ->name('bicycles.restore');
    Route::post('/bicycles/restore-selected', [BicycleController::class, 'restoreSelected'])
        ->name('bicycles.restoreSelected');
    Route::post('/bicycles/restore-all', [BicycleController::class, 'restoreAll'])
        ->name('bicycles.restoreAll');
    Route::delete('/bicycles/{id}/force', [BicycleController::class, 'forceDelete'])
        ->name('bicycles.forceDelete');
    Route::delete('/bicycles/force-selected', [BicycleController::class, 'forceDeleteSelected'])
        ->name('bicycles.forceDeleteSelected');
    Route::delete('/bicycles/force-all', [BicycleController::class, 'forceDeleteAll'])
        ->name('bicycles.forceDeleteAll');

    // ========= CRUD =========
    Route::get('/bicycles', [BicycleController::class, 'adminIndex'])
        ->name('bicycles.index');
    Route::resource('bicycles', BicycleController::class)
        ->except(['index', 'show', 'filter']);
});
