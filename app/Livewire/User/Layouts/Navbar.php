<?php

namespace App\Livewire\User\Layouts;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Navbar extends Component
{
    public $isMobileMenuOpen = false;
    public $isProfileDropdownOpen = false;
    public $isNotifikasiOpen = false;
    public $unreadCount = 0;
    public $notifikasi = [];
    public $search = '';

    public function mount()
    {
        $this->fetchNotifikasi();
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

    public function search()
    {
        if (trim($this->search) !== '') {
            return redirect()->route('books', ['search' => $this->search]);
        }
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
