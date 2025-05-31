<h2 class="text-center font-bold text-3xl my-2">Sales Executive Report</h2>
<div class="flex items-center justify-between mb-2">
    <div class="text-lg">
        <h2 class="font-semibold">Company Name : {{ Auth::user()->name }}</h2>
        <p><span class="font-semibold">Period Date :</span> {{ $start_date }} to {{ $end_date }}</p>
    </div>
    <div class="flex items-center">
        <!-- Empty div for alignment -->
    </div>
</div>
{{-- 
@foreach ($groupedData as $stuff => $group)
    @php
        $total_agent_price = $total_supplier_price = $total_profit = $total_balance = $count = 0;
    @endphp

    <div style="margin-bottom: 20px;">
        <h2 class="text-start uppercase font-bold">{{ $stuff }}</h2>

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
                        @if ($show_agent)
                            <th class="py-3 px-4 text-left border-b border-gray-200 max-w-[150px]">Agent</th>
                            <th class="py-3 px-4 text-left border-b border-gray-200">Agent Amount</th>
                        @endif
                        @if ($show_supplier)
                            <th class="py-3 px-4 text-left border-b border-gray-200 max-w-[150px]">Supplier</th>
                            <th class="py-3 px-4 text-left border-b border-gray-200">Supplier Amount</th>
                        @endif
                        @if ($show_profit)
                            <th class="py-3 px-4 text-left border-b border-gray-200">Net Markup</th>
                        @endif
                        <th class="py-3 px-4 text-left border-b border-gray-200">Balance Amount</th>
                    </tr>
                </thead>
                <tbody class="divide-y-2">
                    @foreach ($group as $data)
                        @php
                            if ($data->stuff == $stuff) {
                                $count++;
                                $total_agent_price += $data->agent_price;
                                $total_supplier_price += $data->supplier_price;
                                $total_profit += $data->profit;
                                $total_balance += $data->agent_new_amount;
                            }
                        @endphp
                        <tr class="hover:bg-gray-50 border-b border-gray-200">
                            <td class="py-3 px-4">{{ (new DateTime($data->invoice_date))->format('d-m-Y') }}</td>
                            <td class="py-3 px-4">{{ $data->ticket_no }}</td>
                            <td class="py-3 px-4">{{ $data->passenger }}</td>
                            <td class="py-3 px-4">{{ (new DateTime($data->flight_date))->format('d-m-Y') }}</td>
                            <td class="py-3 px-4">{{ $data->sector }}</td>
                            <td class="py-3 px-4">{{ $data->airline_name }}</td>
                            @if ($show_agent)
                                <td class="py-3 px-4 max-w-[150px]">{{  $agents[$data->agent] ?? 'N/A' }}</td>
                                <td class="text-center">{{ number_format($data->agent_price, 2) }}</td>
                            @endif
                            @if ($show_supplier)
                                <td class="py-3 px-4 max-w-[150px]">
                                    {{ $suppliers[$data->supplier]->name }} - {{ $suppliers[$data->supplier]->company }}
                                </td>
                                <td class="text-center">{{ number_format($data->supplier_price, 2) }}</td>
                            @endif
                            @if ($show_profit)
                                <td class="text-center">{{ number_format($data->profit, 2) }}</td>
                            @endif
                            <td class="py-3 px-4 text-center">{{ number_format($data->agent_new_amount, 2) }}</td>
                        </tr>
                    @endforeach
                    <tr class="bg-gray-50 font-semibold border-t-2 border-gray-300">
                        <td class="py-3 px-4"><b>Total Ticket: {{ $count }}</b></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        @if ($show_agent)
                            <td></td>
                            <td class="text-center py-2"><b>{{ number_format($total_agent_price, 2) }}</b></td>
                        @endif
                        @if ($show_supplier)
                            <td></td>
                            <td class="text-center py-2"><b>{{ number_format($total_supplier_price, 2) }}</b></td>
                        @endif
                        @if ($show_profit)
                            <td class="text-center py-2"><b>{{ number_format($total_profit, 2) }}</b></td>
                        @endif
                        <td class="text-center py-2"><b>{{ number_format($total_balance, 2) }}</b></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
@endforeach --}}

