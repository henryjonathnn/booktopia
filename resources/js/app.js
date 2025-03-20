import './bootstrap';
import Alpine from 'alpinejs';

// Pastikan Alpine belum diinisialisasi sebelumnya
if (!window.Alpine) {
    window.Alpine = Alpine;
    
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
                    this.data = await this.$wire.getExportData();
                    const opt = {
                        filename: 'laporan_peminjaman_' + new Date().getTime() + '.pdf',
                        image: { type: 'jpeg', quality: 0.98 },
                        html2canvas: { scale: 2 },
                        jsPDF: { unit: 'mm', format: 'a4', orientation: 'landscape' }
                    };
                    const element = document.getElementById('pdf-content');
                    await html2pdf().set(opt).from(element).save();
                    this.$wire.closeExportModal();
                } catch (error) {
                    console.error('Error generating PDF:', error);
                }
            }
        }));

        // Tambahkan data global untuk modal
        Alpine.data('modal', () => ({
            show: false,
            init() {
                this.$watch('show', value => {
                    if (value) {
                        document.body.classList.add('overflow-hidden');
                    } else {
                        document.body.classList.remove('overflow-hidden');
                    }
                });
            }
        }));
    });

    Alpine.start();
}