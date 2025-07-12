<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('reschedules', function (Blueprint $table) {
            $table->id('id_reschedule');
            $table->unsignedBigInteger('id_pemesanan');
            $table->unsignedBigInteger('id_pelanggan');
            $table->date('tanggal_lama');
            $table->time('waktu_lama');
            $table->date('tanggal_baru');
            $table->time('waktu_baru');
            $table->decimal('biaya_tambahan', 10, 2)->default(0);
            $table->text('alasan')->nullable();
            $table->enum('status', ['pending_payment', 'confirmed', 'cancelled'])->default('pending_payment');
            $table->string('snap_token')->nullable();
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamps();


        });
    }

    public function down()
    {
        Schema::dropIfExists('reschedules');
    }
};
