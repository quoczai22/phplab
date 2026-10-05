<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTrangThaiAndSlugToTinTucsAndRoleToUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tin_tucs', function (Blueprint $table) {
            if (!Schema::hasColumn('tin_tucs', 'slug')) {
                $table->string('slug', 255)->nullable()->unique()->after('tieude');
            }
            if (!Schema::hasColumn('tin_tucs', 'trang_thai')) {
                $table->enum('trang_thai', ['draft', 'published'])->default('draft')->after('ngaydang');
            }
        });

        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'role')) {
                $table->enum('role', ['admin', 'editor', 'user'])->default('user')->after('password');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tin_tucs', function (Blueprint $table) {
            if (Schema::hasColumn('tin_tucs', 'trang_thai')) {
                $table->dropColumn('trang_thai');
            }
            if (Schema::hasColumn('tin_tucs', 'slug')) {
                $table->dropColumn('slug');
            }
        });

        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'role')) {
                $table->dropColumn('role');
            }
        });
    }
}
