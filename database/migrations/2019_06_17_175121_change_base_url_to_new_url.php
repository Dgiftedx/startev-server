<?php

use App\Models\User;
use App\Models\Feed;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeBaseUrlToNewUrl extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $old_url = 'http://37.139.29.190:8086';
        $new_url = 'https://server.startev.africa';

        $feeds = Feed::whereNotNull('image')->get(['id','image']);

        foreach ($feeds as $feed) {
            if (strpos($feed->image, $old_url) !== false) {
                $update = [
                    'image' => str_replace($old_url, $new_url, $feed->image)
                ];

                Feed::find($feed->id)->update($update);
            }
        }


        $users = User::whereNotNull('avatar')->get(['id','avatar','bg_image']);

        foreach ($users as $user) {
            if (strpos($user->image, $old_url) !== false) {
                $update = [
                    'avatar' => str_replace($old_url, $new_url, $user->avatar),
                    'bg_image' => str_replace($old_url, $new_url, $user->bg_image)
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

    }
}
