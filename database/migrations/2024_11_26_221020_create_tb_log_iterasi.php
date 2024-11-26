<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbLogIterasi extends Migration
{
    public function up()
    {
        Schema::create('tb_log_iterasi', function (Blueprint $table) {
            $table->id();
            $table->integer('tahun');
            $table->integer('iteration');
            $table->enum('type', ['centroid', 'member', 'euclidean_distance', 'members_count']); // Jenis data
            $table->string('cluster_label')->nullable(); // Label cluster (C1, C2, C3)
            $table->integer('id_kotakab')->nullable(); // ID Kabupaten/Kota (untuk anggota cluster)
            $table->integer('id_kecamatan')->nullable(); // ID Kecamatan (untuk anggota cluster)
            $table->integer('frekuensi_kejadian')->nullable(); // Frekuensi kejadian
            $table->integer('total_kerusakan')->nullable(); // Total kerusakan
            $table->integer('total_korban')->nullable(); // Total korban
            $table->integer('member_count')->nullable(); // Jumlah anggota (untuk members_count)
            $table->integer('euclidean_distance')->nullable(); // Jarak Euclidean (untuk euclidean_distance)
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tb_log_iterasi');
    }
}
