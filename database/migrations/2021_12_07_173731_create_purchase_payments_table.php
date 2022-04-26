<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchasePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_payments', function (Blueprint $table) {
            $table->id();
            $table->date('payment_date');
            $table->tinyInteger('credit')->default(0);
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('vendor_id')->unsigned();
            $table->bigInteger('purchase_id')->nullable();
            $table->decimal('amount',10,2);
            $table->string('pay_type');
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
        Schema::dropIfExists('purchase_payments');
    }
}
