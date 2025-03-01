import './bootstrap';
import Alpine from 'alpinejs'

// Definisikan fungsi untuk sidebar
document.addEventListener('alpine:init', () => {
    Alpine.data('sidebar', () => ({
        sidebarOpen: false,
        toggleSidebar() {
            this.sidebarOpen = !this.sidebarOpen;
        }
    }));
});

// Inisialisasi Alpine
window.Alpine = Alpine;
Alpine.start();