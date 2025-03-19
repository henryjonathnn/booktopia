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
        Schema::create('peminjamans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_user')->constrained('users');
            $table->foreignId('id_buku')->constrained('bukus');
            $table->foreignId('id_staff')->nullable()->constrained('users');
            $table->text('alamat_pengiriman');
            $table->text('catatan_pengiriman')->nullable();
            $table->dateTime('tgl_peminjaman_diinginkan');
            $table->string('bukti_pengiriman')->nullable();
            $table->dateTime('tgl_dikirim')->nullable();
            $table->dateTime('tgl_kembali_rencana')->nullable();
            $table->dateTime('tgl_kembali_aktual')->nullable();
            $table->enum('status', [
                'PENDING',
                'DIPROSES',
                'DIKIRIM',
                'DIPINJAM',
                'TERLAMBAT',
                'DIKEMBALIKAN',
                'DITOLAK'
            ])->default('PENDING');
            $table->enum('metode_pengiriman', ['KURIR', 'AMBIL_DI_TEMPAT'])->default('KURIR');
            $table->string('bukti_pengembalian')->nullable();
            $table->integer('total_denda')->default(0);
            $table->boolean('is_denda_paid')->default(false);
            $table->timestamp('last_denda_calculation')->nullable();
            $table->string('bukti_pembayaran_denda')->nullable();
            $table->text('alasan_penolakan')->nullable();
            $table->string('nomor_resi')->nullable();
            $table->timestamps();

            // INDEXES
            $table->index('status');
            $table->index('tgl_peminjaman_diinginkan');
            $table->index('id_user');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peminjamans');
    }
};
