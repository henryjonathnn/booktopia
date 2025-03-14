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
                        <select wire:model.live="status"
                            class="w-full sm:w-40 px-3 py-2.5 bg-[#0f0a19] rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 text-sm border border-gray-800">
                            <option value="">Semua Status</option>
                            @foreach ($statuses as $status)
                                <option value="{{ $status }}">{{ $status }}</option>
                            @endforeach
                        </select>

                        <!-- Metode Filter -->
                        <select wire:model.live="metode"
                            class="w-full sm:w-40 px-3 py-2.5 bg-[#0f0a19] rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 text-sm border border-gray-800">
                            <option value="">Semua Metode</option>
                            @foreach ($metodes as $metode)
                                <option value="{{ $metode }}">{{ str_replace('_', ' ', $metode) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <!-- Data Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @forelse($peminjamans as $peminjaman)
                    <div class="bg-[#1a1625] rounded-xl shadow-lg border border-purple-500/10 overflow-hidden hover:border-purple-500/20 transition-colors">
                        <div class="p-4">
                            <!-- Header -->
                            <div class="flex justify-between items-start mb-3">
                                <div>
                                    <h3 class="font-medium truncate">{{ $peminjaman->user->name }}</h3>
                                    <p class="text-sm text-gray-400">{{ $peminjaman->created_at->format('d M Y') }}</p>
                                </div>
                                <div class="flex items-center">
                                    <span @class([
                                        'px-2 py-1 text-xs rounded-lg',
                                        'bg-yellow-500/10 text-yellow-500' => $peminjaman->status === 'PENDING',
                                        'bg-blue-500/10 text-blue-500' => $peminjaman->status === 'DIPROSES',
                                        'bg-green-500/10 text-green-500' => $peminjaman->status === 'DIKIRIM',
                                        'bg-purple-500/10 text-purple-500' => $peminjaman->status === 'DIPINJAM',
                                        'bg-red-500/10 text-red-500' => $peminjaman->status === 'TERLAMBAT',
                                        'bg-gray-500/10 text-gray-500' => $peminjaman->status === 'DIKEMBALIKAN',
                                        'bg-red-500/10 text-red-500' => $peminjaman->status === 'DITOLAK',
                                    ])>
                                        {{ $peminjaman->status }}
                                    </span>
                                </div>
                            </div>

                            <!-- Book Info -->
                            <div class="flex items-center space-x-3 mb-4">
                                <img src="{{ Storage::url($peminjaman->buku->cover_img) }}" 
                                    alt="Cover {{ $peminjaman->buku->judul }}"
                                    class="w-12 h-16 object-cover rounded-lg">
                                <div class="flex-1 min-w-0">
                                    <h4 class="font-medium truncate">{{ $peminjaman->buku->judul }}</h4>
                                    <p class="text-sm text-gray-400 truncate">{{ $peminjaman->buku->penulis }}</p>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex justify-between items-center">
                                <button wire:click="openDetailModal({{ $peminjaman->id }})"
                                    class="text-sm text-purple-400 hover:text-purple-300 transition-colors">
                                    Lihat Detail
                                </button>
                                
                                <div class="flex items-center space-x-2">
                                    @if($peminjaman->status === 'PENDING')
                                        <button wire:click="approvePeminjaman({{ $peminjaman->id }})"
                                            class="bg-green-500/10 text-green-500 hover:bg-green-500/20 p-2 rounded-lg transition-colors">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                            </svg>
                                        </button>
                                        <button wire:click="openRejectModal({{ $peminjaman->id }})"
                                            class="bg-red-500/10 text-red-500 hover:bg-red-500/20 p-2 rounded-lg transition-colors">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    @elseif($peminjaman->status === 'DIPROSES')
                                        <button wire:click="openShipmentModal({{ $peminjaman->id }})"
                                            class="bg-purple-500/10 text-purple-500 hover:bg-purple-500/20 px-3 py-1.5 rounded-lg transition-colors inline-flex items-center space-x-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                            </svg>
                                            <span>Kirim</span>
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full">
                        <div class="text-center bg-[#1a1625] rounded-xl p-8">
                            <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-purple-500/10 flex items-center justify-center">
                                <svg class="w-8 h-8 text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                        d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                            </div>
                            <h3 class="text-lg font-medium mb-1">Tidak ada data peminjaman</h3>
                            <p class="text-gray-400">Belum ada peminjaman buku yang tercatat</p>
                        </div>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            <div class="mt-6">
                {{ $peminjamans->links() }}
            </div>
        </div>
    </div>

    <!-- Modal Detail -->
    <div class="relative z-50" aria-labelledby="modal-title" role="dialog" aria-modal="true"
        x-data="{ show: @entangle('isDetailModalOpen') }"
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
                    @if($selectedPeminjaman)
                        <div>
                            <div class="mb-4">
                                <h3 class="text-lg font-medium">Detail Peminjaman</h3>
                                <p class="text-sm text-gray-400 mt-1">ID: #{{ $selectedPeminjaman->id }}</p>
                            </div>

                            <div class="space-y-4">
                                <!-- Informasi Peminjam -->
                                <div>
                                    <h4 class="text-sm font-medium text-gray-400">Informasi Peminjam</h4>
                                    <p class="mt-1">{{ $selectedPeminjaman->user->name }}</p>
                                    <p class="text-sm text-gray-400">{{ $selectedPeminjaman->user->email }}</p>
                                </div>

                                <!-- Informasi Buku -->
                                <div>
                                    <h4 class="text-sm font-medium text-gray-400">Buku yang Dipinjam</h4>
                                    <p class="mt-1">{{ $selectedPeminjaman->buku->judul }}</p>
                                    <p class="text-sm text-gray-400">{{ $selectedPeminjaman->buku->penulis }}</p>
                                </div>

                                <!-- Informasi Pengiriman -->
                                <div>
                                    <h4 class="text-sm font-medium text-gray-400">Informasi Pengiriman</h4>
                                    <p class="mt-1">{{ str_replace('_', ' ', $selectedPeminjaman->metode_pengiriman) }}</p>
                                    <p class="text-sm text-gray-400">{{ $selectedPeminjaman->alamat_pengiriman }}</p>
                                    @if($selectedPeminjaman->catatan_pengiriman)
                                        <p class="text-sm text-gray-400 mt-1">Catatan: {{ $selectedPeminjaman->catatan_pengiriman }}</p>
                                    @endif
                                </div>

                                <!-- Tanggal -->
                                <div>
                                    <h4 class="text-sm font-medium text-gray-400">Informasi Tanggal</h4>
                                    <div class="mt-1 space-y-1">
                                        <p class="text-sm">
                                            <span class="text-gray-400">Dibuat:</span>
                                            {{ $selectedPeminjaman->created_at->format('d M Y H:i') }}
                                        </p>
                                        <p class="text-sm">
                                            <span class="text-gray-400">Rencana Pinjam:</span>
                                            {{ Carbon\Carbon::parse($selectedPeminjaman->tgl_peminjaman_diinginkan)->format('d M Y') }}
                                        </p>
                                        @if($selectedPeminjaman->tgl_dikirim)
                                            <p class="text-sm">
                                                <span class="text-gray-400">Tanggal Kirim:</span>
                                                {{ Carbon\Carbon::parse($selectedPeminjaman->tgl_dikirim)->format('d M Y H:i') }}
                                            </p>
                                        @endif
                                    </div>
                                </div>

                                <!-- Status History -->
                                @if($selectedPeminjaman->staff)
                                    <div>
                                        <h4 class="text-sm font-medium text-gray-400">Diproses Oleh</h4>
                                        <p class="mt-1">{{ $selectedPeminjaman->staff->name }}</p>
                                    </div>
                                @endif
                            </div>

                            <div class="mt-6">
                                <button wire:click="closeDetailModal"
                                    class="w-full inline-flex justify-center rounded-lg bg-purple-500 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-purple-600">
                                    Tutup
                                </button>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Reject -->
    <div class="relative z-50" aria-labelledby="modal-title" role="dialog" aria-modal="true"
        x-data="{ show: @entangle('isRejectModalOpen') }"
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
        x-data="{ show: @entangle('isShipmentModalOpen') }"
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
