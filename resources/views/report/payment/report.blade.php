<h2 class="text-center font-bold text-3xl my-2">Segment Report</h2>

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
                <th class="px-4 py-2 text-md text-left">Date</th>
                <th class="px-4 py-2 text-md text-left">Voucher No</th>
                <th class="px-4 py-2 text-md text-left">Payment From</th>
                <th class="px-4 py-2 text-md text-left">Payment Mode</th>
                <th class="px-4 py-2 text-md text-left">Narration</th>
                <th class="px-4 py-2 text-md text-left">Amount</th>
                <th class="px-4 py-2 text-md text-center">Action</th>
            </tr>
        </thead>
        <tbody id="data" class="divide-y divide-gray-500 text-gray-700">
            @foreach ($result as $key => $item)
                @php
                    $printUrl = url('/payment_voucher', ['id' => $item->id]);
                    $deleteUrl = url('/delete_payment', ['id' => $item->id]);
                @endphp

                <tr class="hover:bg-gray-50">
                    <td class="w-[10%] px-4 py-2 text-sm text-left">{{ $item->date }}</td>
                    <td class="w-[11%] px-4 py-2 text-sm text-left">{{ $item->invoice }}</td>
                    <td class="w-[15%] px-4 py-2 text-sm text-left">{{ $item->name }}</td>
                    <td class="w-[28%] px-4 py-2 text-sm text-left">{{ $item->method_name }}</td>
                    <td class="w-[12%] px-4 py-2 text-sm text-left">{{ $item->remark }}</td>
                    <td class="w-[12%] px-4 py-2 text-sm text-left amount">{{ number_format($item->amount, 2) }}</td>
                    <td class="px-2 py-1 text-center flex justify-center gap-2">
                        <a href="{{ $printUrl }}" class="text-black rounded-md text-md hover:text-blue-600">
                            <i class="fa fa-fw fa-print text-md"></i>
                        </a>
                        <button type="button" class="deleteBtn text-black rounded-md text-md hover:text-red-600"
                            data-url="{{ $deleteUrl }}">
                            <i class="fa fa-trash fa-fw text-md"></i>
                        </button>
                    </td>
                </tr>
            @endforeach
            <tr class="bg-gray-50 font-semibold">
                <td class="px-4 py-2 text-left" colspan="5">Total Amount</td>
                <td class="px-4 py-2 text-left" id="total_amount"></td>
                <td class="px-4 py-2 text-left"></td>
            </tr>
        </tbody>
    </table>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        // Delete button confirmation
        $(".deleteBtn").click(function(event) {
            event.preventDefault();
            const deleteUrl = $(this).data("url");
            
            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!"
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = deleteUrl;
                }
            });
        });

        // Calculate and display total amount
        function calculateTotalAmount() {
            const amountElements = document.querySelectorAll(".amount");
            let totalAmount = 0;

            amountElements.forEach(element => {
                const amount = parseFloat(element.textContent.replace(/,/g, '')) || 0;
                totalAmount += amount;
            });

            document.getElementById("total_amount").textContent = 
                totalAmount.toLocaleString('en-US', { 
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2 
                });
        }

        calculateTotalAmount();
    });
</script>