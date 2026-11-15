<?php

namespace Database\Factories;

use App\Models\Bicycle;
use App\Models\BicycleReview;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class BicycleReviewFactory extends Factory
{
    protected $model = BicycleReview::class;

    public function definition()
    {
        $comments = [
            'Xe đạp chạy rất êm, phanh ăn và yên xe thoải mái.',
            'Xe mới, sạch sẽ, đạp nhẹ nhàng không tốn sức.',
            'Chuông xe và đèn hoạt động tốt, giá thuê hợp lý.',
            'Lốp xe căng, di chuyển trong thành phố rất thích.',
            'Xe tốt, ngoại hình đẹp, sẽ tiếp tục thuê lần sau.',
            'Bàn đạp hơi nặng một chút nhưng tổng thể vẫn ổn.',
        ];

        return [
            'user_id'    => User::inRandomOrder()->value('id') ?? User::factory(),
            'bicycle_id' => Bicycle::inRandomOrder()->value('id') ?? Bicycle::factory(),
            'rating'     => $this->faker->numberBetween(3, 5),
            'comment'    => $this->faker->randomElement($comments),
        ];
    }
}

