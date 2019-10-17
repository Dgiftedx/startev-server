<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStatusToVocalReferrals extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('vocal_referrals','status')){
            Schema::table('vocal_referrals', function (Blueprint $table) {
                $table->string('status')->default('pending')->after('id');
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
        if (Schema::hasColumn('vocal_referrals','status')){
            Schema::table('vocal_referrals', function (Blueprint $table) {
                $table->dropColumn('vocal_referrals');
            });
        }
    }
}
