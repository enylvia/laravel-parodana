<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer', function (Blueprint $table) {
            $table->bigIncrements('id');
			$table->string('name');
			$table->integer('user_id');
			$table->string('slug')->unique();
			$table->string('branch');
			$table->string('mobile_phone');
            $table->string('email')->nullable();
			$table->string('avatar')->nullable();
			$table->string('gender');
			$table->string('family_card_number');
			$table->string('card_number');
			$table->string('date_of_birth');
			$table->string('birth_place');
			$table->string('mother_maiden_name');
			$table->string('religion');
			$table->string('nationality');			
			$table->string('education');
			$table->string('address');
			$table->string('zip_code')->nullable();
			$table->bigInteger('provinsi')->nullable();
			$table->bigInteger('kabupaten')->nullable();
			$table->bigInteger('kecamatan')->nullable();
			$table->bigInteger('kelurahan')->nullable();												
			$table->string('created_by')->nullable();
			$table->string('status')->nullable();
			
			$table->string('company_name')->nullable();
			$table->string('department')->nullable();
			$table->string('part')->nullable();
			$table->string('kpk_number')->nullable();
			$table->string('personalia_name')->nullable();
			$table->string('net_salary')->nullable();
			$table->string('gross_salary')->nullable();
			$table->string('payday_date')->nullable();
			$table->string('bank_name')->nullable();
			$table->string('bank_pin')->nullable();
			
			$table->string('maritial');
			$table->string('husband_wife')->nullable();
			$table->string('alias_husband_wife')->nullable();
			$table->string('husband_wife_profession')->nullable();
			$table->string('husband_wife_income')->nullable();
			$table->string('husband_wife_phone')->nullable();
			$table->bigInteger('husband_wife_provinsi')->nullable();
			$table->bigInteger('husband_wife_kabupaten')->nullable();
			$table->bigInteger('husband_wife_kecamatan')->nullable();
			$table->bigInteger('husband_wife_kelurahan')->nullable();
			$table->string('husband_wife_address')->nullable();
			$table->string('husband_wife_home_status')->nullable();
			
			$table->string('family_father')->nullable();
			$table->string('family_mother')->nullable();
			$table->bigInteger('family_provinsi')->nullable();
			$table->bigInteger('family_kabupaten')->nullable();
			$table->bigInteger('family_kecamatan')->nullable();
			$table->bigInteger('family_kelurahan')->nullable();
			$table->string('family_address')->nullable();
			$table->string('in_law_father')->nullable();
			$table->string('in_law_mother')->nullable();
			$table->string('in_law_phone')->nullable();
			$table->string('in_law_provinsi')->nullable();
			$table->string('in_law_kabupaten')->nullable();
			$table->string('in_law_kecamatan')->nullable();
			$table->string('in_law_kelurahan')->nullable();
			$table->string('in_law_address')->nullable();
			$table->string('connection_name')->nullable();
			$table->string('connection_alias_name')->nullable();
			$table->string('connection_phone')->nullable();
			$table->bigInteger('connection_provinsi')->nullable();
			$table->bigInteger('connection_kabupaten')->nullable();
			$table->bigInteger('connection_kecamatan')->nullable();
			$table->bigInteger('connection_kelurahan')->nullable();
			$table->string('connection_address')->nullable();
			$table->string('family_connection')->nullable();
			
			$table->string('loan_amount')->nullable();
			$table->string('loan_to')->nullable();
			$table->string('time_period')->nullable();
			$table->string('installments_month')->nullable();
			$table->string('necessity_for')->nullable();
			$table->string('survey_plan')->nullable();
			$table->string('surveyor_name')->nullable();
			$table->string('reason')->nullable();
			
			$table->tinyInteger('approve')->default(0);
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
        Schema::dropIfExists('customer');
    }
}
