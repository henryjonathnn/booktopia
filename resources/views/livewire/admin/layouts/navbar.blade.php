<div>
    <header
        class="bg-[#1a1625]/50 backdrop-blur-sm border-b border-purple-500/10 fixed right-0 left-0 md:left-64 top-0 z-20">
        <div class="flex justify-between items-center px-4 py-2">
            <div class="flex items-center space-x-4">
                <button wire:click="toggleSidebar" class="p-2 hover:bg-[#2a2435] rounded-lg md:hidden">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="feather feather-menu">
                        <line x1="3" y1="12" x2="21" y2="12"></line>
                        <line x1="3" y1="6" x2="21" y2="6"></line>
                        <line x1="3" y1="18" x2="21" y2="18"></line>
                    </svg>
                </button>
                <h2 class="text-xl md:text-2xl font-bold">Admin Dashboard</h2>
            </div>

            <div class="flex items-center space-x-4">
                <!-- Notifikasi Dropdown -->
                <div class="relative">
                    <button wire:click="toggleNotifikasi"
                        class="relative p-2 hover:bg-[#2a2435] rounded-lg text-gray-400">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="feather feather-bell">
                            <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
                            <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
                        </svg>
                        @if ($unreadCount > 0)
                            <span
                                class="absolute top-0 right-0 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">
                                {{ $unreadCount }}
                            </span>
                        @endif
                    </button>

                    @if ($isNotifikasiOpen)
                        <div class="absolute right-0 mt-2 w-[420px] bg-[#1A1A2E] rounded-xl shadow-lg border border-purple-500/10 overflow-hidden">
                            <!-- Header -->
                            <div class="p-4 border-b border-purple-500/10 flex justify-between items-center">
                                <div class="flex items-center space-x-2">
                                    <h3 class="font-medium">Notifikasi</h3>
                                    @if($unreadCount > 0)
                                        <span class="bg-red-500 text-white text-xs px-2 py-0.5 rounded-full">
                                            {{ $unreadCount }} baru
                                        </span>
                                    @endif
                                </div>
                                @if($unreadCount > 0)
                                    <button wire:click="markAllAsRead" 
                                        class="text-sm text-purple-400 hover:text-purple-300 transition-colors">
                                        Tandai semua telah dibaca
                                    </button>
                                @endif
                            </div>

                            <!-- Notification List -->
                            <div class="max-h-[480px] overflow-y-auto">
                                @forelse($notifikasi as $notif)
                                    <div wire:click="markAsRead({{ $notif['id'] }})"
                                        class="p-4 hover:bg-purple-500/10 cursor-pointer {{ !$notif['is_read'] ? 'bg-purple-500/5' : '' }} border-b border-purple-500/10">
                                        <div class="flex items-start space-x-3">
                                            <!-- Icon berdasarkan tipe notifikasi -->
                                            <div class="flex-shrink-0 w-10 h-10 rounded-full bg-purple-500/10 flex items-center justify-center">
                                                @if($notif['type'] === 'peminjaman')
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                                    </svg>
                                                @endif
                                            </div>

                                            <div class="flex-1 min-w-0">
                                                <!-- Message -->
                                                <p class="text-sm text-gray-200">
                                                    {{ $notif['message'] }}
                                                </p>

                                                <!-- Timestamp & Status -->
                                                <div class="mt-1 flex items-center space-x-2">
                                                    <span class="text-xs text-gray-400">
                                                        {{ \Carbon\Carbon::parse($notif['created_at'])->diffForHumans() }}
                                                    </span>
                                                    @if(!$notif['is_read'])
                                                        <span class="flex items-center text-xs text-purple-400">
                                                            <span class="w-1.5 h-1.5 bg-purple-400 rounded-full mr-1"></span>
                                                            Belum dibaca
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>

                                            <!-- Action Button -->
                                            <button class="flex-shrink-0 p-2 hover:bg-purple-500/20 rounded-lg transition-colors">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                @empty
                                    <div class="p-8 text-center">
                                        <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-purple-500/10 flex items-center justify-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                            </svg>
                                        </div>
                                        <p class="text-gray-400 mb-1">Tidak ada notifikasi</p>
                                        <p class="text-sm text-gray-500">Anda akan melihat notifikasi ketika ada aktivitas baru</p>
                                    </div>
                                @endforelse
                            </div>

                            <!-- Footer -->
                            @if(count($notifikasi) > 0)
                                <div class="p-4 border-t border-purple-500/10">
                                    <button class="w-full text-center text-sm text-purple-400 hover:text-purple-300 transition-colors">
                                        Lihat semua notifikasi
                                    </button>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>

                <!-- Login/Profile -->
                @if ($user)
                    <div class="relative profile-dropdown">
                        <button wire:click="$toggle('isProfileDropdownOpen')"
                            class="flex items-center space-x-3 p-2 hover:bg-[#2a2435] rounded-lg transition-colors duration-200">
                            <div
                                class="relative w-8 h-8 rounded-lg overflow-hidden bg-purple-500/10 border-2 border-purple-500">
                                @if ($user->profile_img)
                                    <img src={{ asset('storage/'. $user->profile_img) }} alt="Profile"
                                        class="w-full h-full object-cover" />
                                @else
                                    <div class="w-full h-full flex items-center justify-center bg-purple-500">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round"
                                            class="feather feather-user text-white">
                                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                            <circle cx="12" cy="7" r="4"></circle>
                                        </svg>
                                    </div>
                                @endif
                            </div>
                            <div class="hidden md:block text-left">
                                <p class="text-sm font-medium">{{ $user->name }}</p>
                                <p class="text-xs text-gray-400">{{ $user->role }}</p>
                            </div>
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round"
                                class="feather feather-chevron-down hidden md:block text-gray-400">
                                <polyline points="6 9 12 15 18 9"></polyline>
                            </svg>
                        </button>

                        <!-- Dropdown Menu -->
                        @if ($isProfileDropdownOpen)
                            <div 
                                class="absolute right-0 mt-2 w-64 bg-[#1a1625] rounded-xl shadow-lg border border-purple-500/10 py-1 z-50">
                                <div class="flex items-center space-x-3 px-4 py-3 border-b border-purple-500/10">
                                    <div class="w-10 h-10 rounded-full overflow-hidden border-2 border-purple-500">
                                        <img src="{{ asset('storage/' . $user->profile_img ?? 'default-avatar.png') }}"
                                            alt="Profile" class="w-full h-full object-cover" />
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold truncate">{{ $user->name }}</p>
                                        <p class="text-xs text-gray-400 truncate">{{ $user->email }}</p>
                                    </div>
                                </div>
                                <div class="py-1">
                                    <a href="#" wire:click.prevent="$set('isProfileDropdownOpen', false)"
                                        class="flex items-center space-x-3 px-4 py-2 text-sm hover:bg-purple-500/10 transition-colors duration-200">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round"
                                            class="feather feather-user text-purple-400">
                                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                            <circle cx="12" cy="7" r="4"></circle>
                                        </svg>
                                        <span>Profil Saya</span>
                                    </a>

                                    <a href="#" wire:click.prevent="$set('isProfileDropdownOpen', false)"
                                        class="flex items-center space-x-3 px-4 py-2 text-sm hover:bg-purple-500/10 transition-colors duration-200">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round"
                                            class="feather feather-settings text-purple-400">
                                            <circle cx="12" cy="12" r="3"></circle>
                                            <path
                                                d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z">
                                            </path>
                                        </svg>
                                        <span>Pengaturan</span>
                                    </a>
                                </div>

                                <div class="border-t border-purple-500/10 py-1">
                                    <button wire:click="logout"
                                        class="w-full flex items-center space-x-3 px-4 py-2 text-sm text-red-400 hover:bg-red-500/10 transition-colors duration-200">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round"
                                            class="feather feather-log-out">
                                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                                            <polyline points="16 17 21 12 16 7"></polyline>
                                            <line x1="21" y1="12" x2="9" y2="12"></line>
                                        </svg>
                                        <span>Keluar</span>
                                    </button>
                                </div>
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
    </header>
</div>