<div class="px-4 md:px-8 lg:px-16 py-20">
    {{-- Header Section --}}
    <div class="mb-8">
        <h1 class="text-3xl font-bold mb-2">Koleksi Buku</h1>
        <p class="text-gray-400">Temukan berbagai buku menarik untuk dibaca</p>
    </div>

    {{-- Filter Section --}}
    <div class="mb-8">
        <div class="flex flex-wrap items-center gap-3 mb-6">
            <span class="text-gray-400">Urutkan:</span>
            <button 
                wire:click="$set('sortBy', 'newest')" 
                class="px-4 py-2 rounded-xl text-sm transition-all duration-300 border border-purple-500/10 
                {{ $sortBy === 'newest' ? 'bg-purple-500 text-white' : 'bg-[#1A1A2E] hover:bg-purple-500/10' }}"
            >
                Terbaru
            </button>
            <button 
                wire:click="$set('sortBy', 'rating')" 
                class="px-4 py-2 rounded-xl text-sm transition-all duration-300 border border-purple-500/10 
                {{ $sortBy === 'rating' ? 'bg-purple-500 text-white' : 'bg-[#1A1A2E] hover:bg-purple-500/10' }}"
            >
                Rating Tertinggi
            </button>
            <button 
                wire:click="$set('sortBy', 'favorite')" 
                class="px-4 py-2 rounded-xl text-sm transition-all duration-300 border border-purple-500/10 
                {{ $sortBy === 'favorite' ? 'bg-purple-500 text-white' : 'bg-[#1A1A2E] hover:bg-purple-500/10' }}"
            >
                Terfavorit
            </button>
        </div>

        {{-- Categories Pills --}}
        <div>
            <h3 class="text-lg font-semibold mb-3">Kategori</h3>
            <div class="flex flex-wrap gap-3">
                <button 
                    wire:click="$set('selectedCategory', '')" 
                    class="px-4 py-2 rounded-full text-sm transition-all duration-300 
                    {{ $selectedCategory === '' ? 'bg-purple-500 text-white' : 'bg-[#1A1A2E] hover:bg-purple-500/10 border border-purple-500/10' }}"
                >
                    Semua
                </button>
                @foreach($categories as $category)
                    <button 
                        wire:click="$set('selectedCategory', '{{ $category }}')" 
                        class="px-4 py-2 rounded-full text-sm transition-all duration-300 
                        {{ $selectedCategory === $category ? 'bg-purple-500 text-white' : 'bg-[#1A1A2E] hover:bg-purple-500/10 border border-purple-500/10' }}"
                    >
                        {{ $category }}
                    </button>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Books Grid with Loading State --}}
    <div wire:loading.class="opacity-50" class="transition-opacity duration-300">
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-6">
            @forelse($books as $book)
                <livewire:book-card 
                    :wire:key="'book-'.$book->id"
                    :book="$book"
                    :show-rating="true"
                    right-label="Rating"
                />
            @empty
                <div class="col-span-full text-center py-16">
                    <div class="bg-[#1A1A2E] p-8 rounded-2xl border border-purple-500/10 inline-block">
                        <x-icon name="book-open" class="w-16 h-16 mx-auto text-gray-400 mb-4"/>
                        <h3 class="text-xl font-semibold mb-2">Tidak ada buku ditemukan</h3>
                        <p class="text-gray-400 mb-4">Coba pilih kategori lain atau ubah filter</p>
                        <button 
                            wire:click="$set('selectedCategory', '')" 
                            class="px-4 py-2 bg-purple-500 hover:bg-purple-600 rounded-xl text-white transition-colors duration-300"
                        >
                            Lihat Semua Buku
                        </button>
                    </div>
                </div>
            @endforelse
        </div>
    </div>

    {{-- Loading Indicator --}}
    <div wire:loading class="fixed inset-0 flex items-center justify-center z-50 bg-black/20 backdrop-blur-sm">
        <div class="bg-[#1A1A2E] p-6 rounded-2xl border border-purple-500/10 flex items-center space-x-4">
            <div class="w-8 h-8 border-4 border-t-purple-500 border-r-transparent border-b-transparent border-l-transparent rounded-full animate-spin"></div>
            <p>Memuat buku...</p>
        </div>
    </div>

    {{-- Pagination --}}
    <div class="mt-10">
        {{ $books->links() }}
    </div>
</div> 