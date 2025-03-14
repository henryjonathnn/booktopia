<nav class="fixed top-0 right-0 left-0 md:left-20 glass-effect z-40 border-b border-purple-500/10">
    <div class="flex items-center justify-between px-4 md:px-8 py-4">
        <!-- Logo -->
        <a href="/"
            class="text-2xl font-bold bg-gradient-to-r from-purple-400 to-indigo-400 bg-clip-text text-transparent">
            Booktopia
        </a>

        <!-- Search (Desktop) -->
        <div class="hidden md:flex flex-1 max-w-2xl mx-12">
            <div class="relative w-full">
                <input wire:model.live.debounce.500ms="search" type="text"
                    placeholder="Cari buku, penulis, atau genre.."
                    class="w-full bg-[#1A1A2E] rounded-xl pl-12 pr-4 py-3 focus:outline-none focus:ring-2 focus:ring-purple-500/50 border border-purple-500/10" />
                <span class="absolute left-4 top-3.5 h-5 w-5 text-gray-500">
                    <svg wire:loading.remove wire:target="search" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <svg wire:loading wire:target="search" class="animate-spin" xmlns="http://www.w3.org/2000/svg"
                        fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                            stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor"
                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                        </path>
                    </svg>
                </span>

                @if (strlen(trim($search)) >= 2)
                    <div
                        class="absolute top-full left-0 right-0 mt-2 bg-[#1A1A2E] rounded-xl shadow-lg border border-purple-500/10 overflow-hidden z-50">
                        <div class="max-h-[60vh] overflow-y-auto">
                            @if (count($searchResults) > 0)
                                @foreach ($searchResults as $book)
                                    <div wire:click="viewBook({{ $book->id }})"
                                        class="flex items-center p-3 hover:bg-purple-500/10 cursor-pointer">
                                        {{-- Sampul Gambar --}}
                                        <div class="w-12 h-16 flex-shrink-0 rounded overflow-hidden bg-gray-800 mr-3">
                                            @if ($book->cover_img)
                                                <img src="{{ asset('storage/' . $book->cover_img) }}"
                                                    alt="{{ $book->judul }}" class="w-full h-full object-cover">
                                            @else
                                                <div
                                                    class="w-full h-full flex items-center justify-center bg-gray-700 ">
                                                    <svg class="w-6 h-6 text-gray-400" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                                    </svg>
                                                </div>
                                            @endif
                                        </div>

                                        {{-- Info buku --}}
                                        <div class="flex-1 min-w-0">
                                            <p class="text-white font-medium truncate">{{ $book->judul }}</p>
                                            <p class="text-sm text-gray-400 truncate">{{ $book->penulis }}</p>
                                            <span
                                                class="inline-block mt-1 text-xs px-2 py-0.5 bg-purple-500/20 text-purple-400 rounded-full">{{ $book->kategori }}</span>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="p-4 text-center text-gray-400">
                                    <svg class="w-6 h-6 mx-auto mb-2" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                    Tidak ada hasil untuk "{{ $search }}"
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Icon Menu -->
        <div class="flex items-center space-x-6">
            <!-- Notifikasi Dropdown -->
            <div class="relative">
                <button wire:click="toggleNotifikasi"
                    class="relative p-2 hover:bg-[#2a2435] rounded-lg text-gray-400">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>
                    @if($unreadCount > 0)
                        <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full"></span>
                    @endif
                </button>

                @if($showNotifikasi)
                    <div class="absolute right-0 mt-2 w-80 bg-[#1a1625] rounded-xl shadow-lg border border-purple-500/10 overflow-hidden z-50">
                        <!-- Header -->
                        <div class="p-4 border-b border-purple-500/10">
                            <div class="flex items-center justify-between">
                                <h3 class="font-medium">Notifikasi</h3>
                                @if($unreadCount > 0)
                                    <button wire:click="markAllAsRead"
                                        class="text-sm text-purple-400 hover:text-purple-300">
                                        Tandai semua telah dibaca
                                    </button>
                                @endif
                            </div>
                        </div>

                        <!-- Notification List -->
                        <div class="max-h-96 overflow-y-auto">
                            @forelse($notifikasi as $notif)
                                <div wire:key="notif-{{ $notif->id }}"
                                    class="p-4 border-b border-purple-500/10 hover:bg-purple-500/5 {{ !$notif->is_read ? 'bg-purple-500/5' : '' }}">
                                    <div class="flex items-start gap-3">
                                        <!-- Icon berdasarkan tipe -->
                                        <div @class([
                                            'flex-shrink-0 w-8 h-8 rounded-full flex items-center justify-center',
                                            'bg-blue-500/10 text-blue-400' => $notif->tipe === 'PEMINJAMAN_BARU',
                                            'bg-green-500/10 text-green-400' => $notif->tipe === 'PEMINJAMAN_DIPROSES',
                                            'bg-purple-500/10 text-purple-400' => $notif->tipe === 'PEMINJAMAN_DIKIRIM',
                                            'bg-red-500/10 text-red-400' => $notif->tipe === 'PEMINJAMAN_DITOLAK',
                                            'bg-yellow-500/10 text-yellow-400' => $notif->tipe === 'PEMINJAMAN_TERLAMBAT',
                                            'bg-gray-500/10 text-gray-400' => $notif->tipe === 'PEMINJAMAN_DIKEMBALIKAN',
                                        ])>
                                            @switch($notif->tipe)
                                                @case('PEMINJAMAN_BARU')
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                                    </svg>
                                                    @break
                                                @case('PEMINJAMAN_DIPROSES')
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                    </svg>
                                                    @break
                                                @case('PEMINJAMAN_DIKIRIM')
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7M13 3v18m0-18l6 6m-6-6L7 9" />
                                                    </svg>
                                                    @break
                                                @case('PEMINJAMAN_DITOLAK')
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                    </svg>
                                                    @break
                                                @case('PEMINJAMAN_TERLAMBAT')
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                    @break
                                                @default
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                                    </svg>
                                            @endswitch
                                        </div>

                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm text-gray-300">{{ $notif->message }}</p>
                                            <p class="text-xs text-gray-400 mt-1">{{ $notif->created_at->diffForHumans() }}</p>
                                        </div>

                                        <button wire:click="showDetail({{ $notif->id }})"
                                            class="flex-shrink-0 text-gray-400 hover:text-gray-300">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 5l7 7-7 7" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            @empty
                                <div class="p-8 text-center">
                                    <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-purple-500/10 flex items-center justify-center">
                                        <svg class="w-8 h-8 text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                        </svg>
                                    </div>
                                    <p class="text-gray-400">Belum ada notifikasi</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                @endif
            </div>

            <!-- Login/Profile -->
            @if ($user)
                <div class="relative">
                    <button wire:click="toggleProfileDropdown"
                        class="flex items-center space-x-2 p-2 rounded-xl hover:bg-purple-500/10">
                        <div class="w-8 h-8 rounded-full overflow-hidden border-2 border-purple-500">
                            @if ($user->profile_img)
                                <img src="{{ asset('storage/' . $user->profile_img) }}" alt="Profile"
                                    class="w-full h-full object-cover" />
                            @else
                                <div class="w-full h-full bg-purple-500 flex items-center justify-center">
                                    <x-icon name="user" class="h-5 w-5 text-white" />
                                </div>
                            @endif
                        </div>
                        <x-icon name="chevron-down" class="h-4 w-4 text-gray-400" />
                    </button>
                    @if ($isProfileDropdownOpen)
                        <div
                            class="absolute right-0 top-full mt-2 w-64 bg-[#1A1A2E] rounded-xl shadow-lg border border-purple-500/10 py-2">
                            <div class="flex items-center space-x-3 px-4 py-3 border-b border-purple-500/10">
                                <div class="w-10 h-10 rounded-full overflow-hidden border-2 border-purple-500">
                                    @if ($user->profile_img)
                                        <img src="{{ asset('storage/' . $user->profile_img) }}" alt="Profile"
                                            class="w-full h-full object-cover" />
                                    @else
                                        <div class="w-full h-full bg-purple-500 flex items-center justify-center">
                                            <x-icon name="user" class="h-6 w-6 text-white" />
                                        </div>
                                    @endif
                                </div>
                                <div>
                                    <p class="font-semibold text-sm">{{ $user->name }}</p>
                                    <p class="text-xs text-gray-400">{{ $user->email }}</p>
                                </div>
                            </div>
                            <a href="/profile"
                                class="block w-full text-left px-4 py-2 hover:bg-purple-500/10 text-sm">
                                Profil Saya
                            </a>
                            <a href="/peminjaman"
                                class="block w-full text-left px-4 py-2 hover:bg-purple-500/10 text-sm">
                                Peminjaman Saya
                            </a>
                            <button wire:click="logout"
                                class="w-full text-left px-4 py-2 hover:bg-purple-500/10 text-red-500 text-sm">
                                Logout
                            </button>
                        </div>
                    @endif
                </div>
            @else
                <a href="{{ route('login') }}"
                    class="bg-gradient-to-r from-purple-600 to-indigo-600 hover:opacity-90 rounded-xl font-medium transition-all duration-300 px-6 py-2">
                    Masuk
                </a>
            @endif
        </div>
    </div>
