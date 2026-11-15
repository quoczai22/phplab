<?php

namespace App\Http\Controllers\Api;

use App\Helper\WardHelper;
use App\Http\Controllers\Controller;
use App\Models\Bicycle;
use App\Models\Rental;
use App\Models\Station;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class RentalApiController extends Controller
{
    /**
     * 1. API Nội bộ: Lấy danh sách tất cả trạm xe kèm số xe & điểm đánh giá
     * GET /api/v1/stations
     */
    public function stations(Request $request)
    {
        $query = Station::withCount('bicycles')
            ->withAvg('reviews as avg_rating', 'rating');

        if ($request->filled('ward')) {
            $query->where('ward', $request->ward);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        return response()->json([
            'status' => 'success',
            'total'  => $query->count(),
            'data'   => $query->get(),
        ]);
    }

    /**
     * 2. API Nội bộ: Lấy danh sách xe đạp đang có sẵn tại 1 trạm
     * GET /api/v1/stations/{id}/bicycles
     */
    public function stationBicycles($id)
    {
        $station = Station::findOrFail($id);
        $bicycles = Bicycle::where('station_id', $id)
            ->where('location_status', 'at_station')
            ->where('condition_status', 'active')
            ->withAvg('reviews as avg_rating', 'rating')
            ->get();

        return response()->json([
            'status'         => 'success',
            'station'        => $station->station_name,
            'available_count'=> $bicycles->count(),
            'data'           => $bicycles,
        ]);
    }

    /**
     * 3. API Nội bộ: Lấy danh sách toàn bộ xe đạp
     * GET /api/v1/bicycles
     */
    public function bicycles(Request $request)
    {
        $query = Bicycle::with('station:id,station_name,ward')
            ->withAvg('reviews as avg_rating', 'rating');

        if ($request->filled('location_status')) {
            $query->where('location_status', $request->location_status);
        }

        return response()->json([
            'status' => 'success',
            'total'  => $query->count(),
            'data'   => $query->get(),
        ]);
    }

    /**
     * 4. API Nội bộ: Thuê xe qua JSON API
     * POST /api/v1/rent
     */
    public function rent(Request $request)
    {
        $request->validate([
            'user_id'    => 'required|exists:users,id',
            'station_id' => 'required|exists:stations,id',
            'bike_code'  => 'required|exists:bicycles,bike_code',
        ]);

        $hasRental = Rental::where('user_id', $request->user_id)
            ->whereNull('returned_at')
            ->exists();

        if ($hasRental) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Người dùng này đang thuê một xe khác chưa trả.',
            ], 422);
        }

        $bike = Bicycle::where('bike_code', $request->bike_code)
            ->where('station_id', $request->station_id)
            ->where('location_status', 'at_station')
            ->first();

        if (!$bike) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Xe không thuộc trạm này hoặc đang được thuê.',
            ], 422);
        }

        $bike->update([
            'station_id'      => null,
            'location_status' => 'rented',
        ]);

        $rental = Rental::create([
            'user_id'         => $request->user_id,
            'bicycle_id'      => $bike->id,
            'rent_station_id' => $request->station_id,
            'rented_at'       => now(),
            'price_per_hour'  => $bike->rental_price_per_hour,
        ]);

        return response()->json([
            'status'  => 'success',
            'message' => 'Thuê xe thành công qua API!',
            'data'    => $rental->load(['bicycle', 'rentStation']),
        ], 201);
    }

    /**
     * 5. API Nội bộ: Trả xe qua JSON API
     * POST /api/v1/return
     */
    public function returnBike(Request $request)
    {
        $request->validate([
            'user_id'    => 'required|exists:users,id',
            'station_id' => 'required|exists:stations,id',
        ]);

        $rental = Rental::where('user_id', $request->user_id)
            ->whereNull('returned_at')
            ->first();

        if (!$rental) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Người dùng hiện không có xe đang thuê.',
            ], 422);
        }

        $bicycle = Bicycle::find($rental->bicycle_id);
        $minutes = round(abs(now()->diffInMinutes($rental->rented_at)), 2);
        $total = round($minutes * ($rental->price_per_hour / 60), 2);

        if ($bicycle) {
            $bicycle->update([
                'station_id'      => $request->station_id,
                'location_status' => 'at_station',
            ]);
        }

        $rental->update([
            'return_station_id' => $request->station_id,
            'returned_at'       => now(),
            'total_hours'       => $minutes,
            'total_amount'      => $total,
        ]);

        return response()->json([
            'status'  => 'success',
            'message' => 'Trả xe thành công qua API!',
            'data'    => $rental->fresh(['bicycle', 'rentStation', 'returnStation']),
        ]);
    }

    /**
     * 6. Kết nối Public API Quốc tế (CityBikes API - http://api.citybik.es/v2/networks)
     * GET /api/v1/citybikes?network=velib
     */
    public function externalCityBikes(Request $request)
    {
        $network = $request->get('network', 'bixi-toronto');

        try {
            $response = Http::timeout(8)->get("http://api.citybik.es/v2/networks/{$network}");

            if ($response->successful()) {
                $networkData = $response->json('network');
                $stations = array_slice($networkData['stations'] ?? [], 0, 20);

                return response()->json([
                    'status'   => 'success',
                    'source'   => 'CityBikes Public API (api.citybik.es)',
                    'network'  => [
                        'id'       => $networkData['id'] ?? $network,
                        'name'     => $networkData['name'] ?? '',
                        'location' => $networkData['location'] ?? [],
                    ],
                    'stations' => $stations,
                ]);
            }
        } catch (\Throwable $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Không thể kết nối tới CityBikes API: ' . $e->getMessage(),
            ], 502);
        }

        return response()->json([
            'status'  => 'error',
            'message' => 'Không tìm thấy mạng lưới CityBikes.',
        ], 404);
    }

    /**
     * 7. Trang giao diện xem & đồng bộ dữ liệu từ Public API CityBikes + Test API nội bộ
     */
    public function apiExplorer(Request $request)
    {
        $network = $request->get('network', 'bixi-toronto');
        $externalStations = [];
        $networkInfo = null;
        $apiError = null;

        try {
            $response = Http::timeout(8)->get("http://api.citybik.es/v2/networks/{$network}");
            if ($response->successful()) {
                $networkData = $response->json('network');
                $networkInfo = [
                    'id'      => $networkData['id'] ?? $network,
                    'name'    => $networkData['name'] ?? $network,
                    'city'    => $networkData['location']['city'] ?? '',
                    'country' => $networkData['location']['country'] ?? '',
                ];
                $externalStations = array_slice($networkData['stations'] ?? [], 0, 12);
            }
        } catch (\Throwable $e) {
            $apiError = $e->getMessage();
        }

        return view('api.explorer', compact('network', 'networkInfo', 'externalStations', 'apiError'));
    }

    /**
     * 8. Đồng bộ (Import) trạm xe từ CityBikes Public API vào Database của BikeGo
     */
    public function importFromCityBikes(Request $request)
    {
        $network = $request->get('network', 'bixi-toronto');
        $wards = WardHelper::get_ward();
        $wardNames = !empty($wards) ? array_column($wards, 'name') : ['Phường Sài Gòn', 'Phường Bến Thành'];

        try {
            $response = Http::timeout(8)->get("http://api.citybik.es/v2/networks/{$network}");
            if (!$response->successful()) {
                return back()->with('error', 'Không lấy được dữ liệu từ CityBikes API.');
            }

            $stationsData = array_slice($response->json('network.stations') ?? [], 0, 5);
            $importedCount = 0;

            foreach ($stationsData as $extStation) {
                $freeBikes = (int) ($extStation['free_bikes'] ?? 3);
                $emptySlots = (int) ($extStation['empty_slots'] ?? 10);
                $totalDocks = max(10, $freeBikes + $emptySlots);
                $randomWard = $wardNames[array_rand($wardNames)];

                $station = Station::firstOrCreate(
                    ['station_name' => 'API - ' . ($extStation['name'] ?? 'Station')],
                    [
                        'address'     => $extStation['extra']['address'] ?? ('Lat: ' . ($extStation['latitude'] ?? 0) . ', Lng: ' . ($extStation['longitude'] ?? 0)),
                        'ward'        => $randomWard,
                        'total_docks' => $totalDocks,
                        'status'      => 'active',
                    ]
                );

                // Tạo tối đa 3 xe đạp mẫu cho trạm vừa import
                $bikesToCreate = min(3, max(1, $freeBikes));
                for ($i = 0; $i < $bikesToCreate; $i++) {
                    Bicycle::create([
                        'bike_code'             => 'API-' . strtoupper(substr(md5(uniqid('', true)), 0, 5)),
                        'station_id'            => $station->id,
                        'location_status'       => 'at_station',
                        'condition_status'      => 'active',
                        'rental_price_per_hour' => 15000,
                    ]);
                }

                $importedCount++;
            }

            return back()->with('success', "Đã đồng bộ thành công {$importedCount} trạm xe và xe đạp từ CityBikes API vào hệ thống BikeGo!");
        } catch (\Throwable $e) {
            return back()->with('error', 'Lỗi khi đồng bộ từ API: ' . $e->getMessage());
        }
    }
}

