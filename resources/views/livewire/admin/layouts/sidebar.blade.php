<div x-data="{ sidebarOpen: false }">
    <!-- Overlay for mobile -->
    <div 
        x-show="sidebarOpen" 
        @click="sidebarOpen = false"
        class="fixed inset-0 bg-black/60 backdrop-blur-sm z-30 md:hidden"
    ></div>

    <!-- Sidebar - visible on desktop only -->
    <aside 
        x-bind:class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
        class="
            fixed top-0 left-0 h-full z-40 
            bg-[#1a1625] w-64
            transform transition-all duration-300 ease-in-out
            md:translate-x-0 md:z-0 flex flex-col
            md:block hidden
        "
    >
        <!-- Logo Section -->
        <div class="p-5 border-b border-gray-800">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-7 h-7 bg-purple-500/20 rounded flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-book-open text-purple-400"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path></svg>
                    </div>
                    <a class="text-xl font-bold bg-gradient-to-r from-purple-400 to-indigo-400 bg-clip-text text-transparent" href="{{ route('admin.dashboard') }}">
                        BooKoo
                    </a>
                </div>
                <button 
                    @click="sidebarOpen = false"
                    class="p-1.5 hover:bg-[#2a2435] rounded-lg transition-colors md:hidden"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x text-gray-400"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                </button>
            </div>
        </div>

        <!-- Navigation -->
        <div class="flex-1 py-4">
            <div class="px-3 text-xs font-medium text-gray-400 uppercase mb-4">Menu</div>
            <nav class="px-3 space-y-0.5">
                <a 
                    href="{{ route('admin.dashboard') }}" 
                    class="{{ request()->routeIs('admin.dashboard') ? 'bg-purple-500/20 text-purple-400 font-medium' : 'text-gray-400 hover:bg-[#2a2435] hover:text-gray-200' }} flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition-all duration-200"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-book-open"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path></svg>
                    <span>Dashboard</span>
                </a>
                
                <a 
                    href="{{ route('admin.data-user') }}"
                    class="{{ request()->routeIs('admin.data-user*') ? 'bg-purple-500/20 text-purple-400 font-medium' : 'text-gray-400 hover:bg-[#2a2435] hover:text-gray-200' }} flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition-all duration-200"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-users"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                    <span>Data User</span>
                </a>
                
                <a 
                    href="{{ route('admin.data-buku') }}" 
                    class="{{ request()->routeIs('admin.data-buku*') ? 'bg-purple-500/20 text-purple-400 font-medium' : 'text-gray-400 hover:bg-[#2a2435] hover:text-gray-200' }} flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition-all duration-200"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-book"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path></svg>
                    <span>Data Buku</span>
                </a>
                
                <a 
                    href="{{ route('admin.data-peminjaman') }}" 
                    class="{{ request()->routeIs('admin.data-peminjaman*') ? 'bg-purple-500/20 text-purple-400 font-medium' : 'text-gray-400 hover:bg-[#2a2435] hover:text-gray-200' }} flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition-all duration-200"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-package"><line x1="16.5" y1="9.4" x2="7.5" y2="4.21"></line><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path><polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline><line x1="12" y1="22.08" x2="12" y2="12"></line></svg>
                    <span>Data Peminjaman</span>
                </a>
            </nav>
        </div>
    </aside>

    <!-- Mobile Footer Navigation - visible on mobile/tablet only -->
    <div class="fixed bottom-0 left-0 right-0 bg-[#1a1625] shadow-lg z-10 md:hidden">
        <div class="flex justify-around items-center py-2">
            <a 
                href="{{ route('admin.dashboard') }}" 
                class="{{ request()->routeIs('admin.dashboard') ? 'text-purple-400' : 'text-gray-400' }} flex flex-col items-center px-2 py-1"
            >
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-book-open"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path></svg>
                <span class="text-xs mt-1">Dashboard</span>
            </a>
            
            <a 
                href="{{ route('admin.data-user') }}"
                class="{{ request()->routeIs('admin.data-user*') ? 'text-purple-400' : 'text-gray-400' }} flex flex-col items-center px-2 py-1"
            >
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-users"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                <span class="text-xs mt-1">Users</span>
            </a>
            
            <a 
                href="{{ route('admin.data-buku') }}" 
                class="{{ request()->routeIs('admin.data-buku*') ? 'text-purple-400' : 'text-gray-400' }} flex flex-col items-center px-2 py-1"
            >
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-book"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path></svg>
                <span class="text-xs mt-1">Books</span>
            </a>
            
            <a 
                href="{{ route('admin.data-peminjaman') }}" 
                class="{{ request()->routeIs('admin.data-peminjaman*') ? 'text-purple-400' : 'text-gray-400' }} flex flex-col items-center px-2 py-1"
            >
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-package"><line x1="16.5" y1="9.4" x2="7.5" y2="4.21"></line><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path><polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline><line x1="12" y1="22.08" x2="12" y2="12"></line></svg>
                <span class="text-xs mt-1">Borrowed</span>
            </a>
        </div>
    </div>
</div>