// import './bootstrap';
// import Alpine from 'alpinejs'

// // Definisikan fungsi untuk sidebar
// document.addEventListener('alpine:init', () => {
//     Alpine.data('dateRangePicker', () => ({
//         isOpen: false,
//         currentView: 'start', // 'start' or 'end'
//         startDate: null,
//         endDate: null,
//         currentMonth: new Date().getMonth(),
//         currentYear: new Date().getFullYear(),
//         days: [],
//         blankDays: [],
//         formattedRange: '',
//         months: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
        
//         init() {
//             this.calculateDays();
//             this.parseExistingDateRange();
//         },
        
//         parseExistingDateRange() {
//             // Get the date range from Livewire model
//             const existingRange = this.$wire?.get('dateRange');
//             if (!existingRange) return;
            
//             const dates = existingRange.split(' to ');
//             if (dates.length === 2) {
//                 // Parse dates - assuming format YYYY-MM-DD
//                 const start = this.parseDate(dates[0]);
//                 const end = this.parseDate(dates[1]);
                
//                 if (start && end) {
//                     this.startDate = start;
//                     this.endDate = end;
//                     this.currentMonth = start.getMonth();
//                     this.currentYear = start.getFullYear();
//                     this.formattedRange = this.formatDate(start) + ' to ' + this.formatDate(end);
                    
//                     this.calculateDays();
//                 }
//             }
//         },
        
//         parseDate(dateStr) {
//             const parts = dateStr.split('-');
//             if (parts.length === 3) {
//                 return new Date(parts[0], parts[1] - 1, parts[2]);
//             }
//             return null;
//         },
        
//         toggleDatepicker() {
//             this.isOpen = !this.isOpen;
//             // Always start with start date view when opening
//             if (this.isOpen) {
//                 this.currentView = 'start';
//                 if (this.startDate) {
//                     this.currentMonth = this.startDate.getMonth();
//                     this.currentYear = this.startDate.getFullYear();
//                 } else {
//                     this.currentMonth = new Date().getMonth();
//                     this.currentYear = new Date().getFullYear();
//                 }
//                 this.calculateDays();
//             }
//         },
        
//         formatMonthYear(month, year) {
//             return this.months[month] + ' ' + year;
//         },
        
//         calculateDays() {
//             // Get first day of month
//             const firstDay = new Date(this.currentYear, this.currentMonth, 1).getDay();
            
//             // Get blank days
//             const blankDays = [];
//             for (let i = 0; i < firstDay; i++) {
//                 blankDays.push(i);
//             }
            
//             // Get days in month
//             const daysInMonth = new Date(this.currentYear, this.currentMonth + 1, 0).getDate();
            
//             // Get days
//             const days = [];
//             for (let i = 1; i <= daysInMonth; i++) {
//                 days.push(i);
//             }
            
//             this.blankDays = blankDays;
//             this.days = days;
//         },
        
//         prevMonth() {
//             this.currentMonth--;
//             if (this.currentMonth < 0) {
//                 this.currentMonth = 11;
//                 this.currentYear--;
//             }
//             this.calculateDays();
//         },
        
//         nextMonth() {
//             this.currentMonth++;
//             if (this.currentMonth > 11) {
//                 this.currentMonth = 0;
//                 this.currentYear++;
//             }
//             this.calculateDays();
//         },
        
//         selectDate(date) {
//             const selectedDate = new Date(
//                 this.currentYear,
//                 this.currentMonth,
//                 date
//             );
            
//             if (this.currentView === 'start') {
//                 this.startDate = selectedDate;
//                 // Switch to end date view after selecting start date
//                 this.currentView = 'end';
                
//                 // If end date exists and is before start date, reset it
//                 if (this.endDate && this.endDate < this.startDate) {
//                     this.endDate = null;
//                 }
                
