<?php

namespace App\Livewire\Admin;

use App\Models\Buku;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class DataBuku extends Component
{
    use WithPagination, WithFileUploads;

    // For pagination
    protected $paginationTheme = 'tailwind';
    
    // Search & Filter Properties
    public $search = '';
    public $kategori = '';
    public $perPage = 10;
    
    // Modal control properties
    public $isModalOpen = false;
    public $isDetailModalOpen = false;
    public $confirmingBukuDeletion = false;
    public $bukuIdToDelete = null;
    
    // Form properties
    public $bukuId = null;
    public $judul = '';
    public $penulis = '';
    public $isbn = '';
    public $bukuKategori = '';
    public $deskripsi = '';
    public $stock = 1;
    public $denda_harian = 0;
    public $penerbit = '';
    public $tahun_terbit = '';
    public $coverImage = null;
    public $existingCoverImage = null;
    
    // Selected books for batch operations
    public $selectedBooks = [];
    public $selectAll = false;
    
    // Computed property for all books on current page
    public $booksOnCurrentPage = [];
    
    // Cache selected book for detail view
    public $selectedBuku = null;

    // Available categories from database
    public $categories = [];

    protected $listeners = ['refreshBooks' => '$refresh'];

    // Available categories from database
    public function getCategories()
    {
        // Ambil nilai enum dari skema database
        $enumValues = DB::select("SHOW COLUMNS FROM bukus WHERE Field = 'kategori'")[0]->Type;
        preg_match('/^enum\((.*)\)$/', $enumValues, $matches);
        
        $values = [];
        if (isset($matches[1])) {
            foreach(explode(',', $matches[1]) as $value) {
                $values[] = trim($value, "'");
            }
        }
        
        return $values;
    }

    public function mount()
    {
        $this->resetPage();
        $this->categories = $this->getCategories();
    }

    // Define validation rules
    protected function rules()
    {
        return [
            'judul' => 'required|string|min:3|max:255',
            'penulis' => 'required|string|min:3|max:255',
            'isbn' => [
                'required', 
                'string', 
                Rule::unique('bukus')->ignore($this->bukuId)
            ],
            'bukuKategori' => 'required|string',
            'deskripsi' => 'nullable|string',
            'stock' => 'required|integer|min:0',
            'denda_harian' => 'required|integer|min:0',
            'penerbit' => 'required|string|min:2|max:255',
            'tahun_terbit' => 'required|integer|min:1900|max:'.date('Y'),
            'coverImage' => 'nullable|image|max:2048',
        ];
    }

    protected $validationAttributes = [
        'judul' => 'judul buku',
        'penulis' => 'penulis',
        'isbn' => 'ISBN',
        'bukuKategori' => 'kategori',
        'deskripsi' => 'deskripsi',
        'stock' => 'stok',
        'denda_harian' => 'denda harian',
        'penerbit' => 'penerbit',
        'tahun_terbit' => 'tahun terbit',
        'coverImage' => 'cover buku',
    ];

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedKategori()
    {
        $this->resetPage();
    }

    public function updatedSelectAll($value)
    {
        if ($value) {
            $this->selectedBooks = $this->booksOnCurrentPage;
        } else {
            $this->selectedBooks = [];
        }
    }

    // Get form configuration for the book form
    public function getBukuFormConfig()
    {
        return [
            [
                'id' => 'judul',
                'label' => 'Judul',
                'type' => 'text',
                'required' => true
            ],
            [
                'id' => 'penulis',
                'label' => 'Penulis',
                'type' => 'text',
                'required' => true
            ],
            [
                'id' => 'isbn',
                'label' => 'ISBN',
                'type' => 'text',
                'required' => true
            ],
            [
                'id' => 'bukuKategori',
                'label' => 'Kategori',
                'type' => 'select',
                'required' => true,
                'options' => $this->categories
            ],
            [
                'id' => 'stock',
                'label' => 'Stok',
                'type' => 'number',
                'required' => true
            ],
            [
                'id' => 'denda_harian',
                'label' => 'Denda Harian',
                'type' => 'number',
                'required' => true
            ],
            [
                'id' => 'penerbit',
                'label' => 'Penerbit',
                'type' => 'text',
                'required' => true
            ],
            [
                'id' => 'tahun_terbit',
                'label' => 'Tahun Terbit',
                'type' => 'number',
                'required' => true
            ],
            [
                'id' => 'deskripsi',
                'label' => 'Deskripsi',
                'type' => 'textarea',
                'required' => false
            ]
        ];
    }

    public function render()
    {
        $query = Buku::query();
        
        if ($this->search) {
            $query->where(function($q) {
                $q->where('judul', 'like', '%' . $this->search . '%')
                  ->orWhere('penulis', 'like', '%' . $this->search . '%')
                  ->orWhere('isbn', 'like', '%' . $this->search . '%');
            });
        }
        
        if ($this->kategori) {
            $query->where('kategori', $this->kategori);
        }
        
        $books = $query->orderBy('created_at', 'desc')
                       ->paginate($this->perPage);
            
        // Save IDs of books on current page for "Select All" functionality
        $this->booksOnCurrentPage = $books->pluck('id')->toArray();
            
        return view('livewire.admin.data-buku', [
            'books' => $books,
            'formConfig' => $this->getBukuFormConfig(),
            'currentBuku' => $this->bukuId ? Buku::find($this->bukuId) : null
        ])->layout('layouts.admin', ['title' => 'Data Buku']);
    }

    // Open modal to create a new book
    public function createBuku()
    {
        $this->resetValidation();
        $this->resetForm();
        $this->isModalOpen = true;
    }

    // Open modal to edit a book
    public function editBuku($bukuId)
    {
        $this->resetValidation();
        $this->resetForm();
        
        $this->bukuId = $bukuId;
        $buku = Buku::findOrFail($bukuId);
        
        $this->judul = $buku->judul;
        $this->penulis = $buku->penulis;
        $this->isbn = $buku->isbn;
        $this->bukuKategori = $buku->kategori;
        $this->deskripsi = $buku->deskripsi;
        $this->stock = $buku->stock;
        $this->denda_harian = $buku->denda_harian;
        $this->penerbit = $buku->penerbit;
        $this->tahun_terbit = $buku->tahun_terbit;
        $this->existingCoverImage = $buku->cover_img;
        
        $this->isModalOpen = true;
    }

    // Open detail modal for a book
    public function viewBukuDetails($bukuId)
    {
        $this->selectedBuku = Buku::findOrFail($bukuId);
        $this->isDetailModalOpen = true;
    }

    // Close the form modal
    public function closeModal()
    {
        $this->isModalOpen = false;
    }

    // Close the detail modal
    public function closeDetailModal()
    {
        $this->isDetailModalOpen = false;
        $this->selectedBuku = null;
    }

    // Save the book (create or update)
    public function saveBuku()
    {
        $this->validate();
        
        // Creating or updating book
        $bukuData = [
            'judul' => $this->judul,
            'penulis' => $this->penulis,
            'isbn' => $this->isbn,
            'kategori' => $this->bukuKategori,
            'deskripsi' => $this->deskripsi,
            'stock' => $this->stock,
            'denda_harian' => $this->denda_harian,
            'penerbit' => $this->penerbit,
            'tahun_terbit' => $this->tahun_terbit,
        ];
        
        // Handle cover image upload
        if ($this->coverImage) {
            // Delete existing cover image if updating
            if ($this->bukuId && $this->existingCoverImage) {
                Storage::delete('public/' . $this->existingCoverImage);
            }
            
            // Store the new image
            $imagePath = $this->coverImage->store('covers', 'public');
            $bukuData['cover_img'] = $imagePath;
        }
        
        if ($this->bukuId) {
            // Update existing book
            Buku::findOrFail($this->bukuId)->update($bukuData);
            $message = 'Buku berhasil diperbarui!';
        } else {
            // Create new book
            Buku::create($bukuData);
            $message = 'Buku berhasil ditambahkan!';
        }
        
        $this->resetForm();
        $this->isModalOpen = false;
        session()->flash('success', $message);
    }

    // Confirm book deletion 
    public function confirmBukuDeletion($bukuId)
    {
        $this->bukuIdToDelete = $bukuId;
        $this->confirmingBukuDeletion = true;
    }

    // Delete the book
    public function deleteBuku($bukuId = null)
    {
        try {
            $idToDelete = $bukuId ?? $this->bukuIdToDelete;
            $buku = Buku::findOrFail($idToDelete);
            
            // Delete cover image if exists
            if ($buku->cover_img) {
                Storage::delete('public/' . $buku->cover_img);
            }
            
            $buku->delete();
            
            $this->confirmingBukuDeletion = false;
            $this->bukuIdToDelete = null;
            $this->selectedBooks = array_diff($this->selectedBooks, [$idToDelete]);
            
            session()->flash('alert', [
                'type' => 'success',
                'message' => 'Buku berhasil dihapus!'
            ]);

            // Dispatch event untuk refresh komponen
            $this->dispatch('refresh');
            
        } catch (\Exception $e) {
            session()->flash('alert', [
                'type' => 'error',
                'message' => 'Gagal menghapus buku. ' . $e->getMessage()
            ]);
        }
    }

    // Tambahkan method untuk delete selected books
    public function deleteSelectedBooks()
    {
        try {
            $books = Buku::whereIn('id', $this->selectedBooks)->get();
            
            foreach ($books as $book) {
                if ($book->cover_img) {
                    Storage::delete('public/' . $book->cover_img);
                }
                $book->delete();
            }
            
            $this->selectedBooks = [];
            $this->selectAll = false;
            
            session()->flash('alert', [
                'type' => 'success',
                'message' => count($books) . ' buku berhasil dihapus!'
            ]);

            // Dispatch event untuk refresh komponen
            $this->dispatch('refresh');
            
        } catch (\Exception $e) {
            session()->flash('alert', [
                'type' => 'error',
                'message' => 'Gagal menghapus buku. ' . $e->getMessage()
            ]);
        }
    }

    // Reset form fields
    private function resetForm()
    {
        $this->bukuId = null;
        $this->judul = '';
        $this->penulis = '';
        $this->isbn = '';
        $this->bukuKategori = '';
        $this->deskripsi = '';
        $this->stock = 1;
        $this->denda_harian = 0;
        $this->penerbit = '';
        $this->tahun_terbit = date('Y');
        $this->coverImage = null;
        $this->existingCoverImage = null;
    }

    // Validate ISBN in real-time
    public function validateField($field)
    {
        $this->validateOnly($field);
    }
}