<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTypeToLogIterasi extends Migration
{
    public function up()
    {
        Schema::table('tb_log_iterasi', function (Blueprint $table) {
            $table->enum('type', ['centroid', 'member', 'euclidean_distance', 'members_count']);
        });
    }

    public function down()
    {
        Schema::table('tb_log_iterasi', function (Blueprint $table) {
            $table->dropColumn('type');
        });
    }
}
