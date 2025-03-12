<div class="px-4 md:px-8 lg:px-16 py-8 pt-32">
    <div class="max-w-4xl mx-auto">
        <h1 class="text-3xl font-bold mb-8">Pengaturan Profil</h1>

        {{-- Alert Success --}}
        @if (session('success'))
            <div class="mb-6 p-4 bg-green-500/10 border border-green-500/20 rounded-xl text-green-400">
                {{ session('success') }}
            </div>
        @endif

        <div class="space-y-6">
            {{-- Profile Image Section --}}
            <div class="glass-effect rounded-2xl p-6 border border-purple-500/10">
                <h2 class="text-xl font-semibold mb-4">Foto Profil</h2>
                <div class="flex items-center space-x-6">
                    <div class="relative">
                        <div class="w-24 h-24 rounded-full overflow-hidden border-2 border-purple-500">
                            @if ($profile_image)
                                <img src="{{ $profile_image->temporaryUrl() }}" alt="Preview" class="w-full h-full object-cover">
                            @elseif ($user->profile_img)
                                <img src="{{ asset('storage/' . $user->profile_img) }}" alt="Profile" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full bg-purple-500 flex items-center justify-center">
                                    <x-icon name="user" class="h-12 w-12 text-white" />
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="space-y-2">
                        <input type="file" wire:model="profile_image" class="hidden" id="profile_image" accept="image/*">
                        <label for="profile_image" class="inline-flex items-center px-4 py-2 bg-purple-500/10 hover:bg-purple-500/20 rounded-xl cursor-pointer transition-colors">
                            <x-icon name="upload" class="w-5 h-5 mr-2" />
                            Unggah Foto
                        </label>
                        @if ($user->profile_img)
                            <button wire:click="removeProfileImage" class="inline-flex items-center px-4 py-2 bg-red-500/10 hover:bg-red-500/20 rounded-xl text-red-400 transition-colors">
                                <x-icon name="trash" class="w-5 h-5 mr-2" />
                                Hapus Foto
                            </button>
                        @endif
                        @error('profile_image') <span class="text-red-400 text-sm block">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            {{-- Profile Information Section --}}
            <div class="glass-effect rounded-2xl p-6 border border-purple-500/10">
                <h2 class="text-xl font-semibold mb-4">Informasi Profil</h2>
                <form wire:submit="updateProfile" class="space-y-4">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-400 mb-1">Nama Lengkap</label>
                        <input type="text" wire:model="name" id="name"
                            class="w-full bg-[#1A1A2E] rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-purple-500/50 border border-purple-500/10">
                        @error('name') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="username" class="block text-sm font-medium text-gray-400 mb-1">Username</label>
                        <input type="text" wire:model="username" id="username"
                            class="w-full bg-[#1A1A2E] rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-purple-500/50 border border-purple-500/10">
                        @error('username') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-400 mb-1">Email</label>
                        <input type="email" wire:model="email" id="email"
                            class="w-full bg-[#1A1A2E] rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-purple-500/50 border border-purple-500/10">
                        @error('email') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div class="pt-4">
                        <button type="submit"
                            class="w-full bg-gradient-to-r from-purple-600 to-indigo-600 hover:opacity-90 rounded-xl py-3 font-medium transition-all duration-300">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>

            {{-- Change Password Section --}}
            <div class="glass-effect rounded-2xl p-6 border border-purple-500/10">
                <h2 class="text-xl font-semibold mb-4">Ubah Password</h2>
                <form wire:submit="updatePassword" class="space-y-4">
                    <div>
                        <label for="current_password" class="block text-sm font-medium text-gray-400 mb-1">Password Saat Ini</label>
                        <input type="password" wire:model="current_password" id="current_password"
                            class="w-full bg-[#1A1A2E] rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-purple-500/50 border border-purple-500/10">
                        @error('current_password') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="new_password" class="block text-sm font-medium text-gray-400 mb-1">Password Baru</label>
                        <input type="password" wire:model="new_password" id="new_password"
                            class="w-full bg-[#1A1A2E] rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-purple-500/50 border border-purple-500/10">
                        @error('new_password') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="new_password_confirmation" class="block text-sm font-medium text-gray-400 mb-1">Konfirmasi Password Baru</label>
                        <input type="password" wire:model="new_password_confirmation" id="new_password_confirmation"
                            class="w-full bg-[#1A1A2E] rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-purple-500/50 border border-purple-500/10">
                    </div>

                    <div class="pt-4">
                        <button type="submit"
                            class="w-full bg-gradient-to-r from-purple-600 to-indigo-600 hover:opacity-90 rounded-xl py-3 font-medium transition-all duration-300">
                            Ubah Password
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div> 