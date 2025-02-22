<section class="px-4 md:px-8 lg:px-16 mx-2 mb-8 md:mb-16">
    <div class="flex flex-col md:flex-row md:justify-between md:items-center mb-6 md:mb-8">
        <div class="mb-4 md:mb-0">
            <div class="flex items-center space-x-3">
                <h2 class="text-2xl md:text-3xl font-bold">{{ $title }}</h2>
                <span class="flex items-center space-x-1 px-3 py-1 rounded-full {{ $badgeColor === 'purple' ? 'bg-purple-500/20 text-purple-400' : 'bg-red-500/20 text-red-400' }} text-sm">
                    <span class="h-2 w-2 {{ $badgeColor === 'purple' ? 'bg-purple-400' : 'bg-red-400' }} rounded-full animate-pulse"></span>
                    <span>{{ $badgeText }}</span>
                </span>
            </div>
            <p class="text-gray-400 mt-2">{{ $subtitle }}</p>
        </div>
        <div class="flex space-x-3">
            <button
                wire:click="previousPage"
                @class([
                    'p-2 md:p-3 rounded-xl bg-[#1A1A2E] hover:bg-purple-500/10 transition-all duration-300 border border-purple-500/10',
                    'opacity-50 cursor-not-allowed' => $currentPage === 1
                ])
                {{ $currentPage === 1 ? 'disabled' : '' }}
            >
                <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </button>
            <button
                wire:click="nextPage"
                class="p-2 md:p-3 rounded-xl bg-[#1A1A2E] hover:bg-purple-500/10 transition-all duration-300 border border-purple-500/10"
            >
                <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </button>
            <button
                wire:click="toggleFilterModal"
                class="p-2 md:p-3 rounded-xl bg-[#1A1A2E] hover:bg-purple-500/10 transition-all duration-300 border border-purple-500/10"
            >
                <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                </svg>
            </button>
            <button
                wire:click="$emit('showAllBooks')"
                class="px-3 py-1 md:px-4 md:py-2 rounded-xl bg-[#1A1A2E] hover:bg-purple-500/10 transition-all duration-300 border border-purple-500/10 text-white text-sm md:text-base"
            >
                Lihat Semua
            </button>
        </div>
    </div>

    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4 md:gap-6">
        @foreach($books as $book)
            <livewire:book-card 
                :key="$book['id']"
                :book="$book"
                :show-rating="$showRating"
                :right-label="$rightLabel" />
        @endforeach
    </div>

    @if($showFilterModal)
        <div class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center">
            <div class="bg-[#1A1A2E] p-6 rounded-2xl max-w-md w-full mx-4">
                <!-- Filter Modal Content -->
                <h3 class="text-xl font-bold mb-4">Filter Buku</h3>
                <!-- Add your filter controls here -->
                <button
                    wire:click="toggleFilterModal"
                    class="w-full mt-4 px-4 py-2 bg-purple-500 rounded-xl"
                >
                    Tutup
                </button>
            </div>
        </div>
    @endif
</div>