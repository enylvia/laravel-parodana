<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccountBalanceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('account_balance', function (Blueprint $table) {
            $table->bigIncrements('id');
			$table->string('transaction_no')->unique();
			$table->date('mutation_date');
			$table->string('customer_id');
			$table->string('member_number');
			$table->string('from_account');
			$table->string('to_account');
			$table->string('payment_type')->nullable();
			$table->string('payment_method')->nullable();
			$table->string('amount')->default(0);
			$table->string('description');
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
        Schema::dropIfExists('account_balance');
    }
}
