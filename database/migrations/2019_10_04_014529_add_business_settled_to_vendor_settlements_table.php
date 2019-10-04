<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBusinessSettledToVendorSettlementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('vendor_settlements','business_settled')){
            Schema::table('vendor_settlements', function (Blueprint $table) {
                $table->boolean('business_settled')->default(0)->after('id');
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
        if (Schema::hasColumn('vendor_settlements','business_settled')){
            Schema::table('vendor_settlements', function (Blueprint $table) {
                $table->dropColumn('business_settled');
            });
        }
    }
}
