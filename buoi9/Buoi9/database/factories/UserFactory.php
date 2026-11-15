<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition()
    {
        $firstNames = ['Nguyễn', 'Trần', 'Lê', 'Phạm', 'Hoàng', 'Huỳnh', 'Phan', 'Vũ', 'Võ', 'Đặng', 'Bùi', 'Đỗ'];
        $middleNames = ['Văn', 'Thị', 'Hữu', 'Minh', 'Quốc', 'Gia', 'Thanh', 'Ngọc', 'Đức', 'Tuấn'];
        $lastNames = ['An', 'Bình', 'Cường', 'Dũng', 'Giang', 'Hải', 'Hùng', 'Khánh', 'Kiên', 'Linh', 'Long', 'Mai', 'Nam', 'Nga', 'Phúc', 'Quân', 'Quốc', 'Sơn', 'Thảo', 'Trang', 'Tú', 'Việt'];

        $fullName = $this->faker->randomElement($firstNames) . ' ' .
                    $this->faker->randomElement($middleNames) . ' ' .
                    $this->faker->randomElement($lastNames);

        $username = strtolower(Str::slug($fullName, '')) . $this->faker->unique()->numberBetween(100, 9999);

        return [
            'name'              => $fullName,
            'phone'             => '09' . $this->faker->unique()->numerify('########'),
            'email'             => $username . '@gmail.com',
            'email_verified_at' => now(),
            'password'          => Hash::make('User@123'),
            'role'              => User::ROLE_USER,
            'remember_token'    => Str::random(10),
        ];
    }

    public function admin()
    {
        return $this->state(function (array $attributes) {
            return [
                'role' => User::ROLE_ADMIN,
            ];
        });
    }
}
