<div class="px-4 md:px-8 lg:px-16 py-12">
    <div class="max-w-6xl mx-auto">
        {{-- Breadcrumb --}}
        <div class="flex items-center space-x-2 text-sm text-gray-400 mb-8">
            <a href="{{ route('home') }}" class="hover:text-purple-400">Home</a>
            <span>/</span>
            <a href="{{ route('buku') }}" class="hover:text-purple-400">Buku</a>
            <span>/</span>
            <span class="text-purple-400 truncate">{{ $book->judul }}</span>
        </div>

        {{-- Main Content --}}
        <div class="grid grid-cols-12 gap-8">
            {{-- Left Column: Cover & Quick Actions --}}
            <div class="col-span-12 md:col-span-4 lg:col-span-3 space-y-6">
                {{-- Cover Image --}}
                <div class="glass-effect rounded-2xl p-3 border border-purple-500/10">
                    @if ($book->cover_img)
                        <img src="{{ asset('storage/' . $book->cover_img) }}" 
                            alt="{{ $book->judul }}"
                            class="w-full rounded-xl aspect-[3/4] object-cover" />
                    @else
                        <div class="w-full rounded-xl aspect-[3/4] bg-gray-800 flex items-center justify-center">
                            <x-icon name="book-open" class="w-16 h-16 text-gray-600" />
                        </div>
                    @endif
                </div>

                {{-- Quick Stats --}}
                <div class="glass-effect rounded-2xl p-4 border border-purple-500/10 space-y-4">
                    {{-- Rating --}}
                    <div class="flex items-center justify-between">
                        <span class="text-gray-400">Rating</span>
                        <div class="flex items-center space-x-2">
                            <x-icon name="star" class="w-5 h-5 text-yellow-400" />
                            <span class="font-medium">{{ number_format($book->average_rating, 1) }}/5.0</span>
                        </div>
                    </div>

                    {{-- Stock --}}
                    <div class="flex items-center justify-between">
                        <span class="text-gray-400">Stok</span>
                        <span class="font-medium {{ $book->stock > 0 ? 'text-green-400' : 'text-red-400' }}">
                            {{ $book->stock }} tersedia
                        </span>
                    </div>

                    {{-- Bookmark Counter --}}
                    <div class="flex items-center justify-between">
                        <span class="text-gray-400">Disimpan</span>
                        <span class="font-medium">{{ $book->bookmarks_count ?? 0 }} kali</span>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="space-y-3">
                    <button class="w-full bg-gradient-to-r from-purple-600 to-indigo-600 hover:opacity-90 rounded-xl font-medium p-3 flex items-center justify-center space-x-2">
                        <x-icon name="book-open" class="w-5 h-5" />
                        <span>Pinjam Buku</span>
                    </button>
                    <button wire:click="toggleBookmark" 
                        class="w-full rounded-xl p-3 flex items-center justify-center space-x-2 {{ $isBookmarked ? 'bg-purple-500 text-white' : 'bg-purple-500/10 hover:bg-purple-500/20' }} border border-purple-500/10">
                        <x-icon name="bookmark" class="w-5 h-5" />
                        <span>{{ $isBookmarked ? 'Tersimpan' : 'Simpan Buku' }}</span>
                    </button>
                </div>
            </div>

            {{-- Right Column: Book Details --}}
            <div class="col-span-12 md:col-span-8 lg:col-span-9 space-y-6">
                {{-- Book Title & Rating Action --}}
                <div class="glass-effect rounded-2xl p-6 border border-purple-500/10">
                    <div class="flex items-start justify-between mb-4">
                        <h1 class="text-2xl md:text-3xl font-bold">{{ $book->judul }}</h1>
                        <button wire:click="$set('showRatingModal', true)" 
                            class="flex items-center space-x-1 text-sm text-purple-400 hover:text-purple-300">
                            <x-icon name="star" class="w-5 h-5" />
                            <span>Beri Rating</span>
                        </button>
                    </div>
                    <div class="flex flex-wrap gap-4">
                        <div class="flex items-center space-x-2">
                            <x-icon name="user" class="w-5 h-5 text-gray-400" />
                            <span>{{ $book->penulis }}</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <x-icon name="building-library" class="w-5 h-5 text-gray-400" />
                            <span>{{ $book->penerbit }}</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <x-icon name="calendar" class="w-5 h-5 text-gray-400" />
                            <span>{{ $book->tahun_terbit }}</span>
                        </div>
                    </div>
                </div>

                {{-- Book Details --}}
                <div class="glass-effect rounded-2xl p-6 border border-purple-500/10">
                    <h2 class="text-xl font-semibold mb-4">Detail Buku</h2>
                    <div class="grid grid-cols-2 gap-6">
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
                            <p class="text-gray-400 mb-1">Denda per Hari</p>
                            <p class="font-medium">Rp {{ number_format($book->denda_harian, 0, ',', '.') }}</p>
                        </div>
                        <div>
                            <p class="text-gray-400 mb-1">Status</p>
                            <span class="inline-block px-3 py-1 {{ $book->stock > 0 ? 'bg-green-500/20 text-green-400' : 'bg-red-500/20 text-red-400' }} rounded-full text-sm">
                                {{ $book->stock > 0 ? 'Tersedia' : 'Tidak Tersedia' }}
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Description --}}
                <div class="glass-effect rounded-2xl p-6 border border-purple-500/10">
                    <h2 class="text-xl font-semibold mb-4">Deskripsi</h2>
                    <p class="text-gray-300 leading-relaxed">{{ $book->deskripsi }}</p>
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