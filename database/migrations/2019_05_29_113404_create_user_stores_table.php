<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserStoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_stores', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('slug');
            $table->string('identifier');
            $table->integer('user_id');
            $table->string('store_name')->nullable();
            $table->string('store_logo')->nullable();
            $table->string('store_url')->nullable();
            $table->boolean('bank_account_lock')->default(1);
            $table->string('account_name')->nullable();
            $table->string('account_number')->nullable();
            $table->string('bank_name')->nullable();
            $table->bigInteger('otp')->nullable();
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
        Schema::dropIfExists('user_stores');
    }
}
