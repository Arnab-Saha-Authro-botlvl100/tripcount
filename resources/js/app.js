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
    // Initialize both tables with date filters
    initializeTableWithDateFilters('#ticket_table');
    initializeTableWithDateFilters('#ordertable');
});

function applyDateFiltersToTable(tableId, dtInstance) {
    const container = $(dtInstance.table().container());
    const uniqueId = tableId.replace('#', '');

    const filterHtml = `
        <div class="flex items-center space-x-4 ml-3 mx-3">
            <select id="${uniqueId}_dateFilter" class="dt-input border-gray-300 text-sm rounded-md focus:ring-purple-500 focus:border-purple-500">
                <option value="">Filter By Period</option>
                <option value="7">Last 1 Week</option>
                <option value="15">Last 15 Days</option>
                <option value="30">Last 1 Month</option>
            </select>
            
            <div class="date-wise-filter relative">
                <div class="relative">
                        <input type="text" id="${uniqueId}_dateWiseInput" 
                            class="dt-input border-gray-300 text-sm rounded-md focus:ring-purple-500 focus:border-purple-500 w-32 pl-2 pr-8" 
                            placeholder="Select date" readonly>
                        <button id="${uniqueId}_resetDateFilter" 
                                class="absolute inset-y-0 right-0 flex items-center pr-2 text-gray-500 hover:text-gray-700 focus:outline-none" 
                                title="Clear date filter">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                </div>

                <div id="${uniqueId}_dateWiseCalendar" class="absolute z-50 hidden bg-white p-4 shadow-lg rounded-md mt-1 border border-gray-200" 
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

    // Make sure the target exists, or adjust the selector if using a different dom layout
    const prependTarget =  container.find('.dt-layout-end').children().first();
    if (prependTarget.length) {
        prependTarget.prepend(filterHtml);
        initDateWiseCalendar(dtInstance, uniqueId);
        setupDateRangeFilter(dtInstance, uniqueId);
    } else {
        console.warn("Could not find filter target for:", tableId);
    }
}

function initializeTableWithDateFilters(tableId) {
    let dtInstance;

    if (!$.fn.DataTable.isDataTable(tableId)) {
        $(tableId).DataTable({
            dom: '<"top"f>rt<"bottom"ip>', // Ensure your layout includes .top
            initComplete: function() {
                dtInstance = this.api();
                applyDateFiltersToTable(tableId, dtInstance);
            }
        });
    } else {
        dtInstance = $(tableId).DataTable();
        applyDateFiltersToTable(tableId, dtInstance); // Forcefully re-apply
    }
}


let calendarStates = {};

function initDateWiseCalendar(dtInstance, tablePrefix) {
    const months = ['January', 'February', 'March', 'April', 'May', 'June', 
                   'July', 'August', 'September', 'October', 'November', 'December'];
    
    // Initialize calendar state for this table
    calendarStates[tablePrefix] = {
        currentDate: new Date(),
        currentMonth: new Date().getMonth(),
        currentYear: new Date().getFullYear(),
        selectedDates: [],
        dateSelectionActive: false
    };
    
    const state = calendarStates[tablePrefix];
    const container = $(dtInstance.table().container());

    // Initial render
    renderCalendar(state.currentMonth, state.currentYear, tablePrefix);

    // Toggle calendar visibility
    container.find(`#${tablePrefix}_dateWiseInput`).on('click', function(e) {
        e.stopPropagation();
        container.find(`#${tablePrefix}_dateWiseCalendar`).toggleClass('hidden');
        renderCalendar(state.currentMonth, state.currentYear, tablePrefix);
    });

    // Close calendar when clicking elsewhere
    $(document).on('click', function(e) {
        if (!$(e.target).closest(`#${tablePrefix}_dateWiseCalendar`).length && 
            !$(e.target).is(`#${tablePrefix}_dateWiseInput`)) {
            container.find(`#${tablePrefix}_dateWiseCalendar`).addClass('hidden');
        }
    });

    // Navigation between months
    container.on('click', `.prev-month`, function(e) {
        e.stopPropagation();
        state.currentMonth--;
        if (state.currentMonth < 0) {
            state.currentMonth = 11;
            state.currentYear--;
        }
        renderCalendar(state.currentMonth, state.currentYear, tablePrefix);
    });

    // Add reset button handler
    container.find(`#${tablePrefix}_resetDateFilter`).on('click', function() {
        // Clear date-wise filter
        dtInstance.columns().search('').draw();
        $.fn.dataTable.ext.search.pop();
        
        // Clear input field
        container.find(`#${tablePrefix}_dateWiseInput`).val('');
        
        // Reset calendar state
        calendarStates[tablePrefix].selectedDates = [];
        calendarStates[tablePrefix].dateSelectionActive = false;
        
        // Re-render calendar to remove selections
        renderCalendar(calendarStates[tablePrefix].currentMonth, calendarStates[tablePrefix].currentYear, tablePrefix);
    });


    container.on('click', `.next-month`, function(e) {
        e.stopPropagation();
        state.currentMonth++;
        if (state.currentMonth > 11) {
            state.currentMonth = 0;
            state.currentYear++;
        }
        renderCalendar(state.currentMonth, state.currentYear, tablePrefix);
    });

    // Add reset button handler

    function renderCalendar(month, year, prefix) {
        const nextMonth = month === 11 ? 0 : month + 1;
        const nextYear = month === 11 ? year + 1 : year;
        const calendarContainer = $(`#${prefix}_dateWiseCalendar`);
        
        calendarContainer.find('.current-month').text(`${months[month]} ${year}`);
        calendarContainer.find('.next-month').text(`${months[nextMonth]} ${nextYear}`);
        
        calendarContainer.find('.calendar-month').empty();
        renderSingleMonth(month, year, calendarContainer.find('.calendar-month').eq(0), prefix);
        renderSingleMonth(nextMonth, nextYear, calendarContainer.find('.calendar-month').eq(1), prefix);
    }

    function renderSingleMonth(month, year, container, prefix) {
        const state = calendarStates[prefix];
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
            const isSelected = state.selectedDates.includes(dateStr) ? 'bg-purple-500 text-white' : '';
            
            calendarHtml += `
                <div class="calendar-day text-center text-xs py-1 hover:bg-purple-100 rounded cursor-pointer ${isToday} ${isSelected}" 
                     data-date="${dateStr}" data-prefix="${prefix}">
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

   // Date selection handler for all tables
    $(document).off('click', '.calendar-day').on('click', '.calendar-day', function(e) {
        e.stopPropagation();
        const dateStr = $(this).data('date');
        const prefix = $(this).data('prefix');
        const state = calendarStates[prefix];
        const dtInstance = $(`#${prefix}`).DataTable(); // Remove .api() as it's not needed
        const inputField = $(`#${prefix}_dateWiseInput`);
        
        if (!state.dateSelectionActive) {
            // First selection
            state.selectedDates = [dateStr];
            state.dateSelectionActive = true;
            $(this).addClass('bg-purple-500 text-white');
        } else {
            // Second selection
            state.selectedDates.push(dateStr);
            state.selectedDates.sort();
            state.dateSelectionActive = false;
            $(this).addClass('bg-purple-500 text-white');
            
            // Apply filter
            applyDateRangeFilter(dtInstance, state.selectedDates);
            
            // Update input field
            inputField.val(
                `${formatDateForDisplay(state.selectedDates[0])} to ${formatDateForDisplay(state.selectedDates[1])}`
            );
            
            // Close calendar
            $(`#${prefix}_dateWiseCalendar`).addClass('hidden');
        }
        
        // Re-render calendar
        renderCalendar(state.currentMonth, state.currentYear, prefix);
    });
    
}



function setupDateRangeFilter(dtInstance, tablePrefix) {
    const container = $(dtInstance.table().container());
    
    container.find(`#${tablePrefix}_dateFilter`).off('change').on('change', function() {
        const selectedValue = $(this).val();
        const today = new Date();
        today.setHours(0, 0, 0, 0);
        
        // Clear previous filters first
        $.fn.dataTable.ext.search.pop();
        
        if (!selectedValue) {
            // If no value selected, clear all filters and show full table
            dtInstance.columns().search('').draw();
            container.find(`#${tablePrefix}_dateWiseInput`).val('');
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

function applyDateRangeFilter(dtInstance, dates) {
    // Clear previous filters
    dtInstance.columns().search('').draw();
    $.fn.dataTable.ext.search.pop();
    
    // Add new filter for date range
    $.fn.dataTable.ext.search.push(function(settings, data) {
        const invoiceDateText = data[0];
        if (!invoiceDateText) return true; // Skip if empty
        
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