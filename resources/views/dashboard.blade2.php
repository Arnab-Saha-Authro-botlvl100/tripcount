<x-app-layout>

     <!-- Main Content -->
    <div class="main-content" id="main-content">
        <div class="header">
            <div></div> <!-- Empty div for spacing -->
            <div class="user-info">
                <img src="https://via.placeholder.com/40" alt="User">
                <span>Alauddin</span>
            </div>
        </div>
        
        <div class="content">
            <div class="welcome-message">
                <h1>Hello Alauddin, Welcome Back</h1>
                <p>Your course "Overcoming the fear of public speaking" was completed by 11 new users this week</p>
            </div>
            
            <!-- The rest of your dashboard content would go here -->
        </div>
    </div>
    <div class="px-2 py-2 grid grid-cols-3 md:grid-cols-5  gap-y-3 gap-x-4">
        <a href={{ route('ticket.view') }}
            class="text-white text-center bg-[#84857C] font-bold py-3 px-4 rounded hover:no-underline">
            New Ticket Invoice
        </a>
        <a href={{ route('order.view') }}
            class="hover:no-underline text-center text-white bg-[#4E4E4E] font-bold py-3 px-4 rounded">
            Service Invoice Type
        </a>
        <a href={{ route('receive.index') }}
            class="text-white text-center  bg-[#6A764D] font-bold py-3 px-4 rounded hover:no-underline">
            Receive
        </a>
        <a href={{ route('payment.form') }}
            class="text-white bg-[#A58A4C] font-bold py-3 px-4 rounded hover:no-underline text-center">
            Payment
            {{-- sales report --}}
        </a>
        <a href={{ route('profit_loss.view') }}
            class="text-white bg-[#6A764D] font-bold py-3 px-4 rounded hover:no-underline text-center">
            Profit & Loss
        </a>
        <a href={{ route('flight_ticket') }}
            class="text-center hover:no-underline text-white bg-[#576335] font-bold py-3 px-4 rounded">
            Flight Report
        </a>
        <a href={{ route('general_ledger') }}
            class="text-white bg-[#7a4b2b] font-bold py-3 px-4 rounded text-center hover:no-underline">
            General Ledger
        </a>
        <a href={{ route('cash_book.view') }}
            class="text-white text-center hover:no-underline bg-[#A58A4C] font-bold py-3 px-4 rounded">
            Cash Book Report
        </a>
        <a href={{ route('bank_book.view') }}
            class="text-center hover:no-underline text-white bg-[#84857C] font-bold py-3 px-4 rounded">
            Bank Book Report
        </a>
        <a href={{ route('expanditure.view') }}
            class="text-center hover:no-underline text-white bg-[#A58A4C] font-bold py-3 px-4 rounded">
            Expanditure
        </a>
        <a href={{ route('sales_ticket') }}
            class="text-center hover:no-underline text-white bg-[#344d0e] font-bold py-3 px-4 rounded">
            Sales Report
        </a>
        <a href={{ route('due_reminder') }}
            class="text-center hover:no-underline text-white bg-[#574816] font-bold py-3 px-4 rounded">
            Deu Reminder
        </a>
        <a href={{ route('sales_exicutive_stuff') }}
            class="text-center hover:no-underline text-white bg-[#4f595d] font-bold py-3 px-4 rounded">
            Staff Sales Report
        </a>
        <a href={{ route('dailystate.view') }}
            class="text-center hover:no-underline text-white bg-[#7a4b2b] font-bold py-3 px-4 rounded">
            Day Book
        </a>
        <a href={{ route('moneytransfer.view') }}
            class="text-center hover:no-underline text-white bg-[#5c6e70] font-bold py-3 px-4 rounded">
            Contra
        </a>

    </div>

    <div class="grid gap-4 lg:grid-cols-2 sm:grid-cols-1 mt-2">

        @if (!session('employee'))
            <div class="w-full max-w-[100vw] overflow-x-scroll mt-2 bg-white shadow-lg">

                <h2 class="px-3 bg-gray-200 py-2 text-xl text-black border-b border-gray-200 font-semibold">
                    Total Cash In Bank: <span style="color: blue;">{{ $total_amount }}</span>
                </h2>
                <div class="bg-white p-2 overflow-y-scroll h-[400px] overflow-scroll">
                    <table class="my-5 text-sm text-black border table table-hover">
                        <thead>
                            <tr class="border-b bg-[#7CB0B2]">
                                <th class="w-6/12 px-4 py-1 text-left text-gray-700 font-medium">Bank</th>
                                <th class="w-4/12 px-4 py-1 text-left text-gray-700 font-medium">Description</th>
                                <th class="w-2/12 px-4 py-1 text-left text-gray-700 font-medium">Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($transactions as $bank)
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="px-4 py-2 text-gray-700">{{ $bank->name }}</td>
                                    <td class="px-4 py-2 text-gray-700">{{ $bank->description }}</td>
                                    <td class="px-4 py-2 text-gray-700">{{ $bank->amount }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>

                            <tr class="border-b hover:bg-gray-50">
                                <td class="px-4 py-2 text-gray-700 text-md font-semibold">Total</td>
                                <td class="px-4 py-2 text-gray-700"></td>
                                <td class="px-4 py-2 text-gray-700 text-md font-semibold">{{ $total_amount }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        @endif

        <div class="w-full max-w-[100vw] overflow-x-scroll mt-2 bg-white shadow-lg">
            <h2 class="px-3 bg-gray-200 py-2 text-xl text-black border-b border-gray-200 font-semibold">
                Total Receivables: <span style="color: blue;">{{ $total_receive }}</span>
            </h2>
            <div class="bg-white p-2 overflow-y-scroll h-[400px] overflow-scroll">
                <table class="  my-5 text-sm text-black border table table-hover">
                    <thead>
                        <tr class="border-b bg-[#7CB0B2]">
                            <th class="w-1/6 px-4 py-1 text-left text-gray-700 font-medium">Date</th>
                            <th class="w-1/6 px-4 py-1 text-left text-gray-700 font-medium">Invoice Number</th>

                            <th class="w-1/6 px-4 py-1 text-left text-gray-700 font-medium">Name</th>
                            <th class="w-1/6 px-4 py-1 text-left text-gray-700 font-medium">Method</th>
                            <th class="w-1/6 px-4 py-1 text-left text-gray-700 font-medium">Amount</th>
                            <th class="w-1/6 px-4 py-1 text-left text-gray-700 font-medium">Remarks</th>

                        </tr>
                    </thead>
                    <tbody class="">

                        @foreach ($receives as $receive)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="px-4 py-2 text-gray-700">
                                    {{ (new DateTime($receive->date))->format('d/m/Y') }}</td>
                                <td class="px-4 py-2 text-gray-700">{{ $receive->invoice }}</td>

                                <td class="px-4 py-2 text-gray-700">{{ $receive->name }}</td>
                                <td class="px-4 py-2 text-gray-700">{{ $receive->method }}</td>

                                <td class="px-4 py-2 text-gray-700">{{ $receive->amount }}</td>

                                <td class="px-4 py-2 text-gray-700">{{ $receive->remark }}</td>
                            </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
        </div>

        <div class="w-full max-w-[100vw] overflow-x-hidden mt-2 bg-white shadow-lg">
            <h2 class="px-3 bg-gray-200 py-2 text-xl text-black border-b border-gray-200 font-semibold">
                Total Payables: <span style="color: blue;">{{ $total_pay }}</span>
            </h2>
            <div class="bg-white p-2 overflow-y-scroll h-[400px] overflow-scroll">
                <table class="my-5 text-sm text-black border table table-hover">
                    <thead>
                        <tr class="border-b bg-[#7CB0B2]">
                            <th class="w-1/6 px-4 py-1 text-left text-gray-700 font-medium">Date</th>
                            <th class="w-1/6 px-4 py-1 text-left text-gray-700 font-medium">Invoice Number</th>

                            <th class="w-1/6 px-4 py-1 text-left text-gray-700 font-medium">Name</th>
                            <th class="w-1/6 px-4 py-1 text-left text-gray-700 font-medium">Method</th>
                            <th class="w-1/6 px-4 py-1 text-left text-gray-700 font-medium">Amount</th>
                            <th class="w-1/6 px-4 py-1 text-left text-gray-700 font-medium">Remarks</th>

                        </tr>
                    </thead>
                    <tbody class="">

                        @foreach ($payments as $payment)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="px-4 py-2 text-gray-700">
                                    {{ (new DateTime($payment->date))->format('d/m/Y') }}</td>
                                <td class="px-4 py-2 text-gray-700">{{ $payment->invoice }}</td>

                                <td class="px-4 py-2 text-gray-700">{{ $payment->name }}</td>
                                <td class="px-4 py-2 text-gray-700">{{ $payment->method }}</td>

                                <td class="px-4 py-2 text-gray-700">{{ $payment->amount }}</td>

                                <td class="px-4 py-2 text-gray-700">{{ $payment->remark }}</td>
                            </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
        </div>

        <div class="rounded-lg bg-gradient-to-br from-gray-50 to-white shadow-xl">
            <h2
                class="px-5 bg-gradient-to-r from-blue-100 to-gray-200 py-3 text-2xl text-blue-900 border-b border-gray-300 font-semibold rounded-t-lg">
                Sales (Current Billing): <span style="color: #2563EB;">{{ $total_pay }}</span>
            </h2>

            <div class="bg-white h-auto">

                <div class="container mx-auto py-6">
                    <div class="grid md:grid-cols-2 sm:grid-cols-1 text-center border-gray-300">

                        <!-- 20000+ Graduates Students -->
                        <div
                            class="flex h-[170px] flex-col items-center  border-r justify-center border-b border-gray-300">
                            <h2 class="text-teal-600 text-4xl font-extrabold"> {{ $total_month_sales_ticket }}</h2>
                            <p class="text-gray-700 text-lg font-semibold">Total Sales Ticket</p>
                        </div>

                        <!-- 350+ Employees & Academics -->
                        <div class="flex h-[170px] flex-col items-center border-b justify-center border-gray-300">
                            <h2 class="text-red-500 text-4xl font-extrabold">{{ $total_today_sales_ticket }}</h2>
                            <p class="text-gray-700 text-lg font-semibold">Today Sales Ticket</p>
                        </div>

                        <!-- 25+ Programmes -->
                        <div class="flex h-[170px] flex-col items-center border-r justify-center border-gray-300">
                            <h2 class="text-orange-500 text-4xl font-extrabold">{{ $total_month_sales_visa }}</h2>
                            <p class="text-gray-700 text-lg font-semibold">Total Sales Visa</p>
                        </div>

                        <!-- 3000+ Students Per Year Admission -->
                        <div class="flex h-[170px] flex-col items-center justify-center">
                            <h2 class="text-purple-600 text-4xl font-extrabold">{{ $total_today_sales_visa }}</h2>
                            <p class="text-gray-700 text-lg font-semibold">Today Sales Visa</p>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Notification Button -->
    <div class="fixed bottom-6 right-6 z-50">
        <button id="notificationButton"
            class="relative p-4 bg-blue-600 rounded-full shadow-lg hover:bg-blue-700 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
            <!-- Bell Icon -->
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
            </svg>

            <!-- Notification Badge -->
            @if ($closing_ticket_count > 0)
                <span
                    class="absolute -top-2 -right-2 bg-red-500 text-white text-xs font-bold rounded-full h-6 w-6 flex items-center justify-center animate-pulse">
                    {{ $closing_ticket_count }}
                </span>
            @endif
        </button>
    </div>

    <!-- Notification Modal -->
    <div id="notificationModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <!-- Background overlay -->
            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>

            <!-- Modal panel -->
            <div
                class="inline-block align-bottom bg-white rounded-t-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-7xl sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 border-b pb-3">
                                Flight Alerts ({{ $closing_ticket_count }})
                            </h3>

                            <div class="mt-4 overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col"
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Invoice Date</th>
                                            <th scope="col"
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Ticket No</th>
                                            <th scope="col"
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Airline</th>
                                            <th scope="col"
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Passenger</th>
                                            <th scope="col"
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Flight Date</th>
                                            <th scope="col"
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Agent</th>
                                            <th scope="col"
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Supplier</th>
                                            <th scope="col"
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Price</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach ($closetickets as $ticket)
                                            <tr class="hover:bg-gray-50">
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    {{ (new DateTime($ticket->invoice_date))->format('d/m/Y') }}</td>
                                                <td
                                                    class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                    {{ $ticket->ticket_code }}/{{ $ticket->ticket_no }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    {{ $ticket->airline_name }}/{{ $ticket->airline_code }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    {{ $ticket->passenger }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    {{ (new DateTime($ticket->flight_date))->format('d/m/Y') }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    {{ $ticket->agent }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    {{ $ticket->supplier }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    {{ number_format($ticket->agent_price, 2) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" id="closeModal"
                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>


    <div class="mt-2 bg-white py-4">
        <div class="container">
            <h2 class="mb-4 text-center">Transaction Overview</h2>

            <div class="row mx-0"> <!-- Added mx-0 to remove default row margins -->
                <!-- Receive Graph -->
                <div class="col-lg-6 col-md-12 mb-4 px-2"> <!-- Added px-2 for gutter spacing -->
                    <div class="card shadow-sm h-100 overflow-hidden"> <!-- Added overflow-hidden -->
                        <div
                            class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">Receive</h5>
                            <span class="badge bg-white text-primary" id="total_receive"> Total</span>
                        </div>
                        <div class="card-body p-3"> <!-- Added p-3 for consistent padding -->
                            <div class="chart-container" style="position: relative; height: 350px; width: 100%;">
                                <canvas id="receive_graph"></canvas>
                            </div>
                        </div>
                        <div class="card-footer small text-muted d-flex justify-content-between">
                            <span>Updated at {{ now()->format('h:i A') }}</span>
                            <a href="#" class="text-primary">Details →</a>
                        </div>
                    </div>
                </div>

                <!-- Payment Graph -->
                <div class="col-lg-6 col-md-12 mb-4 px-2"> <!-- Added px-2 for gutter spacing -->
                    <div class="card shadow-sm h-100 overflow-hidden"> <!-- Added overflow-hidden -->
                        <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">Payment</h5>
                            <span class="badge bg-white text-info" id="total_payment"> Total</span>
                        </div>
                        <div class="card-body p-3"> <!-- Added p-3 for consistent padding -->
                            <div class="chart-container" style="position: relative; height: 350px; width: 100%;">
                                <canvas id="payment_graph"></canvas>
                            </div>
                        </div>
                        <div class="card-footer small text-muted d-flex justify-content-between">
                            <span>Updated at {{ now()->format('h:i A') }}</span>
                            <a href="#" class="text-info">Details →</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="mt-2 bg-white py-4">
        <div class="container mx-auto px-4">
            <!-- Two Column Graph Layout -->
            <div class="flex flex-col lg:flex-row gap-6">
                <!-- First Graph Column -->
                <div class="w-full lg:w-1/2">
                    <div class="bg-white rounded-lg shadow-md overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <div class="flex items-center justify-between">
                                <h3 class="text-lg font-semibold text-gray-800">Seles Analysis</h3>
                                <span
                                    class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                    Overall
                                </span>
                            </div>
                        </div>
                        <div class="p-4">
                            <div class="chart-container" style="position: relative; height: 350px;">
                                <canvas id="summaryChart"></canvas>
                            </div>
                        </div>
                        <div class="px-6 py-3 bg-gray-50 text-xs text-gray-500 border-t border-gray-200">
                            Updated: {{ now()->format('M d, Y h:i A') }}
                        </div>
                    </div>
                </div>

                <!-- Second Graph Column -->
                <div class="w-full lg:w-1/2">
                    <div class="bg-white rounded-lg shadow-md overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <div class="flex items-center justify-between">
                                <h3 class="text-lg font-semibold text-gray-800">Profit Breakdown</h3>
                                <span
                                    class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                    Full
                                </span>
                            </div>
                        </div>
                        <div class="p-4">
                            <div class="chart-container" style="position: relative; height: 350px;">
                                <canvas id="profitChart"></canvas>
                            </div>
                        </div>
                        <div class="px-6 py-3 bg-gray-50 text-xs text-gray-500 border-t border-gray-200">
                            Updated: {{ now()->format('M d, Y h:i A') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-2 bg-white py-4">
        <div class="container mx-auto px-4">
            <div class="flex flex-col lg:flex-row gap-4">
                <!-- Daily Report Card -->
                <div class="w-full lg:w-1/2 bg-white rounded-xl shadow-md overflow-hidden border">
                    <!-- Header -->
                    <div class="bg-blue-600 p-4 text-white">
                        <h1 class="text-xl font-bold">DAILY REPORT</h1>
                    </div>

                    <!-- Toggle Button -->
                    <div class="p-4 border-b border-gray-200 flex justify-between items-center cursor-pointer"
                        onclick="toggleSection('daily')">
                        <span class="font-medium">Show All</span>
                        <svg id="daily-arrow" xmlns="http://www.w3.org/2000/svg"
                            class="h-5 w-5 transform transition-transform" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>

                    <!-- Visible Section -->
                    <div class="p-4 space-y-4">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Sales Amount</span>
                            <span class="font-bold">{{ $total_today_sales_amount }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Collection Amount</span>
                            <span class="font-bold">{{ $total_today_receive_amount }}</span>
                        </div>

                    </div>

                    <!-- Hidden Section -->
                    <div id="daily-hidden" class="hidden p-4 space-y-4 border-t border-gray-200">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Purchased Amount</span>
                            <span class="font-bold">{{ $total_today_purchase_amount }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Payment Amount</span>
                            <span class="font-bold">{{ $total_today_payment_amount }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Profit/Loss</span>
                            <span
                                class="font-bold">{{ $total_today_sales_amount - $total_today_purchase_amount }}</span>
                        </div>
                    </div>

                    <!-- Show Less Button -->
                    <div id="daily-show-less" class="hidden p-4 border-t border-gray-200 flex justify-center">
                        <button class="text-blue-600 hover:text-blue-800 focus:outline-none"
                            onclick="hideSection('daily')">
                            Show Less
                        </button>
                    </div>
                </div>

                <!-- Monthly Report Card -->
                <div class="w-full lg:w-1/2 bg-white rounded-xl shadow-md overflow-hidden border">
                    <!-- Header -->
                    <div class="bg-green-600 p-4 text-white">
                        <h1 class="text-xl font-bold">MONTHLY REPORT</h1>
                    </div>

                    <!-- Toggle Button -->
                    <div class="p-4 border-b border-gray-200 flex justify-between items-center cursor-pointer"
                        onclick="toggleSection('monthly')">
                        <span class="font-medium">Show All</span>
                        <svg id="monthly-arrow" xmlns="http://www.w3.org/2000/svg"
                            class="h-5 w-5 transform transition-transform" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>

                    <!-- Visible Section -->
                    <div class="p-4 space-y-4">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Sales Amount</span>
                            <span class="font-bold">{{ $total_month_sales_amount }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Collection Amount</span>
                            <span class="font-bold">{{ $total_month_receive_amount }}</span>
                        </div>

                    </div>

                    <!-- Hidden Section -->
                    <div id="monthly-hidden" class="hidden p-4 space-y-4 border-t border-gray-200">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Purchased Amount</span>
                            <span class="font-bold">{{ $total_month_purchase_amount }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Payment Amount</span>
                            <span class="font-bold">{{ $total_month_payment_amount }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Profit/Loss</span>
                            <span
                                class="font-bold text-green-600">{{ $total_month_sales_amount - $total_month_purchase_amount }}</span>
                        </div>
                    </div>

                    <!-- Show Less Button -->
                    <div id="monthly-show-less" class="hidden p-4 border-t border-gray-200 flex justify-center">
                        <button class="text-green-600 hover:text-green-800 focus:outline-none"
                            onclick="hideSection('monthly')">
                            Show Less
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script>
        // Modal toggle functionality
        const modal = document.getElementById('notificationModal');
        const btn = document.getElementById('notificationButton');
        const closeBtn = document.getElementById('closeModal');

        btn.addEventListener('click', () => {
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden'; // Prevent scrolling when modal is open
        });

        closeBtn.addEventListener('click', () => {
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto'; // Re-enable scrolling
        });

        // Close modal when clicking outside
        window.addEventListener('click', (event) => {
            if (event.target === modal) {
                modal.classList.add('hidden');
                document.body.style.overflow = 'auto';
            }
        });
    </script>
    
    <script>
        function toggleSection(type) {
            const hiddenSection = document.getElementById(`${type}-hidden`);
            const showLessButton = document.getElementById(`${type}-show-less`);
            const arrow = document.getElementById(`${type}-arrow`);

            hiddenSection.classList.toggle('hidden');
            showLessButton.classList.toggle('hidden');
            arrow.classList.toggle('rotate-180');
        }

        function hideSection(type) {
            const hiddenSection = document.getElementById(`${type}-hidden`);
            const showLessButton = document.getElementById(`${type}-show-less`);
            const arrow = document.getElementById(`${type}-arrow`);

            hiddenSection.classList.add('hidden');
            showLessButton.classList.add('hidden');
            arrow.classList.remove('rotate-180');
        }
    </script>

    <script type="text/javascript">
        $('#flight_table').DataTable();
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Common configuration
            const chartConfig = {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            boxWidth: 12,
                            padding: 20,
                            font: {
                                size: 11
                            }
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const label = context.dataset.label || '';
                                const value = context.parsed.y || context.raw;
                                const count = context.chart.data.counts[context.dataIndex];

                                if (context.chart.config.type === 'pie' || context.chart.config.type ===
                                    'doughnut') {
                                    const total = context.dataset.data.reduce((a, b) => a + b);
                                    const percentage = Math.round((value / total) * 100);
                                    return `${label}: ${formatCurrency(value)} (${percentage}%)`;
                                }
                                return `${label}: ${formatCurrency(value)}`;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return formatCurrency(value);
                            }
                        }
                    }
                }
            };

            // Format currency
            function formatCurrency(value) {
                return value.toLocaleString('en-US', {
                    style: 'currency',
                    currency: 'BDT',
                    minimumFractionDigits: 0,
                    maximumFractionDigits: 0
                });
            }

            // Generate colors
            function generateColors(count, alpha = 0.7) {
                const colors = [];
                const hueStep = 360 / count;

                for (let i = 0; i < count; i++) {
                    const hue = i * hueStep;
                    colors.push(`hsla(${hue}, 70%, 50%, ${alpha})`);
                }
                return colors;
            }

            // Prepare receive data
            const receiveData = @json($groupedDatareceive);
            const receiveMethods = Object.keys(receiveData);
            const receiveAmounts = receiveMethods.map(method => receiveData[method].total_amount);
            const totalReceiveAmount = receiveAmounts.reduce((sum, amount) => sum + amount, 0);
            document.getElementById('total_receive').innerHTML = `Total: ${totalReceiveAmount}`;
            const receiveCounts = receiveMethods.map(method => receiveData[method].count);
            const receiveColors = generateColors(receiveMethods.length);

            // Prepare payment data
            const paymentData = @json($groupedDatapayment);
            const paymentMethods = Object.keys(paymentData);
            const paymentAmounts = paymentMethods.map(method => paymentData[method].total_amount);
            const totalPaymentAmount = paymentAmounts.reduce((sum, amount) => sum + amount, 0);
            document.getElementById('total_payment').innerHTML = `Total: ${totalPaymentAmount}`;
            const paymentCounts = paymentMethods.map(method => paymentData[method].count);
            const paymentColors = generateColors(paymentMethods.length);

            // Create receive chart
            const receiveCtx = document.getElementById('receive_graph').getContext('2d');
            new Chart(receiveCtx, {
                type: 'bar',
                data: {
                    labels: receiveMethods,
                    datasets: [{
                        label: 'Amount Received',
                        data: receiveAmounts,
                        backgroundColor: receiveColors,
                        borderColor: receiveColors.map(color => color.replace('0.7', '1')),
                        borderWidth: 1
                    }],
                    counts: receiveCounts
                },
                options: chartConfig
            });

            // Create payment chart
            const paymentCtx = document.getElementById('payment_graph').getContext('2d');
            new Chart(paymentCtx, {
                type: 'doughnut',
                data: {
                    labels: paymentMethods,
                    datasets: [{
                        label: 'Amount Paid',
                        data: paymentAmounts,
                        backgroundColor: paymentColors,
                        borderColor: paymentColors.map(color => color.replace('0.7', '1')),
                        borderWidth: 1
                    }],
                    counts: paymentCounts
                },
                options: {
                    ...chartConfig,
                    cutout: '70%',
                    plugins: {
                        ...chartConfig.plugins,
                        legend: {
                            ...chartConfig.plugins.legend,
                            position: 'right'
                        }
                    }
                }
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('summaryChart').getContext('2d');

            // Data from controller
            const data = {
                tickets: {{ $total_ticket_count }},
                orders: {{ $total_order_count }},
                contracts: {{ $total_contract_count }},
                wakala: {{ $total_wakala_count }}
            };
            // console.log(data);
            const colors = [
                'rgba(78, 115, 223, 0.8)', // Blue
                'rgba(28, 200, 138, 0.8)', // Green
                'rgba(54, 185, 204, 0.8)', // Teal
                'rgba(246, 194, 62, 0.8)' // Yellow
            ];

            const borderColors = colors.map(color => color.replace('0.8', '1'));

            new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: ['Tickets', 'Orders', 'Contracts', 'Wakala'],
                    datasets: [{
                        data: Object.values(data),
                        backgroundColor: colors,
                        borderColor: borderColors,
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'right',
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return `${context.label}: ${context.raw}`;
                                }
                            }
                        }
                    }
                }
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Get the profit data passed from PHP
            const profitData = @json($profitReport ?? []);

            // Check if we have data
            if (!profitData || profitData.length === 0) {
                console.log('No profit data available');
                return;
            }

            // Prepare chart data
            const labels = profitData.map(item => item.name);
            const profits = profitData.map(item => item.profit);
            const sellingPrices = profitData.map(item => item.selling_price);
            const buyingPrices = profitData.map(item => item.buying_price);
            const transactionCounts = profitData.map(item => item.count);

            // Generate colors
            const backgroundColors = [
                'rgba(54, 162, 235, 0.7)', // Blue
                'rgba(75, 192, 192, 0.7)', // Teal
                'rgba(255, 159, 64, 0.7)', // Orange
                'rgba(153, 102, 255, 0.7)', // Purple
                'rgba(255, 99, 132, 0.7)' // Red
            ];

            // Create the chart
            const ctx = document.getElementById('profitChart').getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                            label: 'Profit',
                            data: profits,
                            backgroundColor: backgroundColors,
                            borderColor: backgroundColors.map(color => color.replace('0.7', '1')),
                            borderWidth: 1,
                            yAxisID: 'y'
                        },
                        {
                            label: 'Sell Count',
                            data: transactionCounts,
                            type: 'line',
                            borderColor: 'rgba(153, 102, 255, 1)',
                            backgroundColor: 'rgba(153, 102, 255, 0.1)',
                            borderWidth: 2,
                            pointRadius: 4,
                            yAxisID: 'y1'
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    let label = context.dataset.label || '';
                                    let value = context.raw;

                                    if (context.dataset.label === 'Profit') {
                                        return `${label}: ${formatCurrency(value)}`;
                                    } else {
                                        return `${label}: ${value}`;
                                    }
                                },
                                afterLabel: function(context) {
                                    const index = context.dataIndex;
                                    return [
                                        `Selling: ${formatCurrency(sellingPrices[index])}`,
                                        `Buying: ${formatCurrency(buyingPrices[index])}`,
                                        `Sell Count: ${transactionCounts[index]}`
                                    ].join('\n');
                                }
                            }
                        },
                        legend: {
                            position: 'top'
                        }
                    },
                    scales: {
                        y: {
                            type: 'linear',
                            display: true,
                            position: 'left',
                            title: {
                                display: true,
                                text: 'Profit Amount'
                            },
                            ticks: {
                                callback: function(value) {
                                    return formatCurrency(value);
                                }
                            }
                        },
                        y1: {
                            type: 'linear',
                            display: true,
                            position: 'right',
                            title: {
                                display: true,
                                text: 'Sell Count'
                            },
                            grid: {
                                drawOnChartArea: false
                            },
                            ticks: {
                                precision: 0
                            }
                        }
                    }
                }
            });

            // Currency formatter
            function formatCurrency(value) {
                return new Intl.NumberFormat('en-US', {
                    style: 'currency',
                    currency: 'BDT',
                    minimumFractionDigits: 0,
                    maximumFractionDigits: 0
                }).format(value);
            }
        });
    </script>

</x-app-layout>
