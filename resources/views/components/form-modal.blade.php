@if($isOpen)
<div 
    class="fixed inset-0 z-50 flex items-center justify-center"
    x-data
    x-init="$nextTick(() => { document.body.classList.add('overflow-hidden'); $el.querySelector('input:not([type=file]):not([type=hidden])').focus() })"
    x-on:keydown.escape.window="$wire.closeModal()"
>
    <!-- Backdrop -->
    <div class="fixed inset-0 bg-black/50" wire:click="closeModal"></div>
    
    <!-- Modal Content -->
    <div class="relative z-50 w-full max-w-2xl max-h-[90vh] overflow-y-auto bg-[#0f0a19] rounded-lg shadow-xl">
        <!-- Modal Header -->
        <div class="sticky top-0 flex items-center justify-between p-6 bg-[#0f0a19] border-b border-gray-700">
            <h2 class="text-xl font-semibold">
                {{ isset($initialData) ? "Edit $title" : "Tambah $title Baru" }}
            </h2>
            <button
                wire:click="closeModal"
                class="p-2 hover:bg-gray-800 rounded-full transition-colors"
            >
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>
        </div>

        <!-- Form -->
        <form wire:submit.prevent="{{ $submitAction }}" class="p-6 space-y-6">
            @if (session()->has('alert'))
            <div
                class="flex items-center gap-2 px-4 py-3 rounded-lg {{ session('alert.type') === 'error' ? 'bg-red-500/10 text-red-400' : 'bg-green-500/10 text-green-400' }}"
            >
                @if (session('alert.type') === 'error')
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="12" y1="8" x2="12" y2="12"></line>
                    <line x1="12" y1="16" x2="12.01" y2="16"></line>
                </svg>
                @else
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                    <polyline points="22 4 12 14.01 9 11.01"></polyline>
                </svg>
                @endif
                <span>{{ session('alert.message') }}</span>
            </div>
            @endif

            <div class="space-y-4">
                <!-- Image Upload Field -->
                @if ($imageField)
                <div class="flex flex-col gap-2">
                    <label class="font-medium">
                        {{ str_contains(strtolower($title), 'book') || str_contains(strtolower($title), 'buku') ? 'Foto Sampul' : 'Foto Profil' }}
                    </label>
                    <div class="flex items-start gap-4">
                        <div class="w-32 h-40 bg-gray-800 rounded-lg overflow-hidden">
                            @if ($initialData && isset($initialData->{$imageField}) && $initialData->{$imageField})
                                <img
                                    src="{{ Storage::url($initialData->{$imageField}) }}"
                                    alt="Preview"
                                    class="w-full h-full object-cover"
                                />
                            @elseif ($this->{$imageField} && !$errors->has($imageField))
                                <img
                                    src="{{ $this->{$imageField}->temporaryUrl() }}"
                                    alt="Preview"
                                    class="w-full h-full object-cover"
                                />
                            @else
                                <div class="w-full h-full flex items-center justify-center text-gray-500">
                                    @if (str_contains(strtolower($title), 'book') || str_contains(strtolower($title), 'buku'))
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path>
                                            <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path>
                                        </svg>
                                    @else
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                            <circle cx="12" cy="7" r="4"></circle>
                                        </svg>
                                    @endif
                                </div>
                            @endif
                        </div>
                        <div class="flex-1">
                            <input
                                type="file"
                                wire:model="{{ $imageField }}"
                                class="w-full text-sm file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-medium file:bg-purple-600 file:text-white hover:file:bg-purple-700 file:cursor-pointer"
                                accept="image/png, image/jpeg, image/jpg"
                            />
                            <p class="mt-2 text-sm text-gray-400">
                                Format yang diizinkan: JPG, JPEG, PNG. Ukuran maksimal: 2MB
                            </p>
                            @error($imageField)
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
                @endif

                <!-- Form Fields -->
                @foreach ($formConfig as $field)
                <div class="flex flex-col gap-2">
                    <label for="{{ $field['id'] }}" class="font-medium">
                        {{ $field['label'] }}
                        @if ($field['required'] ?? false)
                            <span class="text-red-500 ml-1">*</span>
                        @endif
                    </label>
                    
                    @if ($field['type'] === 'select')
                        <select
                            wire:model="{{ $field['id'] }}"
                            id="{{ $field['id'] }}"
                            class="w-full px-3 py-2 bg-gray-800 border border-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500"
                            {{ ($field['required'] ?? false) ? 'required' : '' }}
                        >
                            <option value="">Pilih {{ $field['label'] }}</option>
                            @foreach ($field['options'] as $option)
                                <option value="{{ $option }}">{{ $option }}</option>
                            @endforeach
                        </select>
                    @elseif ($field['type'] === 'textarea')
                        <textarea
                            wire:model="{{ $field['id'] }}"
                            id="{{ $field['id'] }}"
                            rows="{{ $field['rows'] ?? 3 }}"
                            class="w-full px-3 py-2 bg-gray-800 border border-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500"
                            {{ ($field['required'] ?? false) ? 'required' : '' }}
                        ></textarea>
                    @else
                        <input
                            type="{{ $field['type'] ?? 'text' }}"
                            wire:model="{{ $field['id'] }}"
                            id="{{ $field['id'] }}"
                            class="w-full px-3 py-2 bg-gray-800 border border-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500"
                            {{ ($field['required'] ?? false) ? 'required' : '' }}
                        />
                    @endif
                    
                    @error($field['id'])
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
                @endforeach
            </div>

            <!-- Modal Footer -->
            <div class="flex justify-end gap-3 pt-6 border-t border-gray-700">
                <button
                    type="button"
                    wire:click="closeModal"
                    class="px-4 py-2 text-sm font-medium text-white bg-gray-700 rounded-lg hover:bg-gray-600 transition-colors"
                >
                    Batal
                </button>
                <button
                    type="submit"
                    class="px-4 py-2 text-sm font-medium text-white bg-purple-600 rounded-lg hover:bg-purple-500 transition-colors"
                >
                    {{ isset($initialData) ? 'Simpan Perubahan' : 'Tambah' }}
                </button>
            </div>
        </form>
    </div>
</div>
@endif