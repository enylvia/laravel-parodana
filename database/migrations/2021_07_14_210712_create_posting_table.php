<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posting', function (Blueprint $table) {
            $table->bigIncrements('id');
			$table->string('proof_number');
			$table->string('trans_date');
			$table->string('branch');
			$table->text('description');
			$table->string('nominal');
			$table->string('journal')->default(0);
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
        Schema::dropIfExists('posting');
    }
}
