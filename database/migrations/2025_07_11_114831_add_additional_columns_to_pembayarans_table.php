<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        Schema::table('pembayarans', function (Blueprint $table) {
            // Kolom untuk refund
            $table->decimal('refund_amount', 10, 2)->nullable()->after('total_harga');
            $table->timestamp('refund_date')->nullable()->after('refund_amount');
            $table->string('refund_id')->nullable()->after('refund_date');
            $table->text('refund_reason')->nullable()->after('refund_id');
        });

        // Update enum status_pembayaran via raw SQL
        DB::statement("ALTER TABLE pembayarans MODIFY status_pembayaran ENUM(
            'unpaid',
            'pending',
            'paid',
            'failed',
            'refunded',
            'partial_refund'
        )");
    }

    public function down()
    {
        Schema::table('pembayarans', function (Blueprint $table) {
            $table->dropColumn([
                'refund_amount',
                'refund_date',
                'refund_id',
                'refund_reason'
            ]);
        });

        // Kembalikan enum status_pembayaran ke versi sebelumnya
        DB::statement("ALTER TABLE pembayarans MODIFY status_pembayaran ENUM(
            'unpaid',
            'pending',
            'paid',
            'failed'
        )");
    }
};
