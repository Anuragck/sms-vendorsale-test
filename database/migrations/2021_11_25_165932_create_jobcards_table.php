<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJobcardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jobcards', function (Blueprint $table) {

            $table->id();
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('bill_type')->unsigned();
            $table->bigInteger('customer_id')->unsigned();
            $table->string('vehicle',50)->nullable();
            $table->string('register_no',25)->nullable();
            $table->string('chasis_no',25)->nullable();
            $table->string('odometer',10)->nullable();
            $table->date('expected_date')->nullable();

            $table->string('customer_voice',500)->nullable();
            $table->string('technician_voice',500)->nullable();

            $table->integer('jobadvisor_id')->unsigned()->nullable();

            $table->timestamp('created_date')->nullable();
            $table->timestamp('completed_date')->nullable();
            $table->timestamp('delivered_date')->nullable();

            $table->decimal('total_taxable_amount',10,2)->default(0);
            $table->decimal('total_cgst',10,2)->default(0);
            $table->decimal('total_sgst',10,2)->default(0);
            $table->decimal('total_cess',10,2)->default(0);
            $table->decimal('total_amount',10,2)->default(0);
            $table->decimal('total_discount',10,2)->default(0); 
            $table->decimal('pay_amount',10,2)->default(0); 
            $table->decimal('received_amount',10,2)->default(0); 

            $table->decimal('reward_points',10,2)->default(0); 

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
        Schema::dropIfExists('jobcards');
    }
}
