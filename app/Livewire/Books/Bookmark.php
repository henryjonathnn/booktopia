<?php

namespace App\Livewire\Books;

use App\Models\Buku;
use Livewire\Component;
use Livewire\WithPagination;

class Bookmark extends Component
{
    use WithPagination;

    public $search = '';
    public $sortBy = 'newest';

    protected $queryString = [
        'search' => ['except' => ''],
        'sortBy' => ['except' => 'newest']
    ];

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedSortBy()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = Buku::whereHas('bookmarks', function($query) {
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
            case 'oldest':
                $query->orderBy('created_at');
                break;
            case 'title':
                $query->orderBy('judul');
                break;
            case 'author':
                $query->orderBy('penulis');
                break;
            default:
                $query->latest();
        }

        return view('livewire.books.bookmark', [
            'books' => $query->paginate(12)
        ])->layout('layouts.user');
    }
} 