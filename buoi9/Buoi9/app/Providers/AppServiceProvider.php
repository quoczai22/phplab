<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
        Paginator::useBootstrap();

        try {
            if (Schema::hasTable('rentals') && Schema::hasColumn('rentals', 'returned_ad') && !Schema::hasColumn('rentals', 'rented_at')) {
                DB::statement('ALTER TABLE rentals CHANGE returned_ad rented_at DATETIME NOT NULL');
            }

            foreach (['station_reviews', 'bicycle_reviews'] as $reviewTable) {
                if (Schema::hasTable($reviewTable)) {
                    // Sửa lỗi tên cột rating (nếu gõ nhầm thành ratting hoặc thiếu)
                    if (Schema::hasColumn($reviewTable, 'ratting') && !Schema::hasColumn($reviewTable, 'rating')) {
                        DB::statement("ALTER TABLE {$reviewTable} CHANGE ratting rating INT NOT NULL DEFAULT 5");
                    } elseif (!Schema::hasColumn($reviewTable, 'rating')) {
                        Schema::table($reviewTable, function ($table) {
                            $table->integer('rating')->default(5);
                        });
                    }

                    // Sửa lỗi tên cột comment (nếu gõ nhầm thành coment/comments hoặc thiếu)
                    if (Schema::hasColumn($reviewTable, 'coment') && !Schema::hasColumn($reviewTable, 'comment')) {
                        DB::statement("ALTER TABLE {$reviewTable} CHANGE coment comment TEXT NULL");
                    } elseif (Schema::hasColumn($reviewTable, 'comments') && !Schema::hasColumn($reviewTable, 'comment')) {
                        DB::statement("ALTER TABLE {$reviewTable} CHANGE comments comment TEXT NULL");
                    } elseif (!Schema::hasColumn($reviewTable, 'comment')) {
                        Schema::table($reviewTable, function ($table) {
                            $table->text('comment')->nullable();
                        });
                    }
                }
            }

            // Tự động đổ dữ liệu cho các Phường hoặc bảng chưa có Trạm xe / Xe đạp
            if (Schema::hasTable('stations') && Schema::hasTable('bicycles')) {
                $totalWards = count(\App\Helper\WardHelper::get_ward());
                $seededWards = \App\Models\Station::where('status', 'active')->distinct('ward')->count('ward');

                if ($seededWards < $totalWards || \App\Models\Bicycle::count() === 0) {
                    app(\Database\Seeders\DatabaseSeeder::class)->run();
                }
            }
        } catch (\Throwable $e) {
            // Bỏ qua nếu chưa kết nối DB
        }
    }
}
