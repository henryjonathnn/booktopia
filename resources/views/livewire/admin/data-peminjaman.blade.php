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

                        <!-- Export PDF Button -->
                        <div x-data="exportPDF">
                            <button wire:click="showExportOptions"
                                class="px-4 py-2.5 bg-purple-600 hover:bg-purple-700 text-white rounded-lg text-sm font-medium transition-colors flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                Export PDF
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Data Table -->
            <div class="bg-[#1a1625] rounded-xl shadow-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-purple-500/10">
                                <th
                                    class="px-6 py-4 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">
                                    Peminjam</th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">
                                    Buku</th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">
                                    Status</th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">
                                    Tanggal</th>
                                <th
                                    class="px-6 py-4 text-right text-xs font-medium text-gray-400 uppercase tracking-wider">
                                    Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-purple-500/10">
                            @forelse($peminjamans as $peminjaman)
                                <tr class="hover:bg-purple-500/5 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div>
                                                <div class="font-medium">{{ $peminjaman->user->name }}</div>
                                                <div class="text-sm text-gray-400">{{ $peminjaman->user->email }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center space-x-3">
                                            <img src="{{ Storage::url($peminjaman->buku->cover_img) }}"
                                                class="h-12 w-9 object-cover rounded"
                                                alt="{{ $peminjaman->buku->judul }}">
                                            <div>
                                                <div class="font-medium">{{ $peminjaman->buku->judul }}</div>
                                                <div class="text-sm text-gray-400">{{ $peminjaman->buku->penulis }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span @class([
                                            'px-2 py-1 text-xs rounded-lg inline-flex items-center',
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
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400">
                                        {{ $peminjaman->created_at->format('d M Y') }}
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex justify-end items-center space-x-2">
                                            <button wire:click="showDetail({{ $peminjaman->id }})"
                                                class="p-2 text-gray-400 hover:text-purple-400 transition-colors"
                                                title="Detail">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                            </button>

                                            @if ($peminjaman->status === 'PENDING')
                                                <button wire:click="showReject({{ $peminjaman->id }})"
                                                    class="p-2 text-gray-400 hover:text-red-400 transition-colors"
                                                    title="Tolak">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                    </svg>
                                                </button>
                                                <button wire:click="processPeminjaman({{ $peminjaman->id }})"
                                                    class="p-2 text-gray-400 hover:text-green-400 transition-colors"
                                                    title="Proses">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M5 13l4 4L19 7" />
                                                    </svg>
                                                </button>
                                            @endif

                                            @if ($peminjaman->status === 'DIPROSES')
                                                <button wire:click="showUpload({{ $peminjaman->id }})"
                                                    class="p-2 text-gray-400 hover:text-blue-400 transition-colors"
                                                    title="Upload Bukti">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                                                    </svg>
                                                </button>
                                            @endif

                                            @if ($peminjaman->status === 'DIKIRIM')
                                                <button x-data
                                                    @click="$dispatch('open-modal', { 
                                                        title: 'Konfirmasi Pengiriman',
                                                        message: 'Apakah Anda yakin buku sudah diterima oleh peminjam?',
                                                        peminjamanId: {{ $peminjaman->id }}
                                                    })"
                                                    class="p-2 text-gray-400 hover:text-green-400 transition-colors group relative"
                                                    title="Tandai Terkirim">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                    <span
                                                        class="absolute bottom-full right-0 mb-2 hidden group-hover:block bg-gray-800 text-white text-xs py-1 px-2 rounded">
                                                        Tandai Terkirim
                                                    </span>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-10 text-center">
                                        <div class="flex flex-col items-center">
                                            <div
                                                class="w-16 h-16 mb-4 rounded-full bg-purple-500/10 flex items-center justify-center">
                                                <svg class="w-8 h-8 text-purple-400" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                                </svg>
                                            </div>
                                            <p class="text-gray-400">Belum ada data peminjaman</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="px-6 py-3 border-t border-purple-500/10">
                    {{ $peminjamans->links() }}
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Detail --}}
    @if ($showDetailModal)
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
                <div class="fixed inset-0 transition-opacity bg-black/50" wire:click="closeModal"></div>

                <div
                    class="relative inline-block w-full max-w-3xl text-left align-middle transition-all transform bg-[#1A1625] rounded-2xl shadow-xl">
                    @if ($selectedPeminjaman)
                        <!-- Header -->
                        <div class="flex items-center justify-between p-6 border-b border-purple-500/10">
                            <h3 class="text-2xl font-bold text-white">Detail Peminjaman #{{ $selectedPeminjaman->id }}
                            </h3>
                            <button wire:click="closeModal" class="text-gray-400 hover:text-white transition-colors">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>

                        <!-- Content -->
                        <div class="p-6 space-y-6">
                            <!-- Status Badge -->
                            <div class="flex justify-between items-center">
                                <span
                                    class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-{{ $statusColors[$selectedPeminjaman->status] }}-500/20 text-{{ $statusColors[$selectedPeminjaman->status] }}-400">
                                    {{ $selectedPeminjaman->status }}
                                </span>
                                <span class="text-sm text-gray-400">
                                    Dibuat: {{ $selectedPeminjaman->created_at->format('d M Y H:i') }}
                                </span>
                            </div>

                            <!-- Book and User Info -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Book Info -->
                                <div class="bg-purple-500/5 rounded-xl p-4">
                                    <h4 class="text-lg font-semibold mb-4 text-purple-400">Informasi Buku</h4>
                                    <div class="flex space-x-4">
                                        @if ($selectedPeminjaman->buku->cover_img)
                                            <img src="{{ asset('storage/' . $selectedPeminjaman->buku->cover_img) }}"
                                                alt="{{ $selectedPeminjaman->buku->judul }}"
                                                class="w-24 h-32 object-cover rounded-lg shadow-lg">
                                        @else
                                            <div
                                                class="w-24 h-32 bg-purple-500/10 rounded-lg flex items-center justify-center">
                                                <svg class="w-12 h-12 text-purple-400" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                                </svg>
                                            </div>
                                        @endif
                                        <div>
                                            <h5 class="font-semibold">{{ $selectedPeminjaman->buku->judul }}</h5>
                                            <p class="text-sm text-gray-400">{{ $selectedPeminjaman->buku->penulis }}
                                            </p>
                                            <p class="text-sm text-gray-400 mt-2">ISBN:
                                                {{ $selectedPeminjaman->buku->isbn }}</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- User Info -->
                                <div class="bg-purple-500/5 rounded-xl p-4">
                                    <h4 class="text-lg font-semibold mb-4 text-purple-400">Informasi Peminjam</h4>
                                    <div class="space-y-2">
                                        <div class="flex items-center space-x-3">
                                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                            </svg>
                                            <span>{{ $selectedPeminjaman->user->name }}</span>
                                        </div>
                                        <div class="flex items-center space-x-3">
                                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                            </svg>
                                            <span>{{ $selectedPeminjaman->user->email }}</span>
                                        </div>
                                        <div class="flex items-center space-x-3">
                                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                            </svg>
                                            <span>{{ $selectedPeminjaman->user->phone ?? '-' }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Shipping Info -->
                            <div class="bg-purple-500/5 rounded-xl p-4">
                                <h4 class="text-lg font-semibold mb-4 text-purple-400">Informasi Pengiriman</h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <p class="text-sm text-gray-400 mb-1">Metode Pengiriman</p>
                                        <p class="font-medium">
                                            {{ str_replace('_', ' ', $selectedPeminjaman->metode_pengiriman) }}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-400 mb-1">Status Pengiriman</p>
                                        <p class="font-medium">{{ $selectedPeminjaman->status }}</p>
                                    </div>
                                    <div class="md:col-span-2">
                                        <p class="text-sm text-gray-400 mb-1">Alamat Pengiriman</p>
                                        <p class="font-medium">{{ $selectedPeminjaman->alamat_pengiriman }}</p>
                                    </div>
                                    @if ($selectedPeminjaman->catatan_pengiriman)
                                        <div class="md:col-span-2">
                                            <p class="text-sm text-gray-400 mb-1">Catatan</p>
                                            <p class="font-medium">{{ $selectedPeminjaman->catatan_pengiriman }}</p>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Dates Info -->
                            <div class="bg-purple-500/5 rounded-xl p-4">
                                <h4 class="text-lg font-semibold mb-4 text-purple-400">Informasi Waktu</h4>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <div>
                                        <p class="text-sm text-gray-400 mb-1">Tanggal Peminjaman</p>
                                        <p class="font-medium">
                                            {{ $selectedPeminjaman->tgl_peminjaman_diinginkan?->format('d M Y') ?? '-' }}
                                        </p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-400 mb-1">Tanggal Pengembalian</p>
                                        <p class="font-medium">
                                            {{ $selectedPeminjaman->tgl_kembali_rencana?->format('d M Y') ?? '-' }}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-400 mb-1">Durasi</p>
                                        <p class="font-medium">
                                            {{ $selectedPeminjaman->tgl_peminjaman_diinginkan?->diffInDays($selectedPeminjaman->tgl_kembali_rencana) ?? 0 }}
                                            hari</p>
                                    </div>
                                </div>
                            </div>

                            @if ($selectedPeminjaman->bukti_pengiriman)
                                <!-- Shipping Proof -->
                                <div class="bg-purple-500/5 rounded-xl p-4">
                                    <h4 class="text-lg font-semibold mb-4 text-purple-400">Bukti Pengiriman</h4>
                                    <div class="relative group">
                                        <img src="{{ Storage::url($selectedPeminjaman->bukti_pengiriman) }}"
                                            alt="Bukti Pengiriman"
                                            class="w-full max-w-md rounded-lg shadow-lg cursor-pointer hover:opacity-90 transition-opacity"
                                            onclick="window.open('{{ Storage::url($selectedPeminjaman->bukti_pengiriman) }}', '_blank')" />
                                        <div
                                            class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                            <span class="bg-black/50 text-white px-4 py-2 rounded-lg text-sm">
                                                Klik untuk memperbesar
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Footer -->
                        <div class="flex justify-end px-6 py-4 border-t border-purple-500/10">
                            <button wire:click="closeModal"
                                class="px-4 py-2 text-sm font-medium text-gray-300 bg-[#2a2435] hover:bg-[#2a2435]/70 rounded-lg transition-colors">
                                Tutup
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif

    <!-- Modal Reject -->
    @if ($activeModal === 'reject' && $selectedPeminjaman)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog"
            aria-modal="true">
            <div class="flex min-h-screen items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <div class="fixed inset-0 bg-black/50 transition-opacity" wire:click="closeModal"></div>

                <div
                    class="relative transform overflow-hidden rounded-lg bg-[#1a1625] px-4 pb-4 pt-5 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:p-6">
                    <div>
                        <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-red-500/10">
                            <svg class="h-6 w-6 text-red-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </div>
                        <div class="mt-3 text-center">
                            <h3 class="text-lg font-medium leading-6 text-gray-200">
                                Tolak Peminjaman
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-400">
                                    Masukkan alasan penolakan peminjaman ini
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-5">
                        <textarea wire:model="alasanPenolakan"
                            class="w-full bg-[#0f0a19] rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-500 border border-gray-800"
                            rows="3" placeholder="Alasan penolakan..."></textarea>
                        @error('alasanPenolakan')
                            <span class="text-sm text-red-400 mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mt-5 sm:mt-6 sm:grid sm:grid-flow-row-dense sm:grid-cols-2 sm:gap-3">
                        <button wire:click="rejectPeminjaman"
                            class="inline-flex w-full justify-center rounded-lg bg-red-500 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-600 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-500 sm:col-start-2">
                            Tolak Peminjaman
                        </button>
                        <button wire:click="closeModal"
                            class="mt-3 inline-flex w-full justify-center rounded-lg bg-[#2a2435] px-3 py-2 text-sm font-semibold text-gray-300 shadow-sm ring-1 ring-inset ring-gray-800 hover:bg-[#2a2435]/70 sm:col-start-1 sm:mt-0">
                            Batal
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Modal Upload -->
    @if ($activeModal === 'upload' && $selectedPeminjaman)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog"
            aria-modal="true">
            <div class="flex min-h-screen items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <div class="fixed inset-0 bg-black/50 transition-opacity" wire:click="closeModal"></div>

                <div
                    class="relative transform overflow-hidden rounded-lg bg-[#1a1625] px-4 pb-4 pt-5 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:p-6">
                    <div>
                        <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-purple-500/10">
                            <svg class="h-6 w-6 text-purple-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                            </svg>
                        </div>
                        <div class="mt-3 text-center">
                            <h3 class="text-lg font-medium leading-6 text-gray-200">
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
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <div class="mt-4 flex text-sm leading-6 text-gray-400">
                                    <label for="file-upload"
                                        class="relative cursor-pointer rounded-md bg-[#1a1625] font-semibold text-purple-400 focus-within:outline-none focus-within:ring-2 focus-within:ring-purple-500 focus-within:ring-offset-2 hover:text-purple-300">
                                        <span>Upload file</span>
                                        <input wire:model="buktiPengiriman" id="file-upload" type="file"
                                            class="sr-only" accept="image/*">
                                    </label>
                                    <p class="pl-1">atau drag and drop</p>
                                </div>
                                <p class="text-xs text-gray-400">PNG, JPG, GIF up to 10MB</p>
                            </div>
                            @error('buktiPengiriman')
                                <span class="text-sm text-red-400 block mt-2">{{ $message }}</span>
                            @enderror
                            @if ($buktiPengiriman)
                                <div class="mt-4">
                                    <div class="text-sm text-gray-400">Preview:</div>
                                    <div class="mt-2 rounded-lg overflow-hidden bg-[#0f0a19] p-2">
                                        <img src="{{ $buktiPengiriman->temporaryUrl() }}"
                                            class="max-h-48 mx-auto object-contain" alt="Preview">
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="mt-5 sm:mt-6 sm:grid sm:grid-flow-row-dense sm:grid-cols-2 sm:gap-3">
                        <button wire:click="uploadBuktiPengiriman"
                            class="inline-flex w-full justify-center rounded-lg bg-purple-500 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-purple-600 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-purple-500 sm:col-start-2"
                            @if (!$buktiPengiriman) disabled @endif>
                            Upload dan Kirim
                        </button>
                        <button wire:click="closeModal"
                            class="mt-3 inline-flex w-full justify-center rounded-lg bg-[#2a2435] px-3 py-2 text-sm font-semibold text-gray-300 shadow-sm ring-1 ring-inset ring-gray-800 hover:bg-[#2a2435]/70 sm:col-start-1 sm:mt-0">
                            Batal
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Custom Modal Component --}}
    <div x-data="{
        show: false,
        title: '',
        message: '',
        peminjamanId: null
    }"
        @open-modal.window="
            show = true;
            title = $event.detail.title;
            message = $event.detail.message;
            peminjamanId = $event.detail.peminjamanId;
        "
        x-show="show" x-cloak class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
            <div x-show="show" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                class="fixed inset-0 transition-opacity" @click="show = false">
                <div class="absolute inset-0 bg-black/50"></div>
            </div>

            <div x-show="show" x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                class="relative inline-block overflow-hidden text-left align-bottom transition-all transform bg-[#1A1625] rounded-xl shadow-xl sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div
                            class="flex items-center justify-center flex-shrink-0 w-12 h-12 mx-auto bg-purple-500/10 rounded-full sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="w-6 h-6 text-purple-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg font-medium leading-6 text-white" x-text="title"></h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-400" x-text="message"></p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse sm:space-x-reverse sm:space-x-3">
                    <button type="button"
                        class="inline-flex justify-center w-full px-4 py-2 text-base font-medium text-white bg-purple-500 border border-transparent rounded-lg shadow-sm hover:bg-purple-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 sm:w-auto sm:text-sm"
                        @click="
                            show = false;
                            $wire.markAsDipinjam(peminjamanId);
                        ">
                        Ya, Sudah Diterima
                    </button>
                    <button type="button"
                        class="inline-flex justify-center w-full px-4 py-2 mt-3 text-base font-medium text-gray-300 bg-[#2a2435] border border-gray-800 rounded-lg shadow-sm hover:bg-[#2a2435]/70 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 sm:mt-0 sm:w-auto sm:text-sm"
                        @click="show = false">
                        Batal
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Export Modal --}}
    <div x-data="{ 
            pdfData: null,
            async generatePDF() {
                try {
                    this.pdfData = await $wire.getExportData();
                    const opt = {
                        margin: [10, 10, 10, 10],
                        filename: 'laporan_peminjaman_' + new Date().getTime() + '.pdf',
                        image: { type: 'jpeg', quality: 0.98 },
                        html2canvas: { scale: 2 },
                        jsPDF: { unit: 'mm', format: 'a4', orientation: 'landscape' }
                    };
                    const element = document.getElementById('pdf-content');
                    await html2pdf().set(opt).from(element).save();
                    $wire.closeExportModal();
                } catch (error) {
                    console.error('Error generating PDF:', error);
                }
            }
        }" 
        x-show="$wire.showExportModal" 
        class="fixed inset-0 z-50 overflow-y-auto"
        style="display: none;">
        <div class="flex min-h-screen items-end justify-center p-4 text-center sm:items-center sm:p-0">
            <div class="fixed inset-0 bg-black/50 transition-opacity" @click="$wire.showExportModal = false"></div>

            <div class="relative transform overflow-hidden rounded-lg bg-[#1a1625] px-4 pb-4 pt-5 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:p-6">
                <div class="sm:flex sm:items-start">
                    <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left w-full">
                        <h3 class="text-xl font-semibold leading-6 text-gray-200">Export Data Peminjaman</h3>
                        <div class="mt-6 space-y-4">
                            {{-- Status Filter --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-400 mb-2">Status Peminjaman</label>
                                <select wire:model="exportStatus" class="w-full px-3 py-2.5 bg-[#0f0a19] rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 text-sm border border-gray-800">
                                    <option value="">Semua Status</option>
                                    @foreach($statuses as $status)
                                        <option value="{{ $status }}">{{ $status }}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Date Range --}}
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-400 mb-2">Tanggal Mulai</label>
                                    <input type="date" 
                                        wire:model="exportDateStart" 
                                        min="{{ $minDate }}" 
                                        max="{{ $maxDate }}"
                                        class="w-full px-3 py-2.5 bg-[#0f0a19] rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 text-sm border border-gray-800">
                                    @error('exportDateStart') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-400 mb-2">Tanggal Akhir</label>
                                    <input type="date" 
                                        wire:model="exportDateEnd" 
                                        min="{{ $minDate }}" 
                                        max="{{ $maxDate }}"
                                        class="w-full px-3 py-2.5 bg-[#0f0a19] rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 text-sm border border-gray-800">
                                    @error('exportDateEnd') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-6 flex justify-end space-x-3">
                    <button @click="$wire.showExportModal = false"
                        class="inline-flex justify-center rounded-lg bg-[#2a2435] px-3 py-2 text-sm font-semibold text-gray-300 shadow-sm ring-1 ring-inset ring-gray-800 hover:bg-[#2a2435]/70">
                        Batal
                    </button>
                    <button @click="generatePDF()"
                        class="inline-flex justify-center rounded-lg bg-purple-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-purple-700">
                        Export PDF
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- PDF Template (Hidden) --}}
    <div id="pdf-content" class="hidden">
        <div x-show="pdfData" x-cloak>
            <h1 x-text="'Laporan Data Peminjaman - ' + pdfData?.status"></h1>
            <p x-text="'Periode: ' + pdfData?.dateStart + ' - ' + pdfData?.dateEnd"></p>
            <p x-text="'Dicetak pada: ' + pdfData?.timestamp"></p>
            
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>ID Peminjaman</th>
                        <th>Tanggal</th>
                        <th>Buku</th>
                        <th>Peminjam</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <template x-for="(item, index) in pdfData?.peminjamans" :key="item.id">
                        <tr>
                            <td x-text="index + 1"></td>
                            <td x-text="item.id"></td>
                            <td x-text="item.created_at"></td>
                            <td>
                                <div x-text="item.buku.judul"></div>
                                <div x-text="'Penulis: ' + item.buku.penulis"></div>
                                <div x-text="'ISBN: ' + item.buku.isbn"></div>
                            </td>
                            <td>
                                <div x-text="item.user.name"></div>
                                <div x-text="item.user.email"></div>
                                <div x-text="'Telp: ' + item.user.phone"></div>
                            </td>
                            <td x-text="item.status"></td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        window.addEventListener('showConfirmation', event => {
            Swal.fire({
                title: 'Konfirmasi Pengiriman',
                text: 'Apakah Anda yakin buku sudah diterima oleh peminjam?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, Sudah Diterima',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#10B981',
                cancelButtonColor: '#6B7280',
                background: '#1A1625',
                color: '#fff'
            }).then((result) => {
                if (result.isConfirmed) {
                    @this.markAsDipinjam(event.detail.peminjamanId)
                }
            })
        })
    </script>
