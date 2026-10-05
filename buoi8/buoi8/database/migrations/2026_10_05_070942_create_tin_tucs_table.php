<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTinTucsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::create('tin_tucs', function (Blueprint $table) {
        $table->id();
        $table->string('tieude', 200);
        $table->text('tomtat')->nullable();
        $table->longText('noidung');
        $table->date('ngaydang')->nullable();
        $table->string('hinhanh')->nullable();
        $table->string('hinhanh_path')->nullable();
        $table->foreignId('danhmuc_id')->nullable()->constrained('danh_mucs')->nullOnDelete();
        $table->softDeletes(); // Hỗ trợ xóa tạm
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tin_tucs');
    }
}
