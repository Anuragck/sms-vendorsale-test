<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangesToPurchaseLogs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('purchase_logs', function (Blueprint $table) {
            $table->integer('vendor_id')->after('user_id')->nullable();
            $table->decimal('total_taxable_amount',10,2)->after('total_qty')->default(0);
            $table->decimal('total_tax')->after('total_taxable_amount')->default(0);
            $table->decimal('total_amount')->after('total_tax')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('purchase_logs', function (Blueprint $table) {
            //
        });
    }
}
