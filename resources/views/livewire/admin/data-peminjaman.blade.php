<div>
    <div class="min-h-screen flex flex-col">
        <div class="flex-grow pt-16">
            <!-- Page Header -->
            <div class="mb-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold">Data Peminjaman</h1>
                        <p class="text-sm text-gray-400 mt-1">
                            Monitoring dan manajemen status peminjaman buku di perpustakaan BooKoo</p>
                    </div>
                </div>
            </div>

            <!-- Search & Filter Bar -->
            <div class="bg-[#1a1625] p-4 rounded-xl mb-6 shadow-lg">
                <div class="flex flex-col md:flex-row gap-4">
                    <div class="relative flex-1">
                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400" width="20"
                            height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="11" cy="11" r="8"></circle>
                            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                        </svg>
                        <input wire:model.live.debounce.300ms="search" type="search"
                            class="w-full bg-[#0f0a19] rounded-lg pl-10 pr-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-purple-500 border border-gray-800"
                            placeholder="Cari peminjaman..." />
                    </div>

                    <div class="flex flex-col sm:flex-row gap-3">
                        <!-- Status Filter -->
                        <div class="w-full sm:w-40">
                            <select wire:model.live="status"
                                class="w-full px-3 py-2.5 bg-[#0f0a19] rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 text-sm border border-gray-800">
                                <option value="">Semua Status</option>
                                @foreach ($statuses as $status)
                                    <option value="{{ $status }}">{{ $status }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Metode Pengiriman Filter -->
                        <div class="w-full sm:w-40">
                            <select wire:model.live="metode"
                                class="w-full px-3 py-2.5 bg-[#0f0a19] rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 text-sm border border-gray-800">
                                <option value="">Semua Metode</option>
                                @foreach ($metodes as $metode)
                                    <option value="{{ $metode }}">{{ str_replace('_', ' ', $metode) }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Date Range Filter -->
                        <div class="w-full sm:w-64" x-data="dateRangePicker">
                            <div class="relative">
                                <input type="text" readonly x-model="formattedRange" @click="toggleDatepicker"
                                    class="w-full px-3 py-2.5 bg-[#0f0a19] rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 text-sm cursor-pointer border border-gray-800"
                                    placeholder="Rentang Tanggal" />
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400"
                                    width="16" height="16" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2">
                                    </rect>
                                    <line x1="16" y1="2" x2="16" y2="6"></line>
                                    <line x1="8" y1="2" x2="8" y2="6"></line>
                                    <line x1="3" y1="10" x2="21" y2="10"></line>
                                </svg>
                            </div>

                            <div x-show="isOpen" @click.away="isOpen = false"
                                class="absolute mt-1 bg-[#1a1625] rounded-lg shadow-lg z-50 p-4 border border-gray-800 w-72"
                                x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="opacity-0 scale-95"
                                x-transition:enter-end="opacity-100 scale-100"
                                x-transition:leave="transition ease-in duration-100"
                                x-transition:leave-start="opacity-100 scale-100"
                                x-transition:leave-end="opacity-0 scale-95">

                                <!-- Date Selection Tabs -->
                                <div class="flex justify-between items-center mb-4">
                                    <button @click="switchToStartView()"
                                        class="text-sm font-medium px-3 py-1.5 rounded-lg transition-colors"
                                        :class="currentView === 'start' ? 'bg-purple-900 text-white' :
                                            'text-gray-300 hover:bg-gray-800'">
                                        Start Date <span x-show="startDate" x-text="formatDate(startDate)"
                                            class="text-xs"></span>
                                    </button>
                                    <button @click="switchToEndView()"
                                        class="text-sm font-medium px-3 py-1.5 rounded-lg transition-colors"
                                        :class="currentView === 'end' ? 'bg-purple-900 text-white' :
                                            'text-gray-300 hover:bg-gray-800'">
                                        End Date <span x-show="endDate" x-text="formatDate(endDate)"
                                            class="text-xs"></span>
                                    </button>
                                </div>

                                <!-- Calendar Title with Month/Year Navigation -->
                                <div class="flex justify-between items-center mb-3">
                                    <button @click="prevMonth()" class="p-1 hover:bg-gray-800 rounded-lg">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                            class="text-gray-400">
                                            <polyline points="15 18 9 12 15 6"></polyline>
                                        </svg>
                                    </button>
                                    <span class="text-sm font-medium"
                                        x-text="formatMonthYear(currentMonth, currentYear)"></span>
                                    <button @click="nextMonth()" class="p-1 hover:bg-gray-800 rounded-lg">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                            class="text-gray-400">
                                            <polyline points="9 18 15 12 9 6"></polyline>
                                        </svg>
                                    </button>
                                </div>

                                <!-- Calendar -->
                                <div class="grid grid-cols-7 gap-1 mb-1">
                                    <template x-for="day in ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa']"
                                        :key="day">
                                        <div class="text-center text-xs text-gray-500 font-medium py-1"
                                            x-text="day"></div>
                                    </template>
                                </div>
                                <div class="grid grid-cols-7 gap-1">
                                    <template x-for="blankday in blankDays" :key="'blank' + blankday">
                                        <div class="text-center text-xs text-gray-600 p-1 rounded-lg"></div>
                                    </template>
                                    <template x-for="(date, dateIndex) in days" :key="dateIndex">
                                        <div @click="selectDate(date)"
                                            :class="{
                                                'bg-purple-900 text-white': isSelectedDate(date),
                                                'bg-purple-900/20 text-purple-400': isInRange(date) && !isSelectedDate(
                                                    date),
                                                'hover:bg-gray-800': !isSelectedDate(date),
                                                'cursor-pointer': true
                                            }"
                                            class="text-center text-xs p-1 rounded-lg">
                                            <span x-text="date"></span>
                                        </div>
                                    </template>
                                </div>

                                <!-- Action Buttons -->
                                <div class="flex justify-end mt-4 space-x-2">
                                    <button @click="clearDates"
                                        class="px-3 py-1.5 text-xs bg-gray-800 text-gray-300 rounded-lg hover:bg-gray-700 transition-colors">
                                        Clear
                                    </button>
                                    <button @click="applyDateRange" :disabled="!startDate"
                                        :class="!startDate ? 'bg-gray-700 cursor-not-allowed' :
                                            'bg-purple-900 hover:bg-purple-800'"
                                        class="px-3 py-1.5 text-xs text-white rounded-lg transition-colors">
                                        Apply
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Data Table -->
            <div class="bg-[#1a1625] rounded-xl shadow-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-[#2a2435]">
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">
                                    Peminjam</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">
                                    Buku</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">
                                    Tanggal</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">
                                    Status</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">
                                    Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-800">
                            @forelse($peminjamans as $peminjaman)
                                <tr class="hover:bg-[#2a2435] transition-colors">
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="h-10 w-10 flex-shrink-0">
                                                @if($peminjaman->user->profile_img)
                                                    <img class="h-10 w-10 rounded-full object-cover" 
                                                        src="{{ asset('storage/' . $peminjaman->user->profile_img) }}" 
                                                        alt="{{ $peminjaman->user->name }}" />
                                                @else
                                                    <div class="h-10 w-10 rounded-full bg-purple-500/10 flex items-center justify-center">
                                                        <svg class="w-6 h-6 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                        </svg>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="ml-4">
                                                <div class="font-medium text-gray-200">{{ $peminjaman->user->name }}</div>
                                                <div class="text-sm text-gray-400">{{ $peminjaman->user->email }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="h-10 w-8 flex-shrink-0">
                                                @if($peminjaman->buku->cover_img)
                                                    <img class="h-10 w-8 rounded object-cover" 
                                                        src="{{ asset('storage/' . $peminjaman->buku->cover_img) }}" 
                                                        alt="{{ $peminjaman->buku->judul }}" />
                                                @else
                                                    <div class="h-10 w-8 rounded bg-purple-500/10 flex items-center justify-center">
                                                        <svg class="w-4 h-4 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                                d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                                        </svg>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="ml-4">
                                                <div class="font-medium text-gray-200">{{ $peminjaman->buku->judul }}</div>
                                                <div class="text-sm text-gray-400">{{ $peminjaman->buku->penulis }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <div class="text-sm text-gray-200">
                                            {{ $peminjaman->tgl_peminjaman_diinginkan->format('d M Y') }}
                                        </div>
                                        <div class="text-xs text-gray-400">
                                            {{ $peminjaman->tgl_peminjaman_diinginkan->format('H:i') }}
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            {{ $peminjaman->status === 'PENDING' ? 'bg-yellow-500/10 text-yellow-400' : '' }}
                                            {{ $peminjaman->status === 'DIPROSES' ? 'bg-blue-500/10 text-blue-400' : '' }}
                                            {{ $peminjaman->status === 'DIKIRIM' ? 'bg-indigo-500/10 text-indigo-400' : '' }}
                                            {{ $peminjaman->status === 'DIPINJAM' ? 'bg-green-500/10 text-green-400' : '' }}
                                            {{ $peminjaman->status === 'TERLAMBAT' ? 'bg-red-500/10 text-red-400' : '' }}
                                            {{ $peminjaman->status === 'DIKEMBALIKAN' ? 'bg-gray-500/10 text-gray-400' : '' }}
                                            {{ $peminjaman->status === 'DITOLAK' ? 'bg-red-500/10 text-red-400' : '' }}">
                                            {{ $peminjaman->status }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm">
                                        <div class="flex items-center space-x-2">
                                            @if($peminjaman->status === 'PENDING')
                                                <button wire:click="approvePeminjaman({{ $peminjaman->id }})"
                                                    class="p-2 bg-green-500/10 text-green-400 rounded-lg hover:bg-green-500/20 transition-colors">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                    </svg>
                                                </button>
                                                <button wire:click="showRejectModal({{ $peminjaman->id }})"
                                                    class="p-2 bg-red-500/10 text-red-400 rounded-lg hover:bg-red-500/20 transition-colors">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                    </svg>
                                                </button>
                                            @elseif($peminjaman->status === 'DIPROSES')
                                                <button wire:click="showShipmentModal({{ $peminjaman->id }})"
                                                    class="px-3 py-1 bg-purple-500/10 text-purple-400 rounded-lg hover:bg-purple-500/20 transition-colors">
                                                    Kirim Sekarang
                                                </button>
                                            @endif
                                            <button wire:click="showDetailModal({{ $peminjaman->id }})"
                                                class="p-2 bg-gray-500/10 text-gray-400 rounded-lg hover:bg-gray-500/20 transition-colors">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-8 text-center text-gray-400">
                                        <div class="flex flex-col items-center">
                                            <svg class="w-12 h-12 mb-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                            </svg>
                                            <p class="mb-1">Tidak ada data peminjaman</p>
                                            <p class="text-sm">Data peminjaman akan muncul di sini ketika ada permintaan baru</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="px-4 py-3 border-t border-gray-800">
                    {{ $peminjamans->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Reject -->
    <div class="relative z-50" aria-labelledby="modal-title" role="dialog" aria-modal="true"
        x-data="{ show: @entangle('showRejectModal') }"
        x-show="show"
        x-cloak>
        <div class="fixed inset-0 bg-black/50 transition-opacity"></div>

        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <div x-show="show"
                    x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    class="relative transform overflow-hidden rounded-lg bg-[#1a1625] px-4 pb-4 pt-5 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:p-6">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-red-500/10 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                            <h3 class="text-lg font-medium leading-6 text-gray-200" id="modal-title">
                                Tolak Peminjaman
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-400">
                                    Masukkan alasan penolakan peminjaman ini. Alasan akan ditampilkan kepada peminjam.
                                </p>
                            </div>
                            <div class="mt-4">
                                <textarea wire:model="alasanPenolakan"
                                    class="w-full bg-[#0f0a19] rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-500 border border-gray-800"
                                    rows="3"
                                    placeholder="Contoh: Buku sedang dalam perbaikan"></textarea>
                                @error('alasanPenolakan')
                                    <span class="text-sm text-red-400">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                        <button wire:click="rejectPeminjaman"
                            class="inline-flex w-full justify-center rounded-lg bg-red-500 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-600 sm:ml-3 sm:w-auto">
                            Tolak Peminjaman
                        </button>
                        <button wire:click="closeRejectModal"
                            class="mt-3 inline-flex w-full justify-center rounded-lg bg-[#2a2435] px-3 py-2 text-sm font-semibold text-gray-300 shadow-sm ring-1 ring-inset ring-gray-800 hover:bg-[#2a2435]/70 sm:mt-0 sm:w-auto">
                            Batal
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Shipment -->
    <div class="relative z-50" aria-labelledby="modal-title" role="dialog" aria-modal="true"
        x-data="{ show: @entangle('showShipmentModal') }"
        x-show="show"
        x-cloak>
        <div class="fixed inset-0 bg-black/50 transition-opacity"></div>

        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <div x-show="show"
                    x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    class="relative transform overflow-hidden rounded-lg bg-[#1a1625] px-4 pb-4 pt-5 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:p-6">
                    <div>
                        <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-purple-500/10">
                            <svg class="h-6 w-6 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <div class="mt-3 text-center">
                            <h3 class="text-lg font-medium leading-6 text-gray-200" id="modal-title">
                                Upload Bukti Pengiriman
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-400">
                                    Upload foto bukti pengiriman untuk memperbarui status peminjaman menjadi "Dikirim"
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="mt-5">
                        <div class="rounded-lg border-2 border-dashed border-gray-800 p-4">
                            <div class="text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <div class="mt-4 flex text-sm leading-6 text-gray-400">
                                    <label for="file-upload"
                                        class="relative cursor-pointer rounded-md bg-[#1a1625] font-semibold text-purple-400 focus-within:outline-none focus-within:ring-2 focus-within:ring-purple-500 focus-within:ring-offset-2 hover:text-purple-300">
                                        <span>Upload file</span>
                                        <input wire:model="buktiPengiriman" id="file-upload" type="file" class="sr-only">
                                    </label>
                                    <p class="pl-1">atau drag and drop</p>
                                </div>
                                <p class="text-xs text-gray-400">PNG, JPG, GIF up to 10MB</p>
                            </div>
                            @error('buktiPengiriman')
                                <span class="text-sm text-red-400">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="mt-5 sm:mt-6 sm:grid sm:grid-flow-row-dense sm:grid-cols-2 sm:gap-3">
                        <button wire:click="uploadBuktiPengiriman"
                            class="inline-flex w-full justify-center rounded-lg bg-purple-500 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-purple-600 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-purple-500 sm:col-start-2">
                            Upload dan Kirim
                        </button>
                        <button wire:click="closeShipmentModal"
                            class="mt-3 inline-flex w-full justify-center rounded-lg bg-[#2a2435] px-3 py-2 text-sm font-semibold text-gray-300 shadow-sm ring-1 ring-inset ring-gray-800 hover:bg-[#2a2435]/70 sm:col-start-1 sm:mt-0">
                            Batal
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
