<div class="w-full mx-auto p-6">
    <!-- Header -->
    <div class="mb-8 text-center">
        <h1 class="text-3xl font-bold text-blue-800">{{ Auth::user()->name }}</h1>
        <p class="text-gray-600">{{ Auth::user()->address }}</p>
    </div>

    <!-- Report Title -->
    <div class="mb-10 text-center">
        <h2 class="text-2xl font-semibold text-gray-800">Net Profit / Loss Report</h2>
        <p class="text-gray-600">From: {{ $start_date }} To: {{ $end_date }}</p>
    </div>

    <!-- Revenue Section -->
    <div class="mb-8 bg-white rounded-lg shadow-md overflow-hidden">
        <div class="bg-green-600 px-6 py-3">
            <h3 class="text-lg font-semibold text-white">Revenue (Income)</h3>
        </div>
        <div class="p-6">
            <table class="w-full">
                <tbody>
                    <tr class="border-b border-gray-200">
                        <td class="py-3 font-medium text-gray-700">Ticket Sales</td>
                        <td class="py-3 text-right">{{ number_format($ticket_sell, 2) }}</td>
                    </tr>
                    <tr class="border-b border-gray-200">
                        <td class="py-3 font-medium text-gray-700">Reissue Ticket Sales</td>
                        <td class="py-3 text-right">{{ number_format($reissueticket_sell, 2) }}</td>
                    </tr>
                    <tr class="border-b border-gray-200">
                        <td class="py-3 font-medium text-gray-700">Refund Sales</td>
                        <td class="py-3 text-right">{{ number_format($refund_sell, 2) }}</td>
                    </tr>
                    <tr class="border-b border-gray-200">
                        <td class="py-3 font-medium text-gray-700">Void Ticket Sales</td>
                        <td class="py-3 text-right">{{ number_format($voidticket_sell, 2) }}</td>
                    </tr>
                    <tr class="border-b border-gray-200">
                        <td class="py-3 font-medium text-gray-700">Visa Sales</td>
                        <td class="py-3 text-right">{{ number_format($order_sell, 2) }}</td>
                    </tr>
                    <tr class="border-b border-gray-200">
                        <td class="py-3 font-medium text-gray-700">Contract Sales</td>
                        <td class="py-3 text-right">{{ number_format($contract_sell, 2) }}</td>
                    </tr>
                    <tr class="bg-green-50 font-semibold">
                        <td class="py-3 text-green-700">Total Revenue</td>
                        <td class="py-3 text-right text-green-700">{{ number_format($total_sell, 2) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Expenses Section -->
    <div class="mb-8 bg-white rounded-lg shadow-md overflow-hidden">
        <div class="bg-red-600 px-6 py-3">
            <h3 class="text-lg font-semibold text-white">Expenses (Cost)</h3>
        </div>
        <div class="p-6">
            <table class="w-full">
                <tbody>
                    <tr class="border-b border-gray-200">
                        <td class="py-3 font-medium text-gray-700">Ticket Purchase</td>
                        <td class="py-3 text-right">{{ number_format($ticket_purchase, 2) }}</td>
                    </tr>
                    <tr class="border-b border-gray-200">
                        <td class="py-3 font-medium text-gray-700">Reissue Ticket Purchase</td>
                        <td class="py-3 text-right">{{ number_format($reissueticket_purchase, 2) }}</td>
                    </tr>
                    <tr class="border-b border-gray-200">
                        <td class="py-3 font-medium text-gray-700">Refund Purchase</td>
                        <td class="py-3 text-right">{{ number_format($refund_purchase, 2) }}</td>
                    </tr>
                    <tr class="border-b border-gray-200">
                        <td class="py-3 font-medium text-gray-700">Void Ticket Purchase</td>
                        <td class="py-3 text-right">{{ number_format($voidticket_purchase, 2) }}</td>
                    </tr>
                    <tr class="border-b border-gray-200">
                        <td class="py-3 font-medium text-gray-700">Visa Purchase</td>
                        <td class="py-3 text-right">{{ number_format($order_purchase, 2) }}</td>
                    </tr>
                    <tr class="border-b border-gray-200">
                        <td class="py-3 font-medium text-gray-700">Contract Purchase</td>
                        <td class="py-3 text-right">{{ number_format($contract_cost, 2) }}</td>
                    </tr>

                    <!-- Dynamic Expenditures -->
                    @foreach ($expenditures as $expenditure)
                        <tr class="border-b border-gray-200">
                            <td class="py-3 font-medium text-gray-700">{{ $expenditure->expenditure_name }}</td>
                            <td class="py-3 text-right">{{ number_format($expenditure->amount, 2) }}</td>
                        </tr>
                    @endforeach

                    <tr class="bg-red-50 font-semibold">
                        <td class="py-3 text-red-700">Total Expenses</td>
                        <td class="py-3 text-right text-red-700">{{ number_format($total_purchase, 2) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Net Profit -->
    <div class="bg-blue-100 rounded-lg p-6 text-center">
        <h3 class="text-xl font-semibold text-blue-800 mb-2">Net Profit</h3>
        <p class="text-3xl font-bold {{ $total_sell - $total_purchase >= 0 ? 'text-blue-600' : 'text-red-600' }}">
            {{ number_format($total_sell - $total_purchase, 2) }}
        </p>
    </div>

    <!-- Footer -->
    {{-- <div class="mt-8 pt-4 border-t border-gray-200 text-center text-sm text-gray-500">
        <p>Generated on: {current_date}</p>
        <p class="mt-1">© 2025 SALLU AIR SERVICE. All rights reserved.</p>
    </div> --}}
</div>
