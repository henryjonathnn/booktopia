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
        Schema::create('bukus', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->string('penulis');
            $table->string('isbn')->unique()->nullable();
            $table->enum('kategori', [
                'FIKSI',
                'NON-FIKSI',
                'SAINS',
                'TEKNOLOGI',
                'SEJARAH',
                'SASTRA',
                'KOMIK',
                'LAINNYA'
            ]);
            $table->text('deskripsi')->nullable();
            $table->string('cover_img')->nullable();
            $table->integer('stock')->default(0);
            $table->integer('denda_harian')->default(0);
            $table->string('penerbit')->nullable();
            $table->integer('tahun_terbit')->nullable();
            $table->timestamps();

            
            // INDEXES
            $table->index('kategori');
            $table->index('judul');
            $table->index('penulis');
            $table->index('isbn');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bukus');
    }
};
