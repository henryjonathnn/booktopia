<div class="w-full max-w-md space-y-8 bg-[#1A1A2E]/50 p-8 rounded-2xl border border-purple-500/10 backdrop-blur-sm">
    <div>
        <h2 class="text-3xl font-bold text-center bg-gradient-to-r from-purple-400 to-indigo-400 bg-clip-text text-transparent">
            Buat Akun Baru
        </h2>
        <p class="mt-2 text-center text-gray-400">
            Bergabunglah dengan komunitas pembaca kami
        </p>
    </div>

    <form wire:submit="register" class="mt-8 space-y-6">
        <div class="space-y-4">
            <div>
                <label class="text-sm text-gray-400">Nama Lengkap</label>
                <div class="mt-1">
                    <input wire:model.live.debounce.500ms="name" type="text" required
                        class="w-full bg-[#1A1A2E] rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-purple-500/50 border {{ $errors->has('name') ? 'border-red-500' : 'border-purple-500/10' }} text-white"
                        placeholder="Masukkan nama lengkap Anda">
                    @error('name') <span class="text-sm text-red-400">{{ $message }}</span> @enderror
                </div>
            </div>

            <div>
                <label class="text-sm text-gray-400">Email</label>
                <div class="relative mt-1">
                    <input 
                        wire:model.live.debounce.500ms="email" 
                        type="email" 
                        required
                        class="w-full bg-[#1A1A2E] rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-purple-500/50 border {{ $errors->has('email') ? 'border-red-500' : 'border-purple-500/10' }} text-white"
                        placeholder="Masukkan email Anda">
                    
                    @if($isCheckingEmail)
                    <div class="absolute right-4 top-3.5">
                        <div class="animate-spin rounded-full h-5 w-5 border-2 border-purple-500 border-t-transparent"></div>
                    </div>
                    @endif
                    
                    @error('email') <span class="text-sm text-red-400">{{ $message }}</span> @enderror
                </div>
            </div>

            <div>
                <label class="text-sm text-gray-400">Username</label>
                <div class="relative mt-1">
                    <input 
                        wire:model.live.debounce.500ms="username" 
                        type="text" 
                        required
                        class="w-full bg-[#1A1A2E] rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-purple-500/50 border {{ $errors->has('username') ? 'border-red-500' : 'border-purple-500/10' }} text-white"
                        placeholder="Masukkan username Anda">
                    
                    @if($isCheckingUsername)
                    <div class="absolute right-4 top-3.5">
                        <div class="animate-spin rounded-full h-5 w-5 border-2 border-purple-500 border-t-transparent"></div>
                    </div>
                    @endif
                    
                    @error('username') <span class="text-sm text-red-400">{{ $message }}</span> @enderror
                </div>
            </div>

            <div>
                <label class="text-sm text-gray-400">Password</label>
                <div class="mt-1">
                    <input 
                        wire:model.live.debounce.500ms="password" 
                        type="password" 
                        required
                        class="w-full bg-[#1A1A2E] rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-purple-500/50 border {{ $errors->has('password') ? 'border-red-500' : 'border-purple-500/10' }} text-white"
                        placeholder="Masukkan password Anda">
                    @error('password') <span class="text-sm text-red-400">{{ $message }}</span> @enderror
                </div>
            </div>

            <div>
                <label class="text-sm text-gray-400">Konfirmasi Password</label>
                <div class="mt-1">
                    <input 
                        wire:model.live.debounce.500ms="password_confirmation" 
                        type="password" 
                        required
                        class="w-full bg-[#1A1A2E] rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-purple-500/50 border {{ $errors->has('password_confirmation') ? 'border-red-500' : 'border-purple-500/10' }} text-white"
                        placeholder="Konfirmasi password Anda">
                    @error('password_confirmation') <span class="text-sm text-red-400">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        <button type="submit"
            class="w-full flex justify-center py-3 px-4 border border-transparent rounded-xl shadow-sm text-sm font-medium text-white bg-gradient-to-r from-purple-600 to-indigo-600 hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
            Daftar
        </button>

        <p class="text-center text-sm text-gray-400">
            Sudah punya akun?
            <a href="{{ route('login') }}" class="font-medium text-purple-400 hover:text-purple-300">
                Masuk sekarang
            </a>
        </p>
    </form>
</div>