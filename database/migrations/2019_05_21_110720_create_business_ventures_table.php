<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBusinessVenturesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('business_ventures', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('identifier');
            $table->integer('business_id');
            $table->string('venture_name');
            $table->text('venture_address')->nullable();
            $table->longText('venture_description')->nullable();
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
        Schema::dropIfExists('business_ventures');
    }
}
