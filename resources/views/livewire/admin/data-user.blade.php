<div>
    <div class="pt-16">
        <!-- Page Header -->
        <div class="mb-8">
            <h1 class="text-2xl md:text-3xl font-bold mb-2">Data User</h1>
            <p class="text-gray-400 mb-6">Pengelolaan data user yang ada di perpustakaan BooKoo</p>

            <button wire:click="createUser"
                class="px-4 py-2 bg-purple-600 hover:bg-purple-700 rounded-lg text-white font-medium flex items-center gap-2 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                Tambah User
            </button>
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
                        placeholder="{{ $searchPlaceholder ?? 'Cari user...' }}" />
                </div>

                <div class="flex flex-col sm:flex-row gap-3">
                    <div class="w-full sm:w-40">
                        <select wire:model.live="role"
                            class="w-full px-3 py-2.5 bg-[#0f0a19] rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 text-sm">
                            <option value="">Semua Role</option>
                            <option value="USER">USER</option>
                            <option value="STAFF">STAFF</option>
                            <option value="ADMIN">ADMIN</option>
                        </select>
                    </div>

                    <div class="w-full sm:w-40">
                        <select wire:model.live="active"
                            class="w-full px-3 py-2.5 bg-[#0f0a19] rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 text-sm">
                            <option value="">Semua Status</option>
                            <option value="ACTIVE">Aktif</option>
                            <option value="INACTIVE">Nonaktif</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- User Table -->
        <div class="overflow-x-auto bg-[#1a1625] rounded-xl">
            <table class="min-w-full">
                <thead>
                    <tr class="border-b border-gray-800">
                        <th class="hidden md:table-cell px-6 py-3 text-left">
                            <input type="checkbox" wire:model.live="selectAll"
                                class="rounded border-gray-600 text-purple-600 focus:ring-purple-500" />
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">
                            User Info
                        </th>
                        <th
                            class="hidden sm:table-cell px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">
                            Role
                        </th>
                        <th
                            class="hidden sm:table-cell px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">
                            Status
                        </th>
                        <th
                            class="hidden md:table-cell px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">
                            Join Date
                        </th>
                        <th
                            class="px-6 py-3 text-right sm:text-left text-xs font-medium text-gray-400 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr class="border-b border-gray-800 hover:bg-[#2a2435] transition-colors cursor-pointer"
                            wire:click="viewUserDetails({{ $user->id }})">
                            <td class="hidden md:table-cell px-6 py-4 whitespace-nowrap"
                                onclick="event.stopPropagation();">
                                <input type="checkbox" value="{{ $user->id }}" wire:model.live="selectedUsers"
                                    class="rounded border-gray-600 text-purple-600 focus:ring-purple-500" />
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full overflow-hidden bg-gray-700 flex-shrink-0">
                                        @if ($user->profile_img)
                                            <img src="{{ Storage::url($user->profile_img) }}" alt="{{ $user->name }}"
                                                class="w-full h-full object-cover">
                                        @else
                                            <div class="flex items-center justify-center h-full">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-500"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                </svg>
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        <div class="font-medium text-white">{{ $user->name }}</div>
                                        <div class="text-sm text-gray-400 hidden sm:block">{{ $user->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="hidden sm:table-cell px-6 py-4 whitespace-nowrap">
                                <span
                                    class="px-2 py-1 text-xs inline-flex leading-5 font-medium rounded-full bg-blue-500/10 text-blue-400">
                                    {{ $user->role }}
                                </span>
                            </td>
                            <td class="hidden sm:table-cell px-6 py-4 whitespace-nowrap">
                                <span
                                    class="px-2 py-1 text-xs inline-flex leading-5 font-medium rounded-full 
                                    {{ $user->is_active ? 'bg-green-500/10 text-green-400' : 'bg-red-500/10 text-red-400' }}">
                                    {{ $user->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="hidden md:table-cell px-6 py-4 whitespace-nowrap text-sm text-gray-400">
                                {{ $user->created_at->format('d M Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap" onclick="event.stopPropagation();">
                                <div class="relative">
                                    <div class="flex items-center gap-2">

                                        <!-- Edit Button -->
                                        <button wire:click.stop="editUser({{ $user->id }})"
                                            class="p-1.5 text-gray-400 hover:bg-gray-800 rounded-lg transition-colors">
                                            <x-icon name="pen-tool"
                                                class="w-4.5 h-4.5" />
                                        </button>

                                        <!-- Delete Button -->
                                        <button
                                            wire:click.stop="$set('showDeleteConfirmModal', true); $set('userIdToDelete', {{ $user->id }})"
                                            class="p-1.5 hover:bg-gray-800 rounded-lg transition-colors">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                class="text-gray-400">
                                                <polyline points="3 6 5 6 21 6"></polyline>
                                                <path
                                                    d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2">
                                                </path>
                                                <line x1="10" y1="11" x2="10" y2="17">
                                                </line>
                                                <line x1="14" y1="11" x2="14" y2="17">
                                                </line>
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
                                </div>
                            </td>

                            <!-- Delete Confirmation Modal -->
                            @if ($confirmingUserDeletion)
                                <div
                                    class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                                    <div class="bg-gray-800 p-6 rounded-lg shadow-xl">
                                        <h3 class="text-lg font-semibold mb-4">Konfirmasi Hapus</h3>
                                        <p class="text-gray-400 mb-6">Apakah Anda yakin ingin menghapus item ini?</p>
                                        <div class="flex justify-end gap-4">
                                            <button wire:click="$set('confirmingUserDeletion', false)"
                                                class="px-4 py-2 bg-gray-700 text-gray-300 rounded hover:bg-gray-600">
                                                Batal
                                            </button>
                                            <button wire:click="deleteUser({{ $userIdToDelete }})"
                                                class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">
                                                Hapus
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-gray-400">
                                Tidak ada user yang ditemukan
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-4">
            {{ $users->links() }}
        </div>

        <!-- User Form Modal -->
        <div x-data="{ show: @entangle('isModalOpen') }" x-show="show" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 transform scale-90"
            x-transition:enter-end="opacity-100 transform scale-100"
            x-transition:leave="transition ease-in duration-300"
            x-transition:leave-start="opacity-100 transform scale-100"
            x-transition:leave-end="opacity-0 transform scale-90" class="fixed inset-0 z-50 overflow-y-auto"
            style="display: none;">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                    <div class="absolute inset-0 bg-gray-900 opacity-75"></div>
                </div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div
                    class="inline-block align-bottom bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                                <h3 class="text-lg leading-6 font-medium text-white mb-6">
                                    {{ $userId ? 'Edit User' : 'Tambah User Baru' }}
                                </h3>

                                <form wire:submit.prevent="saveUser" class="space-y-4">
                                    <div>
                                        <label for="name"
                                            class="block text-sm font-medium text-gray-400">Nama</label>
                                        <input type="text" id="name" wire:model="name"
                                            class="mt-1 block w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 text-white">
                                        @error('name')
                                            <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="email"
                                            class="block text-sm font-medium text-gray-400">Email</label>
                                        <input type="email" id="email" wire:model="email"
                                            wire:blur="validateField('email')"
                                            class="mt-1 block w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 text-white">
                                        @error('email')
                                            <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="username"
                                            class="block text-sm font-medium text-gray-400">Username</label>
                                        <input type="text" id="username" wire:model="username"
                                            wire:blur="validateField('username')"
                                            class="mt-1 block w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 text-white">
                                        @error('username')
                                            <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="password" class="block text-sm font-medium text-gray-400">Password
                                            {{ $userId ? '(kosongkan jika tidak ingin mengubah)' : '' }}</label>
                                        <input type="password" id="password" wire:model="password"
                                            class="mt-1 block w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 text-white">
                                        @error('password')
                                            <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="confPassword"
                                            class="block text-sm font-medium text-gray-400">Konfirmasi Password</label>
                                        <input type="password" id="confPassword" wire:model="confPassword"
                                            class="mt-1 block w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 text-white">
                                        @error('confPassword')
                                            <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="userRole"
                                            class="block text-sm font-medium text-gray-400">Role</label>
                                        <select id="userRole" wire:model="userRole"
                                            class="mt-1 block w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 text-white">
                                            <option value="USER">USER</option>
                                            <option value="STAFF">STAFF</option>
                                            <option value="ADMIN">ADMIN</option>
                                        </select>
                                        @error('userRole')
                                            <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="profileImage" class="block text-sm font-medium text-gray-400">Foto
                                            Profil</label>
                                        <input type="file" id="profileImage" wire:model="profileImage"
                                            class="mt-1 block w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 text-white"
                                            accept="image/*">
                                        <div wire:loading wire:target="profileImage"
                                            class="text-xs text-gray-400 mt-1">
                                            Uploading...
                                        </div>
                                        @error('profileImage')
                                            <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                        @enderror

                                        @if ($existingProfileImage)
                                            <div class="mt-2">
                                                <p class="text-xs text-gray-400 mb-1">Current image:</p>
                                                <img src="{{ Storage::url($existingProfileImage) }}"
                                                    class="h-20 w-20 object-cover rounded">
                                            </div>
                                        @endif

                                        @if ($profileImage)
                                            <div class="mt-2">
                                                <p class="text-xs text-gray-400 mb-1">Preview:</p>
                                                <img src="{{ $profileImage->temporaryUrl() }}"
                                                    class="h-20 w-20 object-cover rounded">
                                            </div>
                                        @endif
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button wire:click="saveUser" type="button"
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-purple-600 text-base font-medium text-white hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Simpan
                        </button>
                        <button wire:click="closeModal" type="button"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-600 shadow-sm px-4 py-2 bg-gray-700 text-base font-medium text-gray-300 hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Batal
                        </button>
                    </div>
                    <!-- User Details Modal -->
                    <div x-data="{ show: @entangle('isDetailModalOpen') }" x-show="show" x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 transform scale-90"
                        x-transition:enter-end="opacity-100 transform scale-100"
                        x-transition:leave="transition ease-in duration-300"
                        x-transition:leave-start="opacity-100 transform scale-100"
                        x-transition:leave-end="opacity-0 transform scale-90"
                        class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
                        <div
                            class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                                <div class="absolute inset-0 bg-gray-900 opacity-75"></div>
                            </div>

                            <span class="hidden sm:inline-block sm:align-middle sm:h-screen"
                                aria-hidden="true">&#8203;</span>

                            <div
                                class="inline-block align-bottom bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                                @if ($selectedUser)
                                    <div class="px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                        <div class="sm:flex sm:items-start">
                                            <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                                                <h3 class="text-lg leading-6 font-medium text-white mb-6">
                                                    Detail User
                                                </h3>

                                                <div
                                                    class="flex flex-col items-center sm:flex-row sm:items-start gap-4 mb-6">
                                                    <div
                                                        class="w-24 h-24 rounded-full overflow-hidden bg-gray-700 flex-shrink-0">
                                                        @if ($selectedUser->profile_img)
                                                            <img src="{{ Storage::url($selectedUser->profile_img) }}"
                                                                alt="{{ $selectedUser->name }}"
                                                                class="w-full h-full object-cover">
                                                        @else
                                                            <div class="flex items-center justify-center h-full">
                                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                                    class="h-10 w-10 text-gray-500" fill="none"
                                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                                    <path stroke-linecap="round"
                                                                        stroke-linejoin="round" stroke-width="2"
                                                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                                </svg>
                                                            </div>
                                                        @endif
                                                    </div>

                                                    <div>
                                                        <h3 class="text-xl font-semibold text-white">
                                                            {{ $selectedUser->name }}</h3>
                                                        <p class="text-gray-400">{{ $selectedUser->email }}</p>
                                                        <p class="text-gray-400">{{ $selectedUser->username }}</p>
                                                    </div>
                                                </div>

                                                <div class="border-t border-gray-700 pt-4 mb-4">
                                                    <h4 class="text-md font-medium text-white mb-2">Informasi Akun</h4>

                                                    <div class="space-y-2">
                                                        <div class="flex justify-between">
                                                            <span class="text-gray-400">Role</span>
                                                            <span
                                                                class="px-2 py-1 text-xs inline-flex leading-5 font-medium rounded-full bg-blue-500/10 text-blue-400">
                                                                {{ $selectedUser->role }}
                                                            </span>
                                                        </div>

                                                        <div class="flex justify-between">
                                                            <span class="text-gray-400">Status</span>
                                                            <span
                                                                class="px-2 py-1 text-xs inline-flex leading-5 font-medium rounded-full 
                                   {{ $selectedUser->is_active ? 'bg-green-500/10 text-green-400' : 'bg-red-500/10 text-red-400' }}">
                                                                {{ $selectedUser->is_active ? 'Active' : 'Inactive' }}
                                                            </span>
                                                        </div>

                                                        <div class="flex justify-between">
                                                            <span class="text-gray-400">Tanggal Bergabung</span>
                                                            <span
                                                                class="text-white">{{ $selectedUser->created_at->format('d M Y') }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                <div class="bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                    <button wire:click="closeDetailModal" type="button"
                                        class="w-full inline-flex justify-center rounded-md border border-gray-600 shadow-sm px-4 py-2 bg-gray-700 text-base font-medium text-gray-300 hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 sm:mt-0 sm:w-auto sm:text-sm">
                                        Tutup
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Delete Confirmation Modal -->
                    <div x-data="{ show: @entangle('isDeleteModalOpen') }" x-show="show" x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 transform scale-90"
                        x-transition:enter-end="opacity-100 transform scale-100"
                        x-transition:leave="transition ease-in duration-300"
                        x-transition:leave-start="opacity-100 transform scale-100"
                        x-transition:leave-end="opacity-0 transform scale-90"
                        class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
                        <div
                            class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                                <div class="absolute inset-0 bg-gray-900 opacity-75"></div>
                            </div>

                            <span class="hidden sm:inline-block sm:align-middle sm:h-screen"
                                aria-hidden="true">&#8203;</span>

                            <div
                                class="inline-block align-bottom bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                                <div class="px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                    <div class="sm:flex sm:items-start">
                                        <div
                                            class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                            <svg class="h-6 w-6 text-red-600" xmlns="http://www.w3.org/2000/svg"
                                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                            </svg>
                                        </div>
                                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                            <h3 class="text-lg leading-6 font-medium text-white">
                                                Hapus User
                                            </h3>
                                            <div class="mt-2">
                                                <p class="text-sm text-gray-400">
                                                    Apakah Anda yakin ingin menghapus user ini? Tindakan ini tidak dapat
                                                    dibatalkan.
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                    <button wire:click="deleteUser" type="button"
                                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                                        Hapus
                                    </button>
                                    <button wire:click="closeDeleteModal" type="button"
                                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-600 shadow-sm px-4 py-2 bg-gray-700 text-base font-medium text-gray-300 hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                        Batal
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
