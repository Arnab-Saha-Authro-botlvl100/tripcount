<x-app-layout>
    <div class="main-content" id="main-content">
        <div class="content">
            <main class=" mx-auto w-[95%] ">

                <div class=" px-7 py-3 flex flex-col gap-y-4 mb-3 shadow-2xl">
                    <h2 class="font-bold text-2xl ">Due Reminder</h2>

                    <div class="flex items-center gap-4">
                        <p class="text-black text-xl font-semibold">Type</p>
                        <div class="flex items-center">
                            <input id="all" type="radio" value="all" name="customer-type"
                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500" checked />
                            <label for="all" class="ms-2 text-md font-medium text-green-700">All Customers</label>
                        </div>
                        <div class="flex items-center">
                            <input id="agent" type="radio" value="agent" name="customer-type"
                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500" />
                            <label for="agent" class="ms-2 text-md font-medium text-green-700">Agent</label>
                        </div>
                        <div class="flex items-center">
                            <input id="supplier" type="radio" value="supplier" name="customer-type"
                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500" />
                            <label for="supplier" class="ms-2 text-md font-medium text-green-700">Supplier</label>
                        </div>
                    </div>

                </div>
                <div class="overflow-x-auto shadow-2xl"> <!-- Wrapper for horizontal scrolling -->
                    <table class="min-w-full divide-y divide-gray-400" id="due_reminder_table">
                        <thead class="bg-[#0E7490] text-white">
                            <tr>
                                <th class="px-4 py-2 text-left text-sm sm:text-md">SN</th>
                                <th class="px-4 py-2 text-left text-sm sm:text-md">Customer</th>
                                {{-- <th class="px-4 py-2 text-left text-sm sm:text-md hidden sm:table-cell">Email</th> --}}
                                <th class="px-4 py-2 text-left text-sm sm:text-md">Mobile</th>
                                <th class="px-4 py-2 text-left text-sm sm:text-md hidden md:table-cell">Last Date</th>
                                <th class="px-4 py-2 text-center text-sm sm:text-md">Last Payment</th>
                                <th class="px-4 py-2 text-center text-sm sm:text-md">Total Due</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-400">
                            @foreach ($filteredTransactionsWithNames as $index => $data)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-2 text-sm">{{ $index + 1 }}</td>
                                    <td class="px-4 py-2 text-sm font-medium text-gray-900">
                                        {!! $data['agent_supplier_name'] !!}
                                        <span class="block sm:inline">{{ $data['agent_supplier_company'] }}</span>
                                    </td>
                                    {{-- <td class="px-4 py-2 text-sm hidden sm:table-cell">
                                        {{ $data['agent_supplier_email'] }}</td> --}}
                                    <td class="px-4 py-2 text-sm">
                                        <a href="tel:{{ $data['agent_supplier_phone'] }}" class="hover:text-blue-600">
                                            {{ $data['agent_supplier_phone'] }}
                                        </a>
                                    </td>
                                    <td class="px-4 py-2 text-sm hidden md:table-cell">{{ $data['date'] }}</td>
                                    <td class="px-4 py-2 text-sm text-center font-medium text-green-600">
                                        {{ $data['amount'] }}
                                    </td>
                                    <td class="px-4 py-2 text-sm text-center font-medium text-red-600">
                                        {{ $data['due_amount'] }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </main>
        </div>
    </div>



    <script src="https://unpkg.com/flowbite@1.4.0/dist/flowbite.js"></script>


    <script type="text/javascript">
      
        // Initialize with all customers shown
        document.addEventListener('DOMContentLoaded', function() {
            // Set the "All Customers" radio button as checked by default
            document.getElementById('all').checked = true;

            // Add event listeners to all radio buttons
            document.querySelectorAll('input[name="customer-type"]').forEach(radio => {
                radio.addEventListener('change', function() {
                    filter_table(this.value);
                });
            });
        });

        // Filter table function
        function filter_table(type) {
            const table = document.getElementById('due_reminder_table');
            const rows = table.querySelectorAll('tbody tr');

            rows.forEach(row => {
                // Get the company type from the second cell (Customer column)
                const companySpan = row.querySelector('td:nth-child(2) span');
                if (!companySpan) return;

                const companyType = companySpan.textContent.trim().toLowerCase();

                // Show/hide rows based on filter
                if (type === 'all') {
                    row.style.display = '';
                } else {
                    row.style.display = companyType.includes(type) ? '' : 'none';
                }
            });
        }
    </script>
</x-app-layout>
