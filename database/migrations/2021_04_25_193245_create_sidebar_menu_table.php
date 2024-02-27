<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSidebarMenuTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sidebar_menu', function (Blueprint $table) {
            $table->increments('id');
			$table->integer('id_parent');
            $table->string('title');
            $table->string('display');
            $table->string('slug')->unique();
            $table->string('icon')->nullable();
            $table->string('url')->nullable();
            $table->string('image')->nullable();
			$table->tinyInteger('orders')->nullable();
            $table->tinyInteger('status')->nullable();
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
        Schema::dropIfExists('sidebar_menu');
    }
}
