<?php

namespace App\Livewire\Admin\Layouts;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use App\Models\Notifikasi;

class Navbar extends Component
{
    public $isProfileDropdownOpen = false;
    public $isNotifikasiOpen = false;
    public $unreadCount = 0;
    public $notifikasi = [];

    protected $listeners = ['toggleSidebar'];

    public function mount()
    {
        $this->loadNotifikasi();
    }

    public function loadNotifikasi()
    {
        if (Auth::check()) {
            $this->notifikasi = Notifikasi::where('id_user', Auth::id())
                ->with(['peminjaman.user', 'peminjaman.buku'])
                ->orderBy('created_at', 'desc')
                ->take(10)
                ->get()
                ->map(function ($notif) {
                    return [
                        'id' => $notif->id,
                        'message' => $notif->message,
                        'is_read' => $notif->is_read,
                        'created_at' => $notif->created_at,
                        'type' => 'peminjaman'
                    ];
                })->toArray();

            $this->unreadCount = Notifikasi::where('id_user', Auth::id())
                ->where('is_read', false)
                ->count();
        }
    }

    public function toggleNotifikasi()
    {
        $this->isNotifikasiOpen = !$this->isNotifikasiOpen;
        if ($this->isNotifikasiOpen) {
            $this->loadNotifikasi();
        }
    }

    public function markAsRead($id)
    {
        $notif = Notifikasi::find($id);
        if ($notif && $notif->id_user == Auth::id()) {
            $notif->is_read = true;
            $notif->save();
            
            $this->loadNotifikasi();
        }
    }

    public function toggleSidebar()
    {
        $this->dispatch('toggleSidebar');
    }

    public function logout()
    {
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();
        
        // Use the same approach as in the user navbar
        return redirect('/');
        
        // Alternative approaches if the above doesn't work:
        // return redirect()->route('home');
        // Or for Livewire 3:
        // return $this->redirect('/', navigate: false);
    }
    
    public function render()
    {
        $user = Auth::user();
        return view('livewire.admin.layouts.navbar', compact('user'));
    }
}