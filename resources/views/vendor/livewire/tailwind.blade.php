<div>
    @if ($paginator->hasPages())
        <nav role="navigation" aria-label="Pagination Navigation" class="flex justify-center">
            <div class="flex items-center space-x-2">
                {{-- Previous Page Link --}}
                @if ($paginator->onFirstPage())
                    <button class="p-2 md:p-3 rounded-xl bg-[#1A1A2E] opacity-50 cursor-not-allowed" aria-disabled="true">
                        <x-icon name="chevron-left" class="w-4.5 h-4.5"/>
                    </button>
                @else
                    <button wire:click="previousPage('{{ $paginator->getPageName() }}')" wire:loading.attr="disabled" class="p-2 md:p-3 rounded-xl bg-[#1A1A2E] hover:bg-purple-500/10 transition-all duration-300 border border-purple-500/10">
                        <x-icon name="chevron-left" class="w-4.5 h-4.5"/>
                    </button>
                @endif

                {{-- Pagination Elements --}}
                <div class="hidden md:flex">
                    @foreach ($elements as $element)
                        {{-- "Three Dots" Separator --}}
                        @if (is_string($element))
                            <span class="p-2 md:p-3 rounded-xl bg-[#1A1A2E] opacity-50">{{ $element }}</span>
                        @endif

                        {{-- Array Of Links --}}
                        @if (is_array($element))
                            @foreach ($element as $page => $url)
                                @if ($page == $paginator->currentPage())
                                    <button class="p-2 md:p-3 rounded-xl bg-purple-600 text-white" aria-current="page">
                                        <span>{{ $page }}</span>
                                    </button>
                                @else
                                    <button wire:click="gotoPage({{ $page }}, '{{ $paginator->getPageName() }}')" wire:loading.attr="disabled" class="p-2 md:p-3 rounded-xl bg-[#1A1A2E] hover:bg-purple-500/10 transition-all duration-300 border border-purple-500/10">
                                        {{ $page }}
                                    </button>
                                @endif
                            @endforeach
                        @endif
                    @endforeach
                </div>

                {{-- Mobile Pagination with Current Page Indicator --}}
                <div class="flex md:hidden items-center">
                    <span class="text-sm text-gray-400">
                        {{ $paginator->currentPage() }} dari {{ $paginator->lastPage() }}
                    </span>
                </div>

                {{-- Next Page Link --}}
                @if ($paginator->hasMorePages())
                    <button wire:click="nextPage('{{ $paginator->getPageName() }}')" wire:loading.attr="disabled" class="p-2 md:p-3 rounded-xl bg-[#1A1A2E] hover:bg-purple-500/10 transition-all duration-300 border border-purple-500/10">
                        <x-icon name="chevron-right" class="w-4.5 h-4.5"/>
                    </button>
                @else
                    <button class="p-2 md:p-3 rounded-xl bg-[#1A1A2E] opacity-50 cursor-not-allowed" aria-disabled="true">
                        <x-icon name="chevron-right" class="w-4.5 h-4.5"/>
                    </button>
                @endif
            </div>
        </nav>
    @endif
</div>