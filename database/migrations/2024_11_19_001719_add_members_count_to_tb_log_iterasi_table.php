<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMembersCountToTbLogIterasiTable extends Migration
{
    public function up()
    {
        // Menambahkan kolom members_count ke tabel tb_log_iterasi
        Schema::table('tb_log_iterasi', function (Blueprint $table) {
            $table->string('members_count')->nullable();  // Menambahkan kolom integer dengan nilai default 0
        });
    }

    public function down()
    {
        // Menghapus kolom members_count jika migrasi dibatalkan
        Schema::table('tb_log_iterasi', function (Blueprint $table) {
            $table->dropColumn('members_count');
        });
    }
}
