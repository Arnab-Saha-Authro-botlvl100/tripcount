import $ from 'jquery';
window.$ = window.jQuery = $; 

import select2 from 'select2';
select2($); // Attach to jQuery

import 'select2/dist/css/select2.css'; // Import CSS

// Import DataTables CSS
import 'datatables.net-dt/css/dataTables.dataTables.min.css';
import 'datatables.net-responsive-dt/css/responsive.dataTables.min.css';


import 'datatables.net-dt';
import 'datatables.net-responsive-dt';
// DOM ready handler
$(document).ready(function() {
    $('.select2').select2({
        width: '100%',
        placeholder: "Select an option",
        allowClear: true
    });
  
});

$(document).ready(function() {
    // Initialize DataTable only if not already initialized
    if (!$.fn.DataTable.isDataTable('#ticket_table')) {
        $('.datatable').each(function() {
            const table = $(this).DataTable({
                initComplete: function() {
                    const id = $(this.api().table().node()).attr('id');
                    if (id === 'ticket_table') {
                        const filterHtml = `
                            <div class="flex items-center space-x-4 ml-3 mx-3">
                                <select id="dateFilter" class="dt-input border-gray-300 text-sm rounded-md focus:ring-purple-500 focus:border-purple-500">
                                    <option value="">Filter By Period</option>
                                    <option value="7">Last 1 Week</option>
                                    <option value="15">Last 15 Days</option>
                                    <option value="30">Last 1 Month</option>
                                </select>
                                
                                <div class="date-wise-filter relative">
                                    <input type="text" id="dateWiseInput" 
                                           class="dt-input border-gray-300 text-sm rounded-md focus:ring-purple-500 focus:border-purple-500 w-32" 
                                           placeholder="Select date" readonly>
                                    <div id="dateWiseCalendar" class="absolute z-50 hidden bg-white p-4 shadow-lg rounded-md mt-1 border border-gray-200" 
                                    style="top: 40px;right: 0px;width: 500px;">
                                        <div class="flex justify-between items-center mb-4">
                                            <button class="prev-month px-3 py-1 rounded hover:bg-gray-100">&lt;</button>
                                            <div class="flex space-x-8">
                                                <h3 class="current-month font-medium text-center" style="width: 120px;"></h3>
                                                <h3 class="next-month font-medium text-center" style="width: 120px;"></h3>
                                            </div>
                                            <button class="next-month px-3 py-1 rounded hover:bg-gray-100">&gt;</button>
                                        </div>
                                        <div class="flex space-x-8">
                                            <div class="calendar-month grid grid-cols-7 gap-1" style="width: 210px;"></div>
                                            <div class="calendar-month grid grid-cols-7 gap-1" style="width: 210px;"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `;

                        $('.dt-layout-end').children().first().prepend(filterHtml);
                      
                        const dtInstance = this.api();
                        
                        // Initialize calendar
                        initDateWiseCalendar(dtInstance);
                        
                        // Date range filter
                        $('#dateFilter').off('change').on('change', function() {
                            const selectedValue = $(this).val();
                            const today = new Date();
                            today.setHours(0, 0, 0, 0);
                            
                            // Clear previous filters first
                            $.fn.dataTable.ext.search.pop();
                            
                            if (!selectedValue) {
                                // If no value selected, clear all filters and show full table
                                dtInstance.columns().search('').draw();
                                return;
                            }
                            
                            const startDate = new Date(today);
                            startDate.setDate(today.getDate() - parseInt(selectedValue));
                            
                            // Add date range filter
                            $.fn.dataTable.ext.search.push(function(settings, data) {
                                const invoiceDateText = data[0]; // First column contains date
                                if (!invoiceDateText) return true; // Skip if empty
                                
                                const dateParts = invoiceDateText.split('/');
                                const invoiceDate = new Date(
                                    parseInt(dateParts[2]),  // Year
                                    parseInt(dateParts[1]) - 1, // Month (0-based)
                                    parseInt(dateParts[0])    // Day
                                );
                                invoiceDate.setHours(0, 0, 0, 0);
                                
                                return invoiceDate >= startDate;
                            });
                            
                            dtInstance.draw();
                        });
                    }
                }
            });
        });
    }
   
});

let selectedDates = [];
let dateSelectionActive = false;

