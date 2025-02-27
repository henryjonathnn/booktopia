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
        <div class="bg-gray-800 p-4 rounded-lg mb-6">
            <div class="flex flex-col md:flex-row gap-4">
                <div class="flex-1">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <input wire:model.live.debounce.300ms="search" type="search"
                            class="w-full pl-10 pr-4 py-2 bg-gray-700 border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 text-gray-200"
                            placeholder="Cari user..." />
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row gap-4">
                    <div class="w-full sm:w-40">
                        <select wire:model.live="role"
                            class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 text-gray-200">
                            <option value="">Semua Role</option>
                            <option value="USER">USER</option>
                            <option value="STAFF">STAFF</option>
                            <option value="ADMIN">ADMIN</option>
                        </select>
                    </div>

                    <div class="w-full sm:w-40">
                        <select wire:model.live="active"
                            class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 text-gray-200">
                            <option value="">Semua Status</option>
                            <option value="ACTIVE">Aktif</option>
                            <option value="INACTIVE">Nonaktif</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- User Table -->
        <div class="overflow-x-auto bg-gray-800 rounded-lg">
            <table class="min-w-full divide-y divide-gray-700">
                <thead>
                    <tr>
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
                <tbody class="divide-y divide-gray-700 bg-gray-800">
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
                            <td class="px-6 py-4 whitespace-nowrap text-right sm:text-left"
                                onclick="event.stopPropagation();">
                                <div class="flex justify-end sm:justify-start space-x-2">
                                    <button wire:click.stop="editUser({{ $user->id }})"
                                        class="text-blue-400 hover:text-blue-300">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                            fill="currentColor">
                                            <path
                                                d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                        </svg>
                                    </button>
                                    <button wire:click.stop="confirmUserDeletion({{ $user->id }})"
                                        class="text-red-400 hover:text-red-300">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                            fill="currentColor">
                                            <path fill-rule="evenodd"
                                                d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                    <button wire:click.stop="toggleUserStatus({{ $user->id }})"
                                        class="{{ $user->is_active ? 'text-yellow-400 hover:text-yellow-300' : 'text-green-400 hover:text-green-300' }}">
                                        @if ($user->is_active)
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                                viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd"
                                                    d="M13.477 14.89A6 6 0 015.11 6.524l8.367 8.368zm1.414-1.414L6.524 5.11a6 6 0 018.367 8.367zM18 10a8 8 0 11-16 0 8 8 0 0116 0z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        @else
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                                viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd"
                                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        @endif
                                    </button>
                                </div>
                            </td>
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
