<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\User;
class FinalMigrationOfUrl extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
//        $old_url = 'https://server.startev.africa';
        $old_url = 'https//server.startev.africa';
//        $old_url = 'http://startev.server';
//        $old_url = 'http//37.139.29.190:8086';

        $users = User::whereNotNull('avatar')->get(['id','avatar','bg_image']);

        foreach ($users as $user) {
            if (strpos($user->avatar, $old_url) !== false) {
                $update = [
                    'avatar' => str_replace($old_url, "", $user->avatar),
                ];

                User::find($user->id)->update($update);
            }

            if (strpos($user->bg_image, $old_url) !== false) {
                $update = [
                    'bg_image' => str_replace($old_url, "", $user->bg_image)
                ];

                User::find($user->id)->update($update);
            }
        }



    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
