<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangesToVendorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vendors', function (Blueprint $table) {
            $table->string('location',50)->after('mobile')->nullable();
            $table->string('address_1',50)->after('location')->nullable();
            $table->string('address_2',50)->after('address_1')->nullable();
            $table->string('state',50)->after('address_2')->nullable();
            $table->string('pincode',10)->after('state')->nullable();
            $table->string('gst_no',30)->after('pincode')->nullable();
            $table->decimal('opening_credit',10,2)->after('gst_no')->nullable();
           
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vendors', function (Blueprint $table) {
            //
        });
    }
}
