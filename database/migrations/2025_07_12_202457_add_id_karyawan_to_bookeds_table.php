<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIdKaryawanToBookedsTable extends Migration
{
    public function up()
    {
        Schema::table('bookeds', function (Blueprint $table) {
            if (!Schema::hasColumn('bookeds', 'id_karyawan')) {
                $table->unsignedInteger('id_karyawan')->nullable()->after('id_perawatan');
            }
        });
    }

    public function down()
    {
        Schema::table('bookeds', function (Blueprint $table) {
            if (Schema::hasColumn('bookeds', 'id_karyawan')) {
                $table->dropColumn('id_karyawan');
            }
        });
    }
}
