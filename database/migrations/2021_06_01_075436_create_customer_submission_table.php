<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerSubmissionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_submission', function (Blueprint $table) {
            $table->bigIncrements('id');
			$table->bigInteger('customer_id');
			$table->string('loan_amount')->nullable();
			$table->string('loan_to')->nullable();
			$table->string('time_period')->nullable();
			$table->string('installments_month')->nullable();
			$table->string('necessity_for')->nullable();
			$table->string('survey_plan')->nullable();
			$table->string('surveyor_name')->nullable();
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
        Schema::dropIfExists('customer_submission');
    }
}
