<?php

namespace App\Livewire\User\Layouts;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use App\Models\Buku;
use App\Models\Notifikasi;

class Navbar extends Component
{
    public $isMobileMenuOpen = false;
    public $isProfileDropdownOpen = false;
    public $isNotifikasiOpen = false;
    public $unreadCount = 0;
    public $notifikasi = [];
    public $search = '';
    public $searchResults = [];
    public $isNotifikasiModalOpen = false;
    public $selectedNotifikasiDetail = '';

    protected $listeners = ['clickedOutside' => 'closeSearchResults'];

    public function mount()
    {
        $this->fetchNotifikasi();
    }

    public function updatedSearch($value)
    {
        if (strlen(trim($value)) >= 2) {
            $this->searchResults = Buku::where(function($query) use ($value) {
                $query->where('judul', 'like', '%' . $value . '%')
                      ->orWhere('penulis', 'like', '%' . $value . '%')
                      ->orWhere('kategori', 'like', '%' . $value . '%');
            })
            ->limit(5)
            ->get();
        } else {
            $this->searchResults = [];
        }
    }

    public function closeSearchResults()
    {
        $this->searchResults = [];
    }

    public function viewAllResults()
    {
        if (trim($this->search) !== '') {
            return redirect()->route('books', ['search' => $this->search]);
        }
    }

    public function viewBook($id)
    {
        $book = Buku::find($id);
        if ($book) {
            $slug = \App\Livewire\Books\Detail::generateSlug($book);
            return redirect()->route('buku.detail', ['slug' => $slug]);
        }
        return redirect()->route('buku');
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
        $this->notifikasi = Notifikasi::where('id_user', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get(['id', 'message', 'is_read'])
            ->toArray();
        $this->unreadCount = collect($this->notifikasi)->where('is_read', false)->count();
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

    public function openNotifikasiModal($id)
    {
        $notif = collect($this->notifikasi)->firstWhere('id', $id);
        if ($notif) {
            $this->selectedNotifikasiDetail = $notif['message'];
            $this->isNotifikasiModalOpen = true;
        }
    }

    public function closeNotifikasiModal()
    {
        $this->isNotifikasiModalOpen = false;
        $this->selectedNotifikasiDetail = '';
    }

    public function render()
    {
        $user = Auth::user();
        return view('livewire.user.layouts.navbar', compact('user'));
    }
}
