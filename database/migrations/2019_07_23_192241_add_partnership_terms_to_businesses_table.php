<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPartnershipTermsToBusinessesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('businesses','partnership_terms')){
            Schema::table('businesses', function (Blueprint $table) {
                $table->longText('partnership_terms')->nullable()->after('website');
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
        if (Schema::hasColumn('businesses','partnership_terms')){
            Schema::table('businesses', function (Blueprint $table) {
                $table->dropColumn('partnership_terms');
            });
        }
    }
}
