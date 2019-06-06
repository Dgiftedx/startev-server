<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('sender_id');
            $table->integer('receiver_id');
            $table->integer('conversation_id')->nullable();
            $table->integer('messaging_group_id')->nullable();
            $table->text('message');
            $table->string('file')->nullable();
            $table->enum('type', ['text','file','image','video'])->default('text');
            $table->dateTime('last_read')->nullable();
            $table->enum('status',['read','unread'])->default('unread');
            $table->softDeletes('deleted_at')->nullable();
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
        Schema::dropIfExists('messages');
    }
}