//                 // Update the calendar to show the month after the start date
//                 // This helps users select a range in the future more easily
//                 if (!this.endDate) {
//                     this.currentMonth = this.startDate.getMonth();
//                     if (this.currentMonth === 11) {
//                         this.currentMonth = 0;
//                         this.currentYear = this.startDate.getFullYear() + 1;
//                     } else {
//                         this.currentMonth = this.startDate.getMonth() + 1;
//                         this.currentYear = this.startDate.getFullYear();
//                     }
//                     this.calculateDays();
//                 }
//             } else {
//                 // Don't allow end date before start date
//                 if (this.startDate && selectedDate < this.startDate) {
//                     return;
//                 }
//                 this.endDate = selectedDate;
//                 // After selecting end date, close the picker or allow editing start date
//                 // For now, let's keep it open so user can edit if needed
//             }
            
//             this.updateFormattedRange();
//         },
        
//         isSelectedDate(date) {
//             if (this.currentView === 'start' && this.startDate) {
//                 return date === this.startDate.getDate() && 
//                     this.currentMonth === this.startDate.getMonth() && 
//                     this.currentYear === this.startDate.getFullYear();
//             } else if (this.currentView === 'end' && this.endDate) {
//                 return date === this.endDate.getDate() && 
//                     this.currentMonth === this.endDate.getMonth() && 
//                     this.currentYear === this.endDate.getFullYear();
//             }
//             return false;
//         },
        
//         isInRange(date) {
//             if (!this.startDate || !this.endDate) return false;
            
//             const currentDate = new Date(
//                 this.currentYear,
//                 this.currentMonth,
//                 date
//             );
            
//             return currentDate >= this.startDate && currentDate <= this.endDate;
//         },
        
//         // Switch between start and end date views
//         switchToStartView() {
//             this.currentView = 'start';
//             if (this.startDate) {
//                 this.currentMonth = this.startDate.getMonth();
//                 this.currentYear = this.startDate.getFullYear();
//                 this.calculateDays();
//             }
//         },
        
//         switchToEndView() {
//             this.currentView = 'end';
//             if (this.endDate) {
//                 this.currentMonth = this.endDate.getMonth();
//                 this.currentYear = this.endDate.getFullYear();
//                 this.calculateDays();
//             } else if (this.startDate) {
//                 // If no end date selected yet, show the month after start date
//                 let month = this.startDate.getMonth();
//                 let year = this.startDate.getFullYear();
                
//                 if (month === 11) {
//                     month = 0;
//                     year += 1;
//                 } else {
//                     month += 1;
//                 }
                
//                 this.currentMonth = month;
//                 this.currentYear = year;
//                 this.calculateDays();
//             }
//         },
        
//         clearDates() {
//             this.startDate = null;
//             this.endDate = null;
//             this.formattedRange = '';
//             this.currentView = 'start';
//             this.currentMonth = new Date().getMonth();
//             this.currentYear = new Date().getFullYear();
//             this.calculateDays();
            
//             // Reset Livewire model if using Livewire
//             if (this.$wire) {
//                 this.$wire.set('dateRange', '');
//             }
//         },
        
//         formatDate(date) {
//             if (!date) return '';
            
//             const year = date.getFullYear();
//             const month = String(date.getMonth() + 1).padStart(2, '0');
//             const day = String(date.getDate()).padStart(2, '0');
            
//             return `${year}-${month}-${day}`;
//         },
        
//         updateFormattedRange() {
//             if (this.startDate && this.endDate) {
//                 this.formattedRange = this.formatDate(this.startDate) + ' to ' + this.formatDate(this.endDate);
//             } else if (this.startDate) {
//                 this.formattedRange = this.formatDate(this.startDate);
//             } else {
//                 this.formattedRange = '';
//             }
//         },
        
//         applyDateRange() {
//             if (this.startDate && this.endDate) {
//                 // Update Livewire model if using Livewire
//                 if (this.$wire) {
//                     this.$wire.set('dateRange', this.formattedRange);
//                 }
//                 this.isOpen = false;
//             } else if (this.startDate) {
//                 // If only start date is selected, set start and end date to the same day
//                 this.endDate = new Date(this.startDate);
//                 this.updateFormattedRange();
                
//                 // Update Livewire model if using Livewire
//                 if (this.$wire) {
//                     this.$wire.set('dateRange', this.formattedRange);
//                 }
//                 this.isOpen = false;
//             }
//         }
//     }));
// });

// // Inisialisasi Alpine
// window.Alpine = Alpine;
// Alpine.start();