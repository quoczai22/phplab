<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_code')->unique();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->decimal('subtotal', 12, 2);
            $table->decimal('discount', 12, 2)->default(0);
            $table->decimal('tax', 12, 2)->default(0);
            $table->decimal('total', 12, 2);
            $table->string('coupon_code')->nullable();
            $table->string('name');
            $table->string('phone');
            $table->string('address');
            $table->string('city');
            $table->string('payment_mode')->default('cod');
            $table->string('payment_status')->default('pending');
            $table->string('status')->default('ordered');
            $table->string('shipper_name')->nullable();
            $table->string('shipper_phone')->nullable();
            $table->string('tracking_note')->nullable();
            $table->timestamp('delivered_date')->nullable();
            $table->timestamp('canceled_date')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('orders');
    }
}

