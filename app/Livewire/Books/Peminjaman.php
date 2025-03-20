<?php

namespace App\Livewire\Books;

use App\Models\Peminjaman as ModelsPeminjaman;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class Peminjaman extends Component
{
    use WithPagination;

    public $statusFilter = '';
    public $search = '';
    public $showReturnConfirmation = false;
    public $showRatingModal = false;
    public $showPayDendaModal = false;
    public $selectedPeminjaman = null;
    public $rating = 0;
    public $review = '';

    protected $queryString = [
        'statusFilter' => ['except' => ''],
        'search' => ['except' => '']
    ];

    public $statusColors = [
        'PENDING' => 'yellow',
        'DIPROSES' => 'blue',
        'DIKIRIM' => 'purple',
        'DIPINJAM' => 'green',
        'TERLAMBAT' => 'red',
        'DIKEMBALIKAN' => 'gray',
        'DITOLAK' => 'red'
    ];

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedStatusFilter()
    {
        $this->resetPage();
    }

    public function showReturnConfirmationModal($peminjamanId)
    {
        $this->selectedPeminjaman = $peminjamanId;
        $this->showReturnConfirmation = true;
        $this->dispatch('open-return-modal');
    }

    public function closeReturnModal()
    {
        $this->showReturnConfirmation = false;
        $this->selectedPeminjaman = null;
        $this->dispatch('close-return-modal');
    }

    public function returnBook()
    {
        try {
            $peminjaman = ModelsPeminjaman::find($this->selectedPeminjaman);
            if ($peminjaman && $peminjaman->status === 'DIPINJAM') {
                $peminjaman->status = 'DIKEMBALIKAN';
                $peminjaman->tanggal_pengembalian = now();
                $peminjaman->save();
                
                session()->flash('success', 'Buku berhasil dikembalikan');
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan saat mengembalikan buku');
        }
        
        $this->closeReturnModal();
    }

    public function getStatusList()
    {
        return [
            'PENDING' => 'Menunggu',
            'DIPROSES' => 'Diproses',
            'DIKIRIM' => 'Dikirim',
            'DIPINJAM' => 'Dipinjam',
            'TERLAMBAT' => 'Terlambat',
            'DIKEMBALIKAN' => 'Selesai',
            'DITOLAK' => 'Ditolak'
        ];
    }

    public function render()
    {
        $peminjamans = ModelsPeminjaman::with(['buku'])
            ->where('id_user', Auth::id())
            ->when($this->statusFilter, function($query) {
                return $query->where('status', $this->statusFilter);
            })
            ->when($this->search, function($query) {
                return $query->whereHas('buku', function($q) {
                    $q->where('judul', 'like', '%' . $this->search . '%')
                      ->orWhere('penulis', 'like', '%' . $this->search . '%');
                });
            })
            ->latest()
            ->paginate(10);

        return view('livewire.books.peminjaman', [
            'peminjamans' => $peminjamans,
            'statusList' => $this->getStatusList()
        ])->layout('layouts.user');
    }
} 