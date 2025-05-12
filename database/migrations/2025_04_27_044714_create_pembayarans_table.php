<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePembayaransTable extends Migration
{
    public function up()
    {
        Schema::create('pembayarans', function (Blueprint $table) {
            $table->increments('id_pembayaran');
            $table->unsignedInteger('id_pemesanan');
            $table->timestamp('tanggal_pembayaran');
            $table->decimal('total_harga', 15, 2);
            $table->string('status_pembayaran');
            $table->string('metode_pembayaran');
            $table->string('snap_token')->nullable();
            $table->text('notifikasi')->nullable();
            $table->timestamps();

            $table->foreign('id_pemesanan')
                  ->references('id_pemesanan')->on('pemesanans')
                  ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('pembayarans');
    }
}
