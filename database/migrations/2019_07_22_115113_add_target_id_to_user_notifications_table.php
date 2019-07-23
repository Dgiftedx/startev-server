<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use App\Models\UserNotification;
use Illuminate\Database\Migrations\Migration;

class AddTargetIdToUserNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //wipe all users notifications records
        UserNotification::query()->truncate();
        Schema::table('user_notifications', function (Blueprint $table) {
            $table->integer('target_id')->nullable()->after('user_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_notifications', function (Blueprint $table) {
            //
        });
    }
}
