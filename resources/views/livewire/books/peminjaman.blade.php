<div class="px-4 md:px-8 lg:px-16 py-8 pt-32">
    <div class="max-w-7xl mx-auto">
        {{-- Header --}}
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8">
            <div class="mb-4 md:mb-0">
                <h1 class="text-3xl font-bold mb-2">Peminjaman Saya</h1>
                <p class="text-gray-400">Kelola dan pantau status peminjaman buku Anda</p>
            </div>
        </div>

        {{-- Filters --}}
        <div class="glass-effect rounded-2xl p-4 border border-purple-500/10 mb-6">
            <div class="flex flex-col md:flex-row md:items-center space-y-4 md:space-y-0 md:space-x-4">
                {{-- Search --}}
                <div class="flex-1">
                    <div class="relative">
                        <input 
                            wire:model.live.debounce.300ms="search"
                            type="text"
                            placeholder="Cari judul atau penulis buku..."
                            class="w-full bg-[#1A1A2E] rounded-xl pl-12 pr-4 py-3 focus:outline-none focus:ring-2 focus:ring-purple-500/50 border border-purple-500/10"
                        >
                        <span class="absolute left-4 top-3.5 text-gray-400">
                            <x-icon name="magnifying-glass" class="w-5 h-5" />
                        </span>
                    </div>
                </div>

                {{-- Status Filter --}}
                <div class="w-full md:w-48">
                    <select 
                        wire:model.live="statusFilter"
                        class="w-full bg-[#1A1A2E] rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-purple-500/50 border border-purple-500/10"
                    >
                        <option value="">Semua Status</option>
                        @foreach($statusList as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        {{-- Peminjaman List --}}
        <div class="space-y-4">
            @forelse($peminjamans as $peminjaman)
                <div wire:key="peminjaman-{{ $peminjaman->id }}" 
                    class="glass-effect rounded-2xl p-4 border border-purple-500/10 hover:border-purple-500/20 transition-all duration-300">
                    <a href="{{ route('peminjaman.detail', ['id' => $peminjaman->id]) }}" 
                        class="block">
                        <div class="flex flex-col md:flex-row md:items-center">
                            {{-- Book Info --}}
                            <div class="flex items-start space-x-4 flex-1">
                                {{-- Book Cover --}}
                                <div class="w-20 h-28 flex-shrink-0">
                                    @if($peminjaman->buku->cover_img)
                                        <img 
                                            src="{{ asset('storage/' . $peminjaman->buku->cover_img) }}"
                                            alt="{{ $peminjaman->buku->judul }}"
                                            class="w-full h-full object-cover rounded-lg shadow-lg"
                                        >
                                    @else
                                        <div class="w-full h-full bg-gray-800 rounded-lg flex items-center justify-center">
                                            <x-icon name="book-open" class="w-8 h-8 text-gray-600" />
                                        </div>
                                    @endif
                                </div>

                                {{-- Book Details --}}
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-start justify-between">
                                        <div>
                                            <h3 class="font-semibold truncate">{{ $peminjaman->buku->judul }}</h3>
                                            <p class="text-sm text-gray-400">{{ $peminjaman->buku->penulis }}</p>
                                        </div>
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-{{ $statusColors[$peminjaman->status] }}-500/20 text-{{ $statusColors[$peminjaman->status] }}-400">
                                            {{ $statusList[$peminjaman->status] }}
                                        </span>
                                    </div>

                                    {{-- Dates --}}
                                    <div class="mt-3 grid grid-cols-2 gap-4">
                                        <div>
                                            <p class="text-xs text-gray-400">Tanggal Peminjaman</p>
                                            <p class="text-sm">{{ $peminjaman->tgl_peminjaman_diinginkan?->format('d M Y') ?? '-' }}</p>
                                        </div>
                                        <div>
                                            <p class="text-xs text-gray-400">Batas Pengembalian</p>
                                            <p class="text-sm">{{ $peminjaman->tgl_kembali_rencana?->format('d M Y') ?? '-' }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Arrow Icon for Mobile --}}
                            <div class="mt-4 md:mt-0 md:ml-4">
                                <x-icon name="chevron-right" class="w-5 h-5 text-gray-400" />
                            </div>
                        </div>
                    </a>
                </div>
            @empty
                <div class="glass-effect rounded-2xl p-8 border border-purple-500/10 text-center">
                    <div class="flex flex-col items-center">
                        <x-icon name="book-open" class="w-16 h-16 text-gray-600 mb-4" />
                        <h3 class="text-xl font-semibold mb-2">Belum Ada Peminjaman</h3>
                        <p class="text-gray-400 mb-6">Anda belum memiliki riwayat peminjaman buku</p>
                        <a href="{{ route('buku') }}" 
                            class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-purple-600 to-indigo-600 rounded-xl font-medium hover:opacity-90 transition-opacity">
                            <x-icon name="plus" class="w-5 h-5 mr-2" />
                            Pinjam Buku Sekarang
                        </a>
                    </div>
                </div>
            @endforelse

            {{-- Pagination --}}
            @if($peminjamans->hasPages())
                <div class="mt-6">
                    {{ $peminjamans->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- Modal Konfirmasi Pengembalian -->
    <div x-data="{ show: false }" 
         x-show="show || $wire.showReturnConfirmation" 
         @open-return-modal.window="show = true"
         @close-return-modal.window="show = false"
         class="fixed inset-0 z-50 overflow-y-auto" 
         style="display: none;">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity bg-gray-900 bg-opacity-75"></div>

            <div class="relative inline-block w-full max-w-lg p-6 my-8 overflow-hidden text-left align-middle transition-all transform bg-[#1A1A2E] rounded-2xl">
                <div class="sm:flex sm:items-start">
                    <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                        <h3 class="text-lg font-medium leading-6 text-white">
                            Konfirmasi Pengembalian
                        </h3>
                        <div class="mt-2">
                            <p class="text-gray-400">
                                Apakah Anda yakin ingin mengembalikan buku ini?
                            </p>
                        </div>
                    </div>
                </div>
                <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                    <button type="button" 
                            wire:click="returnBook"
                            class="inline-flex justify-center w-full px-4 py-2 text-base font-medium text-white bg-green-600 border border-transparent rounded-md shadow-sm hover:bg-green-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                        Ya, Kembalikan
                    </button>
                    <button type="button" 
                            wire:click="closeReturnModal"
                            class="mt-3 inline-flex justify-center w-full px-4 py-2 text-base font-medium text-gray-400 bg-[#1A1A2E] border border-gray-600 rounded-md shadow-sm hover:text-gray-300 focus:outline-none sm:mt-0 sm:w-auto sm:text-sm">
                        Batal
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Rating -->
    @if($showRatingModal)
    <!-- Gunakan format yang sama seperti modal konfirmasi pengembalian -->
    @endif

    <!-- Modal Bayar Denda -->
    @if($showPayDendaModal)
    <!-- Gunakan format yang sama seperti modal konfirmasi pengembalian -->
    @endif
</div> 