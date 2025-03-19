<?php

namespace App\Livewire\Books;

use App\Models\Peminjaman;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\DB;
use App\Models\Rating;

class DetailPeminjaman extends Component
{
    use WithFileUploads;

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
    public $showReturnConfirmation = false;
    public $showRatingModal = false;
    public $rating = 0;
    public $komentar = '';
    public $foto;

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

    public function returnPeminjaman($id)
    {
        try {
            DB::beginTransaction();
            
            $peminjaman = Peminjaman::findOrFail($id);
            
            if ($peminjaman->status !== 'DIKIRIM') {
                session()->flash('alert', [
                    'type' => 'error',
                    'message' => 'Status peminjaman tidak valid untuk dikembalikan!'
                ]);
                return;
            }

            // Tambah stok buku
            $peminjaman->buku->increment('stock');

            $peminjaman->update([
                'status' => 'DIKEMBALIKAN',
                'tanggal_pengembalian' => now()
            ]);

            DB::commit();

            session()->flash('alert', [
                'type' => 'success',
                'message' => 'Buku berhasil dikembalikan!'
            ]);

            // Show rating modal
            $this->showRatingModal = true;

        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('alert', [
                'type' => 'error',
                'message' => 'Gagal mengembalikan buku: ' . $e->getMessage()
            ]);
        }
    }

    public function submitRating()
    {
        $this->validate([
            'rating' => 'required|numeric|min:1|max:5',
            'komentar' => 'required|string|min:10',
            'foto' => 'nullable|image|max:2048'
        ]);

        try {
            DB::beginTransaction();

            $ratingData = [
                'id_user' => auth()->id(),
                'id_buku' => $this->peminjaman->id_buku,
                'rating' => $this->rating,
                'komentar' => $this->komentar
            ];

            if ($this->foto) {
                $path = $this->foto->store('ratings', 'public');
                $ratingData['url_foto'] = $path;
            }

            Rating::create($ratingData);

            DB::commit();

            $this->showRatingModal = false;
            session()->flash('alert', [
                'type' => 'success',
                'message' => 'Terima kasih atas penilaian Anda!'
            ]);
            
            // Reset form
            $this->reset(['rating', 'komentar', 'foto']);

        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('alert', [
                'type' => 'error',
                'message' => 'Terjadi kesalahan saat menyimpan rating'
            ]);
        }
    }

    public function render()
    {
        return view('livewire.books.detail-peminjaman')->layout('layouts.user');
    }
} 