<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchaseReturnsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_returns', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('purchase_id');
            $table->bigInteger('bill_type')->unsigned();
            $table->bigInteger('vendor_id')->nullable(); 
            $table->boolean('gst_bill')->default(0);
            $table->date('return_date'); 
            $table->decimal('total_products',10,2)->nullable(); 
            $table->decimal('total_qty',10,2)->nullable(); 
            $table->decimal('total_taxable_amount',10,2)->nullable(); 
            $table->decimal('total_tax',10,2)->nullable(); 
            $table->decimal('total_amount',10,2)->nullable(); 
            $table->string('pay_type',20)->nullable();  

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
        Schema::dropIfExists('purchase_returns');
    }
}
