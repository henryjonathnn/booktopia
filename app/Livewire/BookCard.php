<?php

namespace App\Livewire;

use Livewire\Component;

/**
 * Component untuk menampilkan card buku
 * Digunakan di berbagai halaman seperti:
 * - Halaman utama
 * - Hasil pencarian
 * - Bookmark
 * - Favorite
 */
class BookCard extends Component
{
    public $book;
    public $showRating = false;
    public $rightLabel = 'Peminjam';
    public $isBookmarked = false;
    public $isLiked = false;

    public function mount($book, $showRating = false, $rightLabel = 'Peminjam')
    {
        $this->book = $book->loadCount(['sukas', 'bookmarks']);
        $this->showRating = $showRating;
        $this->rightLabel = $rightLabel;
        
        if (auth()->check()) {
            $this->isLiked = $this->book->sukas()
                ->where('id_user', auth()->id())
                ->exists();
            
            $this->isBookmarked = $this->book->bookmarks()
                ->where('id_user', auth()->id())
                ->exists();
        }
    }

    /**
     * Menangani aksi bookmark/unbookmark buku
     * Update status di database
     */
    public function toggleBookmark()
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        if ($this->isBookmarked) {
            $this->book->bookmarks()
                ->where('id_user', auth()->id())
                ->delete();
        } else {
            $this->book->bookmarks()->create([
                'id_user' => auth()->id()
            ]);
        }

        $this->isBookmarked = !$this->isBookmarked;
        $this->book->refresh();
        $this->book->loadCount('bookmarks');
    }

    /**
     * Menangani aksi like/unlike buku
     * Update counter dan status di database
     */
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
        // Refresh the book data to get updated sukas count
        $this->book->refresh();
        $this->book->loadCount('sukas');
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