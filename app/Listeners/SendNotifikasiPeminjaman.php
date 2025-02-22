<?php

namespace App\Listeners;

use App\Events\PeminjamanStatusChanged;
use App\Models\Notifikasi;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendNotifikasiPeminjaman
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(PeminjamanStatusChanged $event)
    {
        Notifikasi::create([
            'id_user' => $event->peminjaman->id_user,
            'id_peminjaman' => $event->peminjaman->id,
            'message' => "Status peminjaman berubah menjadi {$event->peminjaman->status}",
            'tipe' => 'PEMINJAMAN_' . strtoupper($event->peminjaman->status)
        ]);
    }
}
