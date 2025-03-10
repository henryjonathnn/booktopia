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
            <div class="bg-[#1a1625] p-4 rounded-xl mb-6">
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
                            class="w-full bg-[#0f0a19] rounded-lg pl-10 pr-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-purple-500"
                            placeholder="Cari peminjaman..." />
                    </div>

                    <div class="flex flex-col sm:flex-row gap-3">
                        <!-- Status Filter -->
                        <div class="w-full sm:w-40">
                            <select wire:model.live="status"
                                class="w-full px-3 py-2.5 bg-[#0f0a19] rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 text-sm">
                                <option value="">Semua Status</option>
                                @foreach ($statuses as $status)
                                    <option value="{{ $status }}">{{ $status }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Metode Pengiriman Filter -->
                        <div class="w-full sm:w-40">
                            <select wire:model.live="metode"
                                class="w-full px-3 py-2.5 bg-[#0f0a19] rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 text-sm">
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
                                    class="w-full px-3 py-2.5 bg-[#0f0a19] rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 text-sm cursor-pointer"
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
                                class="absolute mt-1 bg-[#1a1625] rounded-lg shadow-lg z-50 p-4 border border-gray-800"
                                x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="opacity-0 scale-95"
                                x-transition:enter-end="opacity-100 scale-100"
                                x-transition:leave="transition ease-in duration-100"
                                x-transition:leave-start="opacity-100 scale-100"
                                x-transition:leave-end="opacity-0 scale-95">
                                <div class="flex space-x-4">
                                    <!-- Start Date Calendar -->
                                    <div class="w-64">
                                        <div class="flex justify-between items-center mb-2">
                                            <span class="text-sm font-medium text-gray-300">Start Date</span>
                                            <div class="flex space-x-1">
                                                <button @click="prevMonth('start')"
                                                    class="p-1 hover:bg-gray-800 rounded-lg">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16"
                                                        height="16" viewBox="0 0 24 24" fill="none"
                                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                        stroke-linejoin="round" class="text-gray-400">
                                                        <polyline points="15 18 9 12 15 6"></polyline>
                                                    </svg>
                                                </button>
                                                <button @click="nextMonth('start')"
                                                    class="p-1 hover:bg-gray-800 rounded-lg">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16"
                                                        height="16" viewBox="0 0 24 24" fill="none"
                                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                        stroke-linejoin="round" class="text-gray-400">
                                                        <polyline points="9 18 15 12 9 6"></polyline>
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="text-center mb-2 text-sm font-medium"
                                            x-text="formatMonthYear(startMonth, startYear)"></div>
                                        <div class="grid grid-cols-7 gap-1 mb-1">
                                            <template x-for="day in ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa']"
                                                :key="day">
                                                <div class="text-center text-xs text-gray-500 font-medium py-1"
                                                    x-text="day"></div>
                                            </template>
                                        </div>
                                        <div class="grid grid-cols-7 gap-1">
                                            <template x-for="blankday in startBlankDays"
                                                :key="'startblank' + blankday">
                                                <div class="text-center text-xs text-gray-600 p-1 rounded-lg"></div>
                                            </template>
                                            <template x-for="(date, dateIndex) in startDays"
                                                :key="'start' + dateIndex">
                                                <div @click="selectDate(date, 'start')"
                                                    :class="{
                                                        'bg-purple-900 text-white': isSelectedStartDate(date),
                                                        'bg-purple-900/20 text-purple-400': isInRange(date) && !
                                                            isSelectedStartDate(date) && !isSelectedEndDate(date),
                                                        'hover:bg-gray-800': !isSelectedStartDate(date),
                                                        'cursor-pointer': true
                                                    }"
                                                    class="text-center text-xs p-1 rounded-lg">
                                                    <span x-text="date"></span>
                                                </div>
                                            </template>
                                        </div>
                                    </div>

                                    <!-- End Date Calendar -->
                                    <div class="w-64">
                                        <div class="flex justify-between items-center mb-2">
                                            <span class="text-sm font-medium text-gray-300">End Date</span>
                                            <div class="flex space-x-1">
                                                <button @click="prevMonth('end')"
                                                    class="p-1 hover:bg-gray-800 rounded-lg">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16"
                                                        height="16" viewBox="0 0 24 24" fill="none"
                                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                        stroke-linejoin="round" class="text-gray-400">
                                                        <polyline points="15 18 9 12 15 6"></polyline>
                                                    </svg>
                                                </button>
                                                <button @click="nextMonth('end')"
                                                    class="p-1 hover:bg-gray-800 rounded-lg">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16"
                                                        height="16" viewBox="0 0 24 24" fill="none"
                                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                        stroke-linejoin="round" class="text-gray-400">
                                                        <polyline points="9 18 15 12 9 6"></polyline>
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="text-center mb-2 text-sm font-medium"
                                            x-text="formatMonthYear(endMonth, endYear)"></div>
                                        <div class="grid grid-cols-7 gap-1 mb-1">
                                            <template x-for="day in ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa']"
                                                :key="day">
                                                <div class="text-center text-xs text-gray-500 font-medium py-1"
                                                    x-text="day"></div>
                                            </template>
                                        </div>
                                        <div class="grid grid-cols-7 gap-1">
                                            <template x-for="blankday in endBlankDays" :key="'endblank' + blankday">
                                                <div class="text-center text-xs text-gray-600 p-1 rounded-lg"></div>
                                            </template>
                                            <template x-for="(date, dateIndex) in endDays" :key="'end' + dateIndex">
                                                <div @click="selectDate(date, 'end')"
                                                    :class="{
                                                        'bg-purple-900 text-white': isSelectedEndDate(date),
                                                        'bg-purple-900/20 text-purple-400': isInRange(date) && !
                                                            isSelectedStartDate(date) && !isSelectedEndDate(date),
                                                        'hover:bg-gray-800': !isSelectedEndDate(date),
                                                        'cursor-pointer': true
                                                    }"
                                                    class="text-center text-xs p-1 rounded-lg">
                                                    <span x-text="date"></span>
                                                </div>
                                            </template>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex justify-end mt-4 space-x-2">
                                    <button @click="clearDates"
                                        class="px-3 py-1.5 text-xs bg-gray-800 text-gray-300 rounded-lg hover:bg-gray-700 transition-colors">
                                        Clear
                                    </button>
                                    <button @click="applyDateRange"
                                        class="px-3 py-1.5 text-xs bg-purple-900 text-white rounded-lg hover:bg-purple-800 transition-colors">
                                        Apply
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Peminjaman Table -->
            <div class="overflow-x-auto bg-[#1a1625] rounded-xl">
                <table class="min-w-full">
                    <thead>
                        <tr class="border-b border-gray-800">
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">
                                Info Peminjaman
                            </th>
                            <th
                                class="px-6 py-3 text-center text-xs font-medium text-gray-400 uppercase tracking-wider">
                                Status
                            </th>
                            <th
                                class="hidden md:table-cell px-6 py-3 text-center text-xs font-medium text-gray-400 uppercase tracking-wider">
                                Metode
                            </th>
                            <th
                                class="hidden md:table-cell px-6 py-3 text-center text-xs font-medium text-gray-400 uppercase tracking-wider">
                                Tanggal
                            </th>
                            <th
                                class="px-6 py-3 text-right sm:text-left text-xs font-medium text-gray-400 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($peminjamans as $peminjaman)
                            <tr class="border-b border-gray-800 hover:bg-[#2a2435] transition-colors cursor-pointer"
                                wire:click="viewPeminjamanDetails({{ $peminjaman->id }})">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-3">
                                        <div class="min-w-0">
                                            <div class="font-medium text-white">{{ $peminjaman->user->name }}</div>
                                            <div class="text-sm text-gray-400">{{ $peminjaman->buku->judul }}</div>
                                            @if ($peminjaman->nomor_resi)
                                                <div class="text-xs text-gray-500">Resi: {{ $peminjaman->nomor_resi }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <span
                                        class="px-2 py-1 text-xs inline-flex leading-5 font-medium rounded-full 
                                        @switch($peminjaman->status)
                                            @case('PENDING')
                                                bg-yellow-500/10 text-yellow-400
                                                @break
                                            @case('DIPROSES')
                                                bg-blue-500/10 text-blue-400
                                                @break
                                            @case('DIKIRIM')
                                                bg-indigo-500/10 text-indigo-400
                                                @break
                                            @case('DIPINJAM')
                                                bg-green-500/10 text-green-400
                                                @break
                                            @case('TERLAMBAT')
                                                bg-red-500/10 text-red-400
                                                @break
                                            @case('DIKEMBALIKAN')
                                                bg-purple-500/10 text-purple-400
                                                @break
                                            @case('DITOLAK')
                                                bg-gray-500/10 text-gray-400
                                                @break
                                            @default
                                                bg-gray-500/10 text-gray-400
                                        @endswitch">
                                        {{ $peminjaman->status }}
                                    </span>
                                </td>
                                <td
                                    class="hidden md:table-cell px-6 py-4 whitespace-nowrap text-center text-sm text-gray-400">
                                    {{ str_replace('_', ' ', $peminjaman->metode_pengiriman) }}
                                </td>
                                <td
                                    class="hidden md:table-cell px-6 py-4 whitespace-nowrap text-center text-sm text-gray-400">
                                    {{ $peminjaman->tgl_peminjaman_diinginkan->format('d M Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap" onclick="event.stopPropagation();">
                                    <div class="flex justify-end sm:justify-start gap-2">
                                        <!-- Status Update Button -->
                                        <div class="relative" x-data="{ open: false }">
                                            <button @click="open = !open"
                                                class="p-1.5 hover:bg-gray-800 rounded-lg transition-colors">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                    class="text-gray-400">
                                                    <circle cx="12" cy="12" r="1"></circle>
                                                    <circle cx="12" cy="5" r="1"></circle>
                                                    <circle cx="12" cy="19" r="1"></circle>
                                                </svg>
                                            </button>
                                            <div x-show="open" @click.away="open = false"
                                                class="absolute right-0 mt-2 w-48 bg-[#0f0a19] rounded-lg shadow-xl z-10">
                                                @foreach ($statuses as $status)
                                                    @if ($status !== $peminjaman->status)
                                                        <button
                                                            wire:click="updateStatus({{ $peminjaman->id }}, '{{ $status }}')"
                                                            class="block w-full text-left px-4 py-2 text-sm text-gray-300 hover:bg-gray-800">
                                                            Ubah ke {{ $status }}
                                                        </button>
                                                    @endif
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-4 text-center text-gray-400">
                                    Tidak ada peminjaman yang ditemukan
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-4">
                {{ $peminjamans->links() }}
            </div>

            <!-- Detail Modal -->
            @if ($selectedPeminjaman && $isDetailModalOpen)
                <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                    <div class="bg-[#0f0a19] rounded-lg shadow-xl max-w-xl w-full max-h-[90vh] overflow-y-auto">
                        <div class="p-6">
                            <!-- Header with title and close button -->
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-xl font-semibold">Detail Peminjaman</h3>
                                <button wire:click="closeDetailModal" class="text-gray-400 hover:text-white">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>

                            <!-- Peminjaman detail content -->
                            <div class="space-y-6">
                                <!-- User & Book Info -->
                                <div>
                                    <h3 class="text-sm font-medium text-gray-500 uppercase mb-2">Informasi Peminjam &
                                        Buku</h3>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <p class="text-xs text-gray-500">Peminjam</p>
                                            <p class="text-sm">{{ $selectedPeminjaman->user->name }}</p>
                                        </div>
                                        <div>
                                            <p class="text-xs text-gray-500">Buku</p>
                                            <p class="text-sm">{{ $selectedPeminjaman->buku->judul }}</p>
                                        </div>
                                        <div>
                                            <p class="text-xs text-gray-500">Staff</p>
                                            <p class="text-sm">{{ $selectedPeminjaman->staff->name ?? '-' }}</p>
                                        </div>
                                        <div>
                                            <p class="text-xs text-gray-500">Status</p>
                                            <p class="text-sm">{{ $selectedPeminjaman->status }}</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Shipping Info -->
                                <div>
                                    <h3 class="text-sm font-medium text-gray-500 uppercase mb-2">Informasi Pengiriman
                                    </h3>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div class="col-span-2">
                                            <p class="text-xs text-gray-500">Alamat Pengiriman</p>
                                            <p class="text-sm">{{ $selectedPeminjaman->alamat_pengiriman }}</p>
                                        </div>
                                        <div>
                                            <p class="text-xs text-gray-500">Metode Pengiriman</p>
                                            <p class="text-sm">
                                                {{ str_replace('_', ' ', $selectedPeminjaman->metode_pengiriman) }}</p>
                                        </div>
                                        @if ($selectedPeminjaman->nomor_resi)
                                            <div>
                                                <p class="text-xs text-gray-500">Nomor Resi</p>
                                                <p class="text-sm">{{ $selectedPeminjaman->nomor_resi }}</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <!-- Date Info -->
                                <div>
                                    <h3 class="text-sm font-medium text-gray-500 uppercase mb-2">Informasi Tanggal</h3>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <p class="text-xs text-gray-500">Tanggal Peminjaman</p>
                                            <p class="text-sm">
                                                {{ $selectedPeminjaman->tgl_peminjaman_diinginkan->format('d M Y') }}
                                            </p>
                                        </div>
                                        <div>
                                            <p class="text-xs text-gray-500">Rencana Kembali</p>
                                            <p class="text-sm">
                                                {{ $selectedPeminjaman->tgl_kembali_rencana ? $selectedPeminjaman->tgl_kembali_rencana->format('d M Y') : '-' }}
                                            </p>
                                        </div>
                                        @if ($selectedPeminjaman->tgl_dikirim)
                                            <div>
                                                <p class="text-xs text-gray-500">Tanggal Dikirim</p>
                                                <p class="text-sm">
                                                    {{ $selectedPeminjaman->tgl_dikirim->format('d M Y') }}</p>
                                            </div>
                                        @endif
                                        @if ($selectedPeminjaman->tgl_kembali_aktual)
                                            <div>
                                                <p class="text-xs text-gray-500">Tanggal Kembali Aktual</p>
                                                <p class="text-sm">
                                                    {{ $selectedPeminjaman->tgl_kembali_aktual->format('d M Y') }}</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <!-- Additional Info -->
                                @if ($selectedPeminjaman->total_denda > 0 || $selectedPeminjaman->alasan_penolakan)
                                    <div>
                                        <h3 class="text-sm font-medium text-gray-500 uppercase mb-2">Informasi Tambahan
                                        </h3>
                                        <div class="grid grid-cols-2 gap-4">
                                            @if ($selectedPeminjaman->total_denda > 0)
                                                <div>
                                                    <p class="text-xs text-gray-500">Total Denda</p>
                                                    <p class="text-sm">Rp
                                                        {{ number_format($selectedPeminjaman->total_denda, 0, ',', '.') }}
                                                    </p>
                                                </div>
                                            @endif
                                            @if ($selectedPeminjaman->alasan_penolakan)
                                                <div class="col-span-2">
                                                    <p class="text-xs text-gray-500">Alasan Penolakan</p>
                                                    <p class="text-sm">{{ $selectedPeminjaman->alasan_penolakan }}</p>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <!-- Footer with action buttons -->
                            <div class="mt-6 flex justify-end gap-3">
                                <button wire:click="closeDetailModal"
                                    class="px-4 py-2 bg-gray-800 text-gray-300 rounded-lg hover:bg-gray-700 transition-colors">
                                    Tutup
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
