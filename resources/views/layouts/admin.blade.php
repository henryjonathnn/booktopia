<x-app-layout>
    <div class="min-h-screen bg-[#0f0a19] text-white antialiased">
        @livewire('admin.layouts.sidebar')

        <main class="md:ml-64">
            @livewire('admin.layouts.navbar')

            <div class="p-4 md:p-8 pt-20">
                {{ $slot }}
            </div>

            @livewire('admin.layouts.footer')
        </main>
    </div>
</x-app-layout>
