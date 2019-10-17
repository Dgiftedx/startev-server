<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddVendorSettlementIdToUserVentureOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('user_venture_orders','vendor_settlement_id')){
            Schema::table('user_venture_orders', function (Blueprint $table) {
                $table->integer('vendor_settlement_id')->nullable()->after('id');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasColumn('user_venture_orders','vendor_settlement_id')){
            Schema::table('user_venture_orders', function (Blueprint $table) {
                $table->dropColumn('vendor_settlement_id');
            });
        }
    }
}
