<div>
    {{-- Modal --}}
    @if($isOpen)
    <div class="fixed inset-0 flex items-center justify-center z-[60] p-4 overflow-y-auto overflow-x-hidden">
        {{-- Backdrop dengan z-index lebih rendah --}}
        <div class="fixed inset-0 bg-black/60 backdrop-blur-sm z-[61]" wire:click="$set('isOpen', false)"></div>
        
        {{-- Modal Container --}}
        <div class="relative w-full max-w-md z-[62] my-8">
            <div class="bg-[#1A1A2E]/90 border border-purple-500/10 rounded-xl shadow-xl">
                <div class="p-6">
                    {{-- Header --}}
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-bold bg-gradient-to-r from-purple-400 to-indigo-400 bg-clip-text text-transparent">
                            {{ $isLoginMode ? 'Selamat Datang' : 'Buat Akun' }}
                        </h2>
                        <button wire:click="$set('isOpen', false)" class="p-2 hover:bg-purple-500/10 rounded-lg transition-colors">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    {{-- Progress Bar untuk Register --}}
                    @if(!$isLoginMode)
                    <div class="mb-8">
                        <div class="relative pt-2">
                            <div class="w-full bg-gray-700 rounded h-2">
                                <div class="bg-purple-600 h-2 rounded transition-all duration-300 ease-in-out"
                                    style="width: {{ $registerStep === 1 ? '50%' : '100%' }}">
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    {{-- Forms --}}
                    <form wire:submit="{{$isLoginMode ? 'login' : 'register'}}" class="space-y-4">
                        @if($isLoginMode)
                            {{-- Login Form --}}
                            <x-form-input
                                label="Email"
                                type="email"
                                wire:model="loginEmail"
                                placeholder="Masukkan email kamu"
                                :error="$errors->first('loginEmail')"
                            />

                            <x-form-input
                                label="Password"
                                type="password"
                                wire:model="loginPassword"
                                placeholder="Masukkan password kamu"
                                :error="$errors->first('loginPassword')"
                            />

                            <div class="flex items-center">
                                <input wire:model="remember" type="checkbox" id="remember" 
                                    class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                <label for="remember" class="ml-2 block text-sm text-gray-400">
                                    Remember me
                                </label>
                            </div>
                        @else
                            {{-- Register Form --}}
                            @if($registerStep === 1)
                                <x-form-input
                                    label="Nama"
                                    type="text"
                                    wire:model="name"
                                    placeholder="Masukkan nama kamu"
                                    :error="$errors->first('name')"
                                />

                                <x-form-input
                                    label="Email"
                                    type="email"
                                    wire:model.live="email"
                                    placeholder="Masukkan email kamu"
                                    :error="$errors->first('email')"
                                    :is-validating="$isValidating['email'] ?? false"
                                />

                                <x-form-input
                                    label="Password"
                                    type="password"
                                    wire:model="password"
                                    placeholder="Masukkan password kamu"
                                    :error="$errors->first('password')"
                                />

                                <x-form-input
                                    label="Konfirmasi Password"
                                    type="password"
                                    wire:model="password_confirmation"
                                    placeholder="Konfirmasi password kamu"
                                />
                            @else
                                <x-form-input
                                    label="Username"
                                    type="text"
                                    wire:model.live="username"
                                    placeholder="Masukkan username kamu"
                                    :error="$errors->first('username')"
                                    :is-validating="$isValidating['username'] ?? false"
                                />
                            @endif
                        @endif

                        {{-- Action Buttons --}}
                        <div class="flex gap-3">
                            @if(!$isLoginMode && $registerStep === 2)
                                <button type="button" wire:click="$set('registerStep', 1)"
                                    class="flex-1 px-6 py-3 rounded-xl font-medium bg-gray-600 hover:bg-gray-500 transition-colors flex items-center justify-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                                    </svg>
                                    Back
                                </button>
                            @endif

                            <button type="submit"
                                class="flex-1 px-6 py-3 rounded-xl font-medium transition-all duration-200 
                                {{ $this->isFormValid ? 'bg-gradient-to-r from-purple-600 to-indigo-600 hover:opacity-90' : 'bg-gray-600 opacity-50 cursor-not-allowed' }}
                                flex items-center justify-center gap-2">
                                @if($isLoginMode)
                                    Masuk
                                @else
                                    @if($registerStep === 1)
                                        Next
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                        </svg>
                                    @else
                                        Create Account
                                    @endif
                                @endif
                            </button>
                        </div>
                    </form>

                    {{-- Toggle Mode --}}
                    <div class="mt-6 text-center">
                        <p class="text-gray-400">
                            {{ $isLoginMode ? "Belum punya akun?" : "Sudah punya akun?" }}
                            <button wire:click="toggleMode"
                                class="ml-2 text-purple-400 hover:text-purple-300">
                                {{ $isLoginMode ? 'Daftar' : 'Masuk' }}
                            </button>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>