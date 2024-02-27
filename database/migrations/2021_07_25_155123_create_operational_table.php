<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOperationalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('operational', function (Blueprint $table) {
            $table->bigIncrements('id');
			$table->string('transaction_no');
			$table->string('tipe');
			$table->string('transaction_type');
			$table->string('branch');
			$table->string('datetime')->nulabble();
			$table->string('beginning_balance')->nulabble();
			$table->string('amount');
			$table->string('ending_balance')->nulabble();
			$table->string('description');
			$table->string('created_by')->nulabble();
			$table->string('approved_by`')->nulabble();
			$table->tinyInteger('approval_status')->default(0);
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
        Schema::dropIfExists('operational');
    }
}
