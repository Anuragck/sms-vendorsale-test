<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClosingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('closings', function (Blueprint $table) {
            $table->id();
            $table->date('closing_date');
            $table->decimal('opening_cash',10,2)->default(0);
            $table->decimal('opening_bank',10,2)->default(0);
            $table->decimal('today_cash_expense',10,2)->default(0);
            $table->decimal('today_bank_expense',10,2)->default(0);
            $table->decimal('today_cash_sale',10,2)->default(0);
            $table->decimal('today_bank_sale',10,2)->default(0);
            $table->decimal('today_cash_receipt',10,2)->default(0);
            $table->decimal('today_bank_receipt',10,2)->default(0);
            $table->decimal('today_cash_purchase',10,2)->default(0);
            $table->decimal('today_bank_purchase',10,2)->default(0);
            $table->decimal('today_cash_income',10,2)->default(0);
            $table->decimal('today_bank_income',10,2)->default(0);
            $table->decimal('closing_cash',10,2)->default(0);
            $table->decimal('closing_bank',10,2)->default(0);
            $table->boolean('closing_status')->nullable();

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
        Schema::dropIfExists('closings');
    }
}
