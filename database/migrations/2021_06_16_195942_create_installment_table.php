<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInstallmentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('installment', function (Blueprint $table) {
            $table->bigIncrements('id');
			$table->string('customer_id');
			$table->string('reg_number');
			$table->string('contract_number');
			$table->string('member_id');
			$table->string('loan_amount');
			$table->string('time_period');
			$table->string('interest_rate');
			$table->string('pay_date');
			$table->string('pay_method');
			$table->string('amount')->default(0);
			$table->enum('status', ['UNPAID','PAID','PARTIAL','CORRUPT']);
			$table->tinyInteger('posting')->default(0);
			$table->string('created_by');
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
        Schema::dropIfExists('installment');
    }
}
