<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerApproveTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_approve', function (Blueprint $table) {
            $table->bigIncrements('id');
			$table->bigInteger('customer_id');
			$table->string('approve_amount')->nullable();
			$table->string('approve_by')->nullable();
			$table->string('interest_rate')->nullable();
			$table->string('time_period')->nullable();
			$table->string('installment')->nullable();
			$table->tinyInteger('approve')->default(0);
			$table->string('reason')->nullable();
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
        Schema::dropIfExists('customer_approve');
    }
}
