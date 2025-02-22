<x-app-layout>
    <div class="min-h-screen bg-[#0D0D1A] text-white bg-pattern">
        {{-- @livewire('auth.auth-modal') --}}
        @livewire('user.layouts.sidebar')
        <main class="md:ml-20">
            @livewire('user.layouts.navbar')
            {{ $slot }}
            @livewire('user.layouts.footer')
        </main>
    </div>
</x-app-layout>
