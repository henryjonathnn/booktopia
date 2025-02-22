<?php

namespace App\Livewire;

use Livewire\Component;

class Hero extends Component
{
    public $dynamicTexts = ['buku favoritmu', 'genre favoritmu', 'penulis idolamu'];
    public $currentTextIndex = 0;

    public function render()
    {
        return view('livewire.hero');
    }
}
