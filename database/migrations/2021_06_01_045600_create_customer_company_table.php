<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerCompanyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_company', function (Blueprint $table) {
            $table->bigIncrements('id');
			$table->bigInteger('customer_id');
			$table->string('company_name')->nullable();
			$table->string('department')->nullable();
			$table->string('part')->nullable();
			$table->string('kpk_number')->nullable();
			$table->string('personalia_name')->nullable();
			$table->string('net_salary')->nullable();
			$table->string('gross_salary')->nullable();
			$table->string('payday_date')->nullable();
			$table->string('bank_name')->nullable();
			$table->string('bank_pin')->nullable();
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
        Schema::dropIfExists('customer_company');
    }
}
