<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePerawatansTable extends Migration
{
    public function up()
    {
        Schema::create('perawatans', function (Blueprint $table) {
            $table->increments('id_perawatan');
            $table->string('nama_perawatan');
            $table->string('foto')->nullable();
            $table->text('deskripsi')->nullable();
            $table->bigInteger('waktu');
            $table->decimal('harga', 15, 2);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('perawatans');
    }
}
