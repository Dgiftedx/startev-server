<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddProductIdToUserVentureProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('user_venture_products','product_id')){
            Schema::table('user_venture_products', function (Blueprint $table) {
                $table->integer('product_id')->nullable()->after('id');
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
        if (Schema::hasColumn('user_venture_products','product_id')){
            Schema::table('user_venture_products', function (Blueprint $table) {
                $table->dropColumn('product_id');
            });
        }
    }
}
