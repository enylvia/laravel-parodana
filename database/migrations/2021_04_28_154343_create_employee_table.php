<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee', function (Blueprint $table) {
            $table->increments('id');
			$table->integer('user_id');
			$table->string('employee_id');
			$table->string('branch');
			$table->string('population_card');
			$table->string('family_card');			
			$table->string('date_of_birth')->nullable();
			$table->string('birth_place')->nullable();		
			$table->string('gender')->nullable();
			$table->string('religion')->nullable();
			$table->string('maritial')->nullable();
			$table->string('education')->nullable();
			$table->string('address');
			$table->string('zip_code')->nullable();
			$table->string('position')->nullable();
			$table->bigInteger('provinsi')->nullable();
			$table->bigInteger('kabupaten')->nullable();
			$table->bigInteger('kecamatan')->nullable();
			$table->bigInteger('kelurahan')->nullable();
			$table->string('id_card')->nullable();
			$table->string('home_status')->nullable();
			$table->string('payroll_bank')->nullable();	
			$table->string('account_number')->nullable();	
			$table->string('mother_name')->nullable();
			$table->string('mother_phone')->nullable();
			$table->string('father_name')->nullable();
			$table->string('father_phone')->nullable();
			$table->string('created_by')->nullable();
			$table->tinyInteger('active')->default(0);
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
        Schema::dropIfExists('employee');
    }
}
