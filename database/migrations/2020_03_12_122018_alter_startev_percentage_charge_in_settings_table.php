<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterStartevPercentageChargeInSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('settings', function (Blueprint $table) {
            $row = \App\Models\Setting::where('key', \App\Models\Setting::$STARTEV_PERCENTAGE_CHARGE)->first();
            $row->value = 7.5;
            $row->save();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('settings', function (Blueprint $table) {
            $row = \App\Models\Setting::where('key', \App\Models\Setting::$STARTEV_PERCENTAGE_CHARGE)->first();
            $row->value = 5;
            $row->save();
        });
    }
}
