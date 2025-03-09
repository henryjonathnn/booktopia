<div class="px-4 md:px-8 lg:px-16 py-8">
    {{-- Header Section --}}
    <div class="mb-8">
        <h1 class="text-3xl font-bold mb-2">Koleksi Buku</h1>
        <p class="text-gray-400">Jelajahi berbagai koleksi buku kami</p>
    </div>

    {{-- Filter & Search Section --}}
    <div class="flex flex-col md:flex-row gap-4 mb-8">
        <div class="flex-1">
            <div class="relative">
                <input 
                    wire:model.live.debounce.300ms="search"
                    type="text" 
                    class="w-full pl-10 pr-4 py-2 bg-[#1A1A2E] rounded-xl border border-purple-500/10 focus:border-purple-500 focus:ring-1 focus:ring-purple-500"
                    placeholder="Cari judul atau penulis..."
                >
                <x-icon name="search" class="absolute left-3 top-2.5 w-5 h-5 text-gray-400"/>
            </div>
        </div>
        
        <div class="flex gap-4">
            <select 
                wire:model.live="selectedCategory"
                class="bg-[#1A1A2E] rounded-xl border border-purple-500/10 focus:border-purple-500 focus:ring-1 focus:ring-purple-500"
            >
                <option value="">Semua Kategori</option>
                @foreach($categories as $category)
                    <option value="{{ $category }}">{{ $category }}</option>
                @endforeach
            </select>

            <select 
                wire:model.live="sortBy"
                class="bg-[#1A1A2E] rounded-xl border border-purple-500/10 focus:border-purple-500 focus:ring-1 focus:ring-purple-500"
            >
                <option value="newest">Terbaru</option>
                <option value="rating">Rating Tertinggi</option>
                <option value="favorite">Terfavorit</option>
            </select>
        </div>
    </div>

    {{-- Categories Grid --}}
    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-8 gap-4 mb-8">
        @foreach($categories as $category)
            <button 
                wire:click="$set('selectedCategory', '{{ $category }}')"
                class="p-3 text-center rounded-xl {{ $selectedCategory === $category ? 'bg-purple-500 text-white' : 'bg-[#1A1A2E] hover:bg-purple-500/10' }} transition-all duration-300 border border-purple-500/10"
            >
                {{ $category }}
            </button>
        @endforeach
    </div>

    {{-- Books Grid --}}
    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-6">
        @forelse($books as $book)
            <livewire:book-card 
                :wire:key="'book-'.$book->id"
                :book="$book"
                :show-rating="true"
                right-label="Rating"
            />
        @empty
            <div class="col-span-full text-center py-12">
                <x-icon name="book-open" class="w-16 h-16 mx-auto text-gray-400 mb-4"/>
                <h3 class="text-xl font-semibold mb-2">Tidak ada buku ditemukan</h3>
                <p class="text-gray-400">Coba ubah filter atau kata kunci pencarian</p>
            </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    <div class="mt-8">
        {{ $books->links() }}
    </div>
</div> 