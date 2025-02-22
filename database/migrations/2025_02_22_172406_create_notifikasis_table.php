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
        Schema::create('notifikasis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_user')->constrained('users');
            $table->foreignId('id_peminjaman')->constrained('peminjamans');
            $table->text('message');
            $table->enum('tipe', [
                'PEMINJAMAN_CREATED',
                'PEMINJAMAN_DIPROSES',
                'PEMINJAMAN_DIKIRIM',
                'PEMINJAMAN_DITERIMA',
                'PEMINJAMAN_DITOLAK',
                'PEMINJAMAN_DIKEMBALIKAN',
                'DUE_REMINDER',
                'OVERDUE_NOTICE',
                'DENDA_NOTICE'
            ]);
            $table->boolean('is_read')->default(false);
            $table->timestamps();

            // INDEXES
            $table->index('id_user');
            $table->index('tipe');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifikasis');
    }
};
