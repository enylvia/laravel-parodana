<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRolesSidebarmenuTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('roles_sidebar_menu', function (Blueprint $table) {
            $table->unsignedInteger('role_id');
            $table->unsignedInteger('sidemenu_id');
			$table->tinyInteger('menu_access');

            //FOREIGN KEY CONSTRAINTS
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
            $table->foreign('sidemenu_id')->references('id')->on('sidebar_menu')->onDelete('cascade');

            //SETTING THE PRIMARY KEYS
            $table->primary(['role_id','sidemenu_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('roles_sidebar_menu');
    }
}
