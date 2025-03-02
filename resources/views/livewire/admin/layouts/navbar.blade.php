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
                <button class="relative p-2 hover:bg-[#2a2435] rounded-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" class="feather feather-bell">
                        <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
                        <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
                    </svg>
                    <span class="absolute top-1 right-1 w-2 h-2 bg-purple-500 rounded-full"></span>
                </button>

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