</nav>

<!-- Modal Detail Notifikasi -->
@if($selectedNotifikasi)
    <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex min-h-screen items-end justify-center p-4 text-center sm:items-center sm:p-0">
            <div class="fixed inset-0 bg-black/50 transition-opacity" wire:click="closeDetail"></div>

            <div class="relative transform overflow-hidden rounded-lg bg-[#1a1625] px-4 pb-4 pt-5 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:p-6">
                <div>
                    <div @class([
                        'mx-auto flex h-12 w-12 items-center justify-center rounded-full',
                        'bg-blue-500/10' => $selectedNotifikasi->tipe === 'PEMINJAMAN_BARU',
                        'bg-green-500/10' => $selectedNotifikasi->tipe === 'PEMINJAMAN_DIPROSES',
                        'bg-purple-500/10' => $selectedNotifikasi->tipe === 'PEMINJAMAN_DIKIRIM',
                        'bg-red-500/10' => $selectedNotifikasi->tipe === 'PEMINJAMAN_DITOLAK',
                        'bg-yellow-500/10' => $selectedNotifikasi->tipe === 'PEMINJAMAN_TERLAMBAT',
                        'bg-gray-500/10' => $selectedNotifikasi->tipe === 'PEMINJAMAN_DIKEMBALIKAN',
                    ])>
                        <!-- Icon sesuai tipe -->
                    </div>
                    <div class="mt-3 text-center sm:mt-5">
                        <h3 class="text-lg font-medium leading-6 text-gray-200">
                            Detail Notifikasi
                        </h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-400">
                                {{ $selectedNotifikasi->message }}
                            </p>
                            @if($selectedNotifikasi->peminjaman && $selectedNotifikasi->peminjaman->bukti_pengiriman && $selectedNotifikasi->tipe === 'PEMINJAMAN_DIKIRIM')
                                <div class="mt-4">
                                    <img src="{{ Storage::url($selectedNotifikasi->peminjaman->bukti_pengiriman) }}"
                                        alt="Bukti Pengiriman"
                                        class="max-h-64 mx-auto rounded-lg">
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="mt-5 sm:mt-6">
                    <button wire:click="closeDetail"
                        class="inline-flex w-full justify-center rounded-lg bg-purple-500 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-purple-600">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>
@endif
