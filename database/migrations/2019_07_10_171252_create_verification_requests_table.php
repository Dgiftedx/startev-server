<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVerificationRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('verification_requests', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id');
            $table->string('document_type');
            $table->string('document');
            $table->enum('status', ['accepted', 'rejected', 'pending'])->default('pending');
            $table->timestamps();
        });

        Schema::table('mentors', function (Blueprint $table) {
            $table->boolean('verified')->default(0)->after('user_id');
            $table->enum('verification_status', ['none','rejected','accepted', 'pending'])->default('none')->after('user_id');
        });

        Schema::table('businesses', function (Blueprint $table) {
            $table->boolean('verified')->default(0)->after('user_id');
            $table->enum('verification_status', ['none','rejected','accepted', 'pending'])->default('none')->after('user_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('verification_requests');
    }
}
