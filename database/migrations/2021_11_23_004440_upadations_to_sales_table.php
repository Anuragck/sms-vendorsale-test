<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpadationsToSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->decimal('total_taxable_amount',10,2)->after('invoice_date')->default(0);
            $table->decimal('total_cgst',10,2)->after('total_taxable_amount')->default(0);
            $table->decimal('total_sgst',10,2)->after('total_cgst')->default(0);
            $table->decimal('total_cess',10,2)->after('total_sgst')->default(0);

            

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sales', function (Blueprint $table) {
            //
        });
    }
}
