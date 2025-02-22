<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;

class BookSection extends Component {
    use WithPagination;

    public $title;
    public $subtitle;
    public $badgeText;
    public $badgeColor;
    public $sortType;
    public $showRating;
    public $rightLabel;
    public $currentPage = 1;
    public $showFilterModal = false;

    public function mount($title, $subtitle, $badgeText, $badgeColor, $sortType, $showRating, $rightLabel)
    {
        $this->title = $title;
        $this->subtitle = $subtitle;
        $this->badgeText = $badgeText;
        $this->badgeColor = $badgeColor;
        $this->sortType = $sortType;
        $this->showRating = $showRating;
        $this->rightLabel = $rightLabel;
    }

    public function render()
    {
        // Sample data - replace with your actual data fetching logic
        $books = [
            [
                'id' => 1,
                'judul' => 'Dilan 1991',
                'penulis' => 'Pidi Baiq',
                'cover_img' => '/storage/books/dilan.jpg',
                'rating' => 4.8,
                'kategori' => 'Romance',
                'peminjam' => 10000
            ],
            // Add more sample books
        ];

        return view('livewire.book-section', [
            'books' => $books
        ]);
    }

    public function nextPage()
    {
        $this->currentPage++;
    }

    public function previousPage()
    {
        if ($this->currentPage > 1) {
            $this->currentPage--;
        }
    }

    public function toggleFilterModal()
    {
        $this->showFilterModal = !$this->showFilterModal;
    }
}
