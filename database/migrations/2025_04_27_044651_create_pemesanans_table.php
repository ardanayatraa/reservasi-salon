<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePemesanansTable extends Migration
{
    public function up()
    {
        Schema::create('pemesanans', function (Blueprint $table) {
            $table->increments('id_pemesanan');
            $table->unsignedInteger('id_user');        // Admin yang entry
            $table->unsignedInteger('id_pelanggan');
            $table->date('tanggal_pemesanan');
            $table->time('waktu');
            $table->integer('jumlah_perawatan');
            $table->string('status_pemesanan');
            $table->decimal('total', 15, 2);
            $table->decimal('sub_total', 15, 2);
            $table->string('metode_pembayaran');
            $table->string('status_pembayaran');
            $table->string('token')->nullable();
            $table->timestamps();
             $table->unsignedBigInteger('id_karyawan')->nullable();
            $table->foreign('id_pelanggan')
                  ->references('id_pelanggan')->on('pelanggans')
                  ->onDelete('cascade');

        });
    }

    public function down()
    {
        Schema::dropIfExists('pemesanans');
    }
}
