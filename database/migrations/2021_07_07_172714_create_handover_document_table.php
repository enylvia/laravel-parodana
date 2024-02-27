<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHandoverDocumentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('handover_document', function (Blueprint $table) {
            $table->bigIncrements('id');
			$table->string('reg_number');
			$table->string('berkas');
			$table->string('status');
			$table->string('keterangan');
			$table->string('company_id');
			$table->string('created_by');
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
        Schema::dropIfExists('handover_document');
    }
}
