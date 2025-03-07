<div>
    <!-- Category Filter Buttons -->
    <div class="flex flex-wrap gap-3 mb-8">
        <button wire:click="setCategory('all')" 
            class="px-4 py-2 rounded-xl transition-all duration-300 {{ $selectedCategory === 'all' ? 'bg-purple-600 text-white' : 'bg-[#1A1A2E] hover:bg-purple-500/10 border border-purple-500/10' }}">
            Semua Kategori
        </button>
        
        @foreach($categories as $category)
            <button wire:click="setCategory('{{ $category }}')" 
                class="px-4 py-2 rounded-xl transition-all duration-300 {{ $selectedCategory === $category ? 'bg-purple-600 text-white' : 'bg-[#1A1A2E] hover:bg-purple-500/10 border border-purple-500/10' }}">
                {{ $category }}
            </button>
        @endforeach
    </div>
    
    <!-- Sort Options -->
    <div class="flex justify-between items-center mb-6">
        <div class="flex items-center space-x-4">
            <p class="text-gray-400">Urutkan:</p>
            <select wire:model.live="sortOption" class="bg-[#1A1A2E] border border-purple-500/10 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-purple-500">
                <option value="newest">Terbaru</option>
                <option value="rating">Rating Tertinggi</option>
                <option value="title">Judul (A-Z)</option>
                <option value="popular">Terpopuler</option>
            </select>
        </div>
        
        <div class="flex items-center space-x-3">
            <span class="text-sm text-gray-400">Menampilkan {{ $books->firstItem() ?? 0 }}-{{ $books->lastItem() ?? 0 }} dari {{ $books->total() ?? 0 }} buku</span>
        </div>
    </div>
    
    <!-- Book Cards Grid -->
    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4 md:gap-6 mb-8">
        @forelse($books as $book)
            <livewire:book-card 
                :wire:key="'book-'.$book->id"
                :book="$book"
                :show-rating="true"
                :right-label="'Rating'"
            />
        @empty
            <div class="col-span-full text-center py-12">
                <div class="mb-4">
                    <x-icon name="book-open" class="w-12 h-12 mx-auto text-gray-400"/>
                </div>
                <h3 class="text-xl font-medium mb-2">Tidak ada buku ditemukan</h3>
                <p class="text-gray-400">Coba gunakan filter atau kategori lain</p>
            </div>
        @endforelse
    </div>
    
    <!-- Pagination -->
    <div class="flex justify-center my-8">
        {{ $books->links('vendor.livewire.tailwind') }}
    </div>
    
    <!-- Book Detail Modal -->
    <x-modal wire:model="selectedBook">
        @if($selectedBook)
        <div class="bg-[#1A1A2E] p-6 rounded-2xl">
            <h3 class="text-xl font-bold mb-4">{{ $selectedBook->judul }}</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <img 
                        src="{{ $selectedBook->cover_img ? asset('storage/' . $selectedBook->cover_img) : '' }}"
                        alt="{{ $selectedBook->judul }}"
                        class="w-full rounded-xl object-cover"
                    />
                </div>
                <div>
                    <p class="text-gray-400">Penulis</p>
                    <p class="font-medium mb-4">{{ $selectedBook->penulis }}</p>
                    
                    <p class="text-gray-400">Kategori</p>
                    <p class="font-medium mb-4">{{ $selectedBook->kategori }}</p>
                    
                    <p class="text-gray-400">Rating</p>
                    <p class="font-medium mb-4">{{ number_format($selectedBook->rating, 1) }}/5</p>
                    
                    <p class="text-gray-400">Deskripsi</p>
                    <p class="font-medium mb-4">{{ $selectedBook->deskripsi ?? 'Tidak ada deskripsi tersedia.' }}</p>
                    
                    <div class="flex space-x-3 mt-6">
                        <button class="flex-1 px-4 py-2 bg-purple-600 rounded-xl hover:bg-purple-700 transition-colors duration-300">
                            Pinjam Buku
                        </button>
                        <button wire:click="$set('selectedBook', null)" 
                            class="px-4 py-2 bg-[#1A1A2E] border border-purple-500/10 rounded-xl hover:bg-purple-500/10 transition-colors duration-300">
                            Tutup
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </x-modal>
</div>