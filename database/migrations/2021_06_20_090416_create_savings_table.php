<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSavingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('savings', function (Blueprint $table) {
            $table->bigIncrements('id');
			$table->string('member_number');
			$table->string('contract_number');
			//$table->enum('tipe', ['WAJIB','POKOK','SUKARELA']);
			$table->string('wajib')->default(0);
			$table->string('pokok')->default(0);
			$table->string('sukarela')->default(0);
			$table->string('saldo')->default(0);
			//$table->enum('status', ['SETOR','TARIK','TRANSFER']);
			$table->string('status')->nullable();
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
        Schema::dropIfExists('savings');
    }
}
