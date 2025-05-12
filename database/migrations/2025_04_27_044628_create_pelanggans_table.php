<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePelanggansTable extends Migration
{
    public function up()
    {
        Schema::create('pelanggans', function (Blueprint $table) {
            $table->increments('id_pelanggan');
            $table->string('nama_lengkap');
            $table->string('username')->unique();
            $table->string('password');
            $table->string('email')->unique();
            $table->string('no_telepon')->nullable();
            $table->string('alamat')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pelanggans');
    }
}
