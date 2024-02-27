<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchaseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase', function (Blueprint $table) {
            $table->bigIncrements('id');
			$table->string('trans_code');
			$table->string('trans_date');
			$table->string('trans_type');
			$table->string('branch');
			$table->string('description');
			$table->string('unit');
			$table->string('qty');
			$table->string('amount')->default(0);
			$table->string('stock')->default(0);
			$table->string('status')->default(0);
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
        Schema::dropIfExists('purchase');
    }
}
