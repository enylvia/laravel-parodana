<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMailboxTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mailbox', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string("subject");
            $table->longText("body")->nullable();
            $table->integer("sender_id")->unsigned();
			$table->string("branch");
            $table->string("time_sent");
			$table->string("read_at")->nullable();
            $table->integer("parent_id")->default(0);
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
        Schema::dropIfExists('mailbox');
    }
}
