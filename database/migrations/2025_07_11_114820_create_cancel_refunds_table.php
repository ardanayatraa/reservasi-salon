<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('cancel_refunds', function (Blueprint $table) {
            $table->id('id_cancel_refund');
            $table->unsignedBigInteger('id_pemesanan');
            $table->unsignedBigInteger('id_pelanggan');
            $table->enum('type', ['cancel', 'refund']);
            $table->decimal('refund_amount', 10, 2);
            $table->enum('status', ['pending', 'processed', 'failed'])->default('pending');
            $table->timestamp('processed_at')->nullable();
            $table->text('alasan')->nullable();
            $table->timestamps();


        });
    }

    public function down()
    {
        Schema::dropIfExists('cancel_refunds');
    }
};
