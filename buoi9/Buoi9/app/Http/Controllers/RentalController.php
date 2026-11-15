<?php

namespace App\Http\Controllers;

use App\Models\Station;
use App\Models\Bicycle;
use App\Models\Rental;
use App\Helper\WardHelper;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class RentalController extends Controller
{
    //=============== Rental ===========================
    public function index()
    {
        $wards = WardHelper::get_Wards();

        // kiểm tra user đang thuê
        $rental = Rental::where('user_id', Auth::id())
            ->whereNull('returned_at')
            ->with('bicycle', 'rentStation')
            ->first();

        // nếu đang thuê -> show trang trả
        if ($rental) {
            return view('rental.return', compact('rental', 'wards'));
        }

        // chưa thuê -> show trang thuê
        return view('rental.index', compact('wards'));
    }

    public function getStations(Request $request)
    {
        // Nếu phường được chọn chưa được seed trạm/xe thì tự động lấy dữ liệu API/Faker đổ vào
        if ($request->filled('ward')) {
            DatabaseSeeder::ensureWardHasData($request->ward);
        }

        $stations = Station::where('ward', $request->ward)
            ->where('status', 'active')
            ->withCount('bicycles')
            ->get()
            ->filter(fn($s) => $s->bicycles_count > 0) // không lấy trạm hết xe
            ->values();

        return response()->json($stations);
    }

    // load danh sách xe đang có tại trạm (ajax)
    public function getBikesByStation(Request $request)
    {
        // Nếu trạm được chọn chưa có xe rảnh thì tự động seed bổ sung xe vào trạm đó
        if ($request->filled('station_id')) {
            DatabaseSeeder::ensureStationHasBikes($request->station_id);
        }

        $bikes = Bicycle::where('station_id', $request->station_id)
            ->where('location_status', 'at_station')
            ->where('condition_status', 'active')
            ->orderBy('bike_code')
            ->get(['id', 'bike_code', 'rental_price_per_hour']);

        return response()->json($bikes);
    }

    public function rent(Request $request)
    {
        // check đã thuê chưa
        $hasRental = Rental::where('user_id', Auth::id())
            ->whereNull('returned_at')
            ->exists();

        if ($hasRental) {
            return back()->with('error', 'Bạn đang thuê xe khác');
        }

        // validate
        $request->validate([
            'station_id' => 'required',
            'bike_code' => 'required|exists:bicycles,bike_code'
        ]);

        // tìm xe
        $bike = Bicycle::where('bike_code', $request->bike_code)
            ->where('station_id', $request->station_id)
            ->first();

        if (!$bike) {
            return back()->with('error', 'Xe không thuộc trạm này');
        }

        // cập nhật trạng thái xe
        $bike->update([
            'station_id' => null,
            'location_status' => 'rented'
        ]);

        // tạo rental
        Rental::create([
            'user_id' => Auth::id(),
            'bicycle_id' => $bike->id,
            'rent_station_id' => $request->station_id,
            'rented_at' => now(),
            'price_per_hour' => $bike->rental_price_per_hour,
            // các field còn lại để null
        ]);

        return back()->with('success', 'Thuê xe thành công');
    }

    //=============== Return ===========================
    public function returnForm()
    {
        $wards = WardHelper::get_Wards();

        $rental = Rental::where('user_id', Auth::id())
            ->whereNull('returned_at')
            ->with('bicycle', 'rentStation')
            ->first();

        return view('rental.return', compact('wards', 'rental'));
    }

    public function returnBike(Request $request)
    {
        $rental = Rental::where('user_id', Auth::id())
            ->whereNull('returned_at')
            ->with('bicycle')
            ->first();

        if (!$rental) {
            return back()->with('error', 'Bạn chưa thuê xe');
        }

        $bicycle = Bicycle::where('id', $rental->bicycle_id)
            ->first();
        if (!$bicycle) {
            return back()->with('error', 'Xe không tồn tại');
        }

        // tính thời gian
        $minutes = round(abs(now()->diffInMinutes($rental->rented_at)), 2);
        $total = $minutes * $rental->price_per_hour / 60;

        // update
        $bicycle->update([
            'station_id' => $request->station_id,
            'location_status' => 'at_station',
        ]);

        $rental->update([
            'return_station_id' => $request->station_id,
            'returned_at' => now(),
            'total_hours' => $minutes,
            'total_amount' => $total,
        ]);

        return back()->with('success', 'Trả xe thành công');
    }

    public function history()
    {
        $rentals = Rental::where('user_id', Auth::id())
            ->with(['bicycle', 'rentStation', 'returnStation'])
            ->orderByDesc('rented_at')
            ->get();

        return view('rental.history', compact('rentals'));
    }
}

