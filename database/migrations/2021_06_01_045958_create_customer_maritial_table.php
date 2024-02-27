<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerMaritialTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_maritial', function (Blueprint $table) {
            $table->bigIncrements('id');
			$table->bigInteger('customer_id');
			$table->string('maritial');
			$table->string('husband_wife')->nullable();
			$table->string('alias_husband_wife')->nullable();
			$table->string('husband_wife_profession')->nullable();
			$table->string('husband_wife_income')->nullable();
			$table->string('husband_wife_phone')->nullable();
			$table->bigInteger('husband_wife_provinsi')->nullable();
			$table->bigInteger('husband_wife_kabupaten')->nullable();
			$table->bigInteger('husband_wife_kecamatan')->nullable();
			$table->bigInteger('husband_wife_kelurahan')->nullable();
			$table->string('husband_wife_address')->nullable();
			$table->string('husband_wife_home_status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customer_maritial');
    }
}
