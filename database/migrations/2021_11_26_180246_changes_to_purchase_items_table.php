<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangesToPurchaseItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('purchase_items', function (Blueprint $table) {
            $table->decimal('rate_per_qty',10,2)->after('purchase_qty')->default(0);
            $table->decimal('taxable_amount',10,2)->after('rate_per_qty')->default(0);
            $table->decimal('gst_percentage')->after('taxable_amount')->default(0);
            $table->decimal('tax',10,2)->after('gst_percentage')->default(0);
            $table->decimal('amount',10,2)->after('tax')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('purchase_items', function (Blueprint $table) {
            //
        });
    }
}
