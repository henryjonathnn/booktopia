<div class="px-4 md:px-8 lg:px-16 py-8 pt-32">
    <div class="max-w-7xl mx-auto">
        {{-- Header --}}
        <div class="mb-8">
            <h1 class="text-3xl font-bold mb-2">Buku Favorit Saya</h1>
            <p class="text-gray-400">Koleksi buku yang Anda sukai</p>
        </div>

        {{-- Search & Filter --}}
        <div class="glass-effect rounded-xl p-4 border border-purple-500/10 mb-8">
            <div class="flex flex-col md:flex-row gap-4">
                <div class="flex-1">
                    <input wire:model.live.debounce.500ms="search" type="text"
                        placeholder="Cari buku favorit..."
                        class="w-full bg-[#1A1A2E] rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-purple-500/5 border border-purple-500/10" />
                </div>
                <div class="flex gap-4">
                    <select wire:model.live="sortBy"
                        class="bg-[#1A1A2E] rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-purple-500/5 border border-purple-500/10">
                        <option value="newest">Terbaru</option>
                        <option value="oldest">Terlama</option>
                        <option value="rating">Rating Tertinggi</option>
                    </select>
                </div>
            </div>
        </div>

        {{-- Books Grid --}}
        @if($books->count() > 0)
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4 md:gap-6">
                @foreach($books as $book)
                    <livewire:book-card 
                        :wire:key="'book-'.$book->id"
                        :book="$book"
                        :show-rating="true"
                        right-label="Rating"
                    />
                @endforeach
            </div>
            
            <div class="mt-8">
                {{ $books->links() }}
            </div>
        @else
            <div class="glass-effect rounded-xl p-8 border border-purple-500/10 text-center">
                <x-icon name="heart" class="w-16 h-16 text-gray-600 mx-auto mb-4" />
                <h3 class="text-xl font-semibold mb-2">Belum Ada Buku Favorit</h3>
                <p class="text-gray-400">Mulai tambahkan buku ke favorit dengan menekan tombol hati pada buku yang Anda sukai.</p>
                <a href="{{ route('buku.index') }}" class="inline-block mt-4 px-6 py-2 bg-purple-500 text-white rounded-xl hover:bg-purple-600 transition-colors">
                    Jelajahi Buku
                </a>
            </div>
        @endif
    </div>
</div> 