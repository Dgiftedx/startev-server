<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserBusinessOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_business_orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('identifier');
            $table->integer('store_id')->nullable();
            $table->integer('business_id')->nullable();
            $table->integer('product_sku');
            $table->integer('buyer_id');
            $table->mediumInteger('quantity')->default(1);
            $table->double('amount');
            $table->enum('status',[
                'shipped',
                'processing',
                'pending',
                'delivered',
                'cancelled',
                'confirmed'
            ])->default('pending');
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
        Schema::dropIfExists('user_business_orders');
    }
}
