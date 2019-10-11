<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAudioSessionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('audio_sessions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('target_user_id');
            $table->string('uid')->nullable();
            $table->integer('host');
            $table->string('host_name');
            $table->string('channel_id');
            $table->string('status');
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
        Schema::dropIfExists('audio_sessions');
    }
}
