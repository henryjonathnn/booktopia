<?php

namespace App\Livewire\Books;

use App\Models\Buku;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Detail extends Component
{
    public $book;
    public $isBookmarked = false;
    public $userRating = 0;
    public $showRatingModal = false;
    public $relatedBooks = [];

    public function mount($id)
    {
        $this->book = Buku::with(['ratings', 'sukas'])->findOrFail($id);
        
        // Ambil buku terkait berdasarkan kategori
        $this->relatedBooks = Buku::where('kategori', $this->book->kategori)
            ->where('id', '!=', $this->book->id)
            ->limit(5)
            ->get();

        if (Auth::check()) {
            $this->isBookmarked = Auth::user()->bookmarks()
                ->where('id_buku', $this->book->id)
                ->exists();
            
            $userRating = Auth::user()->ratings()
                ->where('id_buku', $this->book->id)
                ->first();
            
            $this->userRating = $userRating ? $userRating->rating : 0;
        }
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

    public function submitRating()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        Auth::user()->ratings()->updateOrCreate(
            ['id_buku' => $this->book->id],
            ['rating' => $this->userRating]
        );

        $this->showRatingModal = false;
        $this->book->refresh();
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
        return view('livewire.books.detail')->layout('layouts.user');
    }
} 