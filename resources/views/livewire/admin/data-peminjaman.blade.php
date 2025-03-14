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

            <!-- Data Table -->
            <div class="bg-[#1a1625] rounded-xl shadow-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-purple-500/10">
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Peminjam</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Buku</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Tanggal</th>
                                <th class="px-6 py-4 text-right text-xs font-medium text-gray-400 uppercase tracking-wider">Aksi</th>
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
                                                <div class="text-sm text-gray-400">{{ $peminjaman->buku->penulis }}</div>
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
                                        <div class="flex items-center justify-end space-x-2">
                                            @if($peminjaman->status === 'PENDING')
                                                <button wire:click="approvePeminjaman({{ $peminjaman->id }})"
                                                    class="bg-green-500/10 text-green-500 hover:bg-green-500/20 p-2 rounded-lg transition-colors">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                    </svg>
                                                </button>
                                                <button wire:click="showReject({{ $peminjaman->id }})"
                                                    class="bg-red-500/10 text-red-500 hover:bg-red-500/20 p-2 rounded-lg transition-colors">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                    </svg>
                                                </button>
                                            @elseif($peminjaman->status === 'DIPROSES')
                                                <input 
                                                    type="file" 
                                                    wire:model="buktiPengiriman" 
                                                    id="buktiPengiriman_{{ $peminjaman->id }}"
                                                    class="hidden"
                                                    accept="image/*"
                                                    wire:change="uploadBuktiPengiriman({{ $peminjaman->id }})"
                                                >
                                                <label 
                                                    for="buktiPengiriman_{{ $peminjaman->id }}"
                                                    class="bg-purple-500/10 text-purple-500 hover:bg-purple-500/20 px-3 py-1.5 rounded-lg transition-colors inline-flex items-center space-x-1 cursor-pointer"
                                                >
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                                                    </svg>
                                                    <span>Kirim Sekarang</span>
                                                </label>
                                            @endif
                                            <button wire:click="showDetail({{ $peminjaman->id }})"
                                                class="bg-gray-500/10 text-gray-400 hover:bg-gray-500/20 p-2 rounded-lg transition-colors">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-10 text-center">
                                        <div class="flex flex-col items-center">
                                            <div class="w-16 h-16 mb-4 rounded-full bg-purple-500/10 flex items-center justify-center">
                                                <svg class="w-8 h-8 text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
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

    <!-- Modal Detail -->
    @if($activeModal === 'detail' && $selectedPeminjaman)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex min-h-screen items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <div class="fixed inset-0 bg-black/50 transition-opacity" wire:click="closeModal"></div>
                
                <div class="relative transform overflow-hidden rounded-lg bg-[#1a1625] px-4 pb-4 pt-5 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:p-6">
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
                            <button wire:click="closeModal"
                                class="w-full inline-flex justify-center rounded-lg bg-purple-500 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-purple-600">
                                Tutup
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Modal Reject -->
    @if($activeModal === 'reject' && $selectedPeminjaman)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex min-h-screen items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <div class="fixed inset-0 bg-black/50 transition-opacity" wire:click="closeModal"></div>
                
                <div class="relative transform overflow-hidden rounded-lg bg-[#1a1625] px-4 pb-4 pt-5 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:p-6">
                    <div>
                        <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-red-500/10">
                            <svg class="h-6 w-6 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
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
                        <textarea
                            wire:model="alasanPenolakan"
                            class="w-full bg-[#0f0a19] rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-500 border border-gray-800"
                            rows="3"
                            placeholder="Alasan penolakan..."
                        ></textarea>
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
</div>
