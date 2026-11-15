<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRentalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('rentals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('bicycle_id')->constrained('bicycles')->onDelete('cascade');
            $table->foreignId('rent_station_id')->constrained('stations')->onDelete('cascade');
            $table->foreignId('return_station_id')->nullable()->constrained('stations')->nullOnDelete();
            $table->dateTime('rented_at');
            $table->dateTime('returned_at')->nullable();
            $table->decimal('price_per_hour',10,2);
            $table->integer('total_hours')->nullable();
            $table->decimal('total_amount',10,2)->nullable();
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
        Schema::dropIfExists('rentals');
    }
}
