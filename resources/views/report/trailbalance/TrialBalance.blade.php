<h2 class="text-center font-bold text-3xl my-2">Trial Balance Report</h2>
<div class="flex items-center justify-between mb-4">
    <div class="text-lg">
        <h2 class="font-semibold">Company Name: {{ Auth::user()->name }}</h2>
        <p><span class="font-semibold">Period Date:</span> {{ $start_date }} to {{ $end_date }}</p>
    </div>
    <div class="flex items-center">
        <!-- Placeholder for additional content -->
    </div>
</div>
{{-- <div class="overflow-x-auto shadow-md rounded-lg">
    <table class="min-w-full bg-white border border-gray-200">
        <thead>
            <tr class="uppercase text-xs leading-tight bg-gray-100 text-gray-700">
                <th class="py-3 px-4 text-left">Particular</th>
                <th class="py-3 px-4 text-center">Debit</th>
                <th class="py-3 px-4 text-center">Credit</th>


            </tr>
        </thead>
        <tbody class="divide-y divide-black text-gray-700">

            <tr class="">
                <td class="w-[70%] px-2 py-2">Ticket Sales</td>
                <td class="text-center px-2 py-2">{{ $totalTicketAgentPrice }}</td>
                <td class="text-end px-2 py-2">0</td>

            </tr>
            <tr class="">
                <td class="w-[70%] px-2 py-2">Ticket Purchase</td>
                <td class="text-center px-2 py-2">0</td>
                <td class="text-end px-2 py-2">{{ $totalTicketSupplierPrice }}</td>

            </tr>

            <tr class="">
                <td class="w-[70%] px-2 py-2">Visa Sales</td>
                <td class="text-center px-2 py-2">{{ $totalOrderAgentPrice }}</td>
                <td class="text-end px-2 py-2">0</td>

            </tr>
            <tr class="">
                <td class="w-[70%] px-2 py-2">Visa Purchase</td>
                <td class="text-center px-2 py-2">0</td>
                <td class="text-end px-2 py-2">{{ $totalOrderSupplierPrice }}</td>

            </tr>
            @foreach ($totals as $method => $total)
                <tr>
                    <td class="w-[70%] px-2 py-2">{{ $method }}</td>
                    <td class="text-center px-2 py-2">{{ $total['debit'] }}</td>
                    <td class="text-end px-2 py-2">{{ $total['credit'] }}</td>
                </tr>
            @endforeach
            @foreach ($agents as $agent)
                <tr>
                    <td class="w-[70%] px-2 py-2">{{ $agent['name'] }}<span style="color:red">(Agent)</span></td>
                    <td class="text-center px-2 py-2">{{ $agent['amount'] }}</td>
                    <td class="text-end px-2 py-2">0</td>
                </tr>
            @endforeach
            @foreach ($suppliers as $agent)
                <tr>
                    <td class="w-[70%] px-2 py-2">{{ $agent['name'] }}<span style="color:red">(Supplier)</span></td>
                    <td class="text-center px-2 py-2">0</td>
                    <td class="text-end px-2 py-2">{{ $agent['amount'] }}</td>
                </tr>
            @endforeach
            <tr class=" text-black font-bold">

                <td class="w-[70%] text-lg px-2 py-2">Total</td>
                <td class="text-center px-2 py-2">{{ $totalDebit }}</td>
                <td class="text-end px-2 py-2">{{ $totalCredit }}</td>
            </tr>

        </tbody>
    </table>
</div> --}}

<div class="overflow-x-auto shadow-lg rounded-lg border border-gray-200">
    <table class="min-w-full bg-white divide-y divide-gray-200">
        <thead class="">
            <tr class="uppercase text-xs leading-tight bg-gray-100 text-gray-700">
                <th class="py-3 px-4 text-left font-semibold uppercase text-sm">Particular</th>
                <th class="py-3 px-4 text-center font-semibold uppercase text-sm">Debit</th>
                <th class="py-3 px-4 text-center font-semibold uppercase text-sm">Credit</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            <!-- Ticket Sales -->
            <tr class="hover:bg-gray-50">
                <td class="w-[70%] px-4 py-3 text-gray-700 font-medium">Ticket Sales</td>
                <td class="px-4 py-3 text-center text-blue-600 font-medium">{{ number_format($totalTicketAgentPrice, 2) }}</td>
                <td class="px-4 py-3 text-end">0.00</td>
            </tr>

            <!-- Ticket Purchase -->
            <tr class="hover:bg-gray-50">
                <td class="w-[70%] px-4 py-3 text-gray-700 font-medium">Ticket Purchase</td>
                <td class="px-4 py-3 text-center">0.00</td>
                <td class="px-4 py-3 text-end text-red-600 font-medium">{{ number_format($totalTicketSupplierPrice, 2) }}</td>
            </tr>

            <!-- Visa Sales -->
            <tr class="hover:bg-gray-50">
                <td class="w-[70%] px-4 py-3 text-gray-700 font-medium">Visa Sales</td>
                <td class="px-4 py-3 text-center text-blue-600 font-medium">{{ number_format($totalOrderAgentPrice, 2) }}</td>
                <td class="px-4 py-3 text-end">0.00</td>
            </tr>

            <!-- Visa Purchase -->
            <tr class="hover:bg-gray-50">
                <td class="w-[70%] px-4 py-3 text-gray-700 font-medium">Visa Purchase</td>
                <td class="px-4 py-3 text-center">0.00</td>
                <td class="px-4 py-3 text-end text-red-600 font-medium">{{ number_format($totalOrderSupplierPrice, 2) }}</td>
            </tr>

            <!-- Payment Methods -->
            @foreach ($totals as $method => $total)
                <tr class="hover:bg-gray-50">
                    <td class="w-[70%] px-4 py-3 text-gray-600">{{ $method }}</td>
                    <td class="px-4 py-3 text-center text-blue-600">{{ number_format($total['debit'], 2) }}</td>
                    <td class="px-4 py-3 text-end text-red-600">{{ number_format($total['credit'], 2) }}</td>
                </tr>
            @endforeach

            <!-- Agents -->
            @foreach ($agents as $agent)
                <tr class="hover:bg-gray-50">
                    <td class="w-[70%] px-4 py-3 text-gray-600">
                        {{ $agent['name'] }} <span class="text-red-500 text-xs">(Agent)</span>
                    </td>
                    <td class="px-4 py-3 text-center text-blue-600">{{ number_format($agent['amount'], 2) }}</td>
                    <td class="px-4 py-3 text-end">0.00</td>
                </tr>
            @endforeach

            <!-- Suppliers -->
            @foreach ($suppliers as $supplier)
                <tr class="hover:bg-gray-50">
                    <td class="w-[70%] px-4 py-3 text-gray-600">
                        {{ $supplier['name'] }} <span class="text-red-500 text-xs">(Supplier)</span>
                    </td>
                    <td class="px-4 py-3 text-center">0.00</td>
                    <td class="px-4 py-3 text-end text-red-600">{{ number_format($supplier['amount'], 2) }}</td>
                </tr>
            @endforeach

            <!-- Grand Total -->
            <tr class="bg-gray-100 font-bold border-t-2 border-gray-300">
                <td class="px-4 py-3 text-lg text-gray-800">Total</td>
                <td class="px-4 py-3 text-center text-blue-700">{{ number_format($totalDebit, 2) }}</td>
                <td class="px-4 py-3 text-end text-red-700">{{ number_format($totalCredit, 2) }}</td>
            </tr>
        </tbody>
    </table>
</div>
