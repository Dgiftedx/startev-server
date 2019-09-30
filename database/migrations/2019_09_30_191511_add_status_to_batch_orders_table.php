<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStatusToBatchOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('batch_orders','status')){
            Schema::table('batch_orders', function (Blueprint $table) {
                $table->string('status')->default('pending')->after('batch_id');
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
        if (Schema::hasColumn('batch_orders','status')){
            Schema::table('batch_orders', function (Blueprint $table) {
                $table->dropColumn('status');
            });
        }
    }
}
