<?php

namespace Tests\Feature;

use App\Models\Pemesanan;
use App\Models\Karyawan;
use App\Models\Pelanggan;
use App\Models\Perawatan;
use App\Models\Shift;
use App\Jobs\CancelUnpaidReservationJob;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;
use Carbon\Carbon;

class CancelUnpaidReservationsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_cancels_unpaid_reservations_with_passed_deadline()
    {
        // Create test data
        $shift = Shift::create([
            'nama_shift' => 'Morning Shift',
            'start_time' => '08:00',
            'end_time' => '16:00',
        ]);

        $karyawan = Karyawan::create([
            'nama_lengkap' => 'Test Employee',
            'email' => 'employee@test.com',
            'no_telepon' => '123456789',
            'alamat' => 'Test Address',
            'id_shift' => $shift->id_shift,
            'availability_status' => 'available',
        ]);

        $pelanggan = Pelanggan::create([
            'nama_lengkap' => 'Test Customer',
            'email' => 'customer@test.com',
            'no_telepon' => '987654321',
            'alamat' => 'Customer Address',
        ]);

        $perawatan = Perawatan::create([
            'nama_perawatan' => 'Test Service',
            'deskripsi' => 'Test Description',
            'harga' => 100000,
            'durasi' => 60,
        ]);

        // Create a reservation with passed payment deadline
        $reservation = Pemesanan::create([
            'id_pelanggan' => $pelanggan->id_pelanggan,
            'id_karyawan' => $karyawan->id_karyawan,
            'tanggal_pemesanan' => Carbon::tomorrow(),
            'waktu' => '10:00',
            'jumlah_perawatan' => 1,
            'total' => 100000,
            'sub_total' => 100000,
            'metode_pembayaran' => 'transfer',
            'status_pemesanan' => 'pending',
            'status_pembayaran' => 'pending',
            'payment_deadline' => Carbon::now()->subMinutes(5), // 5 minutes in the past
        ]);

        // Run the command
        Artisan::call('reservations:cancel-unpaid');

        // Refresh the reservation from database
        $reservation->refresh();

        // Assert the reservation was cancelled
        $this->assertEquals('cancelled', $reservation->status_pemesanan);
        $this->assertEquals('failed', $reservation->status_pembayaran);
        $this->assertEquals('Batas waktu pembayaran telah berakhir', $reservation->alasan_pembatalan);
        $this->assertEquals('system', $reservation->cancelled_by);
        $this->assertNotNull($reservation->cancelled_at);

        // Assert the employee is available
        $karyawan->refresh();
        $this->assertEquals('available', $karyawan->availability_status);
    }

    /** @test */
    public function it_does_not_cancel_paid_reservations()
    {
        // Create test data
        $shift = Shift::create([
            'nama_shift' => 'Morning Shift',
            'start_time' => '08:00',
            'end_time' => '16:00',
        ]);

        $karyawan = Karyawan::create([
            'nama_lengkap' => 'Test Employee',
            'email' => 'employee2@test.com',
            'no_telepon' => '123456789',
            'alamat' => 'Test Address',
            'id_shift' => $shift->id_shift,
            'availability_status' => 'available',
        ]);

        $pelanggan = Pelanggan::create([
            'nama_lengkap' => 'Test Customer',
            'email' => 'customer2@test.com',
            'no_telepon' => '987654321',
            'alamat' => 'Customer Address',
        ]);

        $perawatan = Perawatan::create([
            'nama_perawatan' => 'Test Service',
            'deskripsi' => 'Test Description',
            'harga' => 100000,
            'durasi' => 60,
        ]);

        // Create a paid reservation with passed payment deadline
        $reservation = Pemesanan::create([
            'id_pelanggan' => $pelanggan->id_pelanggan,
            'id_karyawan' => $karyawan->id_karyawan,
            'tanggal_pemesanan' => Carbon::tomorrow(),
            'waktu' => '10:00',
            'jumlah_perawatan' => 1,
            'total' => 100000,
            'sub_total' => 100000,
            'metode_pembayaran' => 'transfer',
            'status_pemesanan' => 'confirmed',
            'status_pembayaran' => 'paid',
            'payment_deadline' => Carbon::now()->subMinutes(5), // 5 minutes in the past
        ]);

        // Run the command
        Artisan::call('reservations:cancel-unpaid');

        // Refresh the reservation from database
        $reservation->refresh();

        // Assert the reservation was not cancelled
        $this->assertEquals('confirmed', $reservation->status_pemesanan);
        $this->assertEquals('paid', $reservation->status_pembayaran);
        $this->assertNull($reservation->alasan_pembatalan);
        $this->assertNull($reservation->cancelled_by);
        $this->assertNull($reservation->cancelled_at);
    }

    /** @test */
    public function it_does_not_cancel_reservations_with_future_deadline()
    {
        // Create test data
        $shift = Shift::create([
            'nama_shift' => 'Morning Shift',
            'start_time' => '08:00',
            'end_time' => '16:00',
        ]);

        $karyawan = Karyawan::create([
            'nama_lengkap' => 'Test Employee',
            'email' => 'employee3@test.com',
            'no_telepon' => '123456789',
            'alamat' => 'Test Address',
            'id_shift' => $shift->id_shift,
            'availability_status' => 'available',
        ]);

        $pelanggan = Pelanggan::create([
            'nama_lengkap' => 'Test Customer',
            'email' => 'customer3@test.com',
            'no_telepon' => '987654321',
            'alamat' => 'Customer Address',
        ]);

        $perawatan = Perawatan::create([
            'nama_perawatan' => 'Test Service',
            'deskripsi' => 'Test Description',
            'harga' => 100000,
            'durasi' => 60,
        ]);

        // Create a reservation with future payment deadline
        $reservation = Pemesanan::create([
            'id_pelanggan' => $pelanggan->id_pelanggan,
            'id_karyawan' => $karyawan->id_karyawan,
            'tanggal_pemesanan' => Carbon::tomorrow(),
            'waktu' => '10:00',
            'jumlah_perawatan' => 1,
            'total' => 100000,
            'sub_total' => 100000,
            'metode_pembayaran' => 'transfer',
            'status_pemesanan' => 'pending',
            'status_pembayaran' => 'pending',
            'payment_deadline' => Carbon::now()->addMinutes(15), // 15 minutes in the future
        ]);

        // Run the command
        Artisan::call('reservations:cancel-unpaid');

        // Refresh the reservation from database
        $reservation->refresh();

        // Assert the reservation was not cancelled
        $this->assertEquals('pending', $reservation->status_pemesanan);
        $this->assertEquals('pending', $reservation->status_pembayaran);
        $this->assertNull($reservation->alasan_pembatalan);
        $this->assertNull($reservation->cancelled_by);
        $this->assertNull($reservation->cancelled_at);
    }
}
