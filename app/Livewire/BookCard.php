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
    }

    public function toggleBookmark()
    {
        // Implement bookmark logic here
        $this->isBookmarked = !$this->isBookmarked;
    }

    public function showBookDetail()
    {
        $slug = \App\Livewire\Books\Detail::generateSlug($this->book);
        return $this->redirect(route('buku.detail', ['slug' => $slug]));
    }

    public function render()
    {
        return view('livewire.book-card');
    }
}