<div class="report-container">
    <h2 class="text-center font-bold text-2xl md:text-3xl my-4">Sales Report (Visa)</h2>

    <div class="report-header flex flex-col md:flex-row justify-between items-start md:items-center mb-4 gap-2">
        <div class="company-info">
            <h2 class="font-semibold text-lg">Company Name: {{ Auth::user()->name }}</h2>
            <p class="text-gray-600"><span class="font-medium">Period Date:</span> {{ $start_date }} to
                {{ $end_date }}</p>
        </div>

        <div class="report-actions flex items-center gap-2">
            <!-- Add any action buttons here if needed -->
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full bg-white border border-gray-200">
            <thead>
                <tr class="bg-gray-100 text-gray-700 uppercase text-xs leading-tight">
                    <th class="py-3 px-4 text-left border-b border-gray-200">Booking Date</th>
                    <th class="py-3 px-4 text-left border-b border-gray-200">Invoice No</th>
                    <th class="py-3 px-4 text-left border-b border-gray-200">Type</th>
                    <th class="py-3 px-4 text-left border-b border-gray-200">Passenger Name</th>
                    <th class="py-3 px-4 text-left border-b border-gray-200">Passport No</th>
                    <th class="py-3 px-4 text-left border-b border-gray-200">Country</th>

                    @if ($show_agent)
                        <th class="py-3 px-4 text-left border-b border-gray-200">Agent</th>
                        <th class="py-3 px-4 text-left border-b border-gray-200">Agent Price</th>
                    @endif

                    @if ($show_supplier)
                        <th class="py-3 px-4 text-left border-b border-gray-200">Supplier</th>
                        <th class="py-3 px-4 text-left border-b border-gray-200">Supplier Price</th>
                    @endif

                    @if ($show_profit)
                        <th class="py-3 px-4 text-left border-b border-gray-200">Profit</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @php
                    $total_agent_price = $total_supplier_price = $total_profit = $count = 0;
                @endphp
                @foreach ($alldata as $item)
                    @php
                        $total_agent_price += $item->contact_amount;
                        $total_supplier_price += $item->payable_amount;
                        $total_profit += $item->profit;
                        $count++;
                    @endphp
                    <tr class="hover:bg-gray-50 border-b border-gray-200">
                        <td class="py-3 px-4">{{ (new DateTime($item->date))->format('d-m-Y') }}</td>
                        <td class="py-3 px-4">{{ $item->invoice }}</td>
                        <td class="py-3 px-4">{{ $item->type }}</td>
                        <td class="py-3 px-4">{{ $item->name }}</td>
                        <td class="py-3 px-4">{{ $item->passport_no }}</td>
                        <td class="py-3 px-4">{{ $item->country }}</td>

                        @if ($show_agent)
                            <td class="py-3 px-4">{{ $item->agent_name }}</td>
                            <td class="py-3 px-4 text-right">{{ number_format($item->contact_amount, 2) }}</td>
                        @endif

                        @if ($show_supplier)
                            <td class="py-3 px-4">{{ $item->supplier_name }}</td>
                            <td class="py-3 px-4 text-right">{{ number_format($item->payable_amount, 2) }}</td>
                        @endif

                        @if ($show_profit)
                            <td class="py-3 px-4 text-right">{{ number_format($item->profit, 2) }}</td>
                        @endif
                    </tr>
                @endforeach
                <tr class="bg-gray-50 font-semibold border-t-2 border-gray-300">
                    <td class="py-3 px-4">Total - {{ $count }}</td>
                    <td class="py-3 px-4"></td>
                    <td class="py-3 px-4"></td>
                    <td class="py-3 px-4"></td>
                    <td class="py-3 px-4"></td>
                    <td class="py-3 px-4"></td>

                    {{-- @if ($show_agent) --}}
                    <td class="py-3 px-4"></td>
                    <td class="py-3 px-4 text-right">{{ number_format($total_agent_price, 2) }}</td>
                    {{-- @endif --}}

                    @if ($show_supplier)
                        <td class="py-3 px-4"></td>
                        <td class="py-3 px-4 text-right">{{ number_format($total_supplier_price, 2) }}</td>
                    @endif

                    @if ($show_profit)
                        <td class="py-3 px-4 text-right">{{ number_format($total_profit, 2) }}</td>
                    @endif
                </tr>
            </tbody>
        </table>
    </div>
</div>
