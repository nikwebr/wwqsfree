<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateShareTableAddIp extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('share', function (Blueprint $table) {
            $table->string('ip')->default('');
            $table->string('user_agent', 500)->default('');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('share', function (Blueprint $table) {
            $table->dropColumn('ip');
            $table->dropColumn('user_agent');
        });
    }
}
