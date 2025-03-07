<?php

namespace App\Livewire\Book;

use Livewire\Component;

class Index extends Component
{
    public function render()
    {
        return view('livewire.book.index')->layout('layouts.user', [
            'title' => 'Koleksi Buku - Booktopia'
        ]);
    }
}