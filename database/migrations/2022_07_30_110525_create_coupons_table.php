<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCouponsTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->longText('code')->nullable(); // Coupon code
            $table->longText('date_from')->nullable(); // Coupon date from
            $table->longText('date_to')->nullable(); // Coupon date to
            $table->longText('type')->nullable(); // percentage
            $table->longText('discount_amount')->nullable(); // Discount amount or percentage
            $table->longText('minimum_purchase')->nullable(); // Minimum purchase amount
            $table->longText('maximum_usage')->nullable(); // Maximum usage
            $table->longText('usage_count')->nullable(); // Maximum usage
            $table->boolean('status')->nullable(); // Status
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('coupons');
    }
}
