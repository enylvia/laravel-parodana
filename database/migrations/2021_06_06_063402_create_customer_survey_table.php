<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerSurveyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_survey', function (Blueprint $table) {
            $table->bigIncrements('id');
			$table->bigInteger('customer_id');
			$table->string('environment_condition')->nullable();
			$table->string('viability')->nullable();
			$table->string('other_income')->nullable();
			$table->string('child_fee')->nullable();
			$table->string('electricity_cost')->nullable();
			$table->string('water_cost')->nullable();
			$table->string('other_installment')->nullable();
			$table->string('longitude')->nullable();
			$table->string('latitude')->nullable();
			$table->string('note')->nullable();
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
        Schema::dropIfExists('customer_survey');
    }
}
