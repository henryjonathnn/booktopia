<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    use HasFactory;

    protected $table = 'peminjamans';

    // Definisikan konstanta untuk status
    const STATUS_MENUNGGU = 'MENUNGGU';
    const STATUS_DISETUJUI = 'DISETUJUI';
    const STATUS_DITOLAK = 'DITOLAK';
    const STATUS_DIKIRIM = 'DIKIRIM';
    const STATUS_DIPINJAM = 'DIPINJAM';
    const STATUS_TERLAMBAT = 'TERLAMBAT';
    const STATUS_SELESAI = 'SELESAI';
    const STATUS_DIBATALKAN = 'DIBATALKAN';

    protected $fillable = [
        'id_user',
        'id_buku',
        'id_staff',
        'alamat_pengiriman',
        'catatan_pengiriman',
        'tgl_peminjaman_diinginkan',
        'bukti_pengiriman',
        'tgl_dikirim',
        'tgl_kembali_rencana',
        'tgl_kembali_aktual',
        'status',
        'metode_pengiriman',
        'total_denda',
        'bukti_pembayaran_denda',
        'alasan_penolakan',
        'nomor_resi'
    ];

    protected $casts = [
        'tgl_peminjaman_diinginkan' => 'datetime',
        'tgl_dikirim' => 'datetime',
        'tgl_kembali_rencana' => 'datetime',
        'tgl_kembali_aktual' => 'datetime',
        'total_denda' => 'integer'
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function staff()
    {
        return $this->belongsTo(User::class, 'id_staff');
    }

    public function buku()
    {
        return $this->belongsTo(Buku::class, 'id_buku');
    }

    public function notifikasis()
    {
        return $this->hasMany(Notifikasi::class, 'id_peminjaman');
    }
}
