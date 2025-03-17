<x-app-layout>
    <div class="min-h-screen bg-[#0D0D1A] text-white bg-pattern">
        @livewire('user.layouts.sidebar')
        <main class="md:ml-20">
            @livewire('user.layouts.navbar')
            {{ $slot }}
            @livewire('user.layouts.footer')
        </main>
    </div>
    <!-- JavaScript for typing animation -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
      const typingTextElement = document.getElementById('typing-text');
      const wordsToType = [
        'Buku Trending',
        'Penulis Idolamu',
        'Koleksi Terbaru'
      ];
      
      let wordIndex = 0;
      let charIndex = 0;
      let isDeleting = false;
      let typingDelay = 150; // Delay between each character typing
      let deletingDelay = 100; // Delay between each character deletion
      let newWordDelay = 1500; // Delay before starting to type a new word
      
      function typeEffect() {
        const currentWord = wordsToType[wordIndex];
        
        if (isDeleting) {
          // Deleting characters
          typingTextElement.textContent = currentWord.substring(0, charIndex - 1);
          charIndex--;
          typingDelay = deletingDelay;
        } else {
          // Typing characters
          typingTextElement.textContent = currentWord.substring(0, charIndex + 1);
          charIndex++;
          typingDelay = 150;
        }
        
        // Handle word completion and deletion
        if (!isDeleting && charIndex === currentWord.length) {
          // Word is complete, wait before deleting
          isDeleting = true;
          typingDelay = newWordDelay;
        } else if (isDeleting && charIndex === 0) {
          // Word is fully deleted, move to next word
          isDeleting = false;
          wordIndex = (wordIndex + 1) % wordsToType.length;
          typingDelay = 500; // Pause before typing next word
        }
        
        setTimeout(typeEffect, typingDelay);
      }
      
      // Start the typing animation
      typeEffect();
    });
    </script>
    
    <!-- Additional CSS for enhanced gradient effect (add to your existing styles) -->
    <style>
    #typing-text {
      background: linear-gradient(to right, #a855f7, #6366f1);
      -webkit-background-clip: text;
      background-clip: text;
      color: transparent;
      display: inline-block;
      min-height: 1.2em; /* Ensures consistent height during typing */
      position: relative;
    }
    
    #typing-text::after {
      content: '|';
      position: absolute;
      right: -8px;
      color: #a855f7;
      animation: blink 0.7s infinite;
    }
    
    @keyframes blink {
      0%, 100% { opacity: 1; }
      50% { opacity: 0; }
    }
    </style>
</x-app-layout>
