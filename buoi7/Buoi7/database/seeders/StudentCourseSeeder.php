<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Course;
use App\Models\Student; 

class StudentCourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() :void
    {
        $course = Course:: factory()->count(3)->create();

        Student::factory()->count(10)->create()->each(function (Student $student) use ($course) {
            $student-> courses()->attach(
                $course->random(rand(1,3))->pluck('id')->toArray()
            );
        });
    }
}
