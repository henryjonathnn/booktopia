<div class="px-4 md:px-8 lg:px-16 py-20">
    <div class="max-w-7xl mx-auto">
        {{-- Breadcrumb --}}
        <div class="flex items-center space-x-2 text-sm text-gray-400 mb-8">
            <a href="{{ route('home') }}" class="hover:text-purple-400">Home</a>
            <span>/</span>
            <a href="{{ route('buku') }}" class="hover:text-purple-400">Buku</a>
            <span>/</span>
            <span class="text-purple-400">Detail</span>
        </div>

        {{-- Book Detail Section --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-16">
            {{-- Left Column: Cover Image --}}
            <div class="md:col-span-1">
                <div class="glass-effect rounded-2xl p-4 border border-purple-500/10">
                    @if ($book->cover_img)
                        <img src="{{ asset('storage/' . $book->cover_img) }}" 
                            alt="{{ $book->judul }}"
                            class="w-full rounded-xl aspect-[2/3] object-cover" />
                    @else
                        <div class="w-full rounded-xl aspect-[2/3] bg-gray-800 flex items-center justify-center">
                            <x-icon name="book-open" class="w-20 h-20 text-gray-600" />
                        </div>
                    @endif
                </div>
            </div>

            {{-- Right Column: Book Info --}}
            <div class="md:col-span-2">
                <div class="glass-effect rounded-2xl p-6 border border-purple-500/10">
                    {{-- Title & Actions --}}
                    <div class="flex justify-between items-start mb-6">
                        <h1 class="text-3xl font-bold">{{ $book->judul }}</h1>
                        <div class="flex space-x-2">
                            <button wire:click="toggleBookmark" 
                                class="p-2 rounded-xl hover:bg-purple-500/10 transition-colors">
                                <x-icon name="bookmark" class="w-6 h-6 {{ $isBookmarked ? 'text-purple-500 fill-purple-500' : 'text-gray-400' }}" />
                            </button>
                        </div>
                    </div>

                    {{-- Book Meta Info --}}
                    <div class="grid grid-cols-2 gap-6 mb-8">
                        <div>
                            <p class="text-gray-400 mb-1">Penulis</p>
                            <p class="font-medium">{{ $book->penulis }}</p>
                        </div>
                        <div>
                            <p class="text-gray-400 mb-1">Penerbit</p>
                            <p class="font-medium">{{ $book->penerbit }}</p>
                        </div>
                        <div>
                            <p class="text-gray-400 mb-1">Tahun Terbit</p>
                            <p class="font-medium">{{ $book->tahun_terbit }}</p>
                        </div>
                        <div>
                            <p class="text-gray-400 mb-1">ISBN</p>
                            <p class="font-medium">{{ $book->isbn }}</p>
                        </div>
                        <div>
                            <p class="text-gray-400 mb-1">Kategori</p>
                            <span class="inline-block px-3 py-1 bg-purple-500/20 text-purple-400 rounded-full text-sm">
                                {{ $book->kategori }}
                            </span>
                        </div>
                        <div>
                            <p class="text-gray-400 mb-1">Rating</p>
                            <div class="flex items-center space-x-2">
                                <div class="flex items-center">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <x-icon name="star" class="w-5 h-5 {{ $i <= $book->average_rating ? 'text-yellow-400' : 'text-gray-600' }}" />
                                    @endfor
                                </div>
                                <span class="text-sm">({{ number_format($book->average_rating, 1) }})</span>
                                <button wire:click="$set('showRatingModal', true)" 
                                    class="text-sm text-purple-400 hover:text-purple-300">
                                    Beri Rating
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- Description --}}
                    <div class="mb-8">
                        <h3 class="text-xl font-semibold mb-4">Deskripsi</h3>
                        <p class="text-gray-300 leading-relaxed">{{ $book->deskripsi }}</p>
                    </div>

                    {{-- Action Buttons --}}
                    <div class="flex space-x-4">
                        <button class="flex-1 bg-gradient-to-r from-purple-600 to-indigo-600 hover:opacity-90 rounded-xl font-medium px-6 py-3">
                            Pinjam Buku
                        </button>
                        <button class="px-6 py-3 rounded-xl bg-purple-500/10 hover:bg-purple-500/20 border border-purple-500/10">
                            Preview
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Related Books Section --}}
        @if(count($relatedBooks) > 0)
            <div class="mb-16">
                <h2 class="text-2xl font-bold mb-6">Buku Terkait</h2>
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-6">
                    @foreach($relatedBooks as $relatedBook)
                        <livewire:book-card 
                            :wire:key="'related-'.$relatedBook->id"
                            :book="$relatedBook"
                            :show-rating="true"
                            right-label="Rating"
                        />
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    {{-- Rating Modal --}}
    <x-modal wire:model="showRatingModal">
        <div class="bg-[#1A1A2E] p-6 rounded-2xl max-w-md mx-auto">
            <h3 class="text-xl font-bold mb-4">Beri Rating</h3>
            <div class="flex justify-center space-x-2 mb-6">
                @for ($i = 1; $i <= 5; $i++)
                    <button wire:click="$set('userRating', {{ $i }})" 
                        class="p-2 hover:scale-110 transition-transform">
                        <x-icon name="star" 
                            class="w-8 h-8 {{ $i <= $userRating ? 'text-yellow-400' : 'text-gray-600' }}" />
                    </button>
                @endfor
            </div>
            <div class="flex space-x-3">
                <button wire:click="submitRating" 
                    class="flex-1 bg-purple-500 hover:bg-purple-600 rounded-xl py-2">
                    Simpan
                </button>
                <button wire:click="$set('showRatingModal', false)" 
                    class="flex-1 bg-gray-700 hover:bg-gray-600 rounded-xl py-2">
                    Batal
                </button>
            </div>
        </div>
    </x-modal>
</div> 