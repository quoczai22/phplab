<?php

namespace Database\Factories;

use App\Models\Station;
use App\Models\StationReview;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class StationReviewFactory extends Factory
{
    protected $model = StationReview::class;

    public function definition()
    {
        $comments = [
            'Trạm xe rộng rãi, dễ tìm và rất sạch sẽ.',
            'Nhiều xe mới, lấy xe và trả xe rất nhanh chóng.',
            'Vị trí trạm thuận tiện ngay trung tâm, rất hài lòng!',
            'Chỗ để xe thoáng mát, còn nhiều chỗ trống.',
            'Hệ thống khóa xe tại trạm hoạt động tốt, an toàn.',
            'Trạm xe hơi đông vào giờ cao điểm nhưng chất lượng tốt.',
            'Rất tiện lợi cho việc đi dạo cuối tuần.',
        ];

        return [
            'user_id'    => User::inRandomOrder()->value('id') ?? User::factory(),
            'station_id' => Station::inRandomOrder()->value('id') ?? Station::factory(),
            'rating'     => $this->faker->numberBetween(3, 5),
            'comment'    => $this->faker->randomElement($comments),
        ];
    }
}

