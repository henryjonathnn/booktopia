<div>
    <!-- Desktop Sidebar -->
    <aside class="hidden md:flex flex-col fixed top-0 left-0 h-screen w-20 bg-[#1A1A2E] border-r border-purple-500/10 z-50">
        <!-- Logo -->
        <div class="flex items-center justify-center h-20 bg-[#1A1A2E] border-b border-purple-500/10">
            <div class="h-8 w-8 bg-gradient-to-br from-purple-600 via-purple-500 to-indigo-600 rounded-lg flex items-center justify-center purple-glow">
                <x-icon name="book" class="text-white w-4 h-4" />
            </div>
        </div>
        
        <!-- Menu Items -->
        <div class="flex-1 flex flex-col items-center justify-center space-y-8">
            @foreach ($menuItems as $item)
                <a href="{{ $item['path'] }}" class="p-3 rounded-xl hover:bg-purple-500/10 text-gray-400 hover:text-purple-400 transition-all duration-300 group relative {{ request()->is(trim($item['path'], '/')) ? 'bg-purple-500/10 text-purple-400' : '' }}">
                    <x-icon :name="$item['icon']" class="w-4 h-4" />
                    <span class="absolute left-full ml-4 px-2 py-1 bg-[#1A1A2E] rounded-md text-sm opacity-0 group-hover:opacity-100 whitespace-nowrap">
                        {{ $item['label'] }}
                    </span>
                </a>
            @endforeach
        </div>
    </aside>

    <!-- Mobile Footer Navigation -->
    <div class="md:hidden fixed bottom-0 left-0 right-0 z-50 bg-[#1A1A2E] border-t border-purple-500/10 shadow-lg">
        <nav class="flex justify-around items-center p-3">
            @foreach ($menuItems as $item)
                <a href="{{ $item['path'] }}" class="flex flex-col items-center justify-center space-y-1 {{ request()->is(trim($item['path'], '/')) ? 'text-purple-400' : 'text-gray-400' }}">
                    <x-icon :name="$item['icon']" class="w-5 h-5" />
                    <span class="text-xs">{{ $item['label'] }}</span>
                </a>
            @endforeach
        </nav>
    </div>
</div>