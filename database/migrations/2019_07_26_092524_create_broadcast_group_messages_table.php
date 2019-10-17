<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBroadcastGroupMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('broadcast_group_messages', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('schedule_id');
            $table->text('message');
            $table->string('user_name');
            $table->string('user_avatar')->nullable();
            $table->boolean('is_mentor')->default(0);
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
        Schema::dropIfExists('broadcast_group_messages');
    }
}
