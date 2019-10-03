<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStatusToVendorSettlementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('vendor_settlements','status')){
            Schema::table('vendor_settlements', function (Blueprint $table) {
                $table->string('status')->default('unpaid')->after('total');
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
        if (Schema::hasColumn('vendor_settlements','status')){
            Schema::table('vendor_settlements', function (Blueprint $table) {
                $table->dropColumn('status');
            });
        }
    }
}
