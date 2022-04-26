<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchaseReturnItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_return_items', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('purchase_return_id')->unsigned();
            $table->bigInteger('product_id')->unsigned();
            $table->decimal('return_qty',10,2)->nullable(); 
            $table->decimal('rate_per_qty',10,2)->nullable(); 
            $table->decimal('taxable_amount',10,2)->nullable(); 
            $table->decimal('gst_percentage',10,2)->nullable(); 
            $table->decimal('gross_amount',10,2)->nullable(); 
            $table->decimal('tax',10,2)->nullable(); 
            $table->decimal('amount',10,2)->nullable(); 
            

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
        Schema::dropIfExists('purchase_return_items');
    }
}
