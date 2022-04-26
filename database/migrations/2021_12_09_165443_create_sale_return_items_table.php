<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSaleReturnItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sale_return_items', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('sale_return_id')->unsigned();
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('category_id')->unsigned();
            $table->bigInteger('product_id')->unsigned();
            $table->decimal('return_qty',10,2)->nullable(); 
            $table->decimal('rate',10,2)->nullable(); 
            $table->decimal('total',10,2)->nullable(); 
            $table->decimal('discount',10,2)->nullable(); 
            $table->decimal('gross_amount',10,2)->nullable(); 
            $table->decimal('gst',10,2)->nullable(); 
            $table->decimal('taxable_amount',10,2)->nullable(); 
            $table->string('cgst',20)->nullable(); 
            $table->decimal('sgst',10,2)->nullable(); 
            $table->decimal('total_rate',10,2)->nullable(); 

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sale_return_items');
    }
}
