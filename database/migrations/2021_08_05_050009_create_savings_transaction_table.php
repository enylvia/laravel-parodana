<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSavingsTransactionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('savings_transaction', function (Blueprint $table) {
            $table->bigIncrements('id');
			$table->unsignedBigInteger('saving_id');
			$table->string('member_number');
			$table->string('tr_date');
			$table->string('tipe');
			$table->string('debet')->default(0);
			$table->string('credit')->default(0);
			$table->string('start_balance')->default(0);
			$table->string('end_balance')->default(0);
			$table->string('pay_method');
			$table->string('amount')->default(0);
			$table->string('status');
			$table->tinyInteger('journal')->default(0);
			$table->string('created_by');
			$table->timestamps();
			
			//FOREIGN KEY CONSTRAINTS
            //$table->foreign('id')->references('id')->on('savings')->onDelete('cascade');
            $table->foreign('saving_id')->references('id')->on('savings')->onDelete('cascade');
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('savings_transaction');
    }
}
