<?php

namespace Database\Factories;

use App\Models\Bicycle;
use App\Models\Rental;
use App\Models\Station;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class RentalFactory extends Factory
{
    protected $model = Rental::class;

    public function definition()
    {
        $rentedAt = Carbon::instance($this->faker->dateTimeBetween(now()->startOfMonth(), now()->subHours(2)));
        $minutes = $this->faker->numberBetween(15, 180);
        $returnedAt = (clone $rentedAt)->addMinutes($minutes);
        $pricePerHour = $this->faker->randomElement([10000, 15000, 20000]);
        $totalAmount = round($minutes * ($pricePerHour / 60), 2);

        return [
            'user_id'           => User::where('role', User::ROLE_USER)->inRandomOrder()->value('id') ?? User::factory(),
            'bicycle_id'        => Bicycle::inRandomOrder()->value('id') ?? Bicycle::factory(),
            'rent_station_id'   => Station::inRandomOrder()->value('id') ?? Station::factory(),
            'return_station_id' => Station::inRandomOrder()->value('id') ?? Station::factory(),
            'rented_at'         => $rentedAt,
            'returned_at'       => $returnedAt,
            'price_per_hour'    => $pricePerHour,
            'total_hours'       => $minutes,
            'total_amount'      => $totalAmount,
        ];
    }
}

