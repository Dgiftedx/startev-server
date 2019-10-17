<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserBusinessSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_business_settings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('business_id');
            $table->boolean('enable_returns')->default(0);
            $table->json('working_days')->nullable();
            $table->string('customer_care_line')->nullable();
            $table->bigInteger('max_return_days')->nullable();
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
        Schema::dropIfExists('user_business_settings');
    }
}
