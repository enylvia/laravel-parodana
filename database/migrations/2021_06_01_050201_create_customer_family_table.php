<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerFamilyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_family', function (Blueprint $table) {
            $table->bigIncrements('id');
			$table->bigInteger('customer_id');
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
        Schema::dropIfExists('customer_family');
    }
}
