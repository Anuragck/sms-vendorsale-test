<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdatesToPurchaseLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('purchase_logs', function (Blueprint $table) {
            $table->tinyInteger('bill_type')->after('vendor_id')->default(1);
            $table->string('pay_type')->after('total_amount')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('purchase_logs', function (Blueprint $table) {
            //
        });
    }
}
