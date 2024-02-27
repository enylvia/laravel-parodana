<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerDocumentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_document', function (Blueprint $table) {
            $table->bigIncrements('id');
			$table->bigInteger('customer_id');
			$table->string('document_category')->nullable();
			$table->string('document_name')->nullable();
			$table->string('document_file')->nullable();
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
        Schema::dropIfExists('customer_document');
    }
}
