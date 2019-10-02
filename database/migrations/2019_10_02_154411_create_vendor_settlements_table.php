<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVendorSettlementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendor_settlements', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('order_id');
            $table->string('batch_id');
            $table->double('paystack_charge');
            $table->double('paystack_percentage');
            $table->double('startev_charge');
            $table->double('commission_payout');
            $table->double('commission_percentage');
            $table->double('delivery')->nullable();
            $table->double('escrowed');
            $table->double('business_payout');
            $table->double('total');
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
        Schema::dropIfExists('vendor_settlements');
    }
}
