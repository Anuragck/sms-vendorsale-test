<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuotationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quotations', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('customer_id')->unsigned();
            $table->tinyInteger('gst_bill')->default(0);
            $table->tinyInteger('wholesale')->default(0);
     
            $table->bigInteger('quotation_no');
            $table->date('quotation_date'); 
            $table->date('quotation_end_date'); 
            $table->decimal('total_taxable_amount',10,2)->default(0);
            $table->decimal('total_cgst',10,2)->default(0);
            $table->decimal('total_sgst',10,2)->default(0);
            $table->decimal('total_cess',10,2)->default(0);
            $table->decimal('total_amount',10,2); 
            $table->decimal('total_discount',10,2); 
            $table->decimal('roundoff',8,2)->default(0);
            $table->decimal('pay_amount',10,2);
            $table->string('remarks',100)->nullable();
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
        Schema::dropIfExists('quotations');
    }
}
