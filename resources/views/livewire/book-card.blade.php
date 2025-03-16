<a href="{{ route('buku.detail', ['slug' => \App\Livewire\Books\Detail::generateSlug($book)]) }}"
    class="glass-effect rounded-2xl p-3 md:p-4 card-glow border border-purple-500/10 transition-all duration-300 hover:-translate-y-2 flex flex-col cursor-pointer"
    x-data="{ isHovered: false }" 
    @mouseover="isHovered = true" 
    @mouseout="isHovered = false"
    :class="{ '-translate-y-2': isHovered }">
    {{-- Cover Image --}}
    @if ($book->cover_img)
        <img src="{{ asset('storage/' . $book->cover_img) }}" alt="{{ $book->judul }}"
            class="w-full aspect-[2/3] rounded-xl object-cover" loading="lazy" decoding="async" />
    @else
        <div class="w-full aspect-[2/3] rounded-xl bg-gray-300"></div>
    @endif

    {{-- Rating and Bookmark display --}}
    <div class="absolute top-2 right-2 flex space-x-2">
        @if ($isBookmarked)
            <button wire:click.stop="toggleBookmark"
                class="p-1.5 md:p-2 rounded-lg bg-black/50 hover:bg-black/70">
                <x-icon name="bookmark"
                    class="w-4.5 h-4.5 {{ $isBookmarked ? 'text-purple-500 fill-purple-500' : 'text-white' }}" />
            </button>
        @endif

        @if ($showRating)
            <div class="inline-flex items-center bg-black/50 px-2 py-1 rounded-lg">
                <x-icon name="star" class="w-3.5 h-3.5 text-yellow-400" />
                <span class="text-white text-xs ml-1">{{ number_format($book->average_rating, 1) }}</span>
            </div>
        @endif
    </div>

    <div class="flex-grow flex flex-col justify-between">
        <div class="mb-3 md:mb-4">
            <h3 class="font-semibold text-sm md:text-base line-clamp-2">{{ $book->judul }}</h3>
            <p class="text-gray-400 text-xs md:text-sm truncate">by {{ $book->penulis }}</p>
        </div>

        <div class="flex items-center justify-between text-xs md:text-sm border-t border-purple-500/10 pt-3 md:pt-4">
            <div>
                <p class="text-gray-400">Kategori</p>
                <p class="font-medium">{{ $book->kategori ?? 'Umum' }}</p>
            </div>
            <div class="text-right">
                <p class="text-gray-400">{{ $rightLabel }}</p>
                <div class="flex items-center justify-end space-x-1">
                    @if (!$showRating)
                        <span class="text-purple-400">+</span>
                        <p class="font-medium">{{ number_format($book->peminjam) }}</p>
                    @else
                        <div class="flex items-center">
                            <x-icon name="star" class="w-3.5 h-3.5 text-yellow-400" />
                            <p class="font-medium ml-1">{{ number_format($book->average_rating, 1) }}/5</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</a>