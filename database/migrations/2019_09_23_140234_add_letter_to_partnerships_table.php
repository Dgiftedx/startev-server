<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLetterToPartnershipsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('partnerships','letter')){
            Schema::table('partnerships', function (Blueprint $table) {
                $table->longText('letter')->nullable()->after('id');
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
        if (Schema::hasColumn('partnerships','letter')){
            Schema::table('partnerships', function (Blueprint $table) {
                $table->dropColumn('letter');
            });
        }
    }
}
