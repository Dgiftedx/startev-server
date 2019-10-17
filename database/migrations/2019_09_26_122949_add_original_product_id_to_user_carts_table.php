<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOriginalProductIdToUserCartsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('user_carts','original_product_id')){
            Schema::table('user_carts', function (Blueprint $table) {
                $table->integer('original_product_id')->nullable()->after('id');
            });
        }

        if (!Schema::hasColumn('user_venture_orders','original_product_id')){
            Schema::table('user_venture_orders', function (Blueprint $table) {
                $table->integer('original_product_id')->nullable()->after('id');
            });
        }

        if (!Schema::hasColumn('user_business_orders','original_product_id')){
            Schema::table('user_business_orders', function (Blueprint $table) {
                $table->integer('original_product_id')->nullable()->after('id');
            });
        }

        if (!Schema::hasColumn('user_venture_orders','venture_id')){
            Schema::table('user_venture_orders', function (Blueprint $table) {
                $table->integer('venture_id')->nullable()->after('id');
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
        if (Schema::hasColumn('user_carts','original_product_id')){
            Schema::table('user_carts', function (Blueprint $table) {
                $table->dropColumn('original_product_id');
            });
        }

        if (Schema::hasColumn('user_venture_orders','original_product_id')){
            Schema::table('user_venture_orders', function (Blueprint $table) {
                $table->dropColumn('original_product_id');
            });
        }

        if (Schema::hasColumn('user_business_orders','original_product_id')){
            Schema::table('user_business_orders', function (Blueprint $table) {
                $table->dropColumn('original_product_id');
            });
        }

        if (Schema::hasColumn('user_venture_orders','venture_id')){
            Schema::table('user_venture_orders', function (Blueprint $table) {
                $table->dropColumn('venture_id');
            });
        }
    }
}