@endpush

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('exportPDF', () => ({
        data: null,
        async generatePDF() {
            try {
                // Get data from Livewire
                this.data = await @this.getExportData();
                
                // Configure PDF options
                const opt = {
                    margin: [10, 10, 10, 10],
                    filename: 'laporan_peminjaman_' + new Date().getTime() + '.pdf',
                    image: { type: 'jpeg', quality: 0.98 },
                    html2canvas: { scale: 2 },
                    jsPDF: { unit: 'mm', format: 'a4', orientation: 'landscape' }
                };

                // Generate PDF
                const element = document.getElementById('pdf-content');
                await html2pdf().set(opt).from(element).save();
                
                // Close modal
                @this.closeExportModal();
            } catch (error) {
                console.error('Error generating PDF:', error);
            }
        },
        formatDate(date) {
            return new Date(date).toLocaleDateString('id-ID', {
                day: 'numeric',
                month: 'long',
                year: 'numeric'
            });
        },
        getStatusStyle(status) {
            const styles = {
                'PENDING': 'background: #FEF3C7; color: #92400E; padding: 4px 8px; border-radius: 4px; display: inline-block;',
                'DIPROSES': 'background: #DBEAFE; color: #1E40AF; padding: 4px 8px; border-radius: 4px; display: inline-block;',
                'DIKIRIM': 'background: #D1FAE5; color: #065F46; padding: 4px 8px; border-radius: 4px; display: inline-block;',
                'DIPINJAM': 'background: #EDE9FE; color: #5B21B6; padding: 4px 8px; border-radius: 4px; display: inline-block;',
                'TERLAMBAT': 'background: #FEE2E2; color: #991B1B; padding: 4px 8px; border-radius: 4px; display: inline-block;',
                'DIKEMBALIKAN': 'background: #F3F4F6; color: #1F2937; padding: 4px 8px; border-radius: 4px; display: inline-block;',
                'DITOLAK': 'background: #FEE2E2; color: #991B1B; padding: 4px 8px; border-radius: 4px; display: inline-block;'
            };
            return styles[status] || '';
        }
    }))
});
</script>
@endpush
