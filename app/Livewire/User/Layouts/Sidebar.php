<?php

namespace App\Livewire\User\Layouts;

use Livewire\Component;

class Sidebar extends Component
{
    public $menuItems = [
        ['icon' => 'home', 'label' => 'Beranda', 'path' => '/'],
        ['icon' => 'book', 'label' => 'Buku', 'path' => '/buku'],
        ['icon' => 'bookmark', 'label' => 'Bookmark', 'path' => '/bookmark'],
        ['icon' => 'heart', 'label' => 'Favorit', 'path' => '/favorit'],
        ['icon' => 'grid', 'label' => 'Koleksi', 'path' => '/koleksi'],
    ];
    
    public function render()
    {
        return view('livewire.user.layouts.sidebar');
    }
}
