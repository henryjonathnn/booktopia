<?php

namespace App\Livewire\Books;

use App\Models\Buku;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $selectedCategory = '';
    public $sortBy = 'newest';
    public $categories = [
        'FIKSI', 'NON-FIKSI', 'SAINS', 'TEKNOLOGI',
        'SEJARAH', 'SASTRA', 'KOMIK', 'LAINNYA'
    ];

    protected $queryString = [
        'selectedCategory' => ['except' => ''],
        'sortBy' => ['except' => 'newest']
    ];

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

        $books = $query->paginate(24);

        return view('livewire.books.index', [
            'books' => $books
        ])->layout('layouts.user');
    }
} 