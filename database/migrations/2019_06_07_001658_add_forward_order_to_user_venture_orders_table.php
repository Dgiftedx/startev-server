<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForwardOrderToUserVentureOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_venture_orders', function (Blueprint $table) {
            $table->boolean('forwarded')->default(0)->after('transaction_ref');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_venture_orders', function (Blueprint $table) {
            //
        });
    }
}
