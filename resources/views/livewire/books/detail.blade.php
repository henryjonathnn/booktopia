<div class="px-4 md:px-8 lg:px-16 py-8 pt-32">
    <div class="max-w-7xl mx-auto">
        {{-- Breadcrumb dengan desain yang lebih subtle --}}
        <nav class="flex items-center space-x-2 text-sm text-gray-400 mb-8">
            <a href="{{ route('home') }}" class="hover:text-purple-400 transition-colors">Home</a>
            <span class="text-gray-600">/</span>
            <a href="{{ route('buku') }}" class="hover:text-purple-400 transition-colors">Buku</a>
            <span class="text-gray-600">/</span>
            <span class="text-purple-400 truncate max-w-[200px]">{{ $book->judul }}</span>
        </nav>

        {{-- Main Content dengan Grid Layout yang Responsif --}}
        <div class="grid grid-cols-12 gap-8">
            {{-- Left Column: Cover & Quick Actions --}}
            <div class="col-span-12 md:col-span-4 lg:col-span-3 space-y-6">
                {{-- Cover Image dalam Card --}}
                <div class="glass-effect rounded-2xl p-4 border border-purple-500/10">
                    @if ($book->cover_img)
                        <img src="{{ asset('storage/' . $book->cover_img) }}" 
                            alt="{{ $book->judul }}"
                            class="w-full rounded-xl aspect-[3/4] object-cover shadow-lg" />
                    @else
                        <div class="w-full rounded-xl aspect-[3/4] bg-gray-800 flex items-center justify-center">
                            <x-icon name="book-open" class="w-16 h-16 text-gray-600" />
                        </div>
                    @endif
                </div>

                {{-- Quick Actions Card --}}
                <div class="glass-effect rounded-2xl p-6 border border-purple-500/10 space-y-4">
                    {{-- Rating & Status --}}
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-gray-400">Rating</span>
                            <div class="flex items-center space-x-2">
                                <div class="flex">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <x-icon name="star" class="w-4 h-4 {{ $i <= $book->average_rating ? 'text-yellow-400' : 'text-gray-600' }}" />
                                    @endfor
                                </div>
                                <span class="font-medium">{{ number_format($book->average_rating, 1) }}</span>
                            </div>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-gray-400">Status</span>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $book->stock > 0 ? 'bg-green-500/10 text-green-400' : 'bg-red-500/10 text-red-400' }}">
                                {{ $book->stock > 0 ? 'Tersedia' : 'Tidak Tersedia' }}
                            </span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-gray-400">Stok</span>
                            <span class="font-medium">{{ $book->stock }} buku</span>
                        </div>
                    </div>

                    <hr class="border-purple-500/10">

                    {{-- Action Buttons --}}
                    <div class="space-y-3">
                        @if($book->stock > 0)
                            <button class="w-full bg-gradient-to-r from-purple-600 to-indigo-600 hover:opacity-90 rounded-xl py-3 font-medium transition-all duration-300 flex items-center justify-center space-x-2">
                                <x-icon name="book-open" class="w-5 h-5" />
                                <span>Pinjam Buku</span>
                            </button>
                        @endif
                        
                        <button wire:click="toggleBookmark" 
                            class="w-full rounded-xl py-3 font-medium transition-all duration-300 flex items-center justify-center space-x-2
                            {{ $isBookmarked ? 'bg-purple-500 text-white' : 'bg-purple-500/10 hover:bg-purple-500/20' }}">
                            <x-icon name="bookmark" class="w-5 h-5" />
                            <span>{{ $isBookmarked ? 'Tersimpan' : 'Simpan' }}</span>
                        </button>
                    </div>
                </div>
            </div>

            {{-- Right Column: Book Details --}}
            <div class="col-span-12 md:col-span-8 lg:col-span-9 space-y-6">
                {{-- Book Title & Basic Info --}}
                <div class="glass-effect rounded-2xl p-6 border border-purple-500/10">
                    <div class="flex items-start justify-between mb-6">
                        <div>
                            <h1 class="text-2xl md:text-3xl font-bold mb-2">{{ $book->judul }}</h1>
                            <p class="text-gray-400">oleh {{ $book->penulis }}</p>
                        </div>
                        <button wire:click="$set('showRatingModal', true)" 
                            class="flex items-center space-x-2 px-4 py-2 rounded-xl bg-purple-500/10 hover:bg-purple-500/20 transition-colors">
                            <x-icon name="star" class="w-5 h-5 text-yellow-400" />
                            <span>Beri Rating</span>
                        </button>
                    </div>

                    {{-- Book Metadata Grid --}}
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                        <div>
                            <h3 class="text-gray-400 text-sm mb-1">Penerbit</h3>
                            <p class="font-medium">{{ $book->penerbit }}</p>
                        </div>
                        <div>
                            <h3 class="text-gray-400 text-sm mb-1">Tahun Terbit</h3>
                            <p class="font-medium">{{ $book->tahun_terbit }}</p>
                        </div>
                        <div>
                            <h3 class="text-gray-400 text-sm mb-1">ISBN</h3>
                            <p class="font-medium">{{ $book->isbn ?? '-' }}</p>
                        </div>
                        <div>
                            <h3 class="text-gray-400 text-sm mb-1">Kategori</h3>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-purple-500/20 text-purple-400">
                                {{ $book->kategori }}
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Description --}}
                <div class="glass-effect rounded-2xl p-6 border border-purple-500/10">
                    <h2 class="text-xl font-semibold mb-4">Deskripsi</h2>
                    <div class="prose prose-invert max-w-none">
                        <p class="text-gray-300 leading-relaxed">{{ $book->deskripsi }}</p>
                    </div>
                </div>

                {{-- Related Books --}}
                @if(count($relatedBooks) > 0)
                    <div class="glass-effect rounded-2xl p-6 border border-purple-500/10">
                        <h2 class="text-xl font-semibold mb-4">Buku Terkait</h2>
                        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
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

    {{-- Rating Modal dengan Desain yang Lebih Menarik --}}
    <x-modal wire:model="showRatingModal">
        <div class="bg-[#1A1A2E] p-6 rounded-2xl max-w-sm mx-auto">
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
                    class="flex-1 bg-purple-500 hover:bg-purple-600 rounded-xl py-2.5 font-medium transition-colors">
                    Simpan Rating
                </button>
                <button wire:click="$set('showRatingModal', false)" 
                    class="flex-1 bg-gray-700 hover:bg-gray-600 rounded-xl py-2.5 font-medium transition-colors">
                    Batal
                </button>
            </div>
        </div>
    </x-modal>
</div> 