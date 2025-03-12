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
                    class="relative p-3 rounded-xl hover:bg-purple-500/10 text-gray-400 hover:text-purple-400">
                    <x-icon name="bell" class="w-5 h-5" />
                    @if ($unreadCount > 0)
                        <span
                            class="absolute top-0 right-0 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">
                            {{ $unreadCount }}
                        </span>
                    @endif
                </button>
                @if ($isNotifikasiOpen)
                    <div class="absolute right-0 mt-2 w-96 bg-[#1A1A2E]/95 rounded-xl shadow-lg shadow-purple-500/10 border border-purple-500/10 backdrop-blur-xl z-50"
                        wire:click.stop>
                        <!-- Header -->
                        <div class="sticky top-0 z-10 bg-[#1A1A2E] border-b border-purple-500/10 backdrop-blur-xl">
                            <div class="flex items-center justify-between px-4 py-3">
                                <div class="flex items-center space-x-2">
                                    <x-icon name="bell" class="w-4 h-4 text-purple-400" />
                                    <h3 class="font-semibold">Notifikasi</h3>
                                    <div class="flex items-center space-x-2">
                                        @if (count($notifikasi) > 0)
                                            <span
                                                class="text-xs bg-purple-500/10 text-purple-400 px-2 py-0.5 rounded-full">
                                                {{ count($notifikasi) }}
                                            </span>
                                        @endif
                                        @if ($unreadCount > 0)
                                            <span
                                                class="text-xs bg-red-500/10 text-red-400 px-2 py-0.5 rounded-full flex items-center space-x-1">
                                                <span>{{ $unreadCount }}</span>
                                                <span>Baru</span>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                @if ($unreadCount > 0)
                                    <button wire:click="markAllAsRead"
                                        class="text-xs text-purple-400 hover:text-purple-300 transition-colors duration-200">
                                        Tandai semua dibaca
                                    </button>
                                @endif
                            </div>
                        </div>

                        <!-- Content -->
                        <div class="max-h-[360px] overflow-y-auto custom-scrollbar">
                            @if (count($notifikasi) === 0)
                                <div class="flex flex-col items-center justify-center py-8 px-4 text-gray-400">
                                    <x-icon name="bell" class="w-12 h-12 mb-3 opacity-20" />
                                    <p class="text-sm">Tidak ada notifikasi</p>
                                </div>
                            @else
                                <div class="divide-y divide-purple-500/10">
                                    @foreach ($notifikasi as $notif)
                                        <div wire:click="openNotifikasiModal({{ $notif['id'] }})"
                                            class="relative p-4 hover:bg-purple-500/5 cursor-pointer transition-all duration-200 group">
                                            <!-- Unread Indicator -->
                                            @if (!$notif['is_read'])
                                                <div
                                                    class="absolute top-4 right-4 w-2 h-2 bg-red-500 rounded-full animate-pulse">
                                                </div>
                                            @endif

                                            <div class="flex items-start space-x-3">
                                                <!-- Icon based on notification type -->
                                                @php
                                                    $iconClass = '';
                                                    $colorClass = '';
                                                    $badgeText = '';

                                                    if (isset($notif['type'])) {
                                                        switch ($notif['type']) {
                                                            case 'peminjaman':
                                                                $iconClass = 'package';
                                                                $colorClass = 'bg-blue-500/10 text-blue-500';
                                                                $badgeText = 'Permintaan Baru';
                                                                break;
                                                            case 'PEMINJAMAN_DIPROSES':
                                                                $iconClass = 'refresh-cw';
                                                                $colorClass = 'bg-purple-500/10 text-purple-500';
                                                                $badgeText = 'Diproses';
                                                                break;
                                                            case 'PEMINJAMAN_DIKIRIM':
                                                                $iconClass = 'check-circle';
                                                                $colorClass = 'bg-green-500/10 text-green-500';
                                                                $badgeText = 'Dikirim';
                                                                break;
                                                            case 'pengembalian':
                                                                $iconClass = 'book-open';
                                                                $colorClass = 'bg-teal-500/10 text-teal-500';
                                                                $badgeText = 'Dikembalikan';
                                                                break;
                                                            case 'DUE_REMINDER':
                                                                $iconClass = 'clock';
                                                                $colorClass = 'bg-yellow-500/10 text-yellow-500';
                                                                $badgeText = 'Pengingat';
                                                                break;
                                                            case 'OVERDUE_NOTICE':
                                                                $iconClass = 'alert-triangle';
                                                                $colorClass = 'bg-orange-500/10 text-orange-500';
                                                                $badgeText = 'Terlambat';
                                                                break;
                                                            default:
                                                                $iconClass = 'bell';
                                                                $colorClass = 'bg-gray-500/10 text-gray-500';
                                                                $badgeText = 'Notifikasi';
                                                                break;
                                                        }
                                                    }
                                                @endphp

                                                <div class="flex-shrink-0 p-2 rounded-xl {{ $colorClass }}">
                                                    <x-icon name="{{ $iconClass }}" class="w-5 h-5" />
                                                </div>

                                                <div class="flex-1 min-w-0">
                                                    <div class="flex items-center gap-2 mb-1 flex-wrap">
                                                        <span
                                                            class="text-xs px-2 py-0.5 rounded-full {{ $colorClass }}">
                                                            {{ $badgeText }}
                                                        </span>
                                                        @if ($notif['is_read'])
                                                            <span
                                                                class="text-xs bg-gray-500/10 text-gray-400 px-2 py-0.5 rounded-full flex items-center gap-1">
                                                                <x-icon name="eye" class="w-3 h-3" />
                                                                <span>Telah dibaca</span>
                                                            </span>
                                                        @else
                                                            <span
                                                                class="text-xs bg-red-500/10 text-red-400 px-2 py-0.5 rounded-full">
                                                                Belum dibaca
                                                            </span>
                                                        @endif
                                                        <span class="text-xs text-gray-400 ml-auto">
                                                            {{ isset($notif['created_at']) ? \Carbon\Carbon::parse($notif['created_at'])->diffForHumans() : 'Baru saja' }}
                                                        </span>
                                                    </div>
                                                    <p class="text-sm text-gray-200 leading-relaxed line-clamp-2">
                                                        {{ $notif['message'] }}
                                                    </p>
                                                    @if (!$notif['is_read'])
                                                        <div
                                                            class="flex items-center mt-2 text-xs text-purple-400 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                                                            <x-icon name="eye" class="w-3 h-3 mr-1" />
                                                            <span>Klik untuk menandai sudah dibaca</span>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>

                                            <!-- View Detail button -->
                                            <div
                                                class="absolute bottom-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                                <button
                                                    class="text-xs text-purple-400 hover:text-purple-300 px-2 py-1 rounded-full bg-purple-500/10 hover:bg-purple-500/20 transition-colors">
                                                    Lihat Detail
                                                </button>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>

                        <!-- Footer -->
                        @if (count($notifikasi) > 0)
                            <div class="sticky bottom-0 border-t border-purple-500/10 bg-[#1A1A2E] backdrop-blur-xl">
                                <div class="px-4 py-2 text-xs text-gray-400 text-center">
                                    @if ($unreadCount > 0)
                                        <span>
                                            {{ $unreadCount }} belum dibaca dari {{ count($notifikasi) }} notifikasi
                                        </span>
                                    @else
                                        <span>Semua notifikasi telah dibaca</span>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>
                @endif
            </div>

            <!-- Notification Detail Modal -->
            @if ($isNotifikasiModalOpen)
                <div class="fixed inset-0 bg-black/70 flex items-center justify-center z-50 backdrop-blur-sm"
                    wire:click.self="closeNotifikasiModal">
                    <div
                        class="bg-[#1A1A2E]/95 rounded-xl shadow-lg shadow-purple-500/10 border border-purple-500/10 p-5 w-96 max-w-[90vw]">
                        <!-- Header -->
                        <div class="flex items-center justify-between mb-5">
                            <h3 class="text-lg font-semibold text-white">Detail Notifikasi</h3>
                            <button wire:click="closeNotifikasiModal"
                                class="text-gray-400 hover:text-white transition-colors">
                                <x-icon name="x" class="h-5 w-5" />
                            </button>
                        </div>

                        <!-- Content -->
                        <div class="space-y-4">
                            <!-- Status & Time -->
                            <div class="flex justify-between items-center">
                                <div>
                                    @if ($selectedNotifikasi['is_read'])
                                        <span
                                            class="text-xs bg-gray-500/10 text-gray-400 px-2 py-0.5 rounded-full flex items-center gap-1">
                                            <x-icon name="eye" class="w-3 h-3" />
                                            <span>Telah dibaca</span>
                                        </span>
                                    @else
                                        <button wire:click="markAsRead({{ $selectedNotifikasi['id'] }})"
                                            class="text-xs bg-purple-500/10 text-purple-400 px-2 py-0.5 rounded-full hover:bg-purple-500/20 transition-colors flex items-center gap-1">
                                            <x-icon name="eye" class="w-3 h-3" />
                                            <span>Tandai sudah dibaca</span>
                                        </button>
                                    @endif
                                </div>
                                <span class="text-xs text-gray-400">
                                    {{ isset($selectedNotifikasi['created_at']) ? \Carbon\Carbon::parse($selectedNotifikasi['created_at'])->diffForHumans() : 'Baru saja' }}
                                </span>
                            </div>

                            <!-- Message -->
                            <div class="bg-purple-500/10 rounded-lg p-4 border border-purple-500/20">
                                <p class="text-gray-300">{{ $selectedNotifikasiDetail }}</p>
                            </div>

                            <!-- Info -->
                            <div class="grid grid-cols-2 gap-3 text-sm">
                                <div class="bg-purple-500/5 p-3 rounded-lg">
                                    <p class="text-gray-400 text-xs mb-1">Waktu</p>
                                    <p class="text-white">
                                        {{ isset($selectedNotifikasi['created_at']) ? \Carbon\Carbon::parse($selectedNotifikasi['created_at'])->format('d F Y, H:i') : 'Waktu tidak tersedia' }}
                                    </p>
                                </div>
                                <div class="bg-purple-500/5 p-3 rounded-lg">
                                    <p class="text-gray-400 text-xs mb-1">Tipe</p>
                                    <p class="text-white">
                                        @php
                                            $tipeName = 'Notifikasi';
                                            if (isset($selectedNotifikasi['type'])) {
                                                switch ($selectedNotifikasi['type']) {
                                                    case 'peminjaman':
                                                        $tipeName = 'Peminjaman Baru';
                                                        break;
                                                    case 'PEMINJAMAN_DIPROSES':
                                                        $tipeName = 'Peminjaman Diproses';
                                                        break;
                                                    case 'PEMINJAMAN_DIKIRIM':
                                                        $tipeName = 'Peminjaman Dikirim';
                                                        break;
                                                    case 'pengembalian':
                                                        $tipeName = 'Pengembalian';
                                                        break;
                                                    case 'DUE_REMINDER':
                                                        $tipeName = 'Pengingat Jatuh Tempo';
                                                        break;
                                                    case 'OVERDUE_NOTICE':
                                                        $tipeName = 'Pemberitahuan Keterlambatan';
                                                        break;
                                                }
                                            }
                                        @endphp
                                        {{ $tipeName }}
                                    </p>
                                </div>
                                @if (isset($selectedNotifikasi['peminjaman_id']))
                                    <div class="bg-purple-500/5 p-3 rounded-lg col-span-2">
                                        <p class="text-gray-400 text-xs mb-1">ID Peminjaman</p>
                                        <p class="text-white">#{!! $selectedNotifikasi['peminjaman_id'] !!}</p>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Footer -->
                        <div class="flex justify-end mt-6">
                            <button wire:click="closeNotifikasiModal"
                                class="bg-gradient-to-r from-purple-600 to-indigo-600 hover:opacity-90 rounded-xl px-6 py-2 font-medium transition-all duration-300">
                                Tutup
                            </button>
                        </div>
                    </div>
                </div>
            @endif

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

<!-- Modal for Notifikasi Detail -->
@if ($isNotifikasiModalOpen)
    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-[#1A1A2E] rounded-xl shadow-lg border border-purple-500/10 p-6 w-96">
            <h3 class="text-lg font-semibold text-white mb-4">Detail Notifikasi</h3>
            <p class="text-sm text-gray-400">{{ $selectedNotifikasiDetail }}</p>
            <div class="flex justify-end mt-4">
                <button wire:click="closeNotifikasiModal"
                    class="bg-gradient-to-r from-purple-600 to-indigo-600 hover:opacity-90 rounded-xl px-4 py-2 font-medium transition-all duration-300">
                    Tutup
                </button>
            </div>
        </div>
    </div>
@endif
