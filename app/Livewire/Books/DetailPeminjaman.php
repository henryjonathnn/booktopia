<?php

namespace App\Livewire\Books;

use App\Models\Peminjaman;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DetailPeminjaman extends Component
{
    public $peminjaman;
    public $statusColors = [
        'PENDING' => 'yellow',
        'DIPROSES' => 'blue',
        'DIKIRIM' => 'purple',
        'DIPINJAM' => 'green',
        'TERLAMBAT' => 'red',
        'DIKEMBALIKAN' => 'gray',
        'DITOLAK' => 'red'
    ];

    public function mount($id)
    {
        $this->peminjaman = Peminjaman::with(['buku', 'user'])
            ->where('id_user', Auth::id())
            ->findOrFail($id);
    }

    public function getStatusColorProperty()
    {
        return $this->statusColors[$this->peminjaman->status] ?? 'gray';
    }

    public function getDueDateStatusProperty()
    {
        if (!$this->peminjaman->tgl_kembali_rencana) {
            return null;
        }

        $dueDate = Carbon::parse($this->peminjaman->tgl_kembali_rencana);
        $now = Carbon::now();

        if ($this->peminjaman->status === 'DIPINJAM') {
            $daysLeft = $now->diffInDays($dueDate, false);
            if ($daysLeft < 0) {
                return ['status' => 'late', 'days' => abs($daysLeft)];
            } else {
                return ['status' => 'remaining', 'days' => $daysLeft];
            }
        }

        return null;
    }

    public function render()
    {
        return view('livewire.books.detail-peminjaman')->layout('layouts.user');
    }
} 