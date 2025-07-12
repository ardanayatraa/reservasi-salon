<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Create reviews table (Updated to match model)
        if (!Schema::hasTable('reviews')) {
            Schema::create('reviews', function (Blueprint $table) {
                $table->increments('id_review'); // Primary key
                $table->unsignedInteger('id_pemesanan');
                $table->unsignedInteger('id_pelanggan');
                $table->tinyInteger('rating')->unsigned()->checkConstraint('rating >= 1 AND rating <= 5');
                $table->text('komentar')->nullable(); // Sesuai dengan model
                $table->enum('status', ['pending', 'approved', 'rejected'])->default('approved'); // Sesuai dengan model
                $table->timestamp('tanggal_review')->nullable(); // Sesuai dengan model
                $table->text('admin_notes')->nullable(); // Sesuai dengan model
                $table->timestamps(); // created_at and updated_at

                $table->foreign('id_pemesanan')->references('id_pemesanan')->on('pemesanans')->onDelete('cascade');
                $table->foreign('id_pelanggan')->references('id_pelanggan')->on('pelanggans')->onDelete('cascade');

                $table->index('id_pelanggan');
                $table->index('rating');
                $table->index('status');
            });
        }

        // Create booking_logs table
        if (!Schema::hasTable('booking_logs')) {
            Schema::create('booking_logs', function (Blueprint $table) {
                $table->increments('id_log'); // Primary key
                $table->unsignedInteger('id_pemesanan');
                $table->enum('action_type', ['cancel', 'reschedule', 'refund']);
                $table->text('reason')->nullable();
                $table->date('old_date')->nullable();
                $table->time('old_time')->nullable();
                $table->date('new_date')->nullable();
                $table->time('new_time')->nullable();
                $table->decimal('refund_amount', 10, 2)->nullable();
                $table->enum('refund_status', ['pending', 'processing', 'completed', 'failed'])->default('pending');
                $table->timestamp('created_at')->useCurrent(); // Only created_at

                $table->foreign('id_pemesanan')->references('id_pemesanan')->on('pemesanans')->onDelete('cascade');

                $table->index('id_pemesanan');
                $table->index('action_type');
            });
        }

        // Create email_notifications table
        if (!Schema::hasTable('email_notifications')) {
            Schema::create('email_notifications', function (Blueprint $table) {
                $table->increments('id_notification'); // Primary key
                $table->unsignedInteger('id_pelanggan');
                $table->enum('email_type', ['booking_confirmation', 'booking_reminder', 'cancellation', 'reschedule', 'refund']);
                $table->string('subject');
                $table->text('body');
                $table->timestamp('sent_at')->useCurrent();
                $table->enum('status', ['sent', 'failed'])->default('sent');

                $table->foreign('id_pelanggan')->references('id_pelanggan')->on('pelanggans')->onDelete('cascade');

                $table->index('id_pelanggan');
                $table->index('email_type');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('email_notifications');
        Schema::dropIfExists('booking_logs');
        Schema::dropIfExists('reviews');
    }
};
