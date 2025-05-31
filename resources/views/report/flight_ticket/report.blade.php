<h2 class="text-center font-bold text-3xl my-2">Flight Report (Ticket)</h2>
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
            <th class="py-3 px-4 text-left border-b border-gray-200 font-medium">Booking Date</th>
            <th class="py-3 px-4 text-left border-b border-gray-200 font-medium">Ticket No</th>
            <th class="py-3 px-4 text-left border-b border-gray-200 font-medium">Passenger Name</th>
            <th class="py-3 px-4 text-left border-b border-gray-200 font-medium">Flight Date</th>
            <th class="py-3 px-4 text-left border-b border-gray-200 font-medium">Sector</th>
            <th class="py-3 px-4 text-left border-b border-gray-200 font-medium">Airlines</th>

            @if($show_agent)
                
                <th class="py-3 px-4 text-left border-b border-gray-200 font-medium">Agent Price</th>
            @endif

            @if($show_supplier)
                <th class="py-3 px-4 text-left border-b border-gray-200 font-medium">Supplier</th>
                <th class="py-3 px-4 text-left border-b border-gray-200 font-medium">Supplier Price</th>
            @endif

            @if($show_profit)
                <th class="py-3 px-4 text-left border-b border-gray-200 font-medium">Net Markup</th>
            @endif

            <th class="py-3 px-4 text-left border-b border-gray-200 font-medium">Balance Amount</th>
        </tr>
    </thead>
    <tbody>
        @php
            // Initialize totals for each field
            $total_agent_price = $total_supplier_price = $total_profit = $total_balance = $count = 0;
        @endphp

        {{-- Loop over grouped tickets (grouped by agent) --}}
        @foreach ($alldata as $agent_id => $tickets)
            @php
                // Fetch agent name using the agent ID (assuming it's the key)
                $agent = \App\Models\Agent::where('id', $agent_id)->value('name');
                $agent_openning_balance = \App\Models\Agent::where('id', $agent_id)->value('opening_balance');
                $agent_total_price = $agent_total_supplier_price = $agent_total_profit = $agent_total_balance =  $per_agent_count = 0;
            @endphp

            {{-- For each agent, display their name --}}
            <tr class="bg-gray-200">
                <td colspan="10" class="font-bold text-lg p-3">{{ $agent }}</td>
            </tr>

            {{-- Loop over tickets for the current agent --}}
          
            @foreach ($tickets as $data)
                @php
                    $supplier = \App\Models\Supplier::where('id', $data->supplier)->value('name');

                    // Update totals for the agent
                    $agent_total_price += $data->agent_price;
                    $agent_total_supplier_price += $data->supplier_price;
                    $agent_total_profit += $data->profit;
                    $agent_total_balance += $data->agent_price;
                    // $this_agent_amount += $data->agent_price;
                    // Update global totals
                    $total_agent_price += $data->agent_price;
                    $total_supplier_price += $data->supplier_price;
                    $total_profit += $data->profit;
                    $total_balance += $data->agent_price;

                    $count++;
                    $per_agent_count++;
                @endphp

                <tr class="hover:bg-gray-50 border-b border-gray-200">
                    <td class="py-3 px-4 font-small">{{ (new DateTime($data->invoice_date))->format('d-m-Y') }}</td>
                    <td class="py-3 px-4 font-small">{{ $data->ticket_no }}</td>
                    <td class="py-3 px-4 font-small">{{ $data->passenger }}</td>
                    <td class="py-3 px-4 font-small">{{ (new DateTime($data->flight_date))->format('d-m-Y') }}</td>
                    <td class="py-3 px-4 font-small">{{ $data->sector }}</td>
                    <td class="py-3 px-4 font-small">{{ $data->airline_name }}</td>

                    @if ($show_agent)
                        {{-- <td class="text-start py-3 px-4 font-small">{{ $agent }}</td> --}}
                        <td class="text-center py-3 px-4 font-small">{{ number_format($data->agent_price) }}</td>
                    @endif

                    @if ($show_supplier)
                        <td class="text-start py-3 px-4 font-small">{{ $supplier }}</td>
                        <td class="text-center py-3 px-4 font-small">{{ number_format($data->supplier_price) }}</td>
                    @endif

                    @if ($show_profit)
                        <td class="text-center py-3 px-4 font-small">{{ number_format($data->profit) }}</td>
                    @endif

                    <td class="py-3 px-4 font-small text-center">{{ number_format($agent_total_price) }}</td>  <!-- Display balance for each ticket -->
                </tr>
            @endforeach

            {{-- Agent totals --}}
            <tr class="bg-gray-100 w-full">
                <td colspan="" class="text-right font-bold">Agent Total - {{$per_agent_count}}</td>
                <td class="text-right font-bold"></td>
                {{-- <td></td> --}}
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                {{-- <td></td> --}}
                
                <td class="text-center py-3 px-4 font-small"><b>{{ number_format($agent_total_price) }}</b></td>
                @if ($show_supplier)
                    <td class="text-center py-3 px-4 font-small"><b>{{ number_format($agent_total_supplier_price) }}</b></td>
                @endif
                @if ($show_profit)
                    <td class="text-center py-3 px-4 font-small"><b>{{ number_format($agent_total_profit) }}</b></td>
                @endif
                <td class="text-center py-3 px-4 font-small"><b>{{ number_format($agent_total_balance) }}</b></td>
            </tr>
        @endforeach

        {{-- Global totals --}}
        <tr class="bg-gray-300">
            <td class="text-center py-3 px-4 font-small"><b>Total - {{ $count }}</b></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            {{-- <td></td> --}}
            
            @if ($show_agent)
                {{-- <td></td> --}}
                <td class="text-center py-3 px-4 font-small"><b>{{ number_format($total_agent_price) }}</b></td>
            @endif

            @if ($show_supplier)
                <td></td>
                <td class="text-center py-3 px-4 font-small"><b>{{ number_format($total_supplier_price) }}</b></td>
            @endif

            @if ($show_profit)
                <td class="text-center py-3 px-4 font-small"><b>{{ number_format($total_profit) }}</b></td>
            @endif

            <td class="text-center py-3 px-4 font-small"><b>{{ number_format($total_balance) }}</b></td>  <!-- Display total balance -->
        </tr>
    </tbody>
</table>

