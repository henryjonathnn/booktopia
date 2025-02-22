<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Buku extends Model
{
    use HasFactory;

    protected $fillable = [
        'judul',
        'penulis',
        'isbn',
        'kategori',
        'deskripsi',
        'cover_img',
        'stock',
        'denda_harian',
        'penerbit',
        'tahun_terbit'
    ];

    protected $casts = [
        'stock' => 'integer',
        'denda_harian' => 'integer',
        'tahun_terbit' => 'integer',
    ];

    // Relationships
    public function peminjamans()
    {
        return $this->hasMany(Peminjaman::class, 'id_buku');
    }

    public function bookmarks()
    {
        return $this->hasMany(Bookmark::class, 'id_buku');
    }

    public function sukas()
    {
        return $this->hasMany(Suka::class, 'id_buku');
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class, 'id_buku');
    }

    // Custom accessor for average rating
    public function getAverageRatingAttribute()
    {
        return cache()->remember("buku.{$this->id}.rating", 3600, function () {
            return $this->ratings()->avg('rating') ?? 0;
        });
    }
}
