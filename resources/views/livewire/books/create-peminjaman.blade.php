<div class="px-4 md:px-8 lg:px-16 py-8 pt-32">
    <div class="max-w-3xl mx-auto">
        {{-- Breadcrumb --}}
        <nav class="flex items-center space-x-2 text-sm text-gray-400 mb-8">
            <a href="{{ route('home') }}" class="hover:text-purple-400 transition-colors">Home</a>
            <span class="text-gray-600">/</span>
            <a href="{{ route('buku') }}" class="hover:text-purple-400 transition-colors">Buku</a>
            <span class="text-gray-600">/</span>
            <a href="{{ route('buku.detail', ['slug' => \App\Livewire\Books\Detail::generateSlug($book)]) }}" 
                class="hover:text-purple-400 transition-colors">
                {{ $book->judul }}
            </a>
            <span class="text-gray-600">/</span>
            <span class="text-purple-400">Pinjam Buku</span>
        </nav>

        {{-- Form Card --}}
        <div class="glass-effect rounded-2xl p-6 border border-purple-500/10">
            <h1 class="text-2xl font-bold mb-6">Form Peminjaman Buku</h1>

            {{-- Book Info --}}
            <div class="flex items-start space-x-4 mb-8 p-4 bg-purple-500/5 rounded-xl">
                @if ($book->cover_img)
                    <img src="{{ asset('storage/' . $book->cover_img) }}" 
                        alt="{{ $book->judul }}"
                        class="w-24 rounded-lg shadow-lg" />
                @else
                    <div class="w-24 h-32 rounded-lg bg-gray-800 flex items-center justify-center">
                        <x-icon name="book-open" class="w-12 h-12 text-gray-600" />
                    </div>
                @endif
                <div>
                    <h2 class="text-xl font-semibold">{{ $book->judul }}</h2>
                    <p class="text-gray-400">oleh {{ $book->penulis }}</p>
                    <div class="mt-2">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-purple-500/20 text-purple-400">
                            {{ $book->kategori }}
                        </span>
                    </div>
                </div>
            </div>

            {{-- Informasi Peminjam --}}
            <div class="mb-8 p-4 bg-purple-500/5 rounded-xl">
                <h3 class="text-lg font-semibold mb-4">Informasi Peminjam</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm text-gray-400 mb-1">Nama Lengkap</label>
                        <p class="font-medium">{{ auth()->user()->name }}</p>
                    </div>
                    <div>
                        <label class="block text-sm text-gray-400 mb-1">Username</label>
                        <p class="font-medium">{{ auth()->user()->username }}</p>
                    </div>
                    <div>
                        <label class="block text-sm text-gray-400 mb-1">Email</label>
                        <p class="font-medium">{{ auth()->user()->email }}</p>
                    </div>
                    <div>
                        <label class="block text-sm text-gray-400 mb-1">Status</label>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-500/10 text-green-400">
                            Aktif
                        </span>
                    </div>
                </div>
            </div>

            <style>
                /* Mengubah warna icon date picker menjadi putih */
                input[type="date"]::-webkit-calendar-picker-indicator {
                    filter: invert(1);
                    opacity: 0.8;
                }
                
                /* Mengatur warna teks input date menjadi putih */
                input[type="date"] {
                    color: white;
                }
                
                /* Mengatur warna placeholder untuk input date */
                input[type="date"]::-webkit-datetime-edit-fields-wrapper { color: white; }
                input[type="date"]::-webkit-datetime-edit { color: white; }
                input[type="date"]::-webkit-datetime-edit-text { color: white; }
                input[type="date"]::-webkit-datetime-edit-month-field { color: white; }
                input[type="date"]::-webkit-datetime-edit-day-field { color: white; }
                input[type="date"]::-webkit-datetime-edit-year-field { color: white; }
            </style>

            <form wire:submit.prevent="createPeminjaman" class="space-y-6">
                {{-- Alamat Pengiriman --}}
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-2">
                        Alamat Pengiriman <span class="text-red-500">*</span>
                    </label>
                    <textarea 
                        wire:model="alamat_pengiriman"
                        class="w-full bg-[#1A1A2E] rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-purple-500/50 border border-purple-500/10"
                        rows="3"
                        placeholder="Masukkan alamat lengkap pengiriman"
                    ></textarea>
                    @error('alamat_pengiriman')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Catatan Pengiriman --}}
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-2">
                        Catatan Pengiriman
                    </label>
                    <textarea 
                        wire:model="catatan_pengiriman"
                        class="w-full bg-[#1A1A2E] rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-purple-500/50 border border-purple-500/10"
                        rows="2"
                        placeholder="Tambahkan catatan untuk pengiriman (opsional)"
                    ></textarea>
                </div>

                {{-- Tanggal Peminjaman dan Pengembalian --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Tanggal Peminjaman --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-2">
                            Tanggal Peminjaman <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="date"
                            wire:model="tgl_peminjaman"
                            class="w-full bg-[#1A1A2E] rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-purple-500/50 border border-purple-500/10"
                            min="{{ $minDatePinjam }}"
                            max="{{ $maxDatePinjam }}"
                        >
                        @error('tgl_peminjaman')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-400">
                            Maksimal pemesanan: {{ Carbon\Carbon::parse($maxDatePinjam)->format('d M Y') }}
                        </p>
                    </div>

                    {{-- Tanggal Pengembalian --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-2">
                            Tanggal Pengembalian <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="date"
                            wire:model="tgl_pengembalian"
                            class="w-full bg-[#1A1A2E] rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-purple-500/50 border border-purple-500/10"
                            min="{{ $minDateKembali }}"
                            max="{{ $maxDateKembali }}"
                            {{ !$tgl_peminjaman ? 'disabled' : '' }}
                        >
                        @error('tgl_pengembalian')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                        @if($tgl_peminjaman)
                        <p class="mt-1 text-xs text-gray-400">
                            Rentang pengembalian: {{ Carbon\Carbon::parse($minDateKembali)->format('d M Y') }} - {{ Carbon\Carbon::parse($maxDateKembali)->format('d M Y') }}
                        </p>
                        @endif
                    </div>
                </div>

                {{-- Info Box --}}
                <div class="p-4 rounded-xl bg-purple-500/10 border border-purple-500/20">
                    <h3 class="font-medium text-purple-400 mb-2">Informasi Peminjaman</h3>
                    <ul class="text-sm text-gray-400 space-y-1">
                        <li>• Peminjaman akan diproses atas nama: {{ auth()->user()->name }}</li>
                        <li>• Tanggal peminjaman bisa hari ini sampai maksimal 3 hari ke depan</li>
                        <li>• Durasi peminjaman minimal 1 hari dan maksimal 7 hari dari tanggal pinjam</li>
                        <li>• Keterlambatan pengembalian akan dikenakan denda</li>
                        <li>• Pastikan alamat pengiriman sudah benar dan lengkap</li>
                    </ul>
                </div>

                {{-- Submit Button --}}
                <div class="flex justify-end">
                    <button type="submit"
                        class="bg-gradient-to-r from-purple-600 to-indigo-600 hover:opacity-90 rounded-xl px-6 py-3 font-medium transition-all duration-300">
                        Buat Peminjaman
                    </button>
                </div>
            </form>
        </div>
    </div>
</div> 