@foreach ($groupedData as $stuff => $group)
    @php
        $total_agent_price = $total_supplier_price = $total_profit = $total_balance = $count = 0;
    @endphp

    <!-- Start of Stuff Group -->
    <div class="stuff-group mb-8 p-4 bg-gray-50 rounded-lg shadow-sm">
        <!-- Group Header -->
        <div class="group-header mb-4">
            <h2 class="text-xl font-bold text-gray-800 uppercase border-b-2 border-blue-500 pb-2">
                {{ $stuff }}
            </h2>
        </div>

        <!-- Group Table -->
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
                        @if ($show_agent)
                            <th class="py-3 px-4 text-left border-b border-gray-200 max-w-[150px]">Agent</th>
                            <th class="py-3 px-4 text-left border-b border-gray-200">Agent Amount</th>
                        @endif
                        @if ($show_supplier)
                            <th class="py-3 px-4 text-left border-b border-gray-200 max-w-[150px]">Supplier</th>
                            <th class="py-3 px-4 text-left border-b border-gray-200">Supplier Amount</th>
                        @endif
                        @if ($show_profit)
                            <th class="py-3 px-4 text-left border-b border-gray-200">Net Markup</th>
                        @endif
                        <th class="py-3 px-4 text-left border-b border-gray-200">Balance Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($group as $data)
                        @php
                            $count++;
                            $total_agent_price += $data->agent_price;
                            $total_supplier_price += $data->supplier_price;
                            $total_profit += $data->profit;
                            $total_balance += $data->agent_new_amount;
                        @endphp
                        <tr class="hover:bg-gray-50 border-b border-gray-200">
                            <td class="py-3 px-4">{{ (new DateTime($data->invoice_date))->format('d-m-Y') }}</td>
                            <td class="py-3 px-4">{{ $data->ticket_no }}</td>
                            <td class="py-3 px-4">{{ $data->passenger }}</td>
                            <td class="py-3 px-4">{{ (new DateTime($data->flight_date))->format('d-m-Y') }}</td>
                            <td class="py-3 px-4">{{ $data->sector }}</td>
                            <td class="py-3 px-4">{{ $data->airline_name }}</td>
                            @if ($show_agent)
                                <td class="py-3 px-4 max-w-[150px]">{{ $agents[$data->agent] ?? 'N/A' }}</td>
                                <td class="text-center">{{ number_format($data->agent_price, 2) }}</td>
                            @endif
                            @if ($show_supplier)
                                <td class="py-3 px-4 max-w-[150px]">
                                    @isset($suppliers[$data->supplier])
                                        {{ $suppliers[$data->supplier]->name }} - {{ $suppliers[$data->supplier]->company }}
                                    @else
                                        N/A
                                    @endisset
                                </td>
                                <td class="text-center">{{ number_format($data->supplier_price, 2) }}</td>
                            @endif
                            @if ($show_profit)
                                <td class="text-center">{{ number_format($data->profit, 2) }}</td>
                            @endif
                            <td class="py-3 px-4 text-center">{{ number_format($data->agent_new_amount, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
                
                <!-- Group Footer with Totals -->
                <tfoot>
                    <tr class="bg-gray-100 font-semibold">
                        <td class="py-3 px-4"><b>Total: {{ $count }}</b></td>
                        <td colspan="5"></td>
                        @if ($show_agent)
                            <td class="text-right pr-4"><b>Total:</b></td>
                            <td class="text-center py-2"><b>{{ number_format($total_agent_price, 2) }}</b></td>
                        @endif
                        @if ($show_supplier)
                            <td class="text-right pr-4"><b>Total:</b></td>
                            <td class="text-center py-2"><b>{{ number_format($total_supplier_price, 2) }}</b></td>
                        @endif
                        @if ($show_profit)
                            <td class="text-center py-2"><b>{{ number_format($total_profit, 2) }}</b></td>
                        @endif
                        <td class="text-center py-2"><b>{{ number_format($total_balance, 2) }}</b></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    <!-- End of Stuff Group -->
    
    <!-- Add page break for printing -->
    <div class="page-break"></div>
@endforeach

<style>
    .stuff-group {
        page-break-inside: avoid;
    }
    .page-break {
        page-break-after: always;
    }
    @media print {
        .stuff-group {
            border: 1px solid #eee;
            margin-bottom: 20px;
        }
    }
</style>