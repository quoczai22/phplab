<?php

namespace Database\Seeders;

use App\Models\Profiles;
use Illuminate\Database\Seeder;
use App\Models\User;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run(): void
    {
        $this->call(StudentCourseSeeder::class);

        \App\Models\Category::factory()
        ->count(10)
        ->hasProducts(10)
        ->create();
        
        User::factory()->hasProfiles()->create([
        'name'=>'Test User',
        'email'=> 'test@example.com',
        ]);

    }
}
