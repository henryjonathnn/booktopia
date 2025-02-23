<?php
namespace App\Livewire;

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
        $query = Buku::query();
        
        // Apply sorting based on sortType
        switch ($this->sortType) {
            case 'favorite':
                $query->withCount('sukas')
                      ->orderBy('sukas_count', 'desc');
                break;
            case 'rating':
                // Using a subquery approach to avoid GROUP BY issues
                $query->select('bukus.*')
                      ->selectSub(
                          function($query) {
                              $query->from('ratings')
                                   ->whereColumn('ratings.id_buku', 'bukus.id')
                                   ->selectRaw('COALESCE(AVG(rating), 0)');
                          },
                          'average_rating'
                      )
                      ->orderBy('average_rating', 'desc');
                break;
            default:
                $query->latest();
        }

        return view('livewire.book-section', [
            'books' => $query->paginate(10)
        ]);
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