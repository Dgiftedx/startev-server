<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOrderDetailsToUserVentureOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('user_venture_orders','batch_id')){
            Schema::table('user_venture_orders', function (Blueprint $table) {
                $table->string('batch_id')->nullable()->after('id');
            });
        }

        if (!Schema::hasColumn('user_venture_orders','email')){
            Schema::table('user_venture_orders', function (Blueprint $table) {
                $table->string('email')->nullable()->after('id');
            });
        }

        if (!Schema::hasColumn('user_venture_orders','name')){
            Schema::table('user_venture_orders', function (Blueprint $table) {
                $table->string('name')->nullable()->after('id');
            });
        }

        if (!Schema::hasColumn('user_venture_orders','phone')){
            Schema::table('user_venture_orders', function (Blueprint $table) {
                $table->string('phone')->nullable()->after('id');
            });
        }

        if (!Schema::hasColumn('user_venture_orders','location')){
            Schema::table('user_venture_orders', function (Blueprint $table) {
                $table->text('location')->nullable()->after('id');
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
        if (Schema::hasColumn('user_venture_orders','email')){
            Schema::table('user_venture_orders', function (Blueprint $table) {
                $table->dropColumn('email');
            });
        }

        if (Schema::hasColumn('user_venture_orders','name')){
            Schema::table('user_venture_orders', function (Blueprint $table) {
                $table->dropColumn('name');
            });
        }

        if (Schema::hasColumn('user_venture_orders','phone')){
            Schema::table('user_venture_orders', function (Blueprint $table) {
                $table->dropColumn('phone');
            });
        }

        if (Schema::hasColumn('user_venture_orders','location')){
            Schema::table('user_venture_orders', function (Blueprint $table) {
                $table->dropColumn('location');
            });
        }
    }
}
