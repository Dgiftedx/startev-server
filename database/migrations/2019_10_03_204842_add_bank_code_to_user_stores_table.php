<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBankCodeToUserStoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('user_stores','bank_code')){
            Schema::table('user_stores', function (Blueprint $table) {
                $table->string('bank_code')->nullable()->after('bank_name');
            });
        }

        if (!Schema::hasColumn('user_stores','payment_id')){
            Schema::table('user_stores', function (Blueprint $table) {
                $table->string('payment_id')->nullable()->after('bank_name');
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
        if (Schema::hasColumn('user_stores','bank_code')){
            Schema::table('user_stores', function (Blueprint $table) {
                $table->dropColumn('bank_code');
            });
        }

        if (Schema::hasColumn('user_stores','payment_id')){
            Schema::table('user_stores', function (Blueprint $table) {
                $table->dropColumn('payment_id');
            });
        }
    }
}
