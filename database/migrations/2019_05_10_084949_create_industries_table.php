<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIndustriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('industries', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('slug');
            $table->string('alias')->nullable();
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('user_industry', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('industry_id');
            $table->integer('user_id');
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
        Schema::dropIfExists('industries');
        Schema::dropIfExists('user_industry');
    }
}
