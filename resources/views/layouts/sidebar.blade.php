@if (session('employee'))
    @php
        $employee = session('employee');
        // dd($employee['permission']);
        $permissionString = $employee['permission'];
        $permissionsArray = explode(',', $permissionString);
        $role = $employee['role'];
        // dd($role, $employee);
    @endphp
@else
    @php
        $permissionsArray = ['entry', 'edit', 'delete', 'print', 'view'];
        $role = 'admin';
    @endphp
@endif



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style>
        :root {
            --primary-color: #007AE8;
            --secondary-color: #67B7FF;
            --sidebar-width: 300px;
            --header-height: 60px;
            --sidebar-collapsed-width: 100px;
            --background-color: #F9FAFB;
            --menu-active-color: #EEF2FF;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .body {
            background: var(--background-color);
        }

        .body_div {
            display: flex;
            min-height: 100vh;
            background-color: var(--background-color);
        }

        /* Sidebar Styles */
        .sidebar {
            width: var(--sidebar-width);
            background-color: var(--background-color);
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            height: 100vh;
            position: fixed;
            overflow-y: auto;
            top: 0;
            z-index: 200;
        }

        .sidebar.collapsed {
            width: var(--sidebar-collapsed-width);
        }

        .sidebar.collapsed .menu-item-text,
        .sidebar.collapsed .menu-title,
        .sidebar.collapsed .logo {
            display: none;
        }

        .sidebar.collapsed .menu-item {
            justify-content: center;
            /* padding: 12px 0; */
        }

        /* .sidebar.collapsed .menu-item > i {
            display: none;
        } */
      
        .sidebar-header {
            padding: 10px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid #eee;
        }

        .upper-part {
            height: 60vh;
            overflow-y: auto;
        }

        .lower-part{
            /* z-index: 10; */
            background-color: #edeef2;
        }

        .logo {
            font-size: 20px;
            font-weight: bold;
            color: var(--primary-color);
        }

        .sidebar-menu {
            padding: 20px 0;
        }

        .menu-title {
            padding: 10px 20px;
            font-size: 12px;
            color: #888;
            text-transform: uppercase;
            font-weight: 600;
        }

        .menu-item {
            padding: 12px 20px;
            display: flex;
            align-items: center;
            color: #555;
            text-decoration: none;
            transition: all 0.2s;
            border-left: 3px solid transparent;
            cursor: pointer;
        }

        .menu-item:hover,
        .menu-item.active {
            background-color: #f0f4ff;
            color: var(--primary-color);
            border-left: 3px solid var(--primary-color);
        }

        .menu-item i {
            margin-right: 10px;
            font-size: 18px;
        }

        /* Improved Dropdown Styles */
        /* Dropdown Menu Styles */
        .submenu {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            background-color: rgba(240, 244, 255, 0.2);
        }

        .submenu:not(.hidden) {
            max-height: 1000px;
            /* Adjust based on content */
        }

        .sub-submenu {
            padding-left: 15px;
            background-color: rgba(240, 244, 255, 0.1);
            border-left: 2px solid rgba(74, 107, 255, 0.1);
        }

        .submenu-item,
        .sub-submenu-item {
            padding: 10px 20px;
            display: flex;
            align-items: center;
            transition: all 0.2s;
        }

        .submenu-item {
            padding-left: 50px;
        }

        .sub-submenu-item {
            padding-left: 40px;
        }

        .submenu-item:hover,
        .sub-submenu-item:hover {
            background-color: rgba(74, 107, 255, 0.05);
        }

        .dropdown-icon {
            transition: transform 0.3s ease;
            margin-left: auto;
        }

        .dropdown-toggle.active .dropdown-icon {
            transform: rotate(180deg);
        }

        /* Icon colors */
        .submenu-item i,
        .sub-submenu-item i {
            margin-right: 12px;
            width: 20px;
            text-align: center;
        }

        /* Type-Specific Icon Colors */
        .submenu-item i.fa-user-tie {
            color: #4e73df;
        }

        /* Agent - Blue */
        .submenu-item i.fa-truck {
            color: #1cc88a;
        }

        /* Supplier - Green */
        .submenu-item i.fa-exchange-alt {
            color: #36b9cc;
        }

        /* Transaction - Teal */
        .submenu-item i.fa-plane {
            color: #f6c23e;
        }

        /* Airline - Yellow */
        .submenu-item i.fa-file-invoice {
            color: #e74a3b;
        }

        /* Invoice - Red */
        .submenu-item i.fa-money-bill-transfer {
            color: #5a5c69;
        }

        /* Financial Transaction Icons */
        .submenu-item i.fa-hand-holding-usd {
            color: #2ecc71;
            /* Green for incoming money */
            font-size: 15px;
        }

        .submenu-item i.fa-money-check-alt {
            color: #e74c3c;
            /* Red for outgoing money */
            font-size: 15px;
        }

        /* Hover States */
        .submenu-item:hover i.fa-hand-holding-usd,
        .submenu-item:hover i.fa-money-check-alt {
            color: var(--primary-color);
            transform: scale(1.1);
            transition: all 0.2s ease;
        }

        /* Money transfer - Dark gray */
        .submenu-item i.fa-wallet {
            color: #858796;
        }

        /* Expenditure - Gray */
        .submenu-item i.fa-calendar-times {
            color: #dddfeb;
        }

        /* Year close - Light gray */

        .submenu-item:hover i {
            color: var(--primary-color);
        }

        /* For collapsed sidebar */
        .sidebar.collapsed .submenu {
            display: none !important;
        }

        /* Hamburger Menu Button */
        .hamburger-menu {
            display: none;

            background: linear-gradient(var(--primary-color), rgb(19, 113, 167));
            color: white;
            border: none;
            border-radius: 4px;
            padding: 8px 12px;
            font-size: 20px;
            cursor: pointer;
        }

        /* Mobile Sidebar Popup */
        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 150;
        }

        .mobile-sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: var(--sidebar-width);
            height: 100vh;
            background-color: white;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
            z-index: 200;
            transform: translateX(-100%);
            transition: transform 0.3s ease;
            overflow-y: auto;
        }

        .mobile-sidebar.open {
            transform: translateX(0);
        }

        /* Hidden class for submenus */
        .hidden {
            display: none;
        }

        .sidebar.collapsed {
            width: var(--sidebar-collapsed-width);
        }

        .main-content {
            margin-left: var(--sidebar-width);
            width: calc(100% - var(--sidebar-width));
            transition: all var(--transition-speed) ease;
            padding: 20px;
            min-height: 100vh;
        }

        .sidebar.collapsed~.main-content {
            margin-left: var(--sidebar-collapsed-width) !important;
            width: calc(100% - var(--sidebar-collapsed-width)) !important;
        }

        /* Responsive Styles */
        @media (max-width: 999px) {

            .sidebar {
                transform: translateX(-100%);
                width: var(--sidebar-width);
            }

            .sidebar.open {
                transform: translateX(0);
            }

            .sidebar.collapsed {
                width: var(--sidebar-collapsed-width);
            }

            .main-content {
                margin-left: 0 !important;
                width: 100% !important;
                padding-left: 20px;
            }

            .sidebar-overlay {
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: rgba(0, 0, 0, 0.5);
                z-index: 99;
                display: none;
            }

            .sidebar-overlay.active {
                display: block;
            }

            .hamburger-menu {
                display: block;
            }


        }

        @media (min-width: 1000px) {

            .sidebar-overlay,
            .hamburger-menu,
            .mobile-sidebar {
                display: none !important;
            }

            .sidebar {
                display: block !important;
            }
        }
    </style>

    <style>
        /* Enhanced Hover Effects */
        .menu-item:hover {
            background-color: var(--menu-active-color);
            color: var(--primary-color);
            border-left: 3px solid var(--primary-color);
            box-shadow: 0 4px 6px -1px rgba(74, 107, 255, 0.1),
                0 2px 4px -1px rgba(74, 107, 255, 0.06);
            transform: translateX(2px);
            transition: all 0.3s ease;
        }

        /* Active State Styling */
        .menu-item.active {
            background-color: var(--menu-active-color);
            color: var(--primary-color);
            border-left: 3px solid var(--primary-color);
            box-shadow: inset 4px 0 0 var(--primary-color),
                0 2px 5px rgba(74, 107, 255, 0.1);
        }

        /* Active Icon Highlight */
        .menu-item.active i {
            color: var(--primary-color);
            transform: scale(1.15);
            filter: drop-shadow(0 0 2px rgba(74, 107, 255, 0.3));
            transition: all 0.3s ease;
        }

        /* Dropdown Item Hover Effects */
        .submenu-item:hover,
        .sub-submenu-item:hover {
            background-color: rgba(74, 107, 255, 0.08);
            color: var(--primary-color);
            box-shadow: inset 4px 0 0 var(--primary-color);
            transition: all 0.2s ease;
        }

        /* Active Dropdown Items */
        .submenu-item.active,
        .sub-submenu-item.active {
            background-color: rgba(74, 107, 255, 0.1);
            color: var(--primary-color);
            box-shadow: inset 4px 0 0 var(--primary-color),
                0 1px 3px rgba(74, 107, 255, 0.1);
        }

        .submenu-item.active i,
        .sub-submenu-item.active i {
            color: var(--primary-color);
            transform: scale(1.1);
            transition: all 0.2s ease;
        }

        /* Dropdown Toggle Hover Effect */
        .dropdown-toggle:hover {
            background-color: rgba(74, 107, 255, 0.05);
            box-shadow: 0 2px 4px rgba(74, 107, 255, 0.1);
        }

        /* Active Dropdown Toggle */
        .dropdown-toggle.active {
            background-color: rgba(74, 107, 255, 0.08);
            box-shadow: inset 3px 0 0 var(--primary-color);
        }

        .dropdown-toggle.active i:not(.dropdown-icon) {
            color: var(--primary-color);
            animation: iconPulse 1.5s infinite;
        }

        @keyframes iconPulse {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.1);
            }

            100% {
                transform: scale(1);
            }
        }

        /* Mobile Menu Item Hover */
        .mobile-sidebar .menu-item:hover {
            background-color: rgba(74, 107, 255, 0.1);
            box-shadow: 0 2px 8px rgba(74, 107, 255, 0.15);
        }

        /* Enhanced Transition Effects */
        .menu-item,
        .submenu-item,
        .sub-submenu-item {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* Icon Transition */
        .menu-item i,
        .submenu-item i,
        .sub-submenu-item i {
            transition: all 0.3s ease;
        }

        /* Specific Icon Active States */
        .menu-item.active .fa-home,
        .menu-item.active .fa-cubes,
        .menu-item.active .fa-tasks,
        .menu-item.active .fa-chart-pie {
            filter: drop-shadow(0 0 4px rgba(74, 107, 255, 0.4));
        }
    </style>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>
    <!-- Hamburger Menu Button -->


    <!-- Sidebar Overlay for Mobile -->
    <div class="sidebar-overlay" id="sidebar-overlay"></div>

    <!-- Regular Sidebar (visible on desktop) -->
    <div class="body_div">
        <div class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <div class="logo">Dashboard</div>
                <button class="toggle-btn" id="toggle-btn">
                    <i class="fas fa-bars"></i>
                </button>
            </div>

            <div class="sidebar-menu">
                <!-- Dashboard -->
                <div class="menu-title">Main</div>
                <a href="{{ route('dashboard') }}"
                    class="menu-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="fas fa-home"></i>
                    <span class="menu-item-text">Dashboard</span>
                </a>
                <div class="upper-part w-full p-2 border-t border-indigo-700">
                    <!-- Master Section -->
                    <div class="menu-title">Management</div>
                    <div class="menu-item {{ request()->routeIs(['agent.view', 'supplier.view', 'transaction.view', 'airline.view', 'type.index', 'moneytransfer.view', 'expanditure.view']) ? 'active' : '' }}"
                        aria-controls="dropdown-master" data-collapse-toggle="dropdown-master">
                        <i class="fas fa-database"></i>
                        <span class="menu-item-text">Master</span>
                        <i class="fas fa-chevron-down ml-auto" sidebar-toggle-item></i>
                    </div>
                    <div id="dropdown-master"
                        class="submenu {{ request()->routeIs(['agent.view', 'supplier.view', 'transaction.view', 'airline.view', 'type.index', 'moneytransfer.view', 'expanditure.view']) ? '' : 'hidden' }}">
                        <a href="{{ route('agent.view') }}"
                            class="submenu-item {{ request()->routeIs('agent.view') ? 'active' : '' }}">
                            <i class="fas fa-user-tie"></i>
                            <span>{{ __('Agent') }}</span>
                        </a>
                        <a href="{{ route('supplier.view') }}"
                            class="submenu-item {{ request()->routeIs('supplier.view') ? 'active' : '' }}">
                            <i class="fas fa-truck"></i>
                            <span>{{ __('Supplier') }}</span>
                        </a>
                        <a href="{{ route('transaction.view') }}"
                            class="submenu-item {{ request()->routeIs('transaction.view') ? 'active' : '' }}">
                            <i class="fas fa-exchange-alt"></i>
                            <span>{{ __('Transaction') }}</span>
                        </a>
                        <a href="{{ route('airline.view') }}"
                            class="submenu-item {{ request()->routeIs('airline.view') ? 'active' : '' }}">
                            <i class="fas fa-plane"></i>
                            <span>{{ __('Add Air Lines') }}</span>
                        </a>
                        <a href="{{ route('type.index') }}"
                            class="submenu-item {{ request()->routeIs('type.index') ? 'active' : '' }}">
                            <i class="fas fa-file-invoice"></i>
                            <span>{{ __('Invoice Types') }}</span>
                        </a>
                        <a href="{{ route('moneytransfer.view') }}"
                            class="submenu-item {{ request()->routeIs('moneytransfer.view') ? 'active' : '' }}">
                            <i class="fas fa-money-bill-transfer"></i>
                            <span>{{ __('Contra') }}</span>
                        </a>
                        <a href="{{ route('expanditure.view') }}"
                            class="submenu-item {{ request()->routeIs('expanditure.view') ? 'active' : '' }}">
                            <i class="fas fa-wallet"></i>
                            <span>{{ __('Expanditure') }}</span>
                        </a>
                    </div>

                    <!-- Service Entry -->
                    <a href="{{ route('order.view') }}"
                        class="menu-item {{ request()->routeIs('order.view') ? 'active' : '' }}">
                        <i class="fas fa-tasks"></i>
                        <span class="menu-item-text">{{ __('Service Entry') }}</span>
                    </a>

                    <!-- Contract Entry -->
                    <a href="{{ route('contract') }}"
                        class="menu-item {{ request()->routeIs('contract') ? 'active' : '' }}">
                        <i class="fas fa-tasks"></i>
                        <span class="menu-item-text">{{ __('Contract Entry') }}</span>
                    </a>

                    <!-- Receive Payment -->
                    <div class="menu-item {{ request()->routeIs(['receive.index', 'payment.form']) ? 'active' : '' }}"
                        aria-controls="dropdown-RP" data-collapse-toggle="dropdown-RP">
                        <i class="fas fa-hand-holding-usd"></i>
                        <span class="menu-item-text">Receive Payment</span>
                        <i class="fas fa-chevron-down ml-auto" sidebar-toggle-item></i>
                    </div>
                    <div id="dropdown-RP"
                        class="submenu {{ request()->routeIs(['receive.index', 'payment.form']) ? '' : 'hidden' }}">
                        <a href="{{ route('receive.index') }}"
                            class="submenu-item {{ request()->routeIs('receive.index') ? 'active' : '' }}">
                            <i class="fas fa-hand-holding-usd"></i>
                            <span>{{ __('Receive') }}</span>
                        </a>
                        <a href="{{ route('payment.form') }}"
                            class="submenu-item {{ request()->routeIs('payment.form') ? 'active' : '' }}">
                            <i class="fas fa-money-check-alt"></i>
                            <span>{{ __('Payment') }}</span>
                        </a>
                    </div>

                    <!-- HR Section -->
                    @if ($role != 'employee')
                        <div class="menu-item {{ request()->routeIs(['pay_salary.view', 'stuff_details.view']) ? 'active' : '' }}"
                            aria-controls="dropdown-hr" data-collapse-toggle="dropdown-hr">
                            <i class="fas fa-users-cog"></i>
                            <span class="menu-item-text">HR</span>
                            <i class="fas fa-chevron-down ml-auto" sidebar-toggle-item></i>
                        </div>
                        <div id="dropdown-hr"
                            class="submenu {{ request()->routeIs(['pay_salary.view', 'stuff_details.view']) ? '' : 'hidden' }}">
                            <a href="{{ route('pay_salary.view') }}"
                                class="submenu-item {{ request()->routeIs('pay_salary.view') ? 'active' : '' }}">
                                <i class="fas fa-money-check-alt"></i>
                                <span>{{ __('Pay Salary') }}</span>
                            </a>
                            <a href="{{ route('stuff_details.view') }}"
                                class="submenu-item {{ request()->routeIs('stuff_details.view') ? 'active' : '' }}">
                                <i class="fas fa-id-card"></i>
                                <span>{{ __('Stuff Details') }}</span>
                            </a>
                        </div>
                    @endif

                    <!-- Ticket Invoice -->
                    <div class="menu-item {{ request()->routeIs(['ticket.view', 'refund_ticket', 'reissue.view', 'void.view', 'adm.view', 'deportee.index']) ? 'active' : '' }}"
                        aria-controls="dropdown-ticket" data-collapse-toggle="dropdown-ticket">
                        <i class="fas fa-ticket-alt"></i>
                        <span class="menu-item-text">Ticket Invoice</span>
                        <i class="fas fa-chevron-down ml-auto" sidebar-toggle-item></i>
                    </div>
                    <div id="dropdown-ticket"
                        class="submenu {{ request()->routeIs(['ticket.view', 'refund_ticket', 'reissue.view', 'void.view', 'adm.view', 'deportee.index']) ? '' : 'hidden' }}">
                        <a href="{{ route('ticket.view') }}"
                            class="submenu-item {{ request()->routeIs('ticket.view') ? 'active' : '' }}">
                            <i class="fas fa-ticket-alt text-blue-500"></i>
                            <span>{{ __('Tickets Invoicing') }}</span>
                        </a>
                        <a href="{{ route('refund_ticket') }}"
                            class="submenu-item {{ request()->routeIs('refund_ticket') ? 'active' : '' }}">
                            <i class="fas fa-undo-alt text-orange-500"></i>
                            <span>{{ __('Refund') }}</span>
                        </a>
                        <a href="{{ route('reissue.view') }}"
                            class="submenu-item {{ request()->routeIs('reissue.view') ? 'active' : '' }}">
                            <i class="fas fa-redo-alt text-purple-500"></i>
                            <span>{{ __('Reissue') }}</span>
                        </a>
                        <a href="{{ route('void.view') }}"
                            class="submenu-item {{ request()->routeIs('void.view') ? 'active' : '' }}">
                            <i class="fas fa-ban text-red-500"></i>
                            <span>{{ __('Void') }}</span>
                        </a>
                        <a href="{{ route('adm.view') }}"
                            class="submenu-item {{ request()->routeIs('adm.view') ? 'active' : '' }}">
                            <i class="fas fa-exclamation-circle text-yellow-500"></i>
                            <span>{{ __('ADM') }}</span>
                        </a>
                        <a href="{{ route('deportee.index') }}"
                            class="submenu-item {{ request()->routeIs('deportee.index') ? 'active' : '' }}">
                            <i class="fas fa-user-slash text-gray-500"></i>
                            <span>{{ __('Deportee') }}</span>
                        </a>
                    </div>

                    <!-- Reports Section -->
                    <div class="menu-item {{ request()->routeIs([
                        'sales_ticket',
                        'sales_visa',
                        'sales_exicutive_stuff',
                        'sales_analysis',
                        'flight_ticket',
                        'refund_ticket_report.view',
                        'void_ticket',
                        'reissue_ticket',
                        'segment.view',
                        'ait_report_index',
                        'payment_report_index',
                        'receive_report_index',
                        'sector_city.view',
                        'general_ledger',
                        'trialbalance.view',
                        'profit_loss.view',
                        'income_statement.index',
                        'profitreport.view',
                        'expenditure.report',
                        'cash_book.view',
                        'bank_book.view',
                        'dailystate.view',
                    ])
                        ? 'active'
                        : '' }}"
                        aria-controls="dropdown-reports" data-collapse-toggle="dropdown-reports">
                        <i class="fas fa-chart-pie"></i>
                        <span class="menu-item-text">Reports</span>
                        <i class="fas fa-chevron-down ml-auto dropdown-icon"></i>
                    </div>
                    <div id="dropdown-reports"
                        class="submenu {{ request()->routeIs([
                            'sales_ticket',
                            'sales_visa',
                            'sales_exicutive_stuff',
                            'sales_analysis',
                            'flight_ticket',
                            'refund_ticket_report.view',
                            'void_ticket',
                            'reissue_ticket',
                            'segment.view',
                            'ait_report_index',
                            'payment_report_index',
                            'receive_report_index',
                            'sector_city.view',
                            'general_ledger',
                            'trialbalance.view',
                            'profit_loss.view',
                            'income_statement.index',
                            'profitreport.view',
                            'expenditure.report',
                            'cash_book.view',
                            'bank_book.view',
                            'dailystate.view',
                        ])
                            ? ''
                            : 'hidden' }}">
                        <!-- Sales Reports -->
                        <div class="submenu-item dropdown-toggle {{ request()->routeIs([
                            'sales_ticket',
                            'sales_visa',
                            'sales_exicutive_stuff',
                            'sales_analysis',
                            'flight_ticket',
                            'refund_ticket_report.view',
                            'void_ticket',
                            'reissue_ticket',
                            'segment.view',
                            'ait_report_index',
                            'payment_report_index',
                            'receive_report_index',
                            'sector_city.view',
                        ])
                            ? 'active'
                            : '' }}"
                            aria-controls="dropdown-sales-reports" data-collapse-toggle="dropdown-sales-reports">
                            <i class="fas fa-cash-register"></i>
                            <span>Sales Reports</span>
                            <i class="fas fa-chevron-down ml-auto"></i>
                        </div>
                        <div id="dropdown-sales-reports"
                            class="sub-submenu {{ request()->routeIs([
                                'sales_ticket',
                                'sales_visa',
                                'sales_exicutive_stuff',
                                'sales_analysis',
                                'flight_ticket',
                                'refund_ticket_report.view',
                                'void_ticket',
                                'reissue_ticket',
                                'segment.view',
                                'ait_report_index',
                                'payment_report_index',
                                'receive_report_index',
                                'sector_city.view',
                            ])
                                ? ''
                                : 'hidden' }}">
                            @if (!session('employee'))
                                <a href="{{ route('sales_ticket') }}"
                                    class="sub-submenu-item {{ request()->routeIs('sales_ticket') ? 'active' : '' }}">
                                    <i class="fas fa-ticket-alt text-blue-500"></i>
                                    <span>{{ __('Sales Report (Ticket)') }}</span>
                                </a>
                                <a href="{{ route('sales_visa') }}"
                                    class="sub-submenu-item {{ request()->routeIs('sales_visa') ? 'active' : '' }}">
                                    <i class="fas fa-passport text-green-500"></i>
                                    <span>{{ __('Sales Report (Visa)') }}</span>
                                </a>
                                <a href="{{ route('sales_exicutive_stuff') }}"
                                    class="sub-submenu-item {{ request()->routeIs('sales_exicutive_stuff') ? 'active' : '' }}">
                                    <i class="fas fa-user-tie text-purple-500"></i>
                                    <span>{{ __('Staff Sales Report') }}</span>
                                </a>
                                <a href="{{ route('sales_analysis') }}"
                                    class="sub-submenu-item {{ request()->routeIs('sales_analysis') ? 'active' : '' }}">
                                    <i class="fas fa-chart-line text-teal-500"></i>
                                    <span>{{ __('Sales Analysis') }}</span>
                                </a>
                                <a href="{{ route('flight_ticket') }}"
                                    class="sub-submenu-item {{ request()->routeIs('flight_ticket') ? 'active' : '' }}">
                                    <i class="fas fa-plane-departure text-indigo-500"></i>
                                    <span>{{ __('Flight Report') }}</span>
                                </a>
                            @endif
                            <a href="{{ route('refund_ticket_report.view') }}"
                                class="sub-submenu-item {{ request()->routeIs('refund_ticket_report.view') ? 'active' : '' }}">
                                <i class="fas fa-undo-alt text-orange-500"></i>
                                <span>{{ __('Refund Report') }}</span>
                            </a>
                            <a href="{{ route('void_ticket') }}"
                                class="sub-submenu-item {{ request()->routeIs('void_ticket') ? 'active' : '' }}">
                                <i class="fas fa-ban text-red-500"></i>
                                <span>{{ __('Void Report') }}</span>
                            </a>
                            <a href="{{ route('reissue_ticket') }}"
                                class="sub-submenu-item {{ request()->routeIs('reissue_ticket') ? 'active' : '' }}">
                                <i class="fas fa-redo-alt text-purple-500"></i>
                                <span>{{ __('Reissue Report') }}</span>
                            </a>
                            <a href="{{ route('segment.view') }}"
                                class="sub-submenu-item {{ request()->routeIs('segment.view') ? 'active' : '' }}">
                                <i class="fas fa-route text-blue-400"></i>
                                <span>{{ __('Segment Report') }}</span>
                            </a>
                            <a href="{{ route('ait_report_index') }}"
                                class="sub-submenu-item {{ request()->routeIs('ait_report_index') ? 'active' : '' }}">
                                <i class="fas fa-file-invoice-dollar text-yellow-500"></i>
                                <span>{{ __('AIT Report') }}</span>
                            </a>
                            <a href="{{ route('payment_report_index') }}"
                                class="sub-submenu-item {{ request()->routeIs('payment_report_index') ? 'active' : '' }}">
                                <i class="fas fa-credit-card text-green-600"></i>
                                <span>{{ __('Payment Report') }}</span>
                            </a>
                            <a href="{{ route('receive_report_index') }}"
                                class="sub-submenu-item {{ request()->routeIs('receive_report_index') ? 'active' : '' }}">
                                <i class="fas fa-hand-holding-usd text-green-500"></i>
                                <span>{{ __('Receive Report') }}</span>
                            </a>
                            <a href="{{ route('sector_city.view') }}"
                                class="sub-submenu-item {{ request()->routeIs('sector_city.view') ? 'active' : '' }}">
                                <i class="fas fa-map-marked-alt text-teal-500"></i>
                                <span>{{ __('City/Sector Report') }}</span>
                            </a>
                        </div>

                        <!-- Financial Reports -->
                        <div class="submenu-item dropdown-toggle {{ request()->routeIs([
                            'general_ledger',
                            'trialbalance.view',
                            'profit_loss.view',
                            'income_statement.index',
                            'profitreport.view',
                            'expenditure.report',
                            'cash_book.view',
                            'bank_book.view',
                            'dailystate.view',
                        ])
                            ? 'active'
                            : '' }}"
                            aria-controls="dropdown-financial-reports"
                            data-collapse-toggle="dropdown-financial-reports">
                            <i class="fas fa-file-invoice-dollar"></i>
                            <span>Financial Reports</span>
                            <i class="fas fa-chevron-down ml-auto"></i>
                        </div>
                        <div id="dropdown-financial-reports"
                            class="sub-submenu {{ request()->routeIs([
                                'general_ledger',
                                'trialbalance.view',
                                'profit_loss.view',
                                'income_statement.index',
                                'profitreport.view',
                                'expenditure.report',
                                'cash_book.view',
                                'bank_book.view',
                                'dailystate.view',
                            ])
                                ? ''
                                : 'hidden' }}">
                            <a href="{{ route('general_ledger') }}"
                                class="sub-submenu-item {{ request()->routeIs('general_ledger') ? 'active' : '' }}">
                                <i class="fas fa-book text-blue-500"></i>
                                <span>{{ __('General Ledger') }}</span>
                            </a>
                            @if (!session('employee'))
                                <a href="{{ route('trialbalance.view') }}"
                                    class="sub-submenu-item {{ request()->routeIs('trialbalance.view') ? 'active' : '' }}">
                                    <i class="fas fa-balance-scale-right text-purple-500"></i>
                                    <span>{{ __('Trial Balance') }}</span>
                                </a>
                                <a href="{{ route('profit_loss.view') }}"
                                    class="sub-submenu-item {{ request()->routeIs('profit_loss.view') ? 'active' : '' }}">
                                    <i class="fas fa-chart-pie text-teal-500"></i>
                                    <span>{{ __('Profit/Loss') }}</span>
                                </a>
                                <a href="{{ route('income_statement.index') }}"
                                    class="sub-submenu-item {{ request()->routeIs('income_statement.index') ? 'active' : '' }}">
                                    <i class="fas fa-money-bill-wave text-green-500"></i>
                                    <span>{{ __('Income Statement') }}</span>
                                </a>
                                <a href="{{ route('profitreport.view') }}"
                                    class="sub-submenu-item {{ request()->routeIs('profitreport.view') ? 'active' : '' }}">
                                    <i class="fas fa-coins text-yellow-500"></i>
                                    <span>{{ __('Profit Report') }}</span>
                                </a>
                                <a href="{{ route('expenditure.report') }}"
                                    class="sub-submenu-item {{ request()->routeIs('expenditure.report') ? 'active' : '' }}">
                                    <i class="fas fa-receipt text-red-500"></i>
                                    <span>{{ __('Expenditure Report') }}</span>
                                </a>
                            @endif
                            <a href="{{ route('cash_book.view') }}"
                                class="sub-submenu-item {{ request()->routeIs('cash_book.view') ? 'active' : '' }}">
                                <i class="fas fa-money-bill-alt text-green-600"></i>
                                <span>{{ __('Cash Book') }}</span>
                            </a>
                            <a href="{{ route('bank_book.view') }}"
                                class="sub-submenu-item {{ request()->routeIs('bank_book.view') ? 'active' : '' }}">
                                <i class="fas fa-university text-blue-600"></i>
                                <span>{{ __('Bank Book') }}</span>
                            </a>
                            <a href="{{ route('dailystate.view') }}"
                                class="sub-submenu-item {{ request()->routeIs('dailystate.view') ? 'active' : '' }}">
                                <i class="fas fa-calendar-day text-indigo-500"></i>
                                <span>{{ __('Daily Statement') }}</span>
                            </a>
                        </div>
                    </div>

                    <!-- Due Reminder -->
                    <a href="{{ route('due_reminder') }}"
                        class="menu-item {{ request()->routeIs('due_reminder') ? 'active' : '' }}">
                        <i class="fas fa-bell"></i>
                        <span class="menu-item-text">{{ __('Due Reminder') }}</span>
                    </a>


                </div>

                <div class="lower-part absolute bottom-0 w-full p-2 border-t border-indigo-700">
                    <!-- Settings -->
                    @if ($role != 'employee')
                        <div class="menu-title">Settings</div>
                        <div class="menu-item {{ request()->routeIs(['profile.edit', 'changePass']) ? 'active' : '' }}"
                            aria-controls="dropdown-settings" data-collapse-toggle="dropdown-settings">
                            <i class="fas fa-cog"></i>
                            <span class="menu-item-text">Settings</span>
                            <i class="fas fa-chevron-down ml-auto" sidebar-toggle-item></i>
                        </div>
                        <div id="dropdown-settings"
                            class="submenu {{ request()->routeIs(['profile.edit', 'changePass']) ? '' : 'hidden' }}">
                            <a href="{{ route('profile.edit') }}"
                                class="submenu-item {{ request()->routeIs('profile.edit') ? 'active' : '' }}">
                                <i class="fas fa-building"></i>
                                <span>{{ __('Company Profile') }}</span>
                            </a>
                            <a href="{{ route('changePass') }}"
                                class="submenu-item {{ request()->routeIs('changePass') ? 'active' : '' }}">
                                <i class="fas fa-key"></i>
                                <span>{{ __('Change Password') }}</span>
                            </a>
                        </div>
                    @endif

                    <!-- Support -->
                    <div class="menu-item" aria-controls="dropdown-support" data-collapse-toggle="dropdown-support">
                        <i class="fas fa-headset"></i>
                        <span class="menu-item-text">Support</span>
                        <i class="fas fa-chevron-down ml-auto" sidebar-toggle-item></i>
                    </div>
                    <div id="dropdown-support" class="hidden submenu">
                        <a href="#" class="submenu-item">
                            <i class="fas fa-graduation-cap"></i>
                            <span>{{ __('Training') }}</span>
                        </a>
                        <a href="#" class="submenu-item">
                            <i class="fas fa-phone"></i>
                            <span>{{ __('Support') }}</span>
                        </a>
                        <a href="https://anydesk.com/en" class="submenu-item" target="_blank">
                            <i class="fas fa-download"></i>
                            <span>{{ __('Download Anydesk') }}</span>
                        </a>
                    </div>

                    <!-- Logout -->
                    <a href="{{ route('logout') }}" class="menu-item"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="fas fa-sign-out-alt"></i>
                        <span class="menu-item-text">{{ __('Logout') }}</span>
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                        @csrf
                    </form>
                </div>

            </div>
        </div>
    </div>

    <!-- Mobile Sidebar (hidden by default, shown on mobile) -->
    <div class="mobile-sidebar" id="mobile-sidebar">
        <div class="sidebar-header">
            <div class="logo">Dashboard</div>
            <button class="toggle-btn" id="mobile-close-btn">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <div class="sidebar-menu">
            <!-- Dashboard -->
            <div class="menu-title">Main</div>
            <a href="{{ route('dashboard') }}"
                class="menu-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="fas fa-home"></i>
                <span class="menu-item-text">Dashboard</span>
            </a>

            <div class="upper-part w-full p-2 border-t border-indigo-700">
                <!-- Master Section -->
                <div class="menu-title">Management</div>
                <div class="menu-item {{ request()->routeIs(['agent.view', 'supplier.view', 'transaction.view', 'airline.view', 'type.index', 'moneytransfer.view', 'expanditure.view']) ? 'active' : '' }}"
                    aria-controls="dropdown-master" data-collapse-toggle="dropdown-master">
                    <i class="fas fa-database"></i>
                    <span class="menu-item-text">Master</span>
                    <i class="fas fa-chevron-down ml-auto" sidebar-toggle-item></i>
                </div>
                <div id="dropdown-master"
                    class="submenu {{ request()->routeIs(['agent.view', 'supplier.view', 'transaction.view', 'airline.view', 'type.index', 'moneytransfer.view', 'expanditure.view']) ? '' : 'hidden' }}">
                    <a href="{{ route('agent.view') }}"
                        class="submenu-item {{ request()->routeIs('agent.view') ? 'active' : '' }}">
                        <i class="fas fa-user-tie"></i>
                        <span>{{ __('Agent') }}</span>
                    </a>
                    <a href="{{ route('supplier.view') }}"
                        class="submenu-item {{ request()->routeIs('supplier.view') ? 'active' : '' }}">
                        <i class="fas fa-truck"></i>
                        <span>{{ __('Supplier') }}</span>
                    </a>
                    <a href="{{ route('transaction.view') }}"
                        class="submenu-item {{ request()->routeIs('transaction.view') ? 'active' : '' }}">
                        <i class="fas fa-exchange-alt"></i>
                        <span>{{ __('Transaction') }}</span>
                    </a>
                    <a href="{{ route('airline.view') }}"
                        class="submenu-item {{ request()->routeIs('airline.view') ? 'active' : '' }}">
                        <i class="fas fa-plane"></i>
                        <span>{{ __('Add Air Lines') }}</span>
                    </a>
                    <a href="{{ route('type.index') }}"
                        class="submenu-item {{ request()->routeIs('type.index') ? 'active' : '' }}">
                        <i class="fas fa-file-invoice"></i>
                        <span>{{ __('Invoice Types') }}</span>
                    </a>
                    <a href="{{ route('moneytransfer.view') }}"
                        class="submenu-item {{ request()->routeIs('moneytransfer.view') ? 'active' : '' }}">
                        <i class="fas fa-money-bill-transfer"></i>
                        <span>{{ __('Contra') }}</span>
                    </a>
                    <a href="{{ route('expanditure.view') }}"
                        class="submenu-item {{ request()->routeIs('expanditure.view') ? 'active' : '' }}">
                        <i class="fas fa-wallet"></i>
                        <span>{{ __('Expanditure') }}</span>
                    </a>
                </div>

                <!-- Service Entry -->
                <a href="{{ route('order.view') }}"
                    class="menu-item {{ request()->routeIs('order.view') ? 'active' : '' }}">
                    <i class="fas fa-tasks"></i>
                    <span class="menu-item-text">{{ __('Service Entry') }}</span>
                </a>

                <!-- Receive Payment -->
                <div class="menu-item {{ request()->routeIs(['receive.index', 'payment.form']) ? 'active' : '' }}"
                    aria-controls="dropdown-RP" data-collapse-toggle="dropdown-RP">
                    <i class="fas fa-hand-holding-usd"></i>
                    <span class="menu-item-text">Receive Payment</span>
                    <i class="fas fa-chevron-down ml-auto" sidebar-toggle-item></i>
                </div>
                <div id="dropdown-RP"
                    class="submenu {{ request()->routeIs(['receive.index', 'payment.form']) ? '' : 'hidden' }}">
                    <a href="{{ route('receive.index') }}"
                        class="submenu-item {{ request()->routeIs('receive.index') ? 'active' : '' }}">
                        <i class="fas fa-hand-holding-usd"></i>
                        <span>{{ __('Receive') }}</span>
                    </a>
                    <a href="{{ route('payment.form') }}"
                        class="submenu-item {{ request()->routeIs('payment.form') ? 'active' : '' }}">
                        <i class="fas fa-money-check-alt"></i>
                        <span>{{ __('Payment') }}</span>
                    </a>
                </div>

                <!-- HR Section -->
                @if ($role != 'employee')
                    <div class="menu-item {{ request()->routeIs(['pay_salary.view', 'stuff_details.view']) ? 'active' : '' }}"
                        aria-controls="dropdown-hr" data-collapse-toggle="dropdown-hr">
                        <i class="fas fa-users-cog"></i>
                        <span class="menu-item-text">HR</span>
                        <i class="fas fa-chevron-down ml-auto" sidebar-toggle-item></i>
                    </div>
                    <div id="dropdown-hr"
                        class="submenu {{ request()->routeIs(['pay_salary.view', 'stuff_details.view']) ? '' : 'hidden' }}">
                        <a href="{{ route('pay_salary.view') }}"
                            class="submenu-item {{ request()->routeIs('pay_salary.view') ? 'active' : '' }}">
                            <i class="fas fa-money-check-alt"></i>
                            <span>{{ __('Pay Salary') }}</span>
                        </a>
                        <a href="{{ route('stuff_details.view') }}"
                            class="submenu-item {{ request()->routeIs('stuff_details.view') ? 'active' : '' }}">
                            <i class="fas fa-id-card"></i>
                            <span>{{ __('Stuff Details') }}</span>
                        </a>
                    </div>
                @endif

                <!-- Ticket Invoice -->
                <div class="menu-item {{ request()->routeIs(['ticket.view', 'refund_ticket', 'reissue.view', 'void.view', 'adm.view', 'deportee.index']) ? 'active' : '' }}"
                    aria-controls="dropdown-ticket" data-collapse-toggle="dropdown-ticket">
                    <i class="fas fa-ticket-alt"></i>
                    <span class="menu-item-text">Ticket Invoice</span>
                    <i class="fas fa-chevron-down ml-auto" sidebar-toggle-item></i>
                </div>
                <div id="dropdown-ticket"
                    class="submenu {{ request()->routeIs(['ticket.view', 'refund_ticket', 'reissue.view', 'void.view', 'adm.view', 'deportee.index']) ? '' : 'hidden' }}">
                    <a href="{{ route('ticket.view') }}"
                        class="submenu-item {{ request()->routeIs('ticket.view') ? 'active' : '' }}">
                        <i class="fas fa-ticket-alt text-blue-500"></i>
                        <span>{{ __('Tickets Invoicing') }}</span>
                    </a>
                    <a href="{{ route('refund_ticket') }}"
                        class="submenu-item {{ request()->routeIs('refund_ticket') ? 'active' : '' }}">
                        <i class="fas fa-undo-alt text-orange-500"></i>
                        <span>{{ __('Refund') }}</span>
                    </a>
                    <a href="{{ route('reissue.view') }}"
                        class="submenu-item {{ request()->routeIs('reissue.view') ? 'active' : '' }}">
                        <i class="fas fa-redo-alt text-purple-500"></i>
                        <span>{{ __('Reissue') }}</span>
                    </a>
                    <a href="{{ route('void.view') }}"
                        class="submenu-item {{ request()->routeIs('void.view') ? 'active' : '' }}">
                        <i class="fas fa-ban text-red-500"></i>
                        <span>{{ __('Void') }}</span>
                    </a>
                    <a href="{{ route('adm.view') }}"
                        class="submenu-item {{ request()->routeIs('adm.view') ? 'active' : '' }}">
                        <i class="fas fa-exclamation-circle text-yellow-500"></i>
                        <span>{{ __('ADM') }}</span>
                    </a>
                    <a href="{{ route('deportee.index') }}"
                        class="submenu-item {{ request()->routeIs('deportee.index') ? 'active' : '' }}">
                        <i class="fas fa-user-slash text-gray-500"></i>
                        <span>{{ __('Deportee') }}</span>
                    </a>
                </div>

                <!-- Reports Section -->
                <div class="menu-item {{ request()->routeIs([
                    'sales_ticket',
                    'sales_visa',
                    'sales_exicutive_stuff',
                    'sales_analysis',
                    'flight_ticket',
                    'refund_ticket_report.view',
                    'void_ticket',
                    'reissue_ticket',
                    'segment.view',
                    'ait_report_index',
                    'payment_report_index',
                    'receive_report_index',
                    'sector_city.view',
                    'general_ledger',
                    'trialbalance.view',
                    'profit_loss.view',
                    'income_statement.index',
                    'profitreport.view',
                    'expenditure.report',
                    'cash_book.view',
                    'bank_book.view',
                    'dailystate.view',
                ])
                    ? 'active'
                    : '' }}"
                    aria-controls="dropdown-reports" data-collapse-toggle="dropdown-reports">
                    <i class="fas fa-chart-pie"></i>
                    <span class="menu-item-text">Reports</span>
                    <i class="fas fa-chevron-down ml-auto dropdown-icon"></i>
                </div>
                <div id="dropdown-reports"
                    class="submenu {{ request()->routeIs([
                        'sales_ticket',
                        'sales_visa',
                        'sales_exicutive_stuff',
                        'sales_analysis',
                        'flight_ticket',
                        'refund_ticket_report.view',
                        'void_ticket',
                        'reissue_ticket',
                        'segment.view',
                        'ait_report_index',
                        'payment_report_index',
                        'receive_report_index',
                        'sector_city.view',
                        'general_ledger',
                        'trialbalance.view',
                        'profit_loss.view',
                        'income_statement.index',
                        'profitreport.view',
                        'expenditure.report',
                        'cash_book.view',
                        'bank_book.view',
                        'dailystate.view',
                    ])
                        ? ''
                        : 'hidden' }}">
                    <!-- Sales Reports -->
                    <div class="submenu-item dropdown-toggle {{ request()->routeIs([
                        'sales_ticket',
                        'sales_visa',
                        'sales_exicutive_stuff',
                        'sales_analysis',
                        'flight_ticket',
                        'refund_ticket_report.view',
                        'void_ticket',
                        'reissue_ticket',
                        'segment.view',
                        'ait_report_index',
                        'payment_report_index',
                        'receive_report_index',
                        'sector_city.view',
                    ])
                        ? 'active'
                        : '' }}"
                        aria-controls="dropdown-sales-reports" data-collapse-toggle="dropdown-sales-reports">
                        <i class="fas fa-cash-register"></i>
                        <span>Sales Reports</span>
                        <i class="fas fa-chevron-down ml-auto"></i>
                    </div>
                    <div id="dropdown-sales-reports"
                        class="sub-submenu {{ request()->routeIs([
                            'sales_ticket',
                            'sales_visa',
                            'sales_exicutive_stuff',
                            'sales_analysis',
                            'flight_ticket',
                            'refund_ticket_report.view',
                            'void_ticket',
                            'reissue_ticket',
                            'segment.view',
                            'ait_report_index',
                            'payment_report_index',
                            'receive_report_index',
                            'sector_city.view',
                        ])
                            ? ''
                            : 'hidden' }}">
                        @if (!session('employee'))
                            <a href="{{ route('sales_ticket') }}"
                                class="sub-submenu-item {{ request()->routeIs('sales_ticket') ? 'active' : '' }}">
                                <i class="fas fa-ticket-alt text-blue-500"></i>
                                <span>{{ __('Sales Report (Ticket)') }}</span>
                            </a>
                            <a href="{{ route('sales_visa') }}"
                                class="sub-submenu-item {{ request()->routeIs('sales_visa') ? 'active' : '' }}">
                                <i class="fas fa-passport text-green-500"></i>
                                <span>{{ __('Sales Report (Visa)') }}</span>
                            </a>
                            <a href="{{ route('sales_exicutive_stuff') }}"
                                class="sub-submenu-item {{ request()->routeIs('sales_exicutive_stuff') ? 'active' : '' }}">
                                <i class="fas fa-user-tie text-purple-500"></i>
                                <span>{{ __('Staff Sales Report') }}</span>
                            </a>
                            <a href="{{ route('sales_analysis') }}"
                                class="sub-submenu-item {{ request()->routeIs('sales_analysis') ? 'active' : '' }}">
                                <i class="fas fa-chart-line text-teal-500"></i>
                                <span>{{ __('Sales Analysis') }}</span>
                            </a>
                            <a href="{{ route('flight_ticket') }}"
                                class="sub-submenu-item {{ request()->routeIs('flight_ticket') ? 'active' : '' }}">
                                <i class="fas fa-plane-departure text-indigo-500"></i>
                                <span>{{ __('Flight Report') }}</span>
                            </a>
                        @endif
                        <a href="{{ route('refund_ticket_report.view') }}"
                            class="sub-submenu-item {{ request()->routeIs('refund_ticket_report.view') ? 'active' : '' }}">
                            <i class="fas fa-undo-alt text-orange-500"></i>
                            <span>{{ __('Refund Report') }}</span>
                        </a>
                        <a href="{{ route('void_ticket') }}"
                            class="sub-submenu-item {{ request()->routeIs('void_ticket') ? 'active' : '' }}">
                            <i class="fas fa-ban text-red-500"></i>
                            <span>{{ __('Void Report') }}</span>
                        </a>
                        <a href="{{ route('reissue_ticket') }}"
                            class="sub-submenu-item {{ request()->routeIs('reissue_ticket') ? 'active' : '' }}">
                            <i class="fas fa-redo-alt text-purple-500"></i>
                            <span>{{ __('Reissue Report') }}</span>
                        </a>
                        <a href="{{ route('segment.view') }}"
                            class="sub-submenu-item {{ request()->routeIs('segment.view') ? 'active' : '' }}">
                            <i class="fas fa-route text-blue-400"></i>
                            <span>{{ __('Segment Report') }}</span>
                        </a>
                        <a href="{{ route('ait_report_index') }}"
                            class="sub-submenu-item {{ request()->routeIs('ait_report_index') ? 'active' : '' }}">
                            <i class="fas fa-file-invoice-dollar text-yellow-500"></i>
                            <span>{{ __('AIT Report') }}</span>
                        </a>
                        <a href="{{ route('payment_report_index') }}"
                            class="sub-submenu-item {{ request()->routeIs('payment_report_index') ? 'active' : '' }}">
                            <i class="fas fa-credit-card text-green-600"></i>
                            <span>{{ __('Payment Report') }}</span>
                        </a>
                        <a href="{{ route('receive_report_index') }}"
                            class="sub-submenu-item {{ request()->routeIs('receive_report_index') ? 'active' : '' }}">
                            <i class="fas fa-hand-holding-usd text-green-500"></i>
                            <span>{{ __('Receive Report') }}</span>
                        </a>
                        <a href="{{ route('sector_city.view') }}"
                            class="sub-submenu-item {{ request()->routeIs('sector_city.view') ? 'active' : '' }}">
                            <i class="fas fa-map-marked-alt text-teal-500"></i>
                            <span>{{ __('City/Sector Report') }}</span>
                        </a>
                    </div>

                    <!-- Financial Reports -->
                    <div class="submenu-item dropdown-toggle {{ request()->routeIs([
                        'general_ledger',
                        'trialbalance.view',
                        'profit_loss.view',
                        'income_statement.index',
                        'profitreport.view',
                        'expenditure.report',
                        'cash_book.view',
                        'bank_book.view',
                        'dailystate.view',
                    ])
                        ? 'active'
                        : '' }}"
                        aria-controls="dropdown-financial-reports" data-collapse-toggle="dropdown-financial-reports">
                        <i class="fas fa-file-invoice-dollar"></i>
                        <span>Financial Reports</span>
                        <i class="fas fa-chevron-down ml-auto"></i>
                    </div>
                    <div id="dropdown-financial-reports"
                        class="sub-submenu {{ request()->routeIs([
                            'general_ledger',
                            'trialbalance.view',
                            'profit_loss.view',
                            'income_statement.index',
                            'profitreport.view',
                            'expenditure.report',
                            'cash_book.view',
                            'bank_book.view',
                            'dailystate.view',
                        ])
                            ? ''
                            : 'hidden' }}">
                        <a href="{{ route('general_ledger') }}"
                            class="sub-submenu-item {{ request()->routeIs('general_ledger') ? 'active' : '' }}">
                            <i class="fas fa-book text-blue-500"></i>
                            <span>{{ __('General Ledger') }}</span>
                        </a>
                        @if (!session('employee'))
                            <a href="{{ route('trialbalance.view') }}"
                                class="sub-submenu-item {{ request()->routeIs('trialbalance.view') ? 'active' : '' }}">
                                <i class="fas fa-balance-scale-right text-purple-500"></i>
                                <span>{{ __('Trial Balance') }}</span>
                            </a>
                            <a href="{{ route('profit_loss.view') }}"
                                class="sub-submenu-item {{ request()->routeIs('profit_loss.view') ? 'active' : '' }}">
                                <i class="fas fa-chart-pie text-teal-500"></i>
                                <span>{{ __('Profit/Loss') }}</span>
                            </a>
                            <a href="{{ route('income_statement.index') }}"
                                class="sub-submenu-item {{ request()->routeIs('income_statement.index') ? 'active' : '' }}">
                                <i class="fas fa-money-bill-wave text-green-500"></i>
                                <span>{{ __('Income Statement') }}</span>
                            </a>
                            <a href="{{ route('profitreport.view') }}"
                                class="sub-submenu-item {{ request()->routeIs('profitreport.view') ? 'active' : '' }}">
                                <i class="fas fa-coins text-yellow-500"></i>
                                <span>{{ __('Profit Report') }}</span>
                            </a>
                            <a href="{{ route('expenditure.report') }}"
                                class="sub-submenu-item {{ request()->routeIs('expenditure.report') ? 'active' : '' }}">
                                <i class="fas fa-receipt text-red-500"></i>
                                <span>{{ __('Expenditure Report') }}</span>
                            </a>
                        @endif
                        <a href="{{ route('cash_book.view') }}"
                            class="sub-submenu-item {{ request()->routeIs('cash_book.view') ? 'active' : '' }}">
                            <i class="fas fa-money-bill-alt text-green-600"></i>
                            <span>{{ __('Cash Book') }}</span>
                        </a>
                        <a href="{{ route('bank_book.view') }}"
                            class="sub-submenu-item {{ request()->routeIs('bank_book.view') ? 'active' : '' }}">
                            <i class="fas fa-university text-blue-600"></i>
                            <span>{{ __('Bank Book') }}</span>
                        </a>
                        <a href="{{ route('dailystate.view') }}"
                            class="sub-submenu-item {{ request()->routeIs('dailystate.view') ? 'active' : '' }}">
                            <i class="fas fa-calendar-day text-indigo-500"></i>
                            <span>{{ __('Daily Statement') }}</span>
                        </a>
                    </div>
                </div>

                <!-- Due Reminder -->
                <a href="{{ route('due_reminder') }}"
                    class="menu-item {{ request()->routeIs('due_reminder') ? 'active' : '' }}">
                    <i class="fas fa-bell"></i>
                    <span class="menu-item-text">{{ __('Due Reminder') }}</span>
                </a>


            </div>

            <div class="absolute bottom-0 w-full p-2 border-t border-indigo-700 lower-part">
                <!-- Settings -->
                @if ($role != 'employee')
                    <div class="menu-title">Settings</div>
                    <div class="menu-item {{ request()->routeIs(['profile.edit', 'changePass']) ? 'active' : '' }}"
                        aria-controls="dropdown-settings" data-collapse-toggle="dropdown-settings">
                        <i class="fas fa-cog"></i>
                        <span class="menu-item-text">Settings</span>
                        <i class="fas fa-chevron-down ml-auto" sidebar-toggle-item></i>
                    </div>
                    <div id="dropdown-settings"
                        class="submenu {{ request()->routeIs(['profile.edit', 'changePass']) ? '' : 'hidden' }}">
                        <a href="{{ route('profile.edit') }}"
                            class="submenu-item {{ request()->routeIs('profile.edit') ? 'active' : '' }}">
                            <i class="fas fa-building"></i>
                            <span>{{ __('Company Profile') }}</span>
                        </a>
                        <a href="{{ route('changePass') }}"
                            class="submenu-item {{ request()->routeIs('changePass') ? 'active' : '' }}">
                            <i class="fas fa-key"></i>
                            <span>{{ __('Change Password') }}</span>
                        </a>
                    </div>
                @endif

                <!-- Support -->
                <div class="menu-item" aria-controls="dropdown-support" data-collapse-toggle="dropdown-support">
                    <i class="fas fa-headset"></i>
                    <span class="menu-item-text">Support</span>
                    <i class="fas fa-chevron-down ml-auto" sidebar-toggle-item></i>
                </div>
                <div id="dropdown-support" class="hidden submenu">
                    <a href="#" class="submenu-item">
                        <i class="fas fa-graduation-cap"></i>
                        <span>{{ __('Training') }}</span>
                    </a>
                    <a href="#" class="submenu-item">
                        <i class="fas fa-phone"></i>
                        <span>{{ __('Support') }}</span>
                    </a>
                    <a href="https://anydesk.com/en" class="submenu-item" target="_blank">
                        <i class="fas fa-download"></i>
                        <span>{{ __('Download Anydesk') }}</span>
                    </a>
                </div>

                <!-- Logout -->
                <a href="{{ route('logout') }}" class="menu-item"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="fas fa-sign-out-alt"></i>
                    <span class="menu-item-text">{{ __('Logout') }}</span>
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                    @csrf
                </form>
            </div>
        </div>
    </div>


    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const hamburgerMenu = document.getElementById('hamburger-menu');
            const toggleBtn = document.getElementById('toggle-btn');
            const mobileCloseBtn = document.getElementById('mobile-close-btn');
            const sidebarOverlay = document.getElementById('sidebar-overlay');
            const mobileSidebar = document.getElementById('mobile-sidebar');
            const sidebar = document.getElementById('sidebar');

            function initDropdowns(sidebarElement) {
                const dropdownToggles = sidebarElement.querySelectorAll('[data-collapse-toggle]');

                dropdownToggles.forEach(toggle => {
                    toggle.addEventListener('click', function(e) {
                        if (e.target.tagName !== 'A') {
                            e.preventDefault();
                        }

                        const targetId = this.getAttribute('aria-controls');
                        const targetMenu = sidebarElement.querySelector(`#${targetId}`);
                        const icon = this.querySelector('[sidebar-toggle-item]');

                        sidebarElement.querySelectorAll('.submenu').forEach(menu => {
                            if (menu.id !== targetId && !menu.classList.contains(
                                    'hidden')) {
                                menu.classList.add('hidden');
                                const otherToggle = menu.previousElementSibling;
                                if (otherToggle && otherToggle.querySelector(
                                        '[sidebar-toggle-item]')) {
                                    otherToggle.querySelector('[sidebar-toggle-item]')
                                        .classList
                                        .replace('fa-chevron-up', 'fa-chevron-down');
                                }
                            }
                        });

                        if (targetMenu) {
                            targetMenu.classList.toggle('hidden');
                            if (icon) {
                                icon.classList.toggle('fa-chevron-down');
                                icon.classList.toggle('fa-chevron-up');
                            }
                        }
                    });
                });

                sidebarElement.querySelectorAll('.submenu-item').forEach(item => {
                    item.addEventListener('click', function(e) {
                        e
                            .stopPropagation(); // Prevent the dropdown from closing when clicking a link
                    });
                });
            }

            initDropdowns(sidebar);
            initDropdowns(mobileSidebar);

            if (hamburgerMenu) {
                hamburgerMenu.addEventListener('click', function(e) {
                    e.stopPropagation();
                    mobileSidebar.classList.add('open');
                    sidebarOverlay.classList.add('active');
                    document.body.style.overflow = 'hidden';
                });
            }

            if (mobileCloseBtn) {
                mobileCloseBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    closeMobileSidebar();
                });
            }

            // Toggle desktop sidebar collapse
            if (toggleBtn) {
                toggleBtn.addEventListener('click', function() {
                    sidebar.classList.toggle('collapsed');
                });
            }

            // Close mobile sidebar when clicking overlay
            if (sidebarOverlay) {
                sidebarOverlay.addEventListener('click', function(e) {
                    e.stopPropagation();
                    closeMobileSidebar();
                });
            }

            // Close mobile sidebar when clicking outside
            document.addEventListener('click', function(e) {
                if (!mobileSidebar.contains(e.target) && e.target !== hamburgerMenu) {
                    closeMobileSidebar();
                }
            });

            // Handle window resize
            window.addEventListener('resize', function() {
                if (window.innerWidth > 1050) {
                    closeMobileSidebar();
                }
            });

            function closeMobileSidebar() {
                if (mobileSidebar) mobileSidebar.classList.remove('open');
                if (sidebarOverlay) sidebarOverlay.classList.remove('active');
                document.body.style.overflow = '';
            }
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Toggle buttons
            const hamburgerMenu = document.getElementById('hamburger-menu');
            const toggleBtn = document.getElementById('toggle-btn');
            const mobileCloseBtn = document.getElementById('mobile-close-btn');
            const sidebarOverlay = document.getElementById('sidebar-overlay');
            const mobileSidebar = document.getElementById('mobile-sidebar');
            const sidebar = document.getElementById('sidebar');

            // Toggle mobile sidebar
            if (hamburgerMenu) {
                hamburgerMenu.addEventListener('click', function(e) {
                    e.stopPropagation();
                    mobileSidebar.classList.add('open');
                    sidebarOverlay.classList.add('active');
                    document.body.style.overflow = 'hidden';
                });
            }

            // Close mobile sidebar
            if (mobileCloseBtn) {
                mobileCloseBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    closeMobileSidebar();
                });
            }

            // Toggle desktop sidebar collapse
            if (toggleBtn) {
                toggleBtn.addEventListener('click', function() {
                    sidebar.classList.toggle('collapsed');
                });
            }

            // Close mobile sidebar when clicking overlay
            if (sidebarOverlay) {
                sidebarOverlay.addEventListener('click', function(e) {
                    e.stopPropagation();
                    closeMobileSidebar();
                });
            }

            // Close mobile sidebar when clicking outside
            document.addEventListener('click', function(e) {
                if (!mobileSidebar.contains(e.target) && e.target !== hamburgerMenu) {
                    closeMobileSidebar();
                }
            });

            // Handle window resize
            window.addEventListener('resize', function() {
                if (window.innerWidth > 1050) {
                    closeMobileSidebar();
                }
            });

            // Initialize submenu toggles for both sidebars
            document.querySelectorAll('[data-collapse-toggle]').forEach(toggle => {
                toggle.addEventListener('click', function() {
                    const targetId = this.getAttribute('aria-controls');
                    const target = document.getElementById(targetId);
                    if (target) {
                        target.classList.toggle('hidden');
                        const icon = this.querySelector('[sidebar-toggle-item]');
                        if (icon) {
                            icon.classList.toggle('fa-chevron-down');
                            icon.classList.toggle('fa-chevron-up');
                        }
                    }
                });
            });

            function closeMobileSidebar() {
                mobileSidebar.classList.remove('open');
                sidebarOverlay.classList.remove('active');
                document.body.style.overflow = '';
            }
        });
    </script>

</body>

</html>
