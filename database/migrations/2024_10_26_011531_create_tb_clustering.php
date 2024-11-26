<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_clustering', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_kotakab');
            $table->foreign('id_kotakab')->references('id')->on('tb_kotakab')->onDelete('cascade');
            $table->unsignedBigInteger('id_kecamatan');
            $table->foreign('id_kecamatan')->references('id')->on('tb_kecamatan')->onDelete('cascade');
            $table->integer('frekuensi_kejadian');
            $table->integer('total_kerusakan');
            $table->integer('total_korban');
            $table->string('cluster');
            $table->string('tahun');
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
        Schema::dropIfExists('tb_clustering');
    }
};
