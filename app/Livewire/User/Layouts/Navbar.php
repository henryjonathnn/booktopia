<?php

namespace App\Livewire\User\Layouts;

use App\Models\Notifikasi;
use App\Models\Book;
use Livewire\Component;

class Navbar extends Component
{
    public $showNotifikasi = false;
    public $showDetailModal = false;
    public $selectedNotifikasi = null;
    public $unreadCount;
    public $notifikasi;
    public $search = '';
    public $searchResults = [];
    public $user;
    public $isProfileDropdownOpen = false;

    protected $listeners = [
        'refreshNotifikasi' => 'refreshNotifikasi',
        'searchUpdated' => 'handleSearch'
    ];

    public function mount()
    {
        $this->user = auth()->user();
        $this->refreshNotifikasi();
    }

    public function updatedSearch()
    {
        if (strlen(trim($this->search)) >= 2) {
            $this->searchResults = Book::where('judul', 'like', '%' . $this->search . '%')
                ->orWhere('penulis', 'like', '%' . $this->search . '%')
                ->orWhere('kategori', 'like', '%' . $this->search . '%')
                ->take(5)
                ->get();
        } else {
            $this->searchResults = [];
        }
    }

    public function viewBook($bookId)
    {
        return redirect()->route('books.show', $bookId);
    }

    public function toggleProfileDropdown()
    {
        $this->isProfileDropdownOpen = !$this->isProfileDropdownOpen;
    }

    public function logout()
    {
        auth()->logout();
        return redirect()->route('login');
    }

    public function toggleNotifikasi()
    {
        $this->showNotifikasi = !$this->showNotifikasi;
        if ($this->showNotifikasi) {
            $this->showDetailModal = false;
            $this->selectedNotifikasi = null;
            $this->isProfileDropdownOpen = false;
        }
    }

    public function refreshNotifikasi()
    {
        if ($this->user) {
            $this->notifikasi = $this->user->notifikasi()->latest()->take(5)->get();
            $this->unreadCount = $this->user->notifikasi()->where('is_read', false)->count();
        }
    }

    public function showDetail($notifId)
    {
        $this->selectedNotifikasi = Notifikasi::with('peminjaman.buku')->find($notifId);
        if (!$this->selectedNotifikasi->is_read) {
            $this->selectedNotifikasi->markAsRead();
            $this->refreshNotifikasi();
        }
        $this->showDetailModal = true;
        $this->showNotifikasi = false;
    }

    public function closeDetail()
    {
        $this->selectedNotifikasi = null;
        $this->showDetailModal = false;
    }

    public function markAllAsRead()
    {
        $this->user->notifikasi()->where('is_read', false)->update(['is_read' => true]);
        $this->refreshNotifikasi();
    }

    public function render()
    {
        return view('livewire.user.layouts.navbar');
    }
}