<?php

namespace App\Livewire\Book;

use App\Models\Buku;
use Livewire\Component;
use Livewire\WithPagination;

class BookFilter extends Component
{
    use WithPagination;
    
    protected $paginationTheme = 'tailwind';
    
    public $selectedCategory = 'all';
    public $sortOption = 'newest';
    public $selectedBook = null;
    
    protected $queryString = [
        'selectedCategory' => ['except' => 'all'],
        'sortOption' => ['except' => 'newest'],
        'page' => ['except' => 1],
    ];
    
    protected $listeners = ['showBookDetail'];
    
    public function showBookDetail($bookId)
    {
        $this->selectedBook = Buku::find($bookId);
    }
    
    public function setCategory($category)
    {
        $this->selectedCategory = $category;
        $this->resetPage();
    }
    
    public function updatedSortOption()
    {
        $this->resetPage();
    }
    
    public function render()
    {
        // Get all unique categories
        $categories = Buku::distinct('kategori')
            ->whereNotNull('kategori')
            ->pluck('kategori')
            ->toArray();
        
        // Build query with filters
        $query = Buku::query();
        
        // Apply category filter
        if ($this->selectedCategory !== 'all') {
            $query->where('kategori', $this->selectedCategory);
        }
        
        // Apply sorting
        switch ($this->sortOption) {
            case 'title':
                $query->orderBy('judul', 'asc');
                break;
            case 'rating':
                $query->select('bukus.*')
                    ->selectSub(
                        function($q) {
                            $q->from('ratings')
                                ->whereColumn('ratings.id_buku', 'bukus.id')
                                ->selectRaw('COALESCE(AVG(rating), 0)');
                        },
                        'average_rating'
                    )
                    ->orderBy('average_rating', 'desc');
                break;
            case 'popular':
                $query->withCount('peminjamans')
                    ->orderBy('peminjamans_count', 'desc');
                break;
            case 'newest':
            default:
                $query->latest();
                break;
        }
        
        // Get paginated results
        $books = $query->paginate(15);
        
        return view('livewire.book.book-filter', [
            'books' => $books,
            'categories' => $categories,
        ]);
    }
}