<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->increments('id');
			$table->string('company_id');
			$table->string('name');
			$table->string('branch');
			$table->string('siup');
			$table->string('address');
			$table->string('zip_code')->nullable();
			$table->bigInteger('provinsi')->nullable();
			$table->bigInteger('kabupaten')->nullable();
			$table->bigInteger('kecamatan')->nullable();
			$table->bigInteger('kelurahan')->nullable();
			$table->string('created_by')->nullable();
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
        Schema::dropIfExists('companies');
    }
}
