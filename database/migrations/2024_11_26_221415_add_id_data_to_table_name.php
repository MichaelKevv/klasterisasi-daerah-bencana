<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIdDataToTableName extends Migration
{
    public function up()
    {
        Schema::table('tb_log_iterasi', function (Blueprint $table) {
            $table->integer('id_data')->nullable(); // Adjust type if necessary
        });
    }

    public function down()
    {
        Schema::table('tb_log_iterasi', function (Blueprint $table) {
            $table->dropColumn('id_data');
        });
    }
}
