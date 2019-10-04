<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStoreSettlementBatchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('store_settlement_batches', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('reference_id')->nullable();
            $table->integer('store_id');
            $table->json('settlement_reference')->nullable(); //saving vendor_settlement_id & commission_payout
            $table->double('total')->nullable();
            $table->bigInteger('counter');
            $table->boolean('active')->default(0);
            $table->string('status')->default('pending');
            $table->timestamps();
        });

        if (!Schema::hasColumn('vendor_settlements','store_reference_id')){
            Schema::table('vendor_settlements', function (Blueprint $table) {
                $table->string('store_reference_id')->nullable()->after('id');
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
        Schema::dropIfExists('store_settlement_batches');

        if (Schema::hasColumn('vendor_settlements','store_reference_id')){
            Schema::table('vendor_settlements', function (Blueprint $table) {
                $table->dropColumn('store_reference_id');
            });
        }
    }
}
