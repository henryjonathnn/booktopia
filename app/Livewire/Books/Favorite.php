<?php

namespace App\Livewire\Books;

use App\Models\Buku;
use Livewire\Component;
use Livewire\WithPagination;

class Favorite extends Component
{
    use WithPagination;

    public $search = '';
    public $sortBy = 'newest';

    protected $queryString = [
        'search' => ['except' => ''],
        'sortBy' => ['except' => 'newest']
    ];

    public function render()
    {
        $query = Buku::whereHas('sukas', function($query) {
            $query->where('id_user', auth()->id());
        })
        ->when($this->search, function($query) {
            $query->where(function($q) {
                $q->where('judul', 'like', '%' . $this->search . '%')
                  ->orWhere('penulis', 'like', '%' . $this->search . '%')
                  ->orWhere('kategori', 'like', '%' . $this->search . '%');
            });
        });

        switch ($this->sortBy) {
            case 'rating':
                $query->withAvg('ratings', 'rating')
                    ->orderByDesc('ratings_avg_rating');
                break;
            case 'oldest':
                $query->orderBy('created_at');
                break;
            default:
                $query->latest();
        }

        return view('livewire.books.favorite', [
            'books' => $query->paginate(12)
        ])->layout('layouts.user');
    }
} 