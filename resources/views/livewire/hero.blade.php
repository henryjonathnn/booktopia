<section class="pt-24 px-2 mb-16 md:mb-16">
    <div class="relative rounded-3xl overflow-hidden">
        <div class="absolute top-0 right-0 w-full md:w-1/2 h-64 md:h-full">
            <img
                src={{ asset('/assets/hero8.png')}}
                alt="Books"
                class="w-full h-full object-contain object-center md:object-right"
                loading="lazy"
                decoding="async"
            />
        </div>
        <div class="relative z-20 p-4 md:p-16 max-w-2xl mt-48 md:mt-0">
            <span class="px-4 py-2 rounded-full bg-purple-500/20 text-purple-400 text-sm font-medium mb-6 inline-block">
                💡 Trending Collection
            </span>
            <div class="text-4xl md:text-6xl font-bold mb-6 leading-tight">
                Jelajahi<br />
                <span
                    x-data="{ texts: @entangle('dynamicTexts'), currentIndex: @entangle('currentTextIndex') }"
                    x-init="setInterval(() => currentIndex = (currentIndex + 1) % texts.length, 3000)"
                    x-text="texts[currentIndex]"
                    class="bg-gradient-to-r from-purple-400 to-indigo-400 bg-clip-text text-transparent"
                ></span><br />
                dimana aja!
            </div>
            <p class="text-gray-400 text-base md:text-xl mb-8 leading-relaxed">
                Akses ribuan buku-buku menarik dari penulis terkenal dan penerbit di seluruh dunia.
            </p>
            <div class="flex flex-col md:flex-row items-center space-y-4 md:space-y-0 md:space-x-6">
                <a 
                    href="#" 
                    class="w-full md:w-auto px-6 py-3 md:px-8 md:py-4 bg-gradient-to-r from-purple-600 to-indigo-600 rounded-xl font-medium hover:opacity-90 transition-all duration-300 text-center"
                >
                    Lihat Buku Trending
                </a>
                <a 
                    href="#"
                    class="w-full md:w-auto px-6 py-3 md:px-8 md:py-4 bg-purple-500/10 rounded-xl font-medium hover:bg-purple-500/20 transition-all duration-300 border border-purple-500/20 text-center"
                >
                    Lihat Semua Buku
                </a>
            </div>
            
            <div class="flex flex-row justify-center md:justify-start items-center gap-x-6 mt-12">
                @foreach([
                    ['count' => '100k+', 'label' => 'Koleksi Buku'],
                    ['count' => '50k+', 'label' => 'Penulis'],
                    ['count' => '75k+', 'label' => 'Peminjam']
                ] as $stat)
                    <div class="text-center md:text-left">
                        <p class="text-2xl md:text-4xl font-bold mb-1">{{ $stat['count'] }}</p>
                        <p class="text-gray-400 text-sm md:text-base whitespace-nowrap">{{ $stat['label'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>