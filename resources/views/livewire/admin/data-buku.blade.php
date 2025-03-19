<div>
    <div class="min-h-screen flex flex-col">
        <div class="flex-grow pt-16">
            <!-- Page Header -->
            <div class="mb-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold">Data Buku</h1>
                        <p class="text-sm text-gray-400 mt-1">
                            Pengelolaan data buku yang ada di perpustakaan BooKoo</p>
                    </div>

                    <button wire:click="createBuku"
                        class="flex items-center justify-center gap-1 px-2 py-1.5 md:px-4 md:py-2.5 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors text-[11px] md:text-sm md:w-auto w-max whitespace-nowrap">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="w-2.5 h-2.5 md:w-4 md:h-4">
                            <circle cx="12" cy="12" r="10"></circle>
                            <line x1="12" y1="8" x2="12" y2="16"></line>
                            <line x1="8" y1="12" x2="16" y2="12"></line>
                        </svg>
                        <span>Tambah Buku</span>
                    </button>
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
                            placeholder="Cari buku..." />
                    </div>

                    <div class="flex flex-col sm:flex-row gap-3">
                        <div class="w-full sm:w-40">
                            <select wire:model.live="kategori"
                                class="w-full px-3 py-2.5 bg-[#0f0a19] rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 text-sm">
                                <option value="">Semua Kategori</option>
                                @foreach ($this->categories as $category)
                                    <option value="{{ $category }}">{{ $category }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Book Table -->
            <div class="overflow-x-auto bg-[#1a1625] rounded-xl">
                <table class="min-w-full">
                    <thead>
                        <tr class="border-b border-gray-800">
                            <th class="hidden md:table-cell px-6 py-3 text-left">
                                <input type="checkbox" wire:model.live="selectAll"
                                    class="rounded border-gray-600 text-purple-600 focus:ring-purple-500" />
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">
                                Buku Info
                            </th>
                            <th
                                class="hidden md:table-cell px-6 py-3 text-center text-xs font-medium text-gray-400 uppercase tracking-wider">
                                ISBN
                            </th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-400 uppercase tracking-wider">
                                Kategori
                            </th>
                            <th
                                class="hidden md:table-cell px-6 py-3 text-center text-xs font-medium text-gray-400 uppercase tracking-wider">
                                Stok
                            </th>
                            <th
                                class="px-6 py-3 text-right sm:text-left text-xs font-medium text-gray-400 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($books as $buku)
                            <tr class="border-b border-gray-800 hover:bg-[#2a2435] transition-colors cursor-pointer"
                                wire:click="viewBukuDetails({{ $buku->id }})">
                                <td class="hidden md:table-cell px-6 py-4 whitespace-nowrap"
                                    onclick="event.stopPropagation();">
                                    <input type="checkbox" value="{{ $buku->id }}" wire:model.live="selectedBooks"
                                        class="rounded border-gray-600 text-purple-600 focus:ring-purple-500" />
                                </td>
                                <td class="px-3 lg:px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-2 lg:gap-3">
                                        <div
                                            class="w-7 h-10 md:w-10 md:h-14 bg-gray-800 rounded overflow-hidden flex-shrink-0">
                                            @if ($buku->cover_img)
                                                <img src="{{ Storage::url($buku->cover_img) }}" alt="{{ $buku->judul }}"
                                                    class="w-full h-full object-cover">
                                            @else
                                                <div class="flex items-center justify-center h-full">
                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                        class="h-3 w-3 md:h-5 md:w-5 text-gray-600" fill="none"
                                                        viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                                    </svg>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="min-w-0">
                                            <h3
                                                class="font-medium text-[11px] md:text-sm text-white leading-tight truncate max-w-[100px] md:max-w-[250px]">
                                                {{ $buku->judul }}
                                            </h3>
                                            <p class="text-[10px] md:text-xs text-gray-400 truncate">{{ $buku->penulis }}
                                            </p>
                                            <p class="text-[10px] md:hidden text-gray-400">Stok: {{ $buku->stock }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="hidden md:table-cell px-2 py-2 text-center text-sm">
                                    {{ $buku->isbn }}
                                </td>
                                <td class="px-2 py-2 text-center">
                                    <span
                                        class="px-1 py-0.5 text-[9px] md:text-xs bg-purple-500/10 text-purple-400 rounded">
                                        {{ $buku->kategori }}
                                    </span>
                                </td>
                                <td class="hidden md:table-cell px-2 py-2 text-center text-sm">
                                    {{ $buku->stock }}
                                </td>
                       -         <td class="px-6 py-4 text-right sm:text-left whitespace-nowrap" onclick="event.stopPropagation();">
                                    <div class="flex items-center gap-2 justify-end sm:justify-start">
                                        <!-- Edit Button -->
                                        <button wire:click="editBuku({{ $buku->id }})"
                                            class="p-1.5 text-gray-400 hover:bg-gray-800 rounded-lg transition-colors">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                            </svg>
                                        </button>

                                        <!-- Delete Button -->
                                        <button wire:click="confirmBukuDeletion({{ $buku->id }})"
                                            class="p-1.5 hover:bg-gray-800 rounded-lg transition-colors">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                class="text-gray-400">
                                                <polyline points="3 6 5 6 21 6"></polyline>
                                                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                                <line x1="10" y1="11" x2="10" y2="17"></line>
                                                <line x1="14" y1="11" x2="14" y2="17"></line>
                                            </svg>
                                        </button>

                                        <!-- More Button -->
                                        <button class="p-1.5 hover:bg-gray-800 rounded-lg transition-colors">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                class="text-gray-400">
                                                <circle cx="12" cy="12" r="1"></circle>
                                                <circle cx="12" cy="5" r="1"></circle>
                                                <circle cx="12" cy="19" r="1"></circle>
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-4 text-center text-gray-400">
                                    Tidak ada buku yang ditemukan
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-4">
                {{ $books->links() }}
            </div>

            <!-- Form Modal -->
            <x-form-modal :isOpen="$isModalOpen" title="Buku" :formConfig="$formConfig" :initialData="$currentBuku" imageField="coverImage"
                submitAction="saveBuku" />

            <!-- Detail Modal -->
            @if ($selectedBuku && $isDetailModalOpen)
                <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                    <div class="bg-[#0f0a19] rounded-lg shadow-xl max-w-xl w-full max-h-[90vh] overflow-y-auto">
                        <div class="p-6">
                            <!-- Header with title and close button -->
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-xl font-semibold">Detail Buku</h3>
                                <button wire:click="closeDetailModal" class="text-gray-400 hover:text-white">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>

                            <!-- Book detail content -->
                            <div class="flex flex-col md:flex-row gap-6">
                                <!-- Cover image -->
                                <div class="w-full md:w-1/3 flex-shrink-0">
                                    <div class="bg-gray-800 rounded-lg overflow-hidden h-48 md:h-64 w-full">
                                        @if ($selectedBuku->cover_img)
                                            <img src="{{ Storage::url($selectedBuku->cover_img) }}"
                                                alt="{{ $selectedBuku->judul }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="flex items-center justify-center h-full">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-gray-600"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                                </svg>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <!-- Book details -->
                                <div class="flex-1">
                                    <h2 class="text-xl font-bold mb-2">{{ $selectedBuku->judul }}</h2>
                                    <p class="text-gray-400 mb-4">{{ $selectedBuku->penulis }}</p>

                                    <div class="space-y-4">
                                        <!-- Informasi Umum -->
                                        <div>
                                            <h3 class="text-sm font-medium text-gray-500 uppercase mb-2">Informasi Umum
                                            </h3>
                                            <div class="grid grid-cols-2 gap-2">
                                                <div>
                                                    <p class="text-xs text-gray-500">ISBN</p>
                                                    <p class="text-sm">{{ $selectedBuku->isbn }}</p>
                                                </div>
                                                <div>
                                                    <p class="text-xs text-gray-500">Kategori</p>
                                                    <p class="text-sm">{{ $selectedBuku->kategori }}</p>
                                                </div>
                                                <div>
                                                    <p class="text-xs text-gray-500">Stok</p>
                                                    <p class="text-sm">{{ $selectedBuku->stock }}</p>
                                                </div>
                                                <div>
                                                    <p class="text-xs text-gray-500">Penerbit</p>
                                                    <p class="text-sm">{{ $selectedBuku->penerbit }}</p>
                                                </div>
                                                <div>
                                                    <p class="text-xs text-gray-500">Tahun Terbit</p>
                                                    <p class="text-sm">{{ $selectedBuku->tahun_terbit }}</p>
                                                </div>
                                                <div>
                                                    <p class="text-xs text-gray-500">Denda Harian</p>
                                                    <p class="text-sm">Rp
                                                        {{ number_format($selectedBuku->denda_harian, 0, ',', '.') }}</p>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Deskripsi -->
                                        @if ($selectedBuku->deskripsi)
                                            <div>
                                                <h3 class="text-sm font-medium text-gray-500 uppercase mb-2">Deskripsi</h3>
                                                <p class="text-sm">{{ $selectedBuku->deskripsi }}</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Footer with action buttons -->
                            <div class="mt-6 flex justify-end gap-3">
                                <button wire:click="closeDetailModal"
                                    class="px-4 py-2 bg-gray-800 text-gray-300 rounded-lg hover:bg-gray-700 transition-colors">
                                    Tutup
                                </button>
                                <button wire:click="editBuku({{ $selectedBuku->id }})"
                                    class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors">
                                    Edit Buku
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Confirmation Modal for Deletion -->
            @if ($confirmingBukuDeletion)
                <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                    <div class="bg-[#0f0a19] rounded-lg shadow-lg max-w-md w-full">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold mb-4">Konfirmasi Hapus</h3>
                            <p class="text-gray-300 mb-6">Apakah Anda yakin ingin menghapus buku ini? Tindakan ini tidak
                                dapat dibatalkan.</p>

                            <div class="flex justify-end gap-3">
                                <button 
                                    wire:click="$set('confirmingBukuDeletion', false)"
                                    class="px-4 py-2 bg-gray-800 text-gray-300 rounded-lg hover:bg-gray-700 transition-colors">
                                    Batal
                                </button>
                                <button 
                                    wire:click="deleteBuku"
                                    class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                                    Hapus
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Batch Actions -->
            @if (count($selectedBooks) > 0)
                <div class="fixed bottom-0 inset-x-0 pb-6 px-4 sm:px-6 lg:px-8 bg-[#0f0a19] border-t border-gray-800 z-30">
                    <div class="flex items-center justify-between py-3">
                        <div class="flex items-center space-x-3">
                            <span class="text-sm text-gray-400">
                                {{ count($selectedBooks) }} item dipilih
                            </span>
                        </div>
                        <div class="flex gap-2">
                            <button type="button" wire:click="$set('selectedBooks', [])"
                                class="py-2 px-4 bg-gray-800 text-gray-300 rounded-lg hover:bg-gray-700 transition-colors text-sm">
                                Batal
                            </button>
                            <button type="button" wire:click="deleteSelectedBooks"
                                class="py-2 px-4 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors text-sm">
                                Hapus Terpilih
                            </button>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
