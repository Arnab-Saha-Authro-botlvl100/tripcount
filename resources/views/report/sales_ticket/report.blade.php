<h2 class="text-center font-bold text-3xl my-2">Sales Report (Ticket)</h2>
<div class="flex items-center justify-between mb-2">
    <div class="text-lg">
        <h2 class="font-semibold">Company Name: {{ Auth::user()->name }}</h2>
        <p><span class="font-semibold">Period Date:</span> {{ $start_date }} to {{ $end_date }}</p>
    </div>
    <div class="flex items-center">
        <!-- You can add any additional content here -->
    </div>
</div>

<div class="overflow-x-auto">
    <table class="min-w-full bg-white border border-gray-200">
        <thead>
            <tr class="bg-gray-100 text-gray-700 uppercase text-xs leading-tight">
                <th class="py-3 px-4 text-left border-b border-gray-200">Booking Date</th>
                <th class="py-3 px-4 text-left border-b border-gray-200">Ticket No</th>
                <th class="py-3 px-4 text-left border-b border-gray-200">Passenger Name</th>
                <th class="py-3 px-4 text-left border-b border-gray-200">Flight Date</th>
                <th class="py-3 px-4 text-left border-b border-gray-200">Sector</th>
                <th class="py-3 px-4 text-left border-b border-gray-200">Airlines</th>

                {{-- @if($show_agent) --}}
                    <th class="py-3 px-4 text-left border-b border-gray-200">Agent</th>
                    <th class="py-3 px-4 text-left border-b border-gray-200">Agent Price</th>
                {{-- @endif --}}

                @if($show_supplier)
                    <th class="py-3 px-4 text-left border-b border-gray-200">Supplier</th>
                    <th class="py-3 px-4 text-left border-b border-gray-200">Supplier Price</th>
                @endif

                @if($show_profit)
                    <th class="py-3 px-4 text-left border-b border-gray-200">Net Markup</th>
                @endif

                <th class="py-3 px-4 text-left border-b border-gray-200">Balance Amount</th>
            </tr>
        </thead>
        <tbody class="text-gray-600 text-sm">
            @php
                $total_agent_price = $total_supplier_price = $total_profit = $count = 0;
            @endphp
            
            @foreach ($alldata as $ticket)
                @php
                    $total_agent_price += $ticket->agent_price;
                    $total_supplier_price += $ticket->supplier_price;
                    $total_profit += $ticket->profit;
                    $count++;
                @endphp
                <tr class="hover:bg-gray-50 border-b border-gray-200"
                    data-refund="{{ $ticket->is_refund }}"
                    data-void="{{ $ticket->is_void }}"
                    data-reissue="{{ $ticket->is_reissue }}"
                    data-reissued-new="{{ $ticket->reissued_new_ticket == 0 ? '0' : '1' }}">
                    <td class="py-3 px-4">{{ (new \DateTime($ticket->invoice_date))->format('d-m-Y') }}</td>
                    <td class="py-3 px-4 font-medium">{{ $ticket->ticket_no }}</td>
                    <td class="py-3 px-4">{{ $ticket->passenger }}</td>
                    <td class="py-3 px-4">{{ (new \DateTime($ticket->flight_date))->format('d-m-Y') }}</td>
                    <td class="py-3 px-4">{{ $ticket->sector }}</td>
                    <td class="py-3 px-4">{{ $ticket->airline_name }}</td>

                    {{-- @if ($show_agent) --}}
                        <td class="py-3 px-4">{{ $ticket->agent_name }}</td>
                        <td class="py-3 px-4 text-right">{{ number_format($ticket->agent_price, 2) }}</td>
                    {{-- @endif --}}

                    @if ($show_supplier)
                        <td class="py-3 px-4">{{ $ticket->supplier_name }}</td>
                        <td class="py-3 px-4 text-right">{{ number_format($ticket->supplier_price, 2) }}</td>
                    @endif

                    @if ($show_profit)
                        <td class="py-3 px-4 text-right">{{ number_format($ticket->profit, 2) }}</td>
                    @endif
                    <td class="py-3 px-4 text-right">{{ number_format($total_agent_price, 2) }}</td>
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

                <td class="py-3 px-4 text-right">{{ number_format($total_agent_price, 2) }}</td>
            </tr>
        </tbody>
    </table>
</div>