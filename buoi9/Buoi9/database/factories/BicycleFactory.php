<?php

namespace Database\Factories;

use App\Models\Bicycle;
use App\Models\Station;
use Illuminate\Database\Eloquent\Factories\Factory;

class BicycleFactory extends Factory
{
    protected $model = Bicycle::class;

    public function definition()
    {
        $stationId = Station::inRandomOrder()->value('id');

        return [
            'bike_code'             => 'BG-' . $this->faker->unique()->numberBetween(1000, 9999),
            'station_id'            => $stationId,
            'location_status'       => 'at_station',
            'condition_status'      => $this->faker->randomElement(['active', 'active', 'active', 'active', 'inactive']),
            'rental_price_per_hour' => $this->faker->randomElement([10000, 15000, 20000, 25000]),
        ];
    }
}

