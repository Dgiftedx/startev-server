<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserVentureOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_venture_orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('identifier');
            $table->integer('store_id');
            $table->integer('product_id');
            $table->string('product_sku');
            $table->integer('buyer_id');
            $table->mediumInteger('quantity')->default(1);
            $table->double('amount');
            $table->text('delivery_address')->nullable();
            $table->string('transaction_ref')->nullable();
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
        Schema::dropIfExists('user_venture_orders');
    }
}
