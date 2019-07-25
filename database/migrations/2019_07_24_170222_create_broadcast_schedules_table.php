<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBroadcastSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('broadcast_schedules', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('identifier');
            $table->integer('user_id'); //The mentor
            $table->string('type'); //video, audio
            $table->json('participants'); //participants
            $table->dateTime('date')->nullable();
            $table->longText('invitation_note')->nullable();
            $table->string('status')->nullable(); //concluded, in progress, cancelled, pending
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
        Schema::dropIfExists('broadcast_schedules');
    }
}
