<div class="px-4 md:px-8 lg:px-16 py-8 pt-32">
    <div class="max-w-7xl mx-auto">
        {{-- Hero Section dengan Cover dan Info Utama --}}
        <div class="glass-effect rounded-3xl p-8 border border-purple-500/10 mb-8">
            <div class="grid grid-cols-12 gap-8 items-start">
                {{-- Cover Image --}}
                <div class="col-span-12 md:col-span-4 lg:col-span-3 relative">
                    {{-- Love Button --}}
                    <div class="absolute top-4 right-4 z-10">
                        <button wire:click="toggleLike"
                            class="p-2.5 rounded-xl bg-black/50 hover:bg-black/70 transition-all duration-300">
                            <x-icon name="heart" 
                                class="w-6 h-6 {{ $isLiked ? 'text-purple-500 fill-purple-500' : 'text-white' }}" />
                        </button>
                    </div>

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
                            <div class="flex items-center gap-2">
                                {{-- Rating --}}
                                <div class="flex items-center gap-1">
                                    @for ($i = 1; $i <= 5; $i++)
                                        @if ($i <= $book->average_rating)
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                                class="w-5 h-5 text-yellow-400">
                                                <path fill-rule="evenodd"
                                                    d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.007 5.404.433c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.433 2.082-5.006z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        @else
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-gray-600">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z" />
                                            </svg>
                                        @endif
                                    @endfor
                                    <span class="text-sm font-medium ml-1">{{ number_format($book->average_rating, 1) }}</span>
                                </div>
                                {{-- Like Count --}}
                                <div class="flex items-center gap-1 text-purple-400">
                                    <x-icon name="heart" class="w-5 h-5 {{ $isLiked ? 'fill-purple-500' : '' }}" />
                                    <span class="text-sm font-medium">{{ $book->sukas_count }}</span>
                                </div>
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

            {{-- Ratings Section --}}
            <div class="glass-effect rounded-2xl p-6 border border-purple-500/10 mb-8 mt-8">
                <h2 class="text-xl font-semibold mb-4">Ulasan & Rating</h2>

                @if (count($bookRatings) > 0)
                    <div class="space-y-6">
                        @foreach ($bookRatings as $rating)
                            <div class="glass-effect rounded-xl p-5 border border-purple-500/10">
                                <div class="flex items-start gap-3">
                                    @if ($rating->user->profile_img)
                                        <img src="{{ asset('storage/' . $rating->user->profile_img) }}" alt="User"
                                            class="w-10 h-10 rounded-full object-cover" />
                                    @else
                                        <div
                                            class="w-10 h-10 rounded-full bg-purple-500/20 flex items-center justify-center">
                                            <span
                                                class="text-purple-400 font-medium">{{ substr($rating->user->name, 0, 1) }}</span>
                                        </div>
                                    @endif

                                    <div class="flex-1">
                                        <div class="flex items-center justify-between">
                                            <h3 class="font-medium">{{ $rating->user->name }}</h3>
                                            <span
                                                class="text-xs text-gray-400">{{ $rating->created_at->diffForHumans() }}</span>
                                        </div>

                                        <div class="flex items-center gap-1 my-1">
                                            @for ($i = 1; $i <= 5; $i++)
                                                @if ($i <= $rating->rating)
                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                                        fill="currentColor" class="w-4 h-4 text-yellow-400">
                                                        <path fill-rule="evenodd"
                                                            d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.007 5.404.433c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.433 2.082-5.006z"
                                                            clip-rule="evenodd" />
                                                    </svg>
                                                @else
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                        class="w-4 h-4 text-gray-600">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z" />
                                                    </svg>
                                                @endif
                                            @endfor
                                        </div>

                                        <p class="text-gray-300 mt-2">{{ $rating->komentar }}</p>

                                        @if ($rating->url_foto)
                                            <div class="mt-3">
                                                <img src="{{ asset('storage/' . $rating->url_foto) }}"
                                                    alt="Rating Photo" class="rounded-lg max-h-48 cursor-pointer"
                                                    wire:click="showPhotoModal('{{ $rating->url_foto }}')" />
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach

                        @if ($bookRatings->hasPages())
                            <div class="mt-4">
                                {{ $bookRatings->links() }}
                            </div>
                        @endif
                    </div>
                @else
                    <div class="glass-effect rounded-xl p-6 border border-purple-500/10 text-center">
                        <x-icon name="chat-bubble-left" class="w-12 h-12 text-gray-600 mx-auto mb-3" />
                        <p class="text-gray-400">Belum ada ulasan untuk buku ini.</p>
                    </div>
                @endif
            </div>

            {{-- Photo Modal --}}
            @if ($showModal)
                <div class="fixed inset-0 bg-black/70 flex items-center justify-center z-50 p-4"
                    wire:click="hidePhotoModal">
                    <div class="max-w-3xl max-h-[90vh] overflow-hidden rounded-xl">
                        <img src="{{ asset('storage/' . $modalPhoto) }}" alt="Rating Photo"
                            class="max-w-full max-h-[90vh] object-contain" />
                    </div>
                </div>
            @endif

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
</div>
