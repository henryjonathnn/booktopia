<?php

namespace App\Livewire\Books;

use App\Models\Peminjaman;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\DB;
use App\Models\Rating;
use App\Models\Dompet;

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
    public $showPayDendaModal = false;

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
            
            if ($peminjaman->status !== 'DIPINJAM') {
                session()->flash('alert', [
                    'type' => 'error',
                    'message' => 'Status peminjaman tidak valid untuk dikembalikan!'
                ]);
                return;
            }

            // Cek keterlambatan
            if (now() > $peminjaman->tgl_kembali_rencana) {
                $daysLate = now()->diffInDays($peminjaman->tgl_kembali_rencana);
                $totalDenda = $peminjaman->buku->denda_harian * $daysLate;
                
                $peminjaman->update([
                    'status' => 'TERLAMBAT',
                    'total_denda' => $totalDenda,
                    'last_denda_calculation' => now()
                ]);

                // Kirim notifikasi keterlambatan
                $peminjaman->user->notify(new \App\Notifications\PeminjamanTerlambat($peminjaman));

                session()->flash('alert', [
                    'type' => 'error',
                    'message' => "Buku terlambat dikembalikan. Total denda: Rp " . number_format($totalDenda, 0, ',', '.')
                ]);

                $this->showPayDendaModal = true;
            } else {
                // Proses pengembalian normal
                $peminjaman->buku->increment('stock');
                $peminjaman->update([
                    'status' => 'DIKEMBALIKAN',
                    'tgl_kembali_aktual' => now()
                ]);

                session()->flash('alert', [
                    'type' => 'success',
                    'message' => 'Buku berhasil dikembalikan!'
                ]);

                $this->showRatingModal = true;
            }

            DB::commit();
            
            // Refresh data
            $this->peminjaman = $peminjaman->fresh();

        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('alert', [
                'type' => 'error',
                'message' => 'Gagal mengembalikan buku: ' . $e->getMessage()
            ]);
        }
    }

    public function payDenda()
    {
        try {
            DB::beginTransaction();

            $peminjaman = $this->peminjaman;
            
            // Update status pembayaran denda
            $peminjaman->update([
                'is_denda_paid' => true,
                'status' => 'DIKEMBALIKAN',
                'tgl_kembali_aktual' => now()
            ]);

            // Tambah saldo ke dompet admin
            $dompet = Dompet::first();
            $dompet->tambahSaldo($peminjaman->total_denda);

            // Kembalikan stok buku
            $peminjaman->buku->increment('stock');

            DB::commit();

            $this->showPayDendaModal = false;
            $this->showRatingModal = true;

            session()->flash('alert', [
                'type' => 'success',
                'message' => 'Pembayaran denda berhasil! Silakan berikan rating untuk buku.'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('alert', [
                'type' => 'error',
                'message' => 'Gagal memproses pembayaran: ' . $e->getMessage()
            ]);
        }
    }

    public function submitRating()
    {
        $this->validate([
            'rating' => 'required|numeric|min:1|max:5',
            'komentar' => 'required|string|min:10',
            'foto' => 'nullable|image|max:2048'
        ], [
            'rating.required' => 'Rating harus diisi',
            'rating.min' => 'Rating minimal 1',
            'rating.max' => 'Rating maksimal 5',
            'komentar.required' => 'Komentar harus diisi',
            'komentar.min' => 'Komentar minimal 10 karakter',
            'foto.image' => 'File harus berupa gambar',
            'foto.max' => 'Ukuran foto maksimal 2MB'
        ]);

        try {
            DB::beginTransaction();

            $ratingData = [
                'id_user' => auth()->id(),
                'id_buku' => $this->peminjaman->buku->id,
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
            
            // Reset form
            $this->reset(['rating', 'komentar', 'foto']);

            // Tampilkan notifikasi sukses
            $this->dispatch('alert', [
                'type' => 'success',
                'message' => 'Terima kasih atas feedback Anda! Rating berhasil disimpan.'
            ]);

            // Refresh data untuk memperbarui tampilan
            $this->peminjaman = $this->peminjaman->fresh(['buku.ratings']);

        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('alert', [
                'type' => 'error',
                'message' => 'Gagal menyimpan rating: ' . $e->getMessage()
            ]);
        }
    }

    public function render()
    {
        return view('livewire.books.detail-peminjaman')->layout('layouts.user');
    }
} 