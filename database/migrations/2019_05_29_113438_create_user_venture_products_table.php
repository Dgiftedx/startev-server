<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserVentureProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_venture_products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('store_id');
            $table->string('sku');
            $table->string('slug');
            $table->json('images')->nullable();
            $table->string('product_name');
            $table->double('product_price');
            $table->text('highlight');
            $table->json('sizes')->nullable();
            $table->json('colors')->nullable();
            $table->longText('product_description')->nullable();
            $table->enum('stock_status',['inStock','outStock'])->default('inStock');
            $table->double('discount_price')->nullable();
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
        Schema::dropIfExists('user_venture_products');
    }
}
