<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStoreIdToVendorSettlementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('vendor_settlements','store_id')){
            Schema::table('vendor_settlements', function (Blueprint $table) {
                $table->integer('store_id')->nullable()->after('id');
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
        if (Schema::hasColumn('vendor_settlements','store_id')){
            Schema::table('vendor_settlements', function (Blueprint $table) {
                $table->dropColumn('store_id');
            });
        }
    }
}
