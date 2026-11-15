<?php

namespace Database\Seeders;

use App\Helper\WardHelper;
use App\Models\Bicycle;
use App\Models\BicycleReview;
use App\Models\Rental;
use App\Models\Station;
use App\Models\StationReview;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Tự động kiểm tra toàn bộ các Phường/Xã trong wards.json:
     * Phường nào chưa có Trạm xe & Xe đạp thì tự động đổ dữ liệu (seed) vào ngay.
     */
    public static function seedAllMissingWards(): void
    {
        $wards = WardHelper::get_ward();
        $wardNames = !empty($wards)
            ? array_column($wards, 'name')
            : ['Phường Sài Gòn', 'Phường Bến Thành', 'Phường Tân Định', 'Phường Bình Thạnh', 'Phường Gò Vấp'];

        // Lấy danh sách các phường đã có ít nhất 1 trạm active
        $existingWards = Station::where('status', 'active')
            ->pluck('ward')
            ->unique()
            ->toArray();

        $missingWards = array_diff($wardNames, $existingWards);

        if (empty($missingWards)) {
            return;
        }

        $streets = [
            'Phạm Ngọc Thạch', 'Nguyễn Huệ', 'Lê Lợi', 'Pasteur', 'Nam Kỳ Khởi Nghĩa',
            'Hai Bà Trưng', 'Điện Biên Phủ', 'Võ Văn Tần', 'Cách Mạng Tháng 8', 'Nguyễn Thị Minh Khai',
            'Lê Duẩn', 'Đồng Khởi', 'Trần Hưng Đạo', 'Nguyễn Văn Cừ', 'Lý Tự Trọng'
        ];
        $docksList = [15, 20, 25, 30];
        $prices = [10000, 15000, 20000, 25000];
        $now = now();

        DB::transaction(function () use ($missingWards, $streets, $docksList, $prices, $now) {
            $bikeCounter = Bicycle::max('id') ?? 0;

            foreach ($missingWards as $wardName) {
                // Tạo 2 trạm xe cho mỗi phường chưa có dữ liệu
                for ($i = 1; $i <= 2; $i++) {
                    $street = $streets[array_rand($streets)];
                    $stationId = DB::table('stations')->insertGetId([
                        'station_name' => 'Trạm BikeGo ' . $wardName . ' #' . $i,
                        'address'      => 'Số ' . rand(1, 300) . ' Đường ' . $street . ', ' . $wardName,
                        'ward'         => $wardName,
                        'total_docks'  => $docksList[array_rand($docksList)],
                        'status'       => 'active',
                        'created_at'   => $now,
                        'updated_at'   => $now,
                    ]);

                    // Đổ luôn 4 xe đạp sẵn sàng (available) vào mỗi trạm vừa tạo
                    $bikesToInsert = [];
                    for ($b = 1; $b <= 4; $b++) {
                        $bikeCounter++;
                        $bikesToInsert[] = [
                            'bike_code'             => 'BG-' . str_pad((string) ($bikeCounter + 1000), 4, '0', STR_PAD_LEFT) . '-' . rand(10, 99),
                            'station_id'            => $stationId,
                            'location_status'       => 'at_station',
                            'condition_status'      => 'active',
                            'rental_price_per_hour' => $prices[array_rand($prices)],
                            'created_at'            => $now,
                            'updated_at'            => $now,
                        ];
                    }
                    DB::table('bicycles')->insert($bikesToInsert);
                }
            }
        });
    }

    /**
     * Đảm bảo 1 Phường cụ thể luôn có Trạm xe và Xe đạp.
     */
    public static function ensureWardHasData(?string $wardName): void
    {
        if (empty($wardName)) {
            return;
        }

        $existingActiveStations = Station::where('ward', $wardName)
            ->where('status', 'active')
            ->get();

        if ($existingActiveStations->isEmpty()) {
            self::seedAllMissingWards();
        } else {
            foreach ($existingActiveStations as $st) {
                self::ensureStationHasBikes($st->id);
            }
        }
    }

    /**
     * Đảm bảo 1 Trạm xe cụ thể luôn có Xe đạp sẵn sàng để thuê.
     */
    public static function ensureStationHasBikes($stationId): void
    {
        if (empty($stationId)) {
            return;
        }

        $availableCount = Bicycle::where('station_id', $stationId)
            ->where('location_status', 'at_station')
            ->where('condition_status', 'active')
            ->count();

        if ($availableCount === 0) {
            $prices = [10000, 15000, 20000, 25000];
            for ($i = 1; $i <= 4; $i++) {
                Bicycle::create([
                    'bike_code'             => 'BG-' . rand(1000, 9999) . '-' . rand(10, 99),
                    'station_id'            => $stationId,
                    'location_status'       => 'at_station',
                    'condition_status'      => 'active',
                    'rental_price_per_hour' => $prices[array_rand($prices)],
                ]);
            }
        }
    }

    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // 1. Tạo hoặc cập nhật tài khoản Admin mặc định
        User::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name'     => 'Quản Trị Viên',
                'phone'    => '0901234567',
                'password' => Hash::make('Admin@123'),
                'role'     => User::ROLE_ADMIN,
            ]
        );

        // 2. Tạo hoặc cập nhật tài khoản User mẫu
        User::updateOrCreate(
            ['email' => 'user@gmail.com'],
            [
                'name'     => 'Trịnh Hữu Kiến Quốc',
                'phone'    => '0912345678',
                'password' => Hash::make('User@123'),
                'role'     => User::ROLE_USER,
            ]
        );

        // 3. Seed User bằng Faker (nếu chưa đủ)
        if (User::count() < 15) {
            User::factory(12)->create();
        }
        $users = User::all();

        // 4. Seed Trạm xe (Station) & Xe đạp (Bicycle) cho TẤT CẢ các phường chưa có dữ liệu
        self::seedAllMissingWards();

        // Đảm bảo mọi trạm hiện có đều có xe đạp sẵn sàng (chỉ truy vấn các trạm đang hết xe)
        $emptyStationIds = Station::whereDoesntHave('bicycles', function ($q) {
            $q->where('location_status', 'at_station')
              ->where('condition_status', 'active');
        })->pluck('id');

        foreach ($emptyStationIds as $stationId) {
            self::ensureStationHasBikes($stationId);
        }

        $stations = Station::all();
        $bicycles = Bicycle::all();

        // 5. Seed Lịch sử thuê xe (Rental) trong tháng này để hiển thị Top Bikers
        if (Rental::count() < 30 && $stations->isNotEmpty() && $bicycles->isNotEmpty()) {
            Rental::factory(40)->make()->each(function ($rental) use ($users, $bicycles, $stations) {
                $rental->user_id = $users->random()->id;
                $rental->bicycle_id = $bicycles->random()->id;
                $rental->rent_station_id = $stations->random()->id;
                $rental->return_station_id = $stations->random()->id;
                $rental->save();
            });
        }

        // 6. Seed Đánh giá Trạm xe (StationReview)
        if (StationReview::count() < 30 && $stations->isNotEmpty()) {
            StationReview::factory(35)->make()->each(function ($review) use ($users, $stations) {
                $review->user_id = $users->random()->id;
                $review->station_id = $stations->random()->id;
                $review->save();
            });
        }

        // 7. Seed Đánh giá Xe đạp (BicycleReview)
        if (BicycleReview::count() < 30 && $bicycles->isNotEmpty()) {
            BicycleReview::factory(35)->make()->each(function ($review) use ($users, $bicycles) {
                $review->user_id = $users->random()->id;
                $review->bicycle_id = $bicycles->random()->id;
                $review->save();
            });
        }
    }
}
