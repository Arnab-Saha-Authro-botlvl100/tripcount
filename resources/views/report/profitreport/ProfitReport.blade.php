<div class="w-full mx-auto p-6">
    <!-- Header -->
    <div class="mb-8 text-center">
        <h1 class="text-3xl font-bold text-blue-800">{{ Auth::user()->name }}</h1>
        <p class="text-gray-600">{{ Auth::user()->address }}</p>
    </div>

    <!-- Report Title -->
    <div class="mb-10 text-center">
        <h2 class="text-2xl font-semibold text-gray-800">Net Profit Report</h2>
        <p class="text-gray-600">From: {{ $start_date }} To: {{ $end_date }}</p>
    </div>

    <div class="overflow-x-auto shadow-md rounded-lg">
        <table class="min-w-full bg-white border border-gray-200">
            <thead>
                <tr class="uppercase text-xs leading-tight bg-gray-100 text-gray-700">
                    <th class="px-4 py-2 text-md text-left">SL</th>
                    <th class="px-4 py-2 text-md text-left">Service Name</th>
                    <th class="px-4 py-2 text-md text-left">Number Of Sales</th>
                    <th class="px-4 py-2 text-md text-left">Buying Price</th>
                    <th class="px-4 py-2 text-md text-left">Selling Price</th>
                    <th class="px-4 py-2 text-md text-left">Profit</th>
                </tr>
            </thead>
            <tbody id="data" class="divide-y divide-gray-500 text-gray-700">
                @php
                    $count = $buying = $selling = $total = 0;
                @endphp
                @foreach ($typeData as $index => $data)
                    @php
                        $count += $data['count'];
                        $buying += $data['buying_price'];
                        $selling += $data['selling_price'];
                        $total += $data['profit'];
                    @endphp
                    <tr class="hover:bg-gray-50">
                        <td class="w-[10%] px-4 py-2 text-sm text-left">{{ $index + 1 }}</td>
                        <td class="w-[10%] px-4 py-2 text-sm text-left">{{ $data['name'] }}</td>
                        <td class="w-[10%] px-4 py-2 text-sm text-left">{{ $data['count'] }}</td>
                        <td class="w-[10%] px-4 py-2 text-sm text-left">{{ number_format($data['buying_price']) }}</td>
                        <td class="w-[10%] px-4 py-2 text-sm text-left">{{ number_format($data['selling_price']) }}</td>
                        <td class="w-[10%] px-4 py-2 text-sm text-left">{{ number_format($data['profit']) }}</td>

                    </tr>
                @endforeach

                <tr class="bold-row border-t-2 border-gray-600 bg-gray-300">
                    <td class="w-[10%] px-4 py-2 text-sm text-left">Total</td>

                    <td class="w-[10%] px-4 py-2 text-sm text-left"></td>

                    <td class="w-[10%] px-4 py-2 text-sm text-left">{{ $count }}</td>
                    <td class="w-[10%] px-4 py-2 text-sm text-left">{{ number_format($buying) }}</td>
                    <td class="w-[10%] px-4 py-2 text-sm text-left ">{{ number_format($selling) }}</td>
                    <td class="w-[10%] px-4 py-2 text-sm text-left ">{{ number_format($total) }}</td>
                </tr>



            </tbody>
        </table>

    </div>
