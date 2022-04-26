<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpadationsToSaleItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sale_items', function (Blueprint $table) {
            $table->decimal('total',10,2)->after('rate')->default(0);
            $table->decimal('discount',10,2)->after('total')->default(0);
            $table->decimal('gross_amount',10,2)->after('discount')->default(0);
            $table->decimal('gst',10,2)->after('gross_amount')->default(0);
            $table->decimal('taxable_amount',10,2)->after('gst')->default(0);
            $table->decimal('cgst',10,2)->after('taxable_amount')->default(0);
            $table->decimal('sgst',10,2)->after('cgst')->default(0);
          
           
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sale_items', function (Blueprint $table) {
            //
        });
    }
}