function initDateWiseCalendar(dtInstance) {
    const months = ['January', 'February', 'March', 'April', 'May', 'June', 
                   'July', 'August', 'September', 'October', 'November', 'December'];
    let currentDate = new Date();
    let currentMonth = currentDate.getMonth();
    let currentYear = currentDate.getFullYear();

    // Initial render
    renderCalendar(currentMonth, currentYear);

    // Toggle calendar visibility
    $('#dateWiseInput').on('click', function(e) {
        e.stopPropagation();
        $('#dateWiseCalendar').toggleClass('hidden');
        renderCalendar(currentMonth, currentYear);
    });

    // Close calendar when clicking elsewhere
    $(document).on('click', function(e) {
        if (!$(e.target).closest('#dateWiseCalendar').length && 
            !$(e.target).is('#dateWiseInput')) {
            $('#dateWiseCalendar').addClass('hidden');
        }
    });

    // Navigation between months
    $(document).on('click', '.prev-month', function(e) {
        e.stopPropagation();
        currentMonth--;
        if (currentMonth < 0) {
            currentMonth = 11;
            currentYear--;
        }
        renderCalendar(currentMonth, currentYear);
    });

    $(document).on('click', '.next-month', function(e) {
        e.stopPropagation();
        currentMonth++;
        if (currentMonth > 11) {
            currentMonth = 0;
            currentYear++;
        }
        renderCalendar(currentMonth, currentYear);
    });

    function renderCalendar(month, year) {
        const nextMonth = month === 11 ? 0 : month + 1;
        const nextYear = month === 11 ? year + 1 : year;
        
        $('.current-month').text(`${months[month]} ${year}`);
        $('.next-month').text(`${months[nextMonth]} ${nextYear}`);
        
        $('.calendar-month').empty();
        renderSingleMonth(month, year, $('.calendar-month').eq(0));
        renderSingleMonth(nextMonth, nextYear, $('.calendar-month').eq(1));
    }

    function renderSingleMonth(month, year, container) {
        const firstDay = new Date(year, month, 1).getDay();
        const daysInMonth = new Date(year, month + 1, 0).getDate();
        const daysInPrevMonth = new Date(year, month, 0).getDate();
        
        let calendarHtml = '';
        
        // Day headers
        ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa'].forEach(day => {
            calendarHtml += `<div class="text-center text-xs font-medium py-1">${day}</div>`;
        });
        
        // Previous month's days
        for (let i = firstDay - 1; i >= 0; i--) {
            calendarHtml += `<div class="text-center text-xs py-1 text-gray-400">${daysInPrevMonth - i}</div>`;
        }
        
        // Current month's days
        for (let day = 1; day <= daysInMonth; day++) {
            const dateStr = `${year}-${String(month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
            const isToday = isCurrentDate(year, month, day) ? 'bg-purple-200' : '';
            const isSelected = selectedDates.includes(dateStr) ? 'bg-purple-500 text-white' : '';
            
            calendarHtml += `
                <div class="calendar-day text-center text-xs py-1 hover:bg-purple-100 rounded cursor-pointer ${isToday} ${isSelected}" 
                     data-date="${dateStr}">
                    ${day}
                </div>`;
        }
        
        // Fill remaining grid
        let nextMonthDay = 1;
        while (firstDay + daysInMonth + nextMonthDay - 1 <= 42) {
            calendarHtml += `<div class="text-center text-xs py-1 text-gray-400">${nextMonthDay++}</div>`;
        }
        
        container.html(calendarHtml);
    }

    function isCurrentDate(year, month, day) {
        const today = new Date();
        return year === today.getFullYear() && 
               month === today.getMonth() && 
               day === today.getDate();
    }

    // Date selection handler
    $(document).off('click', '.calendar-day').on('click', '.calendar-day', function(e) {
        e.stopPropagation();
        const dateStr = $(this).data('date');
        
        if (!dateSelectionActive) {
            // First selection - clear previous and start new
            selectedDates = [dateStr];
            dateSelectionActive = true;
            $(this).addClass('bg-purple-500 text-white');
        } else {
            // Second selection - complete the range
            selectedDates.push(dateStr);
            selectedDates.sort();
            dateSelectionActive = false;
            $(this).addClass('bg-purple-500 text-white');
            
            // Apply the date range filter
            applyDateRangeFilter(dtInstance, selectedDates);
            
            // Update input field
            $('#dateWiseInput').val(
                `${formatDateForDisplay(selectedDates[0])} to ${formatDateForDisplay(selectedDates[1])}`
            );
            
            // Close calendar
            $('#dateWiseCalendar').addClass('hidden');
        }
        
        // Re-render to show selected dates
        renderCalendar(currentMonth, currentYear);
    });
}

function applyDateRangeFilter(dtInstance, dates) {
    // Clear previous filters
    dtInstance.columns().search('').draw();
    $.fn.dataTable.ext.search.pop();
    
    // Add new filter for date range
    $.fn.dataTable.ext.search.push(function(settings, data) {
        const invoiceDateText = data[0];
        const dateParts = invoiceDateText.split('/');
        const invoiceDate = new Date(
            parseInt(dateParts[2]), 
            parseInt(dateParts[1]) - 1, 
            parseInt(dateParts[0])
        );
        invoiceDate.setHours(0, 0, 0, 0);
        
        const startDate = new Date(dates[0]);
        startDate.setHours(0, 0, 0, 0);
        
        const endDate = new Date(dates[1]);
        endDate.setHours(23, 59, 59, 999);
        
        return invoiceDate >= startDate && invoiceDate <= endDate;
    });
    
    dtInstance.draw();
}

function formatDateForDisplay(dateStr) {
    const parts = dateStr.split('-');
    return `${parts[2]}/${parts[1]}/${parts[0]}`;
}

function formatDateForTable(dateStr) {
    const parts = dateStr.split('-');
    return `${parts[2]}/${parts[1]}/${parts[0]}`;
}
