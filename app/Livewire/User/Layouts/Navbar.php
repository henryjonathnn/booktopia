<?php

namespace App\Livewire\User\Layouts;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use App\Models\Buku;
use App\Models\Notifikasi;
use Carbon\Carbon;

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
    public $selectedNotifikasi = null;
    public $showNotifikasi = false;
    public $showDetailModal = false;

    protected $listeners = ['clickedOutside' => 'closeSearchResults'];

    public function mount()
    {
        $this->refreshNotifikasi();
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
        $this->showNotifikasi = !$this->showNotifikasi;
        if ($this->showNotifikasi) {
            $this->showDetailModal = false;
            $this->selectedNotifikasi = null;
        }
    }

    public function refreshNotifikasi()
    {
        $this->notifikasi = auth()->user()->notifikasi()->latest()->take(5)->get();
        $this->unreadCount = auth()->user()->notifikasi()->where('is_read', false)->count();
    }

    public function markAsRead($id)
    {
        $notif = Notifikasi::find($id);
        if ($notif && $notif->id_user == Auth::id()) {
            $notif->is_read = true;
            $notif->save();
            
            // Update local array
            foreach ($this->notifikasi as &$n) {
                if ($n['id'] == $id) {
                    $n['is_read'] = true;
                    break;
                }
            }
            
            $this->unreadCount = Notifikasi::where('id_user', Auth::id())
                ->where('is_read', false)
                ->count();
        }
    }
    
    public function markAllAsRead()
    {
        auth()->user()->notifikasi()->where('is_read', false)->update(['is_read' => true]);
        $this->refreshNotifikasi();
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
        $notif = Notifikasi::find($id);
        if ($notif && $notif->id_user == Auth::id()) {
            $this->selectedNotifikasi = [
                'id' => $notif->id,
                'message' => $notif->message,
                'is_read' => $notif->is_read,
                'created_at' => $notif->created_at
            ];
            
            $this->selectedNotifikasiDetail = $notif->message;
            $this->isNotifikasiModalOpen = true;
            
            // Mark as read when opened
            if (!$notif->is_read) {
                $this->markAsRead($id);
            }
        }
    }

    public function closeNotifikasiModal()
    {
        $this->isNotifikasiModalOpen = false;
        $this->selectedNotifikasiDetail = '';
        $this->selectedNotifikasi = null;
    }

    public function showDetail($notifId)
    {
        $this->selectedNotifikasi = Notifikasi::with('peminjaman.buku')->find($notifId);
        if (!$this->selectedNotifikasi->is_read) {
            $this->selectedNotifikasi->markAsRead();
            $this->refreshNotifikasi();
        }
    }

    public function closeDetail()
    {
        $this->selectedNotifikasi = null;
    }

    public function render()
    {
        $user = Auth::user();
        return view('livewire.user.layouts.navbar', compact('user'));
    }
}