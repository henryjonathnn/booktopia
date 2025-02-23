{{-- resources/views/livewire/book-section.blade.php --}}
<section class="px-4 md:px-8 lg:px-16 mx-2 mb-8 md:mb-16">
    <div class="flex flex-col md:flex-row md:justify-between md:items-center mb-6 md:mb-8">
        <div class="mb-4 md:mb-0">
            <div class="flex items-center space-x-3">
                <h2 class="text-2xl md:text-3xl font-bold">{{ $title }}</h2>
                <span class="flex items-center space-x-1 px-3 py-1 rounded-full {{ $badgeColor === 'purple' ? 'bg-purple-500/20 text-purple-400' : 'bg-red-500/20 text-red-400' }} text-sm">
                    <span class="h-2 w-2 {{ $badgeColor === 'purple' ? 'bg-purple-400' : 'bg-red-400' }} rounded-full animate-pulse"></span>
                    <span>{{ $badgeText }}</span>
                </span>
            </div>
            <p class="text-gray-400 mt-2">{{ $subtitle }}</p>
        </div>
        <div class="flex space-x-3">
            <button
                wire:click="previousPage"
                class="p-2 md:p-3 rounded-xl bg-[#1A1A2E] hover:bg-purple-500/10 transition-all duration-300 border border-purple-500/10 disabled:opacity-50"
                {{ $books->onFirstPage() ? 'disabled' : '' }}
            >
            <x-icon name="chevron-left" class="w-4.5 h-4.5"/>
            </button>
            <button
                wire:click="nextPage"
                class="p-2 md:p-3 rounded-xl bg-[#1A1A2E] hover:bg-purple-500/10 transition-all duration-300 border border-purple-500/10 disabled:opacity-50"
                {{ !$books->hasMorePages() ? 'disabled' : '' }}
            >
            <x-icon name="chevron-right" class="w-4.5 h-4.5"/>
            </button>
            <button
                wire:click="toggleFilterModal"
                class="p-2 md:p-3 rounded-xl bg-[#1A1A2E] hover:bg-purple-500/10 transition-all duration-300 border border-purple-500/10"
            >
            <x-icon name="filter" class="w-4.5 h-4.5"/>
            </button>
            <button
                wire:click="toggleAllModal"
                class="px-3 py-1 md:px-4 md:py-2 rounded-xl bg-[#1A1A2E] hover:bg-purple-500/10 transition-all duration-300 border border-purple-500/10 text-white text-sm md:text-base"
            >
                Lihat Semua
            </button>
        </div>
    </div>

    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4 md:gap-6">
        @foreach($books as $book)
            <livewire:book-card 
                :wire:key="'book-'.$book->id"
                :book="$book"
                :show-rating="$showRating"
                :right-label="$rightLabel"
            />
        @endforeach
    </div>

    {{-- Filter Modal --}}
    <x-modal wire:model="showFilterModal">
        <div class="bg-[#1A1A2E] p-6 rounded-2xl">
            <h3 class="text-xl font-bold mb-4">Filter Buku</h3>
            {{-- Add your filter controls here --}}
            <button
                wire:click="toggleFilterModal"
                class="w-full mt-4 px-4 py-2 bg-purple-500 text-white rounded-xl hover:bg-purple-600 transition-colors duration-200"
            >
                Tutup
            </button>
        </div>
    </x-modal>

    {{-- Book Detail Modal --}}
    <x-modal wire:model="selectedBook">
        @if($selectedBook)
        <div class="bg-[#1A1A2E] p-6 rounded-2xl">
            <h3 class="text-xl font-bold mb-4">{{ $selectedBook->judul }}</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <img 
                        src="{{ $selectedBook->cover_img }}"
                        alt="{{ $selectedBook->judul }}"
                        class="w-full rounded-xl object-cover"
                    />
                </div>
                <div>
                    <p class="text-gray-400">Penulis</p>
                    <p class="font-medium mb-4">{{ $selectedBook->penulis }}</p>
                    
                    <p class="text-gray-400">Kategori</p>
                    <p class="font-medium mb-4">{{ $selectedBook->kategori }}</p>
                    
                    @if($showRating)
                    <p class="text-gray-400">Rating</p>
                    <p class="font-medium mb-4">{{ number_format($selectedBook->rating, 1) }}/5</p>
                    @endif
                    
                    <button
                        wire:click="$set('selectedBook', null)"
                        class="w-full mt-4 px-4 py-2 bg-purple-500 text-white rounded-xl hover:bg-purple-600 transition-colors duration-200"
                    >
                        Tutup
                    </button>
                </div>
            </div>
        </div>
        @endif
    </x-modal>
</section>