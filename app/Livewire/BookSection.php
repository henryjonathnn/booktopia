<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Cache;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Buku;
use Illuminate\Support\Facades\DB;

class BookSection extends Component
{
    use WithPagination;

    public $title;
    public $subtitle;
    public $badgeText;
    public $badgeColor = 'purple';
    public $sortType = 'newest';
    public $showRating = false;
    public $rightLabel = 'Peminjam';
    public $showFilterModal = false;
    public $showAllModal = false;
    public $selectedBook = null;

    protected $paginationTheme = 'tailwind';

    protected $listeners = ['showBookDetail'];

    public function mount($title, $subtitle, $badgeText, $badgeColor = 'purple', $sortType = 'newest', $showRating = false, $rightLabel = 'Peminjam')
    {
        $this->title = $title;
        $this->subtitle = $subtitle;
        $this->badgeText = $badgeText;
        $this->badgeColor = $badgeColor;
        $this->sortType = $sortType;
        $this->showRating = $showRating;
        $this->rightLabel = $rightLabel;
    }

    public function showBookDetail($bookId)
    {
        $this->selectedBook = Buku::find($bookId);
    }

    public function updatingShowFilterModal()
    {
        $this->resetPage();
    }

    public function render()
    {
        $cacheKey = "books_{$this->sortType}_page_";
        
        $books = Cache::remember($cacheKey, now()->addMinutes(5), function () {
            $query = Buku::query();
            
            switch ($this->sortType) {
                case 'favorite':
                    return $query->withCount('sukas')
                        ->orderBy('sukas_count', 'desc')
                        ->paginate(10);
                case 'rating':
                    return $query->select('bukus.*')
                        ->selectSub(
                            function($query) {
                                $query->from('ratings')
                                    ->whereColumn('ratings.id_buku', 'bukus.id')
                                    ->selectRaw('COALESCE(AVG(rating), 0)');
                            },
                            'average_rating'
                        )
                        ->orderBy('average_rating', 'desc')
                        ->paginate(10);
                default:
                    return $query->latest()->paginate(10);
            }
        });

        return view('livewire.book-section', compact('books'));
    }

    public function toggleFilterModal()
    {
        $this->showFilterModal = !$this->showFilterModal;
    }

    public function toggleAllModal()
    {
        $this->showAllModal = !$this->showAllModal;
    }
}
