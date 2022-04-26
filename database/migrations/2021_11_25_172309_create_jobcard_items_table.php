<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJobcardItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jobcard_items', function (Blueprint $table) {
            
            $table->id();
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('technician_id')->nullable();
            $table->bigInteger('jobcard_id')->unsigned();
            $table->bigInteger('category_id')->unsigned();
            $table->bigInteger('product_id')->unsigned();
            $table->decimal('quantity',10,2)->default(1); 
            $table->decimal('rate',10,2)->default(0);
            $table->decimal('total',10,2)->default(0);
            $table->decimal('discount',10,2)->default(0);
            $table->decimal('gross_amount',10,2)->default(0);
            $table->decimal('gst',10,2)->default(0);
            $table->decimal('taxable_amount',10,2)->default(0);
            $table->decimal('cgst',10,2)->default(0);
            $table->decimal('sgst',10,2)->default(0);
            $table->decimal('total_rate',10,2)->default(0);
            

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
        Schema::dropIfExists('jobcard_items');
    }
}
