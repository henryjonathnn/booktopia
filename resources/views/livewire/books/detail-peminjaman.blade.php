<div class="px-4 md:px-8 lg:px-16 py-8 pt-32">
    <div class="max-w-4xl mx-auto">
        {{-- Breadcrumb --}}
        <nav class="flex items-center space-x-2 text-sm text-gray-400 mb-8">
            <a href="{{ route('home') }}" class="hover:text-purple-400 transition-colors">Home</a>
            <span class="text-gray-600">/</span>
            <a href="{{ route('buku') }}" class="hover:text-purple-400 transition-colors">Buku</a>
            <span class="text-gray-600">/</span>
            <a href="{{ route('buku.detail', ['slug' => \App\Livewire\Books\Detail::generateSlug($peminjaman->buku)]) }}" 
                class="hover:text-purple-400 transition-colors">
                {{ $peminjaman->buku->judul }}
            </a>
            <span class="text-gray-600">/</span>
            <span class="text-purple-400">Detail Peminjaman</span>
        </nav>

        {{-- Status Card --}}
        <div class="glass-effect rounded-2xl p-6 border border-purple-500/10 mb-6">
            <div class="flex items-center justify-between mb-4">
                <h1 class="text-2xl font-bold">Detail Peminjaman #{{ $peminjaman->id }}</h1>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-{{ $this->statusColor }}-500/20 text-{{ $this->statusColor }}-400">
                    {{ $peminjaman->status }}
                </span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Book Info --}}
                <div class="flex items-start space-x-4 p-4 bg-purple-500/5 rounded-xl">
                    @if ($peminjaman->buku->cover_img)
                        <img src="{{ asset('storage/' . $peminjaman->buku->cover_img) }}" 
                            alt="{{ $peminjaman->buku->judul }}"
                            class="w-24 rounded-lg shadow-lg" />
                    @else
                        <div class="w-24 h-32 rounded-lg bg-gray-800 flex items-center justify-center">
                            <x-icon name="book-open" class="w-12 h-12 text-gray-600" />
                        </div>
                    @endif
                    <div>
                        <h2 class="text-xl font-semibold">{{ $peminjaman->buku->judul }}</h2>
                        <p class="text-gray-400">oleh {{ $peminjaman->buku->penulis }}</p>
                        <div class="mt-2">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-purple-500/20 text-purple-400">
                                {{ $peminjaman->buku->kategori }}
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Due Date Info --}}
                <div class="p-4 bg-purple-500/5 rounded-xl">
                    <h3 class="font-semibold mb-3">Informasi Waktu</h3>
                    <div class="space-y-2">
                        <div class="flex justify-between">
                            <span class="text-gray-400">Tanggal Peminjaman</span>
                            <span>{{ $peminjaman->tgl_peminjaman_diinginkan?->format('d M Y') ?? '-' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-400">Batas Pengembalian</span>
                            <span>{{ $peminjaman->tgl_kembali_rencana?->format('d M Y') ?? '-' }}</span>
                        </div>
                        @if($this->dueDateStatus)
                            <div class="mt-3 p-2 rounded-lg {{ $this->dueDateStatus['status'] === 'late' ? 'bg-red-500/20 text-red-400' : 'bg-green-500/20 text-green-400' }}">
                                @if($this->dueDateStatus['status'] === 'late')
                                    Terlambat {{ $this->dueDateStatus['days'] }} hari
                                @else
                                    Sisa waktu {{ $this->dueDateStatus['days'] }} hari
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Delivery Info --}}
        <div class="glass-effect rounded-2xl p-6 border border-purple-500/10 mb-6">
            <h3 class="text-lg font-semibold mb-4">Informasi Pengiriman</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <p class="text-gray-400 mb-1">Alamat Pengiriman</p>
                    <p class="font-medium">{{ $peminjaman->alamat_pengiriman }}</p>
                    @if($peminjaman->catatan_pengiriman)
                        <p class="text-gray-400 mt-3 mb-1">Catatan</p>
                        <p class="font-medium">{{ $peminjaman->catatan_pengiriman }}</p>
                    @endif
                </div>
                <div>
                    <p class="text-gray-400 mb-1">Metode Pengiriman</p>
                    <p class="font-medium">{{ $peminjaman->metode_pengiriman }}</p>
                    @if($peminjaman->nomor_resi)
                        <p class="text-gray-400 mt-3 mb-1">Nomor Resi</p>
                        <p class="font-medium">{{ $peminjaman->nomor_resi }}</p>
                    @endif
                </div>
            </div>
        </div>

        {{-- Status Timeline --}}
        <div class="glass-effect rounded-2xl p-6 border border-purple-500/10">
            <h3 class="text-lg font-semibold mb-4">Status Peminjaman</h3>
            <div class="space-y-4">
                {{-- PENDING --}}
                <div class="flex items-start space-x-4">
                    <div class="flex-shrink-0 w-8 h-8 rounded-full {{ $peminjaman->status === 'PENDING' ? 'bg-yellow-500' : ($peminjaman->status === 'DITOLAK' ? 'bg-red-500' : 'bg-green-500') }} flex items-center justify-center">
                        @if($peminjaman->status === 'PENDING')
                            <x-icon name="clock" class="w-4 h-4 text-white" />
                        @elseif($peminjaman->status === 'DITOLAK')
                            <x-icon name="x-mark" class="w-4 h-4 text-white" />
                        @else
                            <x-icon name="check" class="w-4 h-4 text-white" />
                        @endif
                    </div>
                    <div class="flex-1">
                        <p class="font-medium">Menunggu Persetujuan</p>
                        <p class="text-sm text-gray-400">{{ $peminjaman->created_at->format('d M Y H:i') }}</p>
                    </div>
                </div>

                @if($peminjaman->status !== 'PENDING' && $peminjaman->status !== 'DITOLAK')
                    {{-- DIPROSES --}}
                    <div class="flex items-start space-x-4">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full {{ in_array($peminjaman->status, ['DIPROSES', 'DIKIRIM', 'DIPINJAM', 'DIKEMBALIKAN']) ? 'bg-green-500' : 'bg-gray-500' }} flex items-center justify-center">
                            <x-icon name="check" class="w-4 h-4 text-white" />
                        </div>
                        <div class="flex-1">
                            <p class="font-medium">Diproses</p>
                            <p class="text-sm text-gray-400">Peminjaman disetujui dan sedang diproses</p>
                        </div>
                    </div>

                    {{-- DIKIRIM --}}
                    <div class="flex items-start space-x-4">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full {{ in_array($peminjaman->status, ['DIKIRIM', 'DIPINJAM', 'DIKEMBALIKAN']) ? 'bg-green-500' : 'bg-gray-500' }} flex items-center justify-center">
                            <x-icon name="truck" class="w-4 h-4 text-white" />
                        </div>
                        <div class="flex-1">
                            <p class="font-medium">Dalam Pengiriman</p>
                            @if($peminjaman->tgl_dikirim)
                                <p class="text-sm text-gray-400">Dikirim pada {{ $peminjaman->tgl_dikirim->format('d M Y H:i') }}</p>
                            @endif
                        </div>
                    </div>

                    {{-- DIPINJAM/TERLAMBAT --}}
                    <div class="flex items-start space-x-4">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full {{ in_array($peminjaman->status, ['DIPINJAM', 'TERLAMBAT']) ? ($peminjaman->status === 'TERLAMBAT' ? 'bg-red-500' : 'bg-green-500') : 'bg-gray-500' }} flex items-center justify-center">
                            <x-icon name="book-open" class="w-4 h-4 text-white" />
                        </div>
                        <div class="flex-1">
                            <p class="font-medium">{{ $peminjaman->status === 'TERLAMBAT' ? 'Terlambat' : 'Sedang Dipinjam' }}</p>
                            @if($this->dueDateStatus)
                                <p class="text-sm {{ $this->dueDateStatus['status'] === 'late' ? 'text-red-400' : 'text-gray-400' }}">
                                    @if($this->dueDateStatus['status'] === 'late')
                                        Terlambat {{ $this->dueDateStatus['days'] }} hari
                                    @else
                                        Sisa waktu {{ $this->dueDateStatus['days'] }} hari
                                    @endif
                                </p>
                            @endif
                        </div>
                    </div>

                    @if($peminjaman->status === 'DIKEMBALIKAN')
                        {{-- DIKEMBALIKAN --}}
                        <div class="flex items-start space-x-4">
                            <div class="flex-shrink-0 w-8 h-8 rounded-full bg-green-500 flex items-center justify-center">
                                <x-icon name="check" class="w-4 h-4 text-white" />
                            </div>
                            <div class="flex-1">
                                <p class="font-medium">Dikembalikan</p>
                                <p class="text-sm text-gray-400">Dikembalikan pada {{ $peminjaman->tgl_kembali_aktual->format('d M Y H:i') }}</p>
                            </div>
                        </div>
                    @endif
                @endif

                @if($peminjaman->status === 'DITOLAK')
                    {{-- DITOLAK --}}
                    <div class="p-4 bg-red-500/10 rounded-xl mt-4">
                        <p class="text-red-400 font-medium mb-2">Alasan Penolakan:</p>
                        <p>{{ $peminjaman->alasan_penolakan }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div> 