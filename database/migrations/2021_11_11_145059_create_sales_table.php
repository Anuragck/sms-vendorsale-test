<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('bill_type')->unsigned();
            $table->bigInteger('customer_id')->unsigned();
            $table->string('vehicle')->nullable();
            $table->string('register_no')->nullable();
            $table->bigInteger('invoice_no');
            $table->date('invoice_date'); 
            $table->decimal('total_amount',10,2); 
            $table->decimal('total_discount',10,2); 
            $table->decimal('pay_amount',10,2); 
            $table->decimal('reward_points',10,2); 
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
        Schema::dropIfExists('sales');
    }
}
