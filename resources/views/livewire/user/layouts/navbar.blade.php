<nav class="fixed top-0 right-0 left-0 md:left-20 glass-effect z-40 border-b border-purple-500/10">
    <div class="flex items-center justify-between px-4 md:px-8 py-4">
        <!-- Logo -->
        <a href="/" class="text-2xl font-bold bg-gradient-to-r from-purple-400 to-indigo-400 bg-clip-text text-transparent">
            Booktopia
        </a>

        <!-- Search (Desktop) -->
        <div class="hidden md:flex flex-1 max-w-2xl mx-12">
            <div class="relative w-full">
                <input 
                    wire:model.live.debounce.500ms="search" 
                    type="text" 
                    placeholder="Cari buku, penulis, atau genre.."
                    class="w-full bg-[#1A1A2E] rounded-xl pl-12 pr-4 py-3 focus:outline-none focus:ring-2 focus:ring-purple-500/50 border border-purple-500/10" 
                />
                <span class="absolute left-4 top-3.5 h-5 w-5 text-gray-500">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </span>
                
                @if(strlen(trim($search)) >= 2)
                    <div class="absolute top-full left-0 right-0 mt-2 bg-[#1A1A2E] rounded-xl shadow-lg border border-purple-500/10 overflow-hidden z-50">
                        @if(count($searchResults) > 0)
                            @foreach($searchResults as $book)
                                <div wire:click="viewBook({{ $book->id }})" class="flex items-center p-3 hover:bg-purple-500/10 cursor-pointer">
                                    <div class="flex-1">
                                        <p class="text-white">{{ $book->judul }}</p>
                                        <p class="text-sm text-gray-400">{{ $book->penulis }}</p>
                                        <span class="text-xs text-purple-400">{{ $book->kategori }}</span>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="p-4 text-center text-gray-400">
                                Tidak ada hasil untuk "{{ $search }}"
                            </div>
                        @endif
                    </div>
                @endif
            </div>
        </div>

        <!-- Icon Menu -->
        <div class="flex items-center space-x-6">
            <!-- Notifikasi -->
            <div class="relative">
                <button wire:click="toggleNotifikasi"
                    class="relative p-3 rounded-xl hover:bg-purple-500/10 text-gray-400 hover:text-purple-400">
                    <x-icon name="bell" class="w-5 h-5" />
                    @if($unreadCount > 0)
                        <span class="absolute top-0 right-0 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">
                            {{ $unreadCount }}
                        </span>
                    @endif
                </button>
                @if ($isNotifikasiOpen)
                    <div class="absolute right-0 top-full mt-2 w-64 bg-[#1A1A2E] rounded-xl shadow-lg border border-purple-500/10 py-2">
                        @if(count($notifikasi) > 0)
                            @foreach($notifikasi as $notif)
                                <div class="px-4 py-2 hover:bg-purple-500/10 {{ $notif['isRead'] ? 'text-gray-400' : 'text-white' }}">
                                    <div class="flex justify-between items-start">
                                        <p class="text-sm">{{ $notif['message'] }}</p>
                                        @if(!$notif['isRead'])
                                            <button wire:click="markAsRead({{ $notif['id'] }})" class="text-xs text-purple-400 hover:text-purple-300">
                                                Tandai dibaca
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <p class="text-center text-gray-400 text-sm py-2">Belum ada notifikasi</p>
                        @endif
                    </div>
                @endif
            </div>

            <!-- Keranjang -->
            <a href="/pesanan" class="relative p-3 rounded-xl hover:bg-purple-500/10 text-gray-400 hover:text-purple-400">
                <x-icon name="shopping-cart" class="w-5 h-5" />
            </a>

            <!-- Login/Profile -->
            @if ($user)
                <div class="relative">
                    <button wire:click="toggleProfileDropdown"
                        class="flex items-center space-x-2 p-2 rounded-xl hover:bg-purple-500/10">
                        <div class="w-8 h-8 rounded-full overflow-hidden border-2 border-purple-500">
                            @if ($user->profile_img)
                                <img src="{{ asset('storage/'. $user->profile_img) }}" alt="Profile" class="w-full h-full object-cover" />
                            @else
                                <div class="w-full h-full bg-purple-500 flex items-center justify-center">
                                    <x-icon name="user" class="h-5 w-5 text-white" />
                                </div>
                            @endif
                        </div>
                        <x-icon name="chevron-down" class="h-4 w-4 text-gray-400" />
                    </button>
                    @if ($isProfileDropdownOpen)
                        <div class="absolute right-0 top-full mt-2 w-64 bg-[#1A1A2E] rounded-xl shadow-lg border border-purple-500/10 py-2">
                            <div class="flex items-center space-x-3 px-4 py-3 border-b border-purple-500/10">
                                <div class="w-10 h-10 rounded-full overflow-hidden border-2 border-purple-500">
                                    @if ($user->profile_img)
                                        <img src="{{ asset('storage/'. $user->profile_img) }}" alt="Profile" class="w-full h-full object-cover" />
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
                            <a href="/profile" class="block w-full text-left px-4 py-2 hover:bg-purple-500/10 text-sm">
                                Profil Saya
                            </a>
                            <a href="/peminjaman" class="block w-full text-left px-4 py-2 hover:bg-purple-500/10 text-sm">
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
                    class="bg-gradient-to-r from-purple-600 to-indigo-600 hover:opacity-90 rounded-xl font-medium transition-all duration-300 px-6 py-2"
                >
                    Masuk
                </a>
            @endif
        </div>
    </div>
</nav>
