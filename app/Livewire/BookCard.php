<?php

namespace App\Livewire;

use Livewire\Component;

class BookCard extends Component
{
    public $book;
    public $showRating = false;
    public $rightLabel = 'Peminjam';
    public $isBookmarked = false;

    public function mount($book, $showRating = false, $rightLabel = 'Peminjam')
    {
        $this->book = $book;
        $this->showRating = $showRating;
        $this->rightLabel = $rightLabel;
        // You might want to check if the book is bookmarked by the current user
        // $this->isBookmarked = auth()->user()->hasBookmarked($book->id);
    }

    public function toggleBookmark()
    {
        // Implement bookmark logic here
        $this->isBookmarked = !$this->isBookmarked;
    }

    public function showBookDetail()
    {
        $this->emit('showBookDetail', $this->book->id);
    }

    public function render()
    {
        return view('livewire.book-card');
    }
}