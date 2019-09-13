<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddVentureIdToUserBusinessOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        if (!Schema::hasColumn('user_business_orders','venture_id')){
            Schema::table('user_business_orders', function (Blueprint $table) {
                $table->integer('venture_id')->default(0)->after('business_id');
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
        if (Schema::hasColumn('user_business_orders','venture_id')){
            Schema::table('user_business_orders', function (Blueprint $table) {
                $table->dropColumn('venture_id');
            });
        }
    }
}
