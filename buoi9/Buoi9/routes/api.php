<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\RentalApiController;

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

// ===== RESTful API Thuê Xe Đạp (Cách 1 & Cách 2) =====
Route::prefix('v1')->group(function () {
    // 1. Nội bộ BikeGo API
    Route::get('/stations', [RentalApiController::class, 'stations']);
    Route::get('/stations/{id}/bicycles', [RentalApiController::class, 'stationBicycles']);
    Route::get('/bicycles', [RentalApiController::class, 'bicycles']);
    Route::post('/rent', [RentalApiController::class, 'rent']);
    Route::post('/return', [RentalApiController::class, 'returnBike']);

    // 2. Proxy gọi Public API quốc tế CityBikes (api.citybik.es)
    Route::get('/citybikes', [RentalApiController::class, 'externalCityBikes']);
});

