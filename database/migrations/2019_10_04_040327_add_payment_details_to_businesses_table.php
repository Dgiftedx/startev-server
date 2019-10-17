<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPaymentDetailsToBusinessesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('businesses','bank_code')){
            Schema::table('businesses', function (Blueprint $table) {
                $table->string('bank_code')->nullable()->after('id');
            });
        }

        if (!Schema::hasColumn('businesses','bank_name')){
            Schema::table('businesses', function (Blueprint $table) {
                $table->string('bank_name')->nullable()->after('id');
            });
        }

        if (!Schema::hasColumn('businesses','account_name')){
            Schema::table('businesses', function (Blueprint $table) {
                $table->string('account_name')->nullable()->after('id');
            });
        }

        if (!Schema::hasColumn('businesses','account_number')){
            Schema::table('businesses', function (Blueprint $table) {
                $table->string('account_number')->nullable()->after('id');
            });
        }

        if (!Schema::hasColumn('businesses','payment_id')){
            Schema::table('businesses', function (Blueprint $table) {
                $table->string('payment_id')->nullable()->after('id');
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
        if (Schema::hasColumn('businesses','bank_code')){
            Schema::table('businesses', function (Blueprint $table) {
                $table->dropColumn('bank_code');
            });
        }

        if (Schema::hasColumn('businesses','bank_name')){
            Schema::table('businesses', function (Blueprint $table) {
                $table->dropColumn('bank_name');
            });
        }

        if (Schema::hasColumn('businesses','account_name')){
            Schema::table('businesses', function (Blueprint $table) {
                $table->dropColumn('account_name');
            });
        }

        if (Schema::hasColumn('businesses','account_number')){
            Schema::table('businesses', function (Blueprint $table) {
                $table->dropColumn('account_number');
            });
        }

        if (Schema::hasColumn('businesses','payment_id')){
            Schema::table('businesses', function (Blueprint $table) {
                $table->dropColumn('payment_id');
            });
        }
    }
}
