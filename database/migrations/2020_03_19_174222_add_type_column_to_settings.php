<?php

use App\Models\Setting;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTypeColumnToSettings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->string('type')->default('text');
        });
        (new Setting)->create(['key'=>'STARTEV_EMAIL', 'value' => 'info@startev.africa', 'type'=>'email']);
        (new Setting)->create(['key'=>'STARTEV_EMAIL_NAME', 'value' => 'Mfon']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('settings', function (Blueprint $table) {
            //
        });
    }
}
