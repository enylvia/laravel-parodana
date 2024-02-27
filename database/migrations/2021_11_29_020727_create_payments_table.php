<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {            
			$table->bigIncrements('id');
			$table->string('transaction_code')->unique();
			$table->string('pay_date');
			$table->string('transaction_type');
			$table->string('customer');
			$table->string('payment_method');
			$table->string('status');
			$table->string('pay_status');
			$table->string('amount');
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
        Schema::dropIfExists('payments');
    }
}
