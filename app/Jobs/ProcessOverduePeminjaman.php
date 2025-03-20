<?php

namespace App\Jobs;

use App\Models\Peminjaman;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Job untuk memproses peminjaman yang terlambat
 * Dijalankan secara terjadwal untuk:
 * - Mengecek peminjaman yang melewati batas waktu
 * - Menghitung denda keterlambatan
 * - Mengupdate status peminjaman
 * - Mengirim notifikasi ke user
 */
class ProcessOverduePeminjaman implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Ambil semua peminjaman yang masih DIPINJAM
        $peminjamans = Peminjaman::where('status', 'DIPINJAM')
            ->where('tgl_kembali_rencana', '<', now())
            ->where(function($query) {
                $query->whereNull('tgl_kembali_aktual')
                    ->orWhere('is_denda_paid', false);
            })
            ->get();

        foreach ($peminjamans as $peminjaman) {
            DB::beginTransaction();
            try {
                // Hitung selisih hari
                $daysLate = now()->diffInDays($peminjaman->tgl_kembali_rencana);
                
                // Hitung denda (denda_harian * jumlah hari terlambat)
                $totalDenda = $peminjaman->buku->denda_harian * $daysLate;
                
                // Update status dan total denda
                $peminjaman->update([
                    'status' => 'TERLAMBAT',
                    'total_denda' => $totalDenda,
                    'last_denda_calculation' => now()
                ]);

                // Kirim notifikasi ke user
                // $peminjaman->user->notify(new \App\Notifications\PeminjamanTerlambat($peminjaman));

                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('Error processing overdue: ' . $e->getMessage());
            }
        }
    }
}
