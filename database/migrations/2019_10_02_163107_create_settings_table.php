<?php

use App\Models\Setting;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('key');
            $table->text('value');
            $table->timestamps();
        });

        (new Setting)->create(['key'=>'STARTEV_PERCENTAGE_CHARGE', 'value' => 5]);
        (new Setting)->create(['key'=>'STORE_PAY_DAYS', 'value' => 30]);
        (new Setting)->create(['key'=>'DELIVERY_PAYOUT_DAY', 'value' => 'friday']);
        (new Setting)->create(['key'=>'LOGO', 'value' => '']);
        (new Setting)->create(['key'=>'MAX_PARTNERSHIPS', 'value' => 5]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('settings');
    }
}
