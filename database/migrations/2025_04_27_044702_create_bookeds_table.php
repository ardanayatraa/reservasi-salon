<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookedsTable extends Migration
{
    public function up()
    {
        Schema::create('bookeds', function (Blueprint $table) {
            $table->increments('id_booked');
            $table->unsignedInteger('id_pemesanan');
            $table->unsignedInteger('id_perawatan');
            $table->date('tanggal_booked');
            $table->time('waktu');
            $table->timestamps();

            $table->foreign('id_pemesanan')
                  ->references('id_pemesanan')->on('pemesanans')
                  ->onDelete('cascade');
            $table->foreign('id_perawatan')
                  ->references('id_perawatan')->on('perawatans')
                  ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('bookeds');
    }
}
