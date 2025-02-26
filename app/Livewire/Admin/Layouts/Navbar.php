<?php

namespace App\Livewire\Admin\Layouts;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Navbar extends Component
{
    protected $listeners = ['toggleSidebar'];

    public $isProfileDropdownOpen = false;

    
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