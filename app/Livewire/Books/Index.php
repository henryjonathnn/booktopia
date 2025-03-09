<?php

namespace App\Livewire\Books;

use App\Models\Buku;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $selectedCategory = '';
    public $sortBy = 'newest';
    public $categories = [
        'FIKSI', 'NON-FIKSI', 'SAINS', 'TEKNOLOGI',
        'SEJARAH', 'SASTRA', 'KOMIK', 'LAINNYA'
    ];

    protected $queryString = [
        'search' => ['except' => ''],
        'selectedCategory' => ['except' => ''],
        'sortBy' => ['except' => 'newest']
    ];

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedSelectedCategory()
    {
        $this->resetPage();
    }

    public function updatedSortBy()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = Buku::query()
            ->when($this->search, function ($query) {
                $query->where('judul', 'like', '%' . $this->search . '%')
                    ->orWhere('penulis', 'like', '%' . $this->search . '%');
            })
            ->when($this->selectedCategory, function ($query) {
                $query->where('kategori', $this->selectedCategory);
            });

        switch ($this->sortBy) {
            case 'rating':
                $query->withAvg('ratings', 'rating')
                    ->orderByDesc('ratings_avg_rating');
                break;
            case 'favorite':
                $query->withCount('sukas')
                    ->orderByDesc('sukas_count');
                break;
            default:
                $query->latest();
        }

        $books = $query->paginate(20);

        return view('livewire.books.index', [
            'books' => $books
        ])->layout('layouts.user');
    }
} 