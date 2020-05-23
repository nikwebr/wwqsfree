<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShareFileTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('share_file', function (Blueprint $table) {
            $table->increments('id');
            $table->string('share_id');
            $table->string('validity');
            $table->string('file_url', 500);
            $table->string('file_original_name', 500);
            $table->string('file_size', 500)->nullable();
            $table->string('downloaded')->nullable();
            $table->string('download_count')->default(0);
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
