<?php

namespace App\Livewire;

use Livewire\Component;

class BookCard extends Component
{
    public $book;
    public $showRating = false;
    public $rightLabel = 'Peminjam';
    public $isBookmarked = false;
    public $isLiked = false;

    public function mount($book, $showRating = false, $rightLabel = 'Peminjam')
    {
        $this->book = $book;
        $this->showRating = $showRating;
        $this->rightLabel = $rightLabel;
        
        // Check if current user has liked this book
        if (auth()->check()) {
            $this->isLiked = $this->book->sukas()
                ->where('id_user', auth()->id())
                ->exists();
        }
    }

    public function toggleBookmark()
    {
        // Implement bookmark logic here
        $this->isBookmarked = !$this->isBookmarked;
    }

    public function toggleLike()
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        if ($this->isLiked) {
            // Unlike
            $this->book->sukas()
                ->where('id_user', auth()->id())
                ->delete();
        } else {
            // Like
            $this->book->sukas()->create([
                'id_user' => auth()->id()
            ]);
        }

        $this->isLiked = !$this->isLiked;
        $this->book->refresh();
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