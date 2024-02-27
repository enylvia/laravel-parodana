<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJournalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('journals', function (Blueprint $table) {
            $table->bigIncrements('id');
			$table->integer('account_id')->unsigned();
			$table->integer('account_number')->unsigned();
			$table->integer('company_id')->unsigned();			
            $table->date('transaction_date');
			$table->date('transaction_code');
            $table->integer('nominal')->unsigned();
            $table->enum('tipe', ['d', 'k']); 
			$table->string('description', 255);
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
        Schema::dropIfExists('journals');
    }
}
