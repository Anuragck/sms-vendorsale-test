<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuotationItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quotation_items', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('quotation_id')->unsigned();
            $table->bigInteger('category_id')->unsigned();
            $table->bigInteger('product_id')->unsigned();
            $table->decimal('quantity',10,2); 
            $table->decimal('rate',10,2);
            $table->decimal('total',10,2)->default(0);
            $table->decimal('discount',10,2)->default(0);
            $table->decimal('gross_amount',10,2)->default(0);
            $table->decimal('gst',10,2)->default(0);
            $table->decimal('taxable_amount',10,2)->default(0);
            $table->decimal('cgst',10,2)->default(0);
            $table->decimal('sgst',10,2)->default(0);
            $table->decimal('total_rate',10,2);
            $table->timestamps();
       
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('quotation_items');
    }
}
