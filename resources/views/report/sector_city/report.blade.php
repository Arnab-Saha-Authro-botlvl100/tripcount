<h2 class="text-center font-bold text-3xl my-2">Sector City Report</h2>
<div class="flex items-center justify-between mb-4">
    <div class="text-lg">
        <h2 class="font-semibold">Company Name: {{ Auth::user()->name }}</h2>
        <p><span class="font-semibold">Period Date:</span> {{ $start_date }} to {{ $end_date }}</p>
    </div>
    <div class="flex items-center">
        <!-- Placeholder for additional content -->
    </div>
</div>

<div class="overflow-x-auto shadow-md rounded-lg">
    <table class="min-w-full bg-white border border-gray-200">
        <thead>
            <tr class="uppercase text-xs leading-tight bg-gray-100 text-gray-700">
                <th class="py-3 px-4 text-left">SL</th>
                <th class="py-3 px-4 text-left">Ticket No</th>
                <th class="py-3 px-4 text-left">Passenger Name</th>
                <th class="py-3 px-4 text-left">Sector</th>
                <th class="py-3 px-4 text-left">GDS</th>
                <th class="py-3 px-4 text-left">Airlines</th>
                <th class="py-3 px-4 text-left">Sector No</th>
                <th class="py-3 px-4 text-left">Payable Amount</th>
            </tr>
        </thead>
        <tbody class="text-gray-700">
            @foreach ($alldata as $index => $data)
            <tr class="border-b border-gray-200 hover:bg-gray-50">
                <td class="py-3 px-4">{{ $index + 1 }}</td>
                <td class="py-3 px-4">{{ $data->ticket_code }}-{{ $data->ticket_no }}</td>
                <td class="py-3 px-4">{{ $data->passenger }}</td>
                <td class="py-3 px-4">{{ $data->sector }}</td>
                <td class="py-3 px-4">1G</td>
                <td class="py-3 px-4">{{ $data->airline_name }}</td>
                <td class="py-3 px-4">{{ $data->count }}</td>
                <td class="py-3 px-4">{{ number_format($data->supplier_price, 2) }}</td>
            </tr>
            @endforeach

            <tr class="bg-gray-100 font-semibold">
                <td class="py-3 px-4" colspan="4"></td>
                <td colspan="2" class="py-3 px-4 text-right">Total Segment:</td>
                <td class="py-3 px-4">{{ $totalCount }}</td>
                <td class="py-3 px-4">{{ number_format($totalSupplierPrice, 2) }}</td>
            </tr>
        </tbody>
    </table>
</div>