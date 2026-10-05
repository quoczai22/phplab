<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin',
                'password' => bcrypt('admin123'),
                'role' => 'admin',
            ]
        );

        $c1 = \App\Models\DanhMuc::firstOrCreate(['ten' => 'Tin công nghệ'], ['slug' => 'tin-cong-nghe']);
        $c2 = \App\Models\DanhMuc::firstOrCreate(['ten' => 'Tin trong nước'], ['slug' => 'tin-trong-nuoc']);

        \App\Models\TinTuc::firstOrCreate(
            ['tieude' => 'Hệ thống quản trị tin tức đã sẵn sàng'],
            [
                'slug' => 'he-thong-quan-tri-tin-tuc-da-san-sang',
                'tomtat' => 'Chào mừng đến với khu vực quản trị website tin tức Laravel.',
                'noidung' => 'Hệ thống đã hoàn thiện các chức năng CRUD Danh mục, CRUD Tin tức, Upload ảnh, Phân quyền Admin và Soft delete.',
                'ngaydang' => now()->toDateString(),
                'trang_thai' => 'published',
                'danhmuc_id' => $c1->id,
            ]
        );
    }
}
