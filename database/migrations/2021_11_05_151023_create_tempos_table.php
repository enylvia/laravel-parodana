<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTemposTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tempos', function (Blueprint $table) {
            $table->bigIncrements('id');
			$table->date('tempo_date');
			$table->string('member_number')->unique();
			$table->string('amount')->default(0);
			$table->string('rates')->default(0);
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
        Schema::dropIfExists('tempos');
    }
}
