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
        Schema::create('baskets', function (Blueprint $table) {
            $table->bigInteger('id')->autoIncrement();
            $table->foreignId('customerId')->references('id')->on('customers');
            $table->foreignId('productId')->references('id')->on('products');
            $table->integer('category')->comment('category');
            $table->integer('quantity')->comment('Quantity');
            $table->decimal('unit_price',13)->comment('Product Unit Price');
            $table->decimal('total_price',13)->comment('Total Price');
            $table->ipAddress('ip_address_of_transaction')->nullable()->comment('Ip Address Of transaction');
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
        Schema::dropIfExists('baskets');
    }
};
