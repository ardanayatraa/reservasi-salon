<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKaryawansTable extends Migration
{
    public function up()
    {
        Schema::create('karyawans', function (Blueprint $table) {
            $table->id('id_karyawan');
            $table->string('nama_lengkap');
            $table->string('email')->unique();
            $table->string('no_telepon')->nullable();
            $table->text('alamat')->nullable();
            $table->unsignedBigInteger('id_shift');
            $table->foreign('id_shift')->references('id_shift')->on('shifts')->onDelete('cascade');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('karyawans');
    }
}
