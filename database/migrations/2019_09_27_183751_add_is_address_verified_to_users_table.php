<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIsAddressVerifiedToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('users','is_address_verified')){
            Schema::table('users', function (Blueprint $table) {
                $table->boolean('is_address_verified')->default(0)->after('id');
            });
        }

        if (!Schema::hasColumn('users','lat')){
            Schema::table('users', function (Blueprint $table) {
                $table->string('lat')->nullable()->after('id');
            });
        }

        if (!Schema::hasColumn('users','long')){
            Schema::table('users', function (Blueprint $table) {
                $table->string('long')->nullable()->after('id');
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
        if (Schema::hasColumn('users','is_address_verified')){
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('is_address_verified');
            });
        }

        if (Schema::hasColumn('users','lat')){
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('lat');
            });
        }

        if (Schema::hasColumn('users','long')){
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('long');
            });
        }
    }
}
