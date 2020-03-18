<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderlistsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orderlists', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('identifier_id');
            $table->string('cart_id');
            $table->string('buyer_email');
            $table->string('buyer_name');
            $table->string('buyer_address');
            $table->string('buyer_phone');
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
        Schema::dropIfExists('orderlists');
    }
}
