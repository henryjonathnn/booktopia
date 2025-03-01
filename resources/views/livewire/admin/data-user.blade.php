<div>
    <div class="min-h-screen flex flex-col">
        <div class="flex-grow pt-16">
            <!-- Page Header -->
            <div class="mb-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold">{{ $title ?? 'Data User' }}</h1>
                        <p class="text-sm text-gray-400 mt-1">
                            {{ $subtitle ?? 'Pengelolaan data user yang ada di perpustakaan BooKoo' }}</p>
                    </div>

                    <button wire:click="createUser"
                        class="flex items-center justify-center gap-1 px-2 py-1.5 md:px-4 md:py-2.5 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors text-[11px] md:text-sm md:w-auto w-max whitespace-nowrap">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="w-2.5 h-2.5 md:w-4 md:h-4">
                            <circle cx="12" cy="12" r="10"></circle>
                            <line x1="12" y1="8" x2="12" y2="16"></line>
                            <line x1="8" y1="12" x2="16" y2="12"></line>
                        </svg>
                        <span>{{ $buttonLabel ?? 'Tambah User' }}</span>
                    </button>
                </div>
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
                                                <x-icon name="pen-tool" class="w-4.5 h-4.5" />
                                            </button>

                                            <!-- Delete Button -->
                                            <button
                                                wire:click.stop="$set('confirmingUserDeletion', true); $set('userIdToDelete', {{ $user->id }})"
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
            <x-form-modal :isOpen="$isModalOpen" title="User" :formConfig="$formConfig" :initialData="$currentUser"
                imageField="profileImage" submitAction="saveUser" />

            <!-- Delete Confirmation Modal -->
            @if ($confirmingUserDeletion)
                <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                    <div class="bg-[#0f0a19] p-6 rounded-lg shadow-xl max-w-md w-full">
                        <h3 class="text-lg font-semibold mb-4">Konfirmasi Hapus</h3>
                        <p class="text-gray-400 mb-6">Apakah Anda yakin ingin menghapus user ini?</p>
                        <div class="flex justify-end gap-4">
                            <button wire:click="$set('confirmingUserDeletion', false)"
                                class="px-4 py-2 bg-gray-700 text-gray-300 rounded hover:bg-gray-600">
                                Batal
                            </button>
                            <button wire:click="deleteUser"
                                class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">
                                Hapus
                            </button>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
