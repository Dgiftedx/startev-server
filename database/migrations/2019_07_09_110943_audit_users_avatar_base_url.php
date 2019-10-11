<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AuditUsersAvatarBaseUrl extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $usersAvatar = \App\Models\User::orderBy('id','desc')->pluck('avatar','id')->toArray();

        foreach ($usersAvatar as $id => $avatar) {
           if (!is_null($avatar)) {
               if( strpos($avatar, "http://37.139.29.190:8086") !== false) {
                   $newUrl = str_replace('http://37.139.29.190:8086', 'https://server.startev.africa', $avatar);
                   \App\Models\User::find($id)->update(['avatar' => $newUrl]);
               }
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
