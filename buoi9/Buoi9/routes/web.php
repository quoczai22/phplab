<?php

use Illuminate\Support\Facades\Route;
use App\Models\User;
use App\Models\Station;
use App\Models\Bicycle;
use App\Http\Controllers\StationController;
use App\Http\Controllers\BicycleController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\RentalController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\Api\RentalApiController;

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

    // ===== THÁNG NÀY =====
    $start = now()->startOfMonth();
    $end = now();

    // ===== TOP BIKERS =====
    $topBikers = User::select(
            'users.id',
            'users.name',
            'users.phone',
            'users.created_at'
        )
        ->join('rentals', 'users.id', '=', 'rentals.user_id')
        ->whereBetween('rentals.rented_at', [$start, $end])
        ->selectRaw('
            SUM(TIMESTAMPDIFF(MINUTE, rented_at, returned_at)) as total_minutes,
            COUNT(rentals.id) as total_rentals
        ')
        ->groupBy(
            'users.id',
            'users.name',
            'users.phone',
            'users.created_at'
        )
        ->orderByDesc('total_minutes')
        ->orderByDesc('total_rentals')
        ->orderBy('users.created_at')
        ->limit(10)
        ->get();

    // ===== THỐNG KÊ =====
    $totalBikes = Bicycle::count();
    $totalStations = Station::count();

    // ===== TOP STATIONS =====
    $topStations = Station::with(['reviews' => function($q){
            $q->latest()->take(3);
        }])
        ->withAvg('reviews', 'rating')
        ->inRandomOrder()
        ->take(10)
        ->get();

    return view('welcome', compact(
        'topBikers',
        'totalBikes',
        'totalStations',
        'topStations'
    ));
});

// ===== AUTH (USER) ===== 
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');



// ===== ADMIN AUTH ===== 
Route::get('/admin/login', [AdminController::class, 'showLogin'])->name('admin.login');
Route::post('/admin/login', [AdminController::class, 'login'])->name('admin.login.post');

// ===== STATION (USER) ===== 
Route::resource('stations', StationController::class)
    ->only(['index']); // chỉ cho user xem
Route::get('/api/stations', [StationController::class, 'filter'])
    ->name('stations.filter');

// ===== REVIEWS (PUBLIC VIEW) =====
Route::get('/reviews', [ReviewController::class, 'index'])->name('reviews.index');

// ===== TẠO DỮ LIỆU MẪU FAKER NHANH TRÊN TRÌNH DUYỆT =====
Route::get('/seed-faker', function () {
    \Illuminate\Support\Facades\Artisan::call('db:seed');
    return redirect('/')->with('success', 'Đã tạo dữ liệu mẫu bằng Faker thành công! (Admin: admin@gmail.com / Admin@123 | User: user@gmail.com / User@123)');
})->name('seed.faker');

// ===== ADMIN ROUTES (Protected by 'admin' middleware) =====
Route::prefix('admin')->name('admin.')->middleware('admin')->group(function () {

    Route::get('/', [AdminController::class, 'index'])->name('dashboard');
    Route::post('/logout', [AdminController::class, 'logout'])->name('logout');

    // ===== Route admin cho station ===== 
    // ===== TRASH =====
    Route::get('/stations/trash', [StationController::class, 'trash'])
        ->name('stations.trash');
    Route::get('/stations/{id}/restore', [StationController::class, 'restore'])
        ->name('stations.restore');
    Route::post('/stations/restore-selected', [StationController::class, 'restoreSelected'])
        ->name('stations.restoreSelected');
    Route::post('/stations/restore-all', [StationController::class, 'restoreAll'])
        ->name('stations.restoreAll');
    Route::delete('/stations/{id}/force', [StationController::class, 'forceDelete'])
        ->name('stations.forceDelete');
    Route::delete('/stations/force-selected', [StationController::class, 'forceDeleteSelected'])
        ->name('stations.forceDeleteSelected');
    Route::delete('/stations/force-all', [StationController::class, 'forceDeleteAll'])
        ->name('stations.forceDeleteAll');

    // ========= CRUD =========
    Route::get('/stations', [StationController::class, 'adminIndex'])
        ->name('stations.index');
    Route::resource('stations', StationController::class)
        ->except(['index', 'show', 'filter']);

    // ===== Route admin cho bicycle ===== 
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

//Route thuê xe trả xe & đánh giá
Route::middleware(['auth'])->group(function () {
    // rental
    Route::get('/bike-rental', [RentalController::class, 'index'])->name('rental.index');
    Route::post('/bike-rental', [RentalController::class, 'rent'])->name('rental.store');
    Route::get('/stations-by-ward', [RentalController::class, 'getStations']);
    Route::get('/bikes-by-station', [RentalController::class, 'getBikesByStation']);

    // return
    Route::get('/bike-return', [RentalController::class, 'returnForm'])->name('rental.return.form');
    Route::post('/bike-return', [RentalController::class, 'returnBike'])->name('rental.return');

    //history
    Route::get('/rental-history', [RentalController::class, 'history'])->name('rental.history');

    // reviews & comments
    Route::post('/reviews/station', [ReviewController::class, 'storeStationReview'])->name('reviews.station.store');
    Route::post('/reviews/bicycle', [ReviewController::class, 'storeBicycleReview'])->name('reviews.bicycle.store');
    Route::get('/rental/{id}/review', [ReviewController::class, 'createFromRental'])->name('reviews.rental');
    Route::post('/rental/{id}/review', [ReviewController::class, 'storeRentalReview'])->name('reviews.rental.store');
    Route::delete('/reviews/station/{id}', [ReviewController::class, 'destroyStationReview'])->name('reviews.station.destroy');
    Route::delete('/reviews/bicycle/{id}', [ReviewController::class, 'destroyBicycleReview'])->name('reviews.bicycle.destroy');
});

// ===== TRUNG TÂM API THUÊ XE (NỘI BỘ & PUBLIC API CITYBIKES) =====
Route::get('/api-explorer', [RentalApiController::class, 'apiExplorer'])->name('api.explorer');
Route::post('/api-explorer/import', [RentalApiController::class, 'importFromCityBikes'])->name('api.citybikes.import');
