import './bootstrap';
import Alpine from 'alpinejs';

// Definisikan fungsi untuk export PDF
document.addEventListener('alpine:init', () => {
    Alpine.data('exportPDF', () => ({
        data: null,
        init() {
            this.data = {
                status: '',
                dateStart: '',
                dateEnd: '',
                timestamp: '',
                peminjamans: []
            };
        },
        async generatePDF() {
            try {
                // Get data from Livewire
                this.data = await this.$wire.getExportData();
                
                // Configure PDF options
                const opt = {
                    margin: [10, 10, 10, 10],
                    filename: 'laporan_peminjaman_' + new Date().getTime() + '.pdf',
                    image: { type: 'jpeg', quality: 0.98 },
                    html2canvas: { scale: 2 },
                    jsPDF: { unit: 'mm', format: 'a4', orientation: 'landscape' }
                };

                // Generate PDF
                const element = document.getElementById('pdf-content');
                await html2pdf().set(opt).from(element).save();
                
                // Close modal
                this.$wire.closeExportModal();
            } catch (error) {
                console.error('Error generating PDF:', error);
            }
        }
    }));
});

// Inisialisasi Alpine
window.Alpine = Alpine;
Alpine.start();