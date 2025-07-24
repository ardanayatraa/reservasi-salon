<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Update enum status_pemesanan to include 'rescheduled'
        DB::statement("ALTER TABLE pemesanans MODIFY status_pemesanan ENUM(
            'pending',
            'confirmed',
            'in_progress',
            'completed',
            'cancelled',
            'cancelled_by_salon',
            'no_show',
            'rescheduled'
        )");
    }

    public function down()
    {
        // Rollback enum status_pemesanan to exclude 'rescheduled'
        DB::statement("ALTER TABLE pemesanans MODIFY status_pemesanan ENUM(
            'pending',
            'confirmed',
            'in_progress',
            'completed',
            'cancelled',
            'cancelled_by_salon',
            'no_show'
        )");
    }
};
