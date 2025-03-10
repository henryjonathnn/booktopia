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

    // Register dateRangePicker as an Alpine.js component
    Alpine.data('dateRangePicker', () => ({
        isOpen: false,
        startDate: null,
        endDate: null,
        startMonth: new Date().getMonth(),
        startYear: new Date().getFullYear(),
        endMonth: new Date().getMonth(),
        endYear: new Date().getFullYear(),
        startDays: [],
        endDays: [],
        startBlankDays: [],
        endBlankDays: [],
        formattedRange: '',
        months: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
        
        init() {
            this.calculateDays('start');
            this.calculateDays('end');
            
            // Parse existing date range from Livewire
            this.parseExistingDateRange();
        },
        
        parseExistingDateRange() {
            // Get the date range from Livewire model
            const existingRange = this.$wire.get('dateRange');
            if (!existingRange) return;
            
            const dates = existingRange.split(' to ');
            if (dates.length === 2) {
                // Parse dates - assuming format YYYY-MM-DD
                const start = this.parseDate(dates[0]);
                const end = this.parseDate(dates[1]);
                
                if (start && end) {
                    this.startDate = start;
                    this.endDate = end;
                    this.startMonth = start.getMonth();
                    this.startYear = start.getFullYear();
                    this.endMonth = end.getMonth();
                    this.endYear = end.getFullYear();
                    this.formattedRange = this.formatDate(start) + ' to ' + this.formatDate(end);
                    
                    this.calculateDays('start');
                    this.calculateDays('end');
                }
            }
        },
        
        parseDate(dateStr) {
            const parts = dateStr.split('-');
            if (parts.length === 3) {
                return new Date(parts[0], parts[1] - 1, parts[2]);
            }
            return null;
        },
        
        toggleDatepicker() {
            this.isOpen = !this.isOpen;
        },
        
        formatMonthYear(month, year) {
            return this.months[month] + ' ' + year;
        },
        
        calculateDays(type) {
            let month = type === 'start' ? this.startMonth : this.endMonth;
            let year = type === 'start' ? this.startYear : this.endYear;
            
            // Get first day of month
            const firstDay = new Date(year, month, 1).getDay();
            
            // Get blank days
            const blankDays = [];
            for (let i = 0; i < firstDay; i++) {
                blankDays.push(i);
            }
            
            // Get days in month
            const daysInMonth = new Date(year, month + 1, 0).getDate();
            
            // Get days
            const days = [];
            for (let i = 1; i <= daysInMonth; i++) {
                days.push(i);
            }
            
            if (type === 'start') {
                this.startBlankDays = blankDays;
                this.startDays = days;
            } else {
                this.endBlankDays = blankDays;
                this.endDays = days;
            }
        },
        
        prevMonth(type) {
            if (type === 'start') {
                this.startMonth--;
                if (this.startMonth < 0) {
                    this.startMonth = 11;
                    this.startYear--;
                }
                this.calculateDays('start');
            } else {
                this.endMonth--;
                if (this.endMonth < 0) {
                    this.endMonth = 11;
                    this.endYear--;
                }
                this.calculateDays('end');
            }
        },
        
        nextMonth(type) {
            if (type === 'start') {
                this.startMonth++;
                if (this.startMonth > 11) {
                    this.startMonth = 0;
                    this.startYear++;
                }
                this.calculateDays('start');
            } else {
                this.endMonth++;
                if (this.endMonth > 11) {
                    this.endMonth = 0;
                    this.endYear++;
                }
                this.calculateDays('end');
            }
        },
        
        selectDate(date, type) {
            const selectedDate = new Date(
                type === 'start' ? this.startYear : this.endYear,
                type === 'start' ? this.startMonth : this.endMonth,
                date
            );
            
            if (type === 'start') {
                this.startDate = selectedDate;
                // If end date is before start date, reset end date
                if (this.endDate && this.endDate < this.startDate) {
                    this.endDate = null;
                }
            } else {
                // Don't allow end date before start date
                if (this.startDate && selectedDate < this.startDate) {
                    return;
                }
                this.endDate = selectedDate;
            }
            
            this.updateFormattedRange();
        },
        
        isSelectedStartDate(date) {
            if (!this.startDate) return false;
            
            return date === this.startDate.getDate() && 
                this.startMonth === this.startDate.getMonth() && 
                this.startYear === this.startDate.getFullYear();
        },
        
        isSelectedEndDate(date) {
            if (!this.endDate) return false;
            
            return date === this.endDate.getDate() && 
                this.endMonth === this.endDate.getMonth() && 
                this.endYear === this.endDate.getFullYear();
        },
        
        isInRange(date) {
            if (!this.startDate || !this.endDate) return false;
            
            const currentDate = new Date(
                this.endMonth === this.startMonth ? this.startYear : this.endYear,
                this.endMonth === this.startMonth ? this.startMonth : this.endMonth,
                date
            );
            
            return currentDate >= this.startDate && currentDate <= this.endDate;
        },
        
        clearDates() {
            this.startDate = null;
            this.endDate = null;
            this.formattedRange = '';
            
            // Reset Livewire model
            this.$wire.set('dateRange', '');
        },
        
        formatDate(date) {
            if (!date) return '';
            
            const year = date.getFullYear();
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const day = String(date.getDate()).padStart(2, '0');
            
            return `${year}-${month}-${day}`;
        },
        
        updateFormattedRange() {
            if (this.startDate && this.endDate) {
                this.formattedRange = this.formatDate(this.startDate) + ' to ' + this.formatDate(this.endDate);
            } else if (this.startDate) {
                this.formattedRange = this.formatDate(this.startDate);
            } else {
                this.formattedRange = '';
            }
        },
        
        applyDateRange() {
            if (this.startDate && this.endDate) {
                // Update Livewire model
                this.$wire.set('dateRange', this.formattedRange);
                this.isOpen = false;
            } else if (this.startDate) {
                // If only start date is selected, set start and end date to the same day
                this.endDate = new Date(this.startDate);
                this.updateFormattedRange();
                
                // Update Livewire model
                this.$wire.set('dateRange', this.formattedRange);
                this.isOpen = false;
            }
        }
    }));
});

// Inisialisasi Alpine
window.Alpine = Alpine;
Alpine.start();