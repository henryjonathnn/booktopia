<div class="px-4 md:px-8 lg:px-16 py-8">
    <div class="max-w-6xl mx-auto">
        {{-- Breadcrumb --}}
        <div class="flex items-center space-x-2 text-sm text-gray-400 mb-6">
            <a href="{{ route('home') }}" class="hover:text-purple-400">Home</a>
            <span>/</span>
            <a href="{{ route('buku') }}" class="hover:text-purple-400">Buku</a>
            <span>/</span>
            <span class="text-purple-400 truncate">{{ $book->judul }}</span>
        </div>

        {{-- Main Content --}}
        <div class="grid grid-cols-12 gap-6">
            {{-- Left Column: Cover & Quick Actions --}}
            <div class="col-span-12 md:col-span-3 space-y-4">
                {{-- Cover Image dengan ukuran lebih kecil --}}
                <div class="glass-effect rounded-xl p-2 border border-purple-500/10">
                    @if ($book->cover_img)
                        <img src="{{ asset('storage/' . $book->cover_img) }}" 
                            alt="{{ $book->judul }}"
                            class="w-full rounded-lg aspect-[3/4] object-cover" />
                    @else
                        <div class="w-full rounded-lg aspect-[3/4] bg-gray-800 flex items-center justify-center">
                            <x-icon name="book-open" class="w-12 h-12 text-gray-600" />
                        </div>
                    @endif
                </div>

                {{-- Quick Stats & Actions dalam satu card --}}
                <div class="glass-effect rounded-xl p-4 border border-purple-500/10 space-y-3">
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-400">Rating</span>
                        <div class="flex items-center space-x-1">
                            <x-icon name="star" class="w-4 h-4 text-yellow-400" />
                            <span>{{ number_format($book->average_rating, 1) }}/5.0</span>
                        </div>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-400">Status</span>
                        <span class="{{ $book->stock > 0 ? 'text-green-400' : 'text-red-400' }}">
                            {{ $book->stock > 0 ? 'Tersedia' : 'Tidak Tersedia' }}
                        </span>
                    </div>
                    <hr class="border-purple-500/10">
                    <button class="w-full bg-gradient-to-r from-purple-600 to-indigo-600 hover:opacity-90 rounded-lg text-sm font-medium p-2 flex items-center justify-center space-x-2">
                        <x-icon name="book-open" class="w-4 h-4" />
                        <span>Pinjam Buku</span>
                    </button>
                    <button wire:click="toggleBookmark" 
                        class="w-full rounded-lg p-2 text-sm flex items-center justify-center space-x-2 {{ $isBookmarked ? 'bg-purple-500 text-white' : 'bg-purple-500/10 hover:bg-purple-500/20' }}">
                        <x-icon name="bookmark" class="w-4 h-4" />
                        <span>{{ $isBookmarked ? 'Tersimpan' : 'Simpan' }}</span>
                    </button>
                </div>
            </div>

            {{-- Right Column: Book Details --}}
            <div class="col-span-12 md:col-span-9 space-y-4">
                {{-- Book Title & Basic Info --}}
                <div class="glass-effect rounded-xl p-5 border border-purple-500/10">
                    <div class="flex items-start justify-between mb-3">
                        <h1 class="text-xl md:text-2xl font-bold">{{ $book->judul }}</h1>
                        <button wire:click="$set('showRatingModal', true)" 
                            class="flex items-center space-x-1 text-sm text-purple-400 hover:text-purple-300">
                            <x-icon name="star" class="w-4 h-4" />
                            <span>Rate</span>
                        </button>
                    </div>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                        <div>
                            <p class="text-gray-400 mb-0.5">Penulis</p>
                            <p class="font-medium truncate">{{ $book->penulis }}</p>
                        </div>
                        <div>
                            <p class="text-gray-400 mb-0.5">Penerbit</p>
                            <p class="font-medium truncate">{{ $book->penerbit }}</p>
                        </div>
                        <div>
                            <p class="text-gray-400 mb-0.5">Tahun</p>
                            <p class="font-medium">{{ $book->tahun_terbit }}</p>
                        </div>
                        <div>
                            <p class="text-gray-400 mb-0.5">Kategori</p>
                            <span class="inline-block px-2 py-0.5 bg-purple-500/20 text-purple-400 rounded-full text-xs">
                                {{ $book->kategori }}
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Description --}}
                <div class="glass-effect rounded-xl p-5 border border-purple-500/10">
                    <h2 class="text-lg font-semibold mb-2">Deskripsi</h2>
                    <p class="text-gray-300 text-sm leading-relaxed">{{ $book->deskripsi }}</p>
                </div>

                {{-- Related Books dengan tampilan lebih compact --}}
                @if(count($relatedBooks) > 0)
                    <div class="glass-effect rounded-xl p-5 border border-purple-500/10">
                        <h2 class="text-lg font-semibold mb-3">Buku Terkait</h2>
                        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3">
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
        </div>
    </div>

    {{-- Rating Modal --}}
    <x-modal wire:model="showRatingModal">
        <div class="bg-[#1A1A2E] p-5 rounded-xl max-w-sm mx-auto">
            <h3 class="text-lg font-bold mb-4">Beri Rating</h3>
            <div class="flex justify-center space-x-2 mb-4">
                @for ($i = 1; $i <= 5; $i++)
                    <button wire:click="$set('userRating', {{ $i }})" 
                        class="p-1.5 hover:scale-110 transition-transform">
                        <x-icon name="star" 
                            class="w-6 h-6 {{ $i <= $userRating ? 'text-yellow-400' : 'text-gray-600' }}" />
                    </button>
                @endfor
            </div>
            <div class="flex space-x-2">
                <button wire:click="submitRating" 
                    class="flex-1 bg-purple-500 hover:bg-purple-600 rounded-lg py-2 text-sm">
                    Simpan
                </button>
                <button wire:click="$set('showRatingModal', false)" 
                    class="flex-1 bg-gray-700 hover:bg-gray-600 rounded-lg py-2 text-sm">
                    Batal
                </button>
            </div>
        </div>
    </x-modal>
</div> 