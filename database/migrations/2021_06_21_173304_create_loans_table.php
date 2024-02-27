<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLoansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loans', function (Blueprint $table) {
            $table->bigIncrements('id');
			$table->string('member_number');
			$table->string('contract_number');
			$table->string('contract_date');
			$table->string('start_month');
			$table->string('loan_amount');
			$table->string('time_period');
			$table->string('pay_date');
			$table->string('interest_rate');
			$table->string('loan_remaining');
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
        Schema::dropIfExists('loans');
    }
}
