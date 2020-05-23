<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShareTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('share', function (Blueprint $table) {
            $table->increments('id');
            $table->string('sender_email');
            $table->string('reciver_email');
            $table->string('share_code');
            $table->string('validity');
            $table->string('mail_sent')->nullable();
            $table->string('note')->nullable();
            $table->tinyInteger('downloaded')->default(0);
            $table->tinyInteger('status')->default(1);
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
        Schema::dropIfExists('share_file');
    }
}
