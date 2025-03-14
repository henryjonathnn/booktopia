<div class="px-4 md:px-8 lg:px-16 py-8 pt-32">
    <div class="max-w-7xl mx-auto">
        {{-- Hero Section dengan Cover dan Info Utama --}}
        <div class="glass-effect rounded-3xl p-8 border border-purple-500/10 mb-8">
            <div class="grid grid-cols-12 gap-8 items-center">
                {{-- Cover Image --}}
                <div class="col-span-12 md:col-span-4 lg:col-span-3">
                    @if ($book->cover_img)
                        <img src="{{ asset('storage/' . $book->cover_img) }}" alt="{{ $book->judul }}"
                            class="w-full rounded-2xl aspect-[3/4] object-cover shadow-lg" />
                    @else
                        <div class="w-full rounded-2xl aspect-[3/4] bg-gray-800 flex items-center justify-center">
                            <x-icon name="book-open" class="w-16 h-16 text-gray-600" />
                        </div>
                    @endif
                </div>

                {{-- Book Info --}}
                <div class="col-span-12 md:col-span-8 lg:col-span-9 space-y-6">
                    <div>
                        <div class="flex items-center gap-3 mb-2">
                            <span class="px-3 py-1 rounded-full text-sm font-medium bg-purple-500/20 text-purple-400">
                                {{ $book->kategori }}
                            </span>
                            <div class="flex items-center gap-1">
                                @for ($i = 1; $i <= 5; $i++)
                                    <x-icon name="star"
                                        class="w-5 h-5 {{ $i <= $book->average_rating ? 'text-yellow-400' : 'text-gray-600' }}" />
                                @endfor
                                <span
                                    class="text-sm font-medium ml-1">{{ number_format($book->average_rating, 1) }}</span>
                            </div>
                        </div>
                        <h1 class="text-4xl font-bold mb-2">{{ $book->judul }}</h1>
                        <p class="text-xl text-gray-400">oleh {{ $book->penulis }}</p>
                    </div>

                    {{-- Quick Stats --}}
                    <div class="grid grid-cols-3 gap-4">
                        <div class="glass-effect rounded-xl p-4 border border-purple-500/10">
                            <p class="text-gray-400 text-sm mb-1">Status</p>
                            <p class="font-semibold {{ $book->stock > 0 ? 'text-green-400' : 'text-red-400' }}">
                                {{ $book->stock > 0 ? 'Tersedia' : 'Tidak Tersedia' }}
                            </p>
                        </div>
                        <div class="glass-effect rounded-xl p-4 border border-purple-500/10">
                            <p class="text-gray-400 text-sm mb-1">Stok</p>
                            <p class="font-semibold">{{ $book->stock }} buku</p>
                        </div>
                        <div class="glass-effect rounded-xl p-4 border border-purple-500/10">
                            <p class="text-gray-400 text-sm mb-1">Total Peminjam</p>
                            <p class="font-semibold">{{ $book->total_peminjam ?? 0 }}</p>
                        </div>
                    </div>

                    {{-- Action Buttons --}}
                    <div class="flex gap-4">
                        @if ($book->stock > 0)
                            <button wire:click="createPeminjamanToken"
                                class="flex-1 bg-gradient-to-r from-purple-600 to-indigo-600 hover:opacity-90 rounded-xl py-3.5 font-medium transition-all duration-300">
                                Pinjam Buku
                            </button>
                        @endif
                        <button wire:click="toggleBookmark"
                            class="flex-none w-14 rounded-xl transition-all duration-300 flex items-center justify-center
                            {{ $isBookmarked ? 'bg-purple-500 text-white' : 'bg-purple-500/10 hover:bg-purple-500/20' }}">
                            <x-icon name="bookmark" class="w-6 h-6" />
                        </button>
                        <button wire:click="$set('showRatingModal', true)"
                            class="flex-none w-14 rounded-xl bg-purple-500/10 hover:bg-purple-500/20 transition-all duration-300 flex items-center justify-center">
                            <x-icon name="star" class="w-6 h-6 text-yellow-400" />
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Detail Sections --}}
        <div class="glass-effect rounded-3xl p-8 border border-purple-500/10 mb-8">
            {{-- Main Content --}}
            <div class="col-span-12 lg:col-span-8 space-y-8">
                {{-- Book Details --}}
                <div class="glass-effect rounded-2xl p-6 border border-purple-500/10">
                    <h2 class="text-xl font-semibold mb-4">Detail Buku</h2>
                    <div class="grid grid-cols-2 gap-6">
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
                            <p class="font-medium">{{ $book->isbn ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-gray-400 mb-1">Bahasa</p>
                            <p class="font-medium">{{ $book->bahasa ?? 'Indonesia' }}</p>
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
            </div>

            {{-- Sidebar --}}
            <div class="col-span-12 lg:col-span-4 space-y-8">
                {{-- Related Books --}}
                @if (count($relatedBooks) > 0)
                    <div class="glass-effect rounded-2xl p-6 border border-purple-500/10">
                        <h2 class="text-xl font-semibold mb-4">Buku Terkait</h2>
                        <div class="space-y-4">
                            @foreach ($relatedBooks as $relatedBook)
                                <a href="{{ route('buku.detail', ['slug' => \App\Livewire\Books\Detail::generateSlug($relatedBook)]) }}"
                                    class="flex items-start space-x-4 p-3 rounded-xl hover:bg-purple-500/5 transition-colors">
                                    @if ($relatedBook->cover_img)
                                        <img src="{{ asset('storage/' . $relatedBook->cover_img) }}"
                                            alt="{{ $relatedBook->judul }}" class="w-16 rounded-lg shadow" />
                                    @else
                                        <div class="w-16 h-24 rounded-lg bg-gray-800 flex items-center justify-center">
                                            <x-icon name="book-open" class="w-8 h-8 text-gray-600" />
                                        </div>
                                    @endif
                                    <div class="flex-1">
                                        <h3 class="font-medium line-clamp-2">{{ $relatedBook->judul }}</h3>
                                        <p class="text-sm text-gray-400">{{ $relatedBook->penulis }}</p>
                                        <div class="flex items-center mt-1">
                                            <x-icon name="star" class="w-4 h-4 text-yellow-400" />
                                            <span
                                                class="text-sm ml-1">{{ number_format($relatedBook->average_rating, 1) }}</span>
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Rating Modal --}}
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
