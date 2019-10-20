<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBusinessSettlementBatchIdToUserBusinessOrders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_business_orders', function (Blueprint $table) {
            $table->integer('business_settlement_batch_id')->nullable()->after('vendor_settlement_id');
        });
        Schema::table('user_venture_orders', function (Blueprint $table) {
            $table->integer('store_settlement_batch_id')->nullable()->after('vendor_settlement_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_business_orders', function (Blueprint $table) {
            $table->dropColumn('business_settlement_batch_id');
            $table->dropColumn('store_settlement_batch_id');
        });
    }
}
