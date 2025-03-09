<?php

namespace App\Livewire\User\Layouts;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use App\Models\Buku;

class Navbar extends Component
{
    public $isMobileMenuOpen = false;
    public $isProfileDropdownOpen = false;
    public $isNotifikasiOpen = false;
    public $unreadCount = 0;
    public $notifikasi = [];
    public $search = '';
    public $searchResults = [];
    public $showSearchResults = false;

    protected $listeners = ['clickedOutside' => 'closeSearchResults'];

    public function mount()
    {
        $this->fetchNotifikasi();
    }

    public function updatedSearch()
    {
        if (strlen(trim($this->search)) >= 2) {
            $this->searchResults = Buku::where('judul', 'like', '%' . $this->search . '%')
                ->orWhere('penulis', 'like', '%' . $this->search . '%')
                ->orWhere('kategori', 'like', '%' . $this->search . '%')
                ->limit(5)
                ->get();
            $this->showSearchResults = true;
        } else {
            $this->searchResults = [];
            $this->showSearchResults = false;
        }
    }

    public function closeSearchResults()
    {
        $this->showSearchResults = false;
    }

    public function viewAllResults()
    {
        if (trim($this->search) !== '') {
            return redirect()->route('books', ['search' => $this->search]);
        }
    }

    public function viewBook($id)
    {
        $this->showSearchResults = false;
        $this->search = '';
        // Redirect to book detail page
        return redirect()->route('book.detail', ['id' => $id]);
    }

    public function toggleMobileMenu()
    {
        $this->isMobileMenuOpen = !$this->isMobileMenuOpen;
    }

    public function toggleProfileDropdown()
    {
        $this->isProfileDropdownOpen = !$this->isProfileDropdownOpen;
    }

    public function toggleNotifikasi()
    {
        $this->isNotifikasiOpen = !$this->isNotifikasiOpen;
    }

    public function fetchNotifikasi()
    {
        // Fetch notifications logic here
        $this->notifikasi = [
            ['id' => 1, 'message' => 'Notifikasi 1', 'isRead' => false],
            ['id' => 2, 'message' => 'Notifikasi 2', 'isRead' => true],
        ];
        $this->unreadCount = collect($this->notifikasi)->where('isRead', false)->count();
    }

    public function markAsRead($id)
    {
        foreach ($this->notifikasi as &$notif) {
            if ($notif['id'] == $id) {
                $notif['isRead'] = true;
                break;
            }
        }
        $this->unreadCount = collect($this->notifikasi)->where('isRead', false)->count();
    }

    public function logout()
    {
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();
        return redirect('/');
    }

    public function render()
    {
        $user = Auth::user();
        return view('livewire.user.layouts.navbar', compact('user'));
    }
}
