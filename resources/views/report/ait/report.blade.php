<h2 class="text-center font-bold text-3xl my-2">AIT Report</h2>
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

                <th class="py-3 px-4 text-left">Airlines</th>
                <th class="py-3 px-4 text-left">AIT Amount</th>
                <th class="py-3 px-4 text-left">Amount</th>
            </tr>
        </thead>
        <tbody class="text-gray-700">
            @foreach ($result as $index => $item)
                <tr class="border-b border-gray-200 hover:bg-gray-50">
                    <td class="py-3 px-4">{{ $index + 1 }}</td>
                    <td class="py-3 px-4">{{ $item->ticket_code }}-{{ $item->ticket_no }}</td>
                    <td class="py-3 px-4">{{ $item->airline_name }}</td>
                    <td class="py-3 px-4 ait_amount">{{ number_format($item->ait_amount) }}</td>

                    <td class="py-3 px-4 amount">{{ number_format($item->supplier_price, 2) }}</td>
                </tr>
            @endforeach

            <tr class="bg-gray-100 font-semibold">
                <td class="py-3 px-4"></td>
                <td colspan="2" class="py-3 px-4 text-right">Total:</td>
                <td class="py-3 px-4" id="total_ait"></td>
                <td class="py-3 px-4" id="total_amount"></td>
            </tr>
        </tbody>
    </table>
</div>

<script type="text/javascript">
    function calculateTotalAmount() {
        const amountElements = document.querySelectorAll(".amount");
        const ait_amountElements = document.querySelectorAll(".ait_amount");

        let totalAmount = 0;
        let totalAmount_ait = 0;

        // Helper function to parse numeric values
        const parseNumber = (text) => {
            const numericString = text.replace(/,/g, '') // Remove commas
                .replace(/[^\d.-]/g, ''); // Remove non-numeric chars
            return parseFloat(numericString) || 0; // Return 0 if NaN
        };

        // Calculate main amount total
        amountElements.forEach(element => {
            totalAmount += parseNumber(element.textContent);
        });

        // Calculate AIT amount total
        ait_amountElements.forEach(element => {
            totalAmount_ait += parseNumber(element.textContent);
        });

        // Format numbers with commas for display
        document.getElementById("total_amount").textContent =
            totalAmount.toLocaleString('en-US', {
                maximumFractionDigits: 2
            });

        document.getElementById("total_ait").textContent =
            totalAmount_ait.toLocaleString('en-US', {
                maximumFractionDigits: 2
            });

        // For debugging:
        // console.log("Total amount:", totalAmount);
        // console.log("Total AIT:", totalAmount_ait);
    }
    calculateTotalAmount();
</script>
