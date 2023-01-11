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
        Schema::create('discount_settings', function (Blueprint $table) {
            $table->id();
            $table->string('discount_code',200)->comment('Discount Code');
            $table->string('discount_reason',210)->comment('Discount Reason');
            $table->string('discount_description',300)->comment('Discount Reason Description');
            $table->json('discount_setting')->comment('Discount Setting');
            $table->enum('status',['active','passive'])->default('active');
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
        Schema::dropIfExists('discount_settings');
    }
};
