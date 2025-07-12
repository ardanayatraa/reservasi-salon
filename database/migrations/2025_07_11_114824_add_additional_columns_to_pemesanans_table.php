<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        Schema::table('pemesanans', function (Blueprint $table) {
            // Tambahkan kolom baru
            $table->text('alasan_pembatalan')->nullable()->after('status_pembayaran');
            $table->timestamp('cancelled_at')->nullable()->after('alasan_pembatalan');
            $table->enum('cancelled_by', ['customer', 'salon'])->nullable()->after('cancelled_at');
            $table->timestamp('completed_at')->nullable()->after('cancelled_by');
            $table->timestamp('started_at')->nullable()->after('completed_at');
            $table->unsignedInteger('reschedule_count')->default(0)->after('started_at');
            $table->date('original_date')->nullable()->after('reschedule_count');
            $table->time('original_time')->nullable()->after('original_date');
        });

        // Update enum status_pemesanan tanpa doctrine/dbal
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

    public function down()
    {
        Schema::table('pemesanans', function (Blueprint $table) {
            // Drop kolom tambahan
            $table->dropColumn([
                'alasan_pembatalan',
                'cancelled_at',
                'cancelled_by',
                'completed_at',
                'started_at'
            ]);
        });

        // Rollback enum status_pemesanan ke versi sebelumnya (jika perlu)
        DB::statement("ALTER TABLE pemesanans MODIFY status_pemesanan ENUM(
            'pending',
            'confirmed'
        )");
    }
};
