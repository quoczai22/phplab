<?php

namespace Database\Factories;

use App\Helper\WardHelper;
use App\Models\Station;
use Illuminate\Database\Eloquent\Factories\Factory;

class StationFactory extends Factory
{
    protected $model = Station::class;

    public function definition()
    {
        $wards = WardHelper::get_ward();
        $wardNames = !empty($wards)
            ? array_column($wards, 'name')
            : ['Phường Sài Gòn', 'Phường Bến Thành', 'Phường Tân Định', 'Phường Bình Thạnh', 'Phường Gò Vấp'];

        $ward = $this->faker->randomElement($wardNames);
        $streets = [
            'Phạm Ngọc Thạch', 'Nguyễn Huệ', 'Lê Lợi', 'Pasteur', 'Nam Kỳ Khởi Nghĩa',
            'Hai Bà Trưng', 'Điện Biên Phủ', 'Võ Văn Tần', 'Cách Mạng Tháng 8', 'Nguyễn Thị Minh Khai',
            'Lê Duẩn', 'Đồng Khởi', 'Trần Hưng Đạo', 'Nguyễn Văn Cừ', 'Lý Tự Trọng'
        ];

        return [
            'station_name' => 'Trạm BikeGo ' . $ward . ' #' . $this->faker->unique()->numberBetween(1, 999),
            'address'      => 'Số ' . $this->faker->numberBetween(1, 350) . ' Đường ' . $this->faker->randomElement($streets),
            'ward'         => $ward,
            'total_docks'  => $this->faker->randomElement([15, 20, 25, 30, 40]),
            'status'       => $this->faker->randomElement(['active', 'active', 'active', 'active', 'inactive']),
        ];
    }
}

