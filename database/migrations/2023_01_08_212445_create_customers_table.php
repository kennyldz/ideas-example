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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('name',250);
            $table->string('email',250)->comment('Customer Email Address');
            $table->string('password')->comment('Customer Password');
            $table->date('since')->nullable()->comment('Customer Sınce');
            $table->decimal('revenue',13)->nullable()->comment('Customer Revenue');
            $table->ipAddress('last_login_ip_address')->nullable()->comment('Last Login Ip Address');
            $table->enum('status',[
                "active",
                "passive"
            ])->default('active');
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
        Schema::dropIfExists('customers');
    }
};
