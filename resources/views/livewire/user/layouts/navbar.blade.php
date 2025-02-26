<nav class="fixed top-0 right-0 left-0 md:left-20 glass-effect z-40 border-b border-purple-500/10">
    <div class="flex items-center justify-between px-4 md:px-8 py-4">
        <!-- Logo -->
        <a href="/" class="text-2xl font-bold bg-gradient-to-r from-purple-400 to-indigo-400 bg-clip-text text-transparent">
            Booktopia
        </a>

        <!-- Search (Desktop) -->
        <div class="hidden md:flex flex-1 max-w-2xl mx-12">
            <div class="relative w-full">
                <input type="text" placeholder="Cari buku, penulis, atau genre.."
                    class="w-full bg-[#1A1A2E] rounded-xl pl-12 pr-4 py-3 focus:outline-none focus:ring-2 focus:ring-purple-500/50 border border-purple-500/10" />
                <svg class="absolute left-4 top-3.5 h-5 w-5 text-gray-500" fill="none" stroke="currentColor" stroke-width="2"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 15l5 5m-5-5a7 7 0 1 0-10 0 7 7 0 0 0 10 0z" />
                </svg>
            </div>
        </div>

        <!-- Icon Menu -->
        <div class="flex items-center space-x-6">
            <!-- Notifikasi -->
            <div class="relative">
                <button wire:click="toggleNotifikasi"
                    class="relative p-3 rounded-xl hover:bg-purple-500/10 text-gray-400 hover:text-purple-400">
                    <x-icon name="bell" class="w-5 h-5" />
                </button>
                @if ($isNotifikasiOpen)
                    <div class="absolute right-0 top-full mt-2 w-64 bg-[#1A1A2E] rounded-xl shadow-lg border border-purple-500/10 py-2">
                        <p class="text-center text-gray-400 text-sm">Belum ada notifikasi</p>
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
                                <img src="{{ asset($user->profile_img) }}" alt="Profile" class="w-full h-full object-cover" />
                            @else
                                <div class="w-full h-full bg-purple-500 flex items-center justify-center">
                                    <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" stroke-width="2"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M16 14a4 4 0 0 0-8 0m8 0a4 4 0 1 1-8 0m8 0v1a4 4 0 1 1-8 0v-1m8 0H9" />
                                    </svg>
                                </div>
                            @endif
                        </div>
                        <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    @if ($isProfileDropdownOpen)
                        <div class="absolute right-0 top-full mt-2 w-64 bg-[#1A1A2E] rounded-xl shadow-lg border border-purple-500/10 py-2">
                            <div class="flex items-center space-x-3 px-4 py-3 border-b border-purple-500/10">
                                <div class="w-10 h-10 rounded-full overflow-hidden border-2 border-purple-500">
                                    <img src="{{ asset($user->profile_img ?? 'default-avatar.png') }}" alt="Profile"
                                        class="w-full h-full object-cover" />
                                </div>
                                <div>
                                    <p class="font-semibold text-sm">{{ $user->name }}</p>
                                    <p class="text-xs text-gray-400">{{ $user->email }}</p>
                                </div>
                            </div>
                            <button wire:click="logout"
                                class="w-full text-left px-4 py-2 hover:bg-purple-500/10 text-red-500 text-sm">
                                Logout
                            </button>
                        </div>
                    @endif
                </div>
            @else
                <button
                    wire:click="toggleAuthModal"
                    class="bg-gradient-to-r from-purple-600 to-indigo-600 hover:opacity-90 rounded-xl font-medium transition-all duration-300 px-6 py-2"
                >
                    Masuk
                </button>
            @endif
        </div>
    </div>

    <!-- Auth Modal -->
    @livewire('auth.auth-modal', ['isOpen' => $isAuthModalOpen])
</nav>
