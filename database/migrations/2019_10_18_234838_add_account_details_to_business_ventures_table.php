<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAccountDetailsToBusinessVenturesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('business_ventures', function (Blueprint $table) {
            $table->string('bank_name')->nullable()->before('created_at');
            $table->string('bank_code')->nullable()->before('created_at');
            $table->string('account_name')->nullable()->before('created_at');
            $table->string('account_number')->nullable()->before('created_at');
            $table->string('payment_id')->nullable()->before('created_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('business_ventures', function (Blueprint $table) {
            $table->dropColumn('bank_name')->nullable()->before('created_at');
            $table->dropColumn('bank_code')->nullable()->before('created_at');
            $table->dropColumn('account_name')->nullable()->before('created_at');
            $table->dropColumn('account_number')->nullable()->before('created_at');
            $table->dropColumn('payment_id')->nullable()->before('created_at');
        });
    }
}
