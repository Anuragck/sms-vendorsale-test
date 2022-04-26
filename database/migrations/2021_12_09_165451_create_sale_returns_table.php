<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSaleReturnsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sale_returns', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('invoice_no');
            $table->bigInteger('bill_type')->unsigned();
            $table->bigInteger('customer_id')->unsigned();
            $table->boolean('gst_bill')->default(0);
            $table->date('return_date'); 
            $table->decimal('total_taxable_amount',10,2)->nullable(); 
            $table->decimal('total_cgst',10,2)->nullable(); 
            $table->decimal('total_sgst',10,2)->nullable(); 
            $table->decimal('total_cess',10,2)->nullable(); 
            $table->decimal('total_amount',10,2)->nullable(); 
            $table->decimal('total_discount',10,2)->nullable(); 
            $table->decimal('pay_amount',10,2)->nullable(); 
            $table->string('pay_type',20)->nullable(); 
            $table->decimal('reward_points',10,2)->nullable(); 

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
        Schema::dropIfExists('sale_returns');
    }
}
