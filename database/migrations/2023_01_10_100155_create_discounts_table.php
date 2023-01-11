<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('discounts', function (Blueprint $table) {
            $table->bigInteger('id')->autoIncrement();
            $table->foreignId('orderId')->references('id')->on('orders');
            $table->foreignId('customerId')->references('id')->on('customers');
            $table->json('discounts')->comment('discounts');
            $table->decimal('total_discount',13)->comment('Total discount');
            $table->decimal('discounted_total',13)->comment('Discount before total');
            $table->decimal('total_without_discount',13)->comment('Total wtihout discount');
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
        Schema::dropIfExists('discounts');
    }
};
