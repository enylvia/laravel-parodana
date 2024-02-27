<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerContractTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_contract', function (Blueprint $table) {
            $table->bigIncrements('id');
			$table->bigInteger('customer_id');
			$table->string('contract_date')->nullable();
			$table->string('contract_number');
			//$table->string('loan_amount')->nullable();
			//$table->string('loan_to')->nullable();
			//$table->string('time_period')->nullable();
			//$table->string('installment_month')->nullable();
			//$table->customer_id = $request->customer_id;
			//$table->contract_date = Carbon::now();
			$table->string('c_day')->nullable();
			$table->string('c_date')->nullable();
			$table->string('c_month')->nullable();
			$table->string('c_year')->nullable();
			$table->string('employee_id')->nullable();									
			$table->string('atm_number')->default(0);
			$table->string('bank_pin')->default(0);
			$table->string('m_savings')->default(0);
			$table->string('insurance')->default(0);
			$table->string('stamp')->default(0);
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
        Schema::dropIfExists('customer_contract');
    }
}
