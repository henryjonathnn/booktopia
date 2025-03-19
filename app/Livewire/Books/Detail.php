<?php

namespace App\Livewire\Books;

use App\Models\Buku;
use App\Models\Rating;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class Detail extends Component
{
    public $book;
    public $isBookmarked = false;
    public $relatedBooks = [];

    public $showModal = false;
    public $modalPhoto = '';

    public function mount($slug)
    {
        // Parse slug untuk mendapatkan ID
        $id = explode('-', $slug);
        $id = end($id);

        $this->book = Buku::with(['ratings', 'sukas'])->find($id);

        if (!$this->book || $slug !== self::generateSlug($this->book)) {
            abort(404);
        }
        
        // Calculate average rating from all ratings
        $this->calculateAverageRating();
        
        // Ambil buku terkait berdasarkan kategori
        $this->relatedBooks = Buku::where('kategori', $this->book->kategori)
            ->where('id', '!=', $this->book->id)
            ->limit(5)
            ->get();

        // Calculate average rating for each related book
        foreach ($this->relatedBooks as $relatedBook) {
            $this->calculateBookRating($relatedBook);
        }

        if (Auth::check()) {
            $this->isBookmarked = Auth::user()->bookmarks()
                ->where('id_buku', $this->book->id)
                ->exists();
        }
    }

    public function showPhotoModal($photo)
    {
        $this->modalPhoto = $photo;
        $this->showModal = true;
    }
    
    // Method untuk menyembunyikan modal foto
    public function hidePhotoModal()
    {
        $this->showModal = false;
        $this->modalPhoto = '';
    }


    // Helper method to calculate average rating
    private function calculateAverageRating()
    {
        // Get all ratings for this book
        $ratings = $this->book->ratings;
        
        if ($ratings->count() > 0) {
            // Calculate the average of all ratings
            $this->book->average_rating = $ratings->avg('rating');
        } else {
            // If no ratings, set to 0
            $this->book->average_rating = 0;
        }
    }
    
    // Helper method to calculate average rating for a related book
    private function calculateBookRating($book)
    {
        $ratings = $book->ratings()->get();
        
        if ($ratings->count() > 0) {
            $book->average_rating = $ratings->avg('rating');
        } else {
            $book->average_rating = 0;
        }
    }

    // Helper method untuk generate slug
    public static function generateSlug($book)
    {
        return Str::slug($book->judul) . '-' . $book->id;
    }

    public function toggleBookmark()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        if ($this->isBookmarked) {
            Auth::user()->bookmarks()->where('id_buku', $this->book->id)->delete();
        } else {
            Auth::user()->bookmarks()->create(['id_buku' => $this->book->id]);
        }

        $this->isBookmarked = !$this->isBookmarked;
    }

    public function createPeminjamanToken()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // Generate token yang berisi ID buku dan waktu kadaluarsa (1 jam)
        $token = base64_encode(json_encode([
            'book_id' => $this->book->id,
            'expiry' => now()->addHour()->toIso8601String()
        ]));

        return redirect()->route('peminjaman.create', ['token' => $token]);
    }

    public function render()
    {
        // Load ratings with pagination and eager load user relationship
        $bookRatings = Rating::where('id_buku', $this->book->id)
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(5);
            
        return view('livewire.books.detail', [
            'bookRatings' => $bookRatings
        ])->layout('layouts.user');
    }
}