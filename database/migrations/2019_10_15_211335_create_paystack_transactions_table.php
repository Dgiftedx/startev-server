<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaystackTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('paystack_transactions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('batch_id');
            $table->string('phone')->nullable();
            $table->string('reference');
            $table->boolean('status')->default(0);
            $table->string('message')->nullable();
            $table->json('data')->nullable();
            $table->timestamps();
        });

        //Remember to Run Verification Check on all Previous References
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('paystack_transactions');
    }
}
