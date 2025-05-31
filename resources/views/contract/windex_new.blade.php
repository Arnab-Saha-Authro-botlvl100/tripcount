<x-app-layout>
    @if (session('employee'))
        @php
            $employee = session('employee');
            // dd($employee['permission']);
            $permissionString = $employee['permission'];
            $permissionsArray = explode(',', $permissionString);
            $role = $employee['role'];
            // dd($role, $employee);
        @endphp
    @else
        @php
            $permissionsArray = ['entry', 'edit', 'delete', 'print', 'view'];
            $role = 'admin';
        @endphp
    @endif


    <div class="max-w-5xl mx-auto px-4 py-8">
        <!-- Form Section -->
        <div class="bg-white shadow-md rounded-lg p-6 mb-10">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Create New Contract</h2>
            <form action="{{ route('contracts.store') }}" method="POST" class="space-y-5">
                @csrf
                <div class="mb-4">
                    <label for="agent_id" class="block text-sm font-medium text-gray-700 mb-1">Agent</label>
                    <select name="agent_id" id="agent_id"
                        class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @foreach ($agents as $agent)
                            <option value="{{ $agent->id }}">{{ $agent->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-4">
                    <label for="total_amount" class="block text-sm font-medium text-gray-700 mb-1">Total Amount</label>
                    <input type="number" name="total_amount" id="total_amount" step="0.01"
                        class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        required>
                </div>

                <div class="mb-4">
                    <label for="contract_date" class="block text-sm font-medium text-gray-700 mb-1">Date</label>
                    <input type="date" name="contract_date" id="contract_date"
                        class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        required>
                </div>

                <div class="mb-4">
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                    <textarea name="notes" id="notes" rows="3"
                        class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                </div>

                <div class="pt-4">
                    <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2 rounded shadow-md transition">
                        Create Contract
                    </button>
                </div>
            </form>
        </div>

        <!-- Table Section -->
        <div class="bg-white shadow-md rounded-lg p-6">
            <h3 class="text-xl font-semibold text-gray-800 mb-4">All Contracts</h3>
            <div class="overflow-x-auto">
                <table id="contracts-table" class="min-w-full divide-y divide-gray-200 text-sm text-left">
                    <thead class="bg-gray-100 text-gray-700">
                        <tr>
                            <th class="px-4 py-2">SL</th>
                            <th class="px-4 py-2">Agent</th>
                            <th class="px-4 py-2">Total Amount</th>
                            <th class="px-4 py-2">Date</th>
                            <th class="px-4 py-2">Note</th>
                            <th class="px-4 py-2 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach ($contracts as $key => $contract)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-2">{{ $key + 1 }}</td>
                                <td class="px-4 py-2">{{ $contract->agent_name ?? 'N/A' }}</td>
                                <td class="px-4 py-2">৳{{ number_format($contract->total_amount, 2) }}</td>
                                <td class="px-4 py-2">{{ $contract->contract_date }}</td>
                                <td class="px-4 py-2">{{ $contract->notes }}</td>
                                <td class="px-4 py-2 text-center">
                                    <div class="flex justify-center space-x-2">
                                        <a href="#" class="text-blue-600 hover:text-blue-800 font-medium">View</a>
                                        {{-- <button 
                                            @click="openModal({ 
                                                id: {{ $contract->id }}, 
                                                total_amount: {{ $contract->total_amount }}, 
                                                notes: @js($contract->notes) 
                                            })"
                                            class="text-blue-600 hover:underline text-sm font-medium">
                                            Edit
                                        </button> --}}
                                        {{-- @php
                                                dd($contract->id);
                                        @endphp --}}
                                        <button
                                            onclick="openModal('{{ $contract->id }}', '{{ $contract->total_amount }}', '{{ $contract->notes }}')"
                                            class="text-blue-600 hover:underline text-sm font-medium">
                                            Edit
                                        </button>


                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div id="contractModal"
        class="fixed inset-0 bg-gray-800 bg-opacity-50 hidden flex items-center justify-center z-50 overflow-y-auto">
        <div class="bg-white max-w-[60vh]  max-h-[90vh] rounded-lg shadow-lg p-6 relative overflow-hidden">

            <!-- Close Button -->
            <button onclick="document.getElementById('contractModal').classList.add('hidden')"
                class="absolute top-2 right-2 text-gray-500 hover:text-gray-700 text-2xl z-10">&times;</button>

            <!-- Scrollable Body -->
            <div class="overflow-y-auto max-h-[75vh] pr-2">
                <h2 class="text-2xl font-semibold mb-6 text-gray-800">Edit Contract</h2>

                <!-- Hidden ID field for editing the contract -->

                <form action="{{ route('contracts.update') }}" method="POST">
                    @csrf
                    {{-- @method('PUT') --}}

                    <!-- Total Amount -->
                    <input type="hidden" id="contract_id" name="contract_id">

                    <div class="mb-4">
                        <label for="total_amount" class="block text-sm font-medium text-gray-700 mb-1">Total
                            Amount</label>
                        <input type="number" name="total_amount" id="total_amount_edit" step="0.01" required
                            class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <!-- Notes -->
                    <div class="mb-4">
                        <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                        <textarea name="notes" id="notes_edit" rows="3"
                            class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                    </div>

                    <!-- Service Breakdown -->
                    <h3 class="text-lg font-medium text-gray-800 mt-6 mb-4">Service Breakdown</h3>
                    <div id="serviceContainer" class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        @foreach ($types as $type)
                            <div
                                class="flex flex-col bg-gray-300 shadow-sm rounded-lg p-4 border border-gray-200 hover:shadow-lg transition duration-200">
                                <div class="flex items-center space-x-3 mb-4">
                                    {{-- <input type="checkbox" name="services_checked[]" value="{{ $type }}" id="service_checkbox_{{ $type }}" class="mr-2 h-5 w-5 text-blue-600 focus:ring-blue-500"> --}}
                                    <label for="service_checkbox_{{ $type }}"
                                        class="text-sm font-medium text-gray-700">{{ $type->name }}</label>
                                </div>
                                <div class="flex flex-col space-y-3">
                                    {{-- <label for="amount_{{ $type }}" class="block text-sm font-medium text-gray-700">Amount</label> --}}
                                    <input type="number" name="services_amount[{{ $type }}]"
                                        id="amount_{{ $type }}" step="0.01" placeholder="amount"
                                        class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Submit Button -->
                    <div class="mt-6 text-right">
                        <button type="submit"
                            class="bg-blue-600 text-white px-5 py-2 rounded hover:bg-blue-700 transition duration-200">
                            Update Contract
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>



    <script>
        function openModal(id, totalAmount, notes) {
            // Set the values in the modal fields
            // console.log(id);
            document.getElementById('contract_id').value = id;
            document.getElementById('total_amount_edit').value = totalAmount;
            document.getElementById('notes_edit').value = notes;

            // Show the modal
            document.getElementById('contractModal').classList.remove('hidden');
        }

        function addServiceField() {
            const container = document.getElementById('serviceContainer');
            const newServiceField = document.createElement('div');
            newServiceField.classList.add('flex', 'items-center', 'space-x-4', 'mt-4');
            newServiceField.innerHTML = `
            <div class="flex-1">
                <label class="block text-sm font-medium text-gray-700 mb-1">New Service Type</label>
                <select name="services[new_service][]" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Select Service Type</option>
                    <!-- You can dynamically add more options here if needed -->
                    <option value="custom_service">Custom Service</option>
                </select>
            </div>
            <div class="flex-1">
                <label class="block text-sm font-medium text-gray-700 mb-1">Amount</label>
                <input type="number" name="services_amount[new_service][]" step="0.01"
                       class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
        `;
            container.appendChild(newServiceField);
        }
    </script>


    <script>
        function capitalizeFirstLetter(string) {
            return string.charAt(0).toUpperCase() + string.slice(1);
        }
        // Modify your toggle functions to accept a second parameter for initialization
        function toggleServiceDetailsWakala(checkbox, isInitializing = false, initialData = null) {
            // console.log(initialData);
            if (typeof checkbox === 'object' && checkbox.detail) {
                // Called via event
                const {
                    isInitializing: init,
                    initialData: data
                } = checkbox.detail;
                isInitializing = init;
                initialData = data;
                checkbox = document.getElementById('type_wakala'); // Get the actual checkbox
            }

            // console.log("Toggle received:", { isInitializing, initialData });
            const serviceId = 'wakala';
            const serviceName = 'Wakala Service';
            const container = document.getElementById('serviceDetailsContainer');
            const existingDiv = document.getElementById(`service_${serviceId}`);

            // Handle uncheck case
            if (!checkbox.checked) {
                if (existingDiv) existingDiv.remove();
                return;
            }

            // Remove existing div if present (we'll recreate it)
            if (existingDiv) existingDiv.remove();

            // Create new div with fields
            const wakalaDiv = document.createElement('div');
            wakalaDiv.id = `service_${serviceId}`;
            wakalaDiv.className = 'p-4 border rounded-lg bg-gray-50 mb-4';

            // Generate the HTML content
            wakalaDiv.innerHTML = `
                <div class="flex justify-between items-center mb-4">
                    <h4 class="text-lg font-semibold text-gray-800">${serviceName}</h4>
                    <button type="button" onclick="removeServiceDetail('${serviceId}')" 
                            class="text-red-500 hover:text-red-700 focus:outline-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Column 1 -->
                    <div>
                        <label for="wakala_visa_no" class="block text-sm font-medium text-gray-700 mb-1">Visa No</label>
                        <input type="text" id="wakala_visa_no" name="wakala_visa_no" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                            value="${initialData ? (initialData.wakala_visa_no || '') : ''}">
                    </div>
                    
                    <div>
                        <label for="wakala_id_no" class="block text-sm font-medium text-gray-700 mb-1">ID No</label>
                        <input type="text" id="wakala_id_no" name="wakala_id_no" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                            value="${initialData ? (initialData.wakala_id_no || '') : ''}">
                    </div>
                    
                    <div>
                        <label for="wakala_buying_price" class="block text-sm font-medium text-gray-700 mb-1">Buying Price</label>
                        <input type="number" step="0.01" id="wakala_buying_price" name="wakala_buying_price" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 calculate-total"
                            placeholder="0.00"
                            value="${initialData ? (initialData.wakala_buying_price || '') : ''}">
                    </div>
                    
                    <!-- Column 2 -->
                    <div>
                        <label for="wakala_multi_currency" class="block text-sm font-medium text-gray-700 mb-1">Multi-Currency Rate</label>
                        <input type="number" step="0.0001" id="wakala_multi_currency" name="wakala_multi_currency" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 calculate-total"
                            placeholder="1.0000" 
                            value="${initialData ? (initialData.wakala_multi_currency || '1.0000') : '1.0000'}">
                    </div>
                    
                    <div>
                        <label for="wakala_total_price" class="block text-sm font-medium text-gray-700 mb-1">Total Price</label>
                        <input type="number" step="0.01" id="wakala_total_price" name="wakala_total_price" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm bg-gray-100 focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                            readonly
                            value="${initialData ? (initialData.amount || '') : ''}">
                    </div>
                    
                    <div>
                        <label for="wakala_supplier" class="block text-sm font-medium text-gray-700 mb-1">Supplier</label>
                        <select id="wakala_supplier" name="wakala_supplier" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Select Supplier</option>
                            ${generateSupplierOptions('wakala', initialData ? `${initialData.agent_supplier}_${initialData.supplier}` : '')}
                        </select>
                    </div>
                    
                    <div>
                        <label for="wakala_sales_by" class="block text-sm font-medium text-gray-700 mb-1">Sales By</label>
                        <input type="text" id="wakala_sales_by" name="wakala_sales_by" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                            value="${initialData ? (initialData.wakala_sales_by || '') : ''}">
                    </div>
                
                    
                    <div>
                        <label for="wakala_date" class="block text-sm font-medium text-gray-700 mb-1">Date</label>
                        <input type="date" id="wakala_date" name="wakala_date" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                            value="${initialData ? (initialData.wakala_date || '') : ''}">
                    </div>
                
                    
                    <div class="md:col-span-2">
                        <label for="wakala_note" class="block text-sm font-medium text-gray-700 mb-1">Note</label>
                        <textarea id="wakala_note" name="wakala_note" rows="2"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none
                                focus:ring-blue-500 focus:border-blue-500">${initialData ? (initialData.wakala_note || '') : ''}</textarea>
                    </div>
                </div>
            `;

            container.appendChild(wakalaDiv);

            document.getElementById('wakala_buying_price').addEventListener('input', calculateWakalaTotal);
            document.getElementById('wakala_multi_currency').addEventListener('input', calculateWakalaTotal);

            // Calculate total if we have initial data
            if (initialData) {
                calculateWakalaTotal();
            }
        }

        function toggleServiceDetailsTicket(checkbox, isInitializing = false, initialData = null) {
            let actualCheckbox = checkbox;
            let actualInitializing = isInitializing;
            let actualData = initialData;

            if (checkbox && checkbox.detail) {
                actualCheckbox = checkbox.target;
                actualInitializing = checkbox.detail.isInitializing;
                actualData = checkbox.detail.initialData;
            }


            const serviceId = 'ticket';
            const serviceName = 'Ticket Service'; // Fixed name or can be dynamic
            const container = document.getElementById('serviceDetailsContainer');

            const existingDiv = document.getElementById(`service_${serviceId}`);
            if (existingDiv) existingDiv.remove();

            if (!actualCheckbox.checked) return;

            const ticketDiv = document.createElement('div');
            ticketDiv.id = `service_${serviceId}`;
            ticketDiv.className = 'p-4 border rounded-lg bg-gray-50 mb-4';

            ticketDiv.innerHTML = `
                <div class="flex justify-between items-center mb-4">
                    <h4 class="text-lg font-semibold text-gray-800">${serviceName}</h4>
                    <button type="button" onclick="removeServiceDetail('${serviceId}')" 
                            class="text-red-500 hover:text-red-700 focus:outline-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Column 1 -->
                    <div>
                        <label for="ticket_invoice_date" class="block text-sm font-medium text-gray-700 mb-1">Invoice Date</label>
                        <input type="date" id="ticket_invoice_date" name="ticket_invoice_date" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                            value="${initialData ? (initialData.ticket_invoice_date || '') : ''}">
                    </div>
                    
                    <div>
                        <label for="ticket_travel_date" class="block text-sm font-medium text-gray-700 mb-1">Travel Date</label>
                        <input type="date" id="ticket_travel_date" name="ticket_travel_date" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                            value="${initialData ? (initialData.ticket_travel_date || '') : ''}">
                    </div>
                    
                    <div>
                        <label for="ticket_sector" class="block text-sm font-medium text-gray-700 mb-1">Sector</label>
                        <input type="text" id="ticket_sector" name="ticket_sector" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                            value="${initialData ? (initialData.ticket_sector || '') : ''}">
                    </div>
                    
                    <!-- Column 2 -->
                    <div>
                        <label for="ticket_number" class="block text-sm font-medium text-gray-700 mb-1">Ticket Number</label>
                        <input type="text" id="ticket_number" name="ticket_number" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                            value="${initialData ? (initialData.ticket_number || '') : ''}">
                    </div>
                    
                    <div>
                        <label for="ticket_passenger_name" class="block text-sm font-medium text-gray-700 mb-1">Passenger Name</label>
                        <input type="text" id="ticket_passenger_name" name="ticket_passenger_name" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                            value="${initialData ? (initialData.ticket_passenger_name || '') : ''}">
                    </div>
                    
                    <div>
                        <label for="ticket_airline" class="block text-sm font-medium text-gray-700 mb-1">Airline</label>
                        <input type="text" id="ticket_airline" name="ticket_airline" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                            value="${initialData ? (initialData.ticket_airline || '') : ''}">
                    </div>
                    
                    <div>
                        <label for="ticket_selling_price" class="block text-sm font-medium text-gray-700 mb-1">Selling Price</label>
                        <input type="number" step="0.01" id="ticket_selling_price" name="ticket_selling_price" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                            value="${initialData ? (initialData.amount || '') : ''}">
                    </div>
                    
                    <div>
                        <label for="ticket_supplier" class="block text-sm font-medium text-gray-700 mb-1">Supplier</label>
                        <select id="ticket_supplier" name="ticket_supplier" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Select Supplier</option>
                            ${generateSupplierOptions('ticket', initialData ? `${initialData.agent_supplier}_${initialData.supplier}` : '')}
                        </select>
                    </div>
                    
                    <div class="md:col-span-2">
                        <label for="ticket_note" class="block text-sm font-medium text-gray-700 mb-1">Note</label>
                        <textarea id="ticket_note" name="ticket_note" rows="2"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm 
                                focus:outline-none focus:ring-blue-500 focus:border-blue-500">${initialData ? (initialData.ticket_note || '') : ''}</textarea>
                    </div>
                </div>
            `;

            container.appendChild(ticketDiv);
        }


        function populateExtraServiceFields(serviceType, data) {
            // console.log("Populating:", serviceType, data);
            const checkbox = document.getElementById(`type_${serviceType}`);
            if (!checkbox) {
                console.error("Checkbox not found for type:", serviceType);
                return;
            }

            checkbox.checked = true;

            const funcName = `toggleServiceDetails${capitalizeFirstLetter(serviceType)}`;
            // console.log("Function to call:", funcName);

            if (typeof window[funcName] !== 'function') {
                console.error("Function not found in window scope:", funcName);
                return;
            }

            const event = {
                target: checkbox,
                detail: {
                    isInitializing: true,
                    initialData: data
                }
            };

            try {

                window[funcName](event);

                window[funcName](checkbox, true, data);

                // console.log("Function called successfully");
            } catch (e) {
                console.error("Error calling function:", e);
            }
        }

        function openModal(id, totalAmount, notes) {
            // Set basic contract info
            document.getElementById('contract_id').value = id;
            document.getElementById('total_amount_edit').value = totalAmount;
            document.getElementById('notes_edit').value = notes;

            // Reset containers
            document.getElementById('serviceContainer').innerHTML = '';
            document.getElementById('serviceDetailsContainer').innerHTML = '';

            // Show modal
            document.getElementById('contractModal').classList.remove('hidden');

            // Load contract services
            $.ajax({
                url: `/contracts_service/details/${id}`,
                method: 'GET',
                success: function(response) {
                    const allServiceTypes = window.allServiceTypes || [];
                    const existingServices = response.services || {};
                    const existingServiceIds = Object.keys(existingServices);
                    const extraServiceTypes = response.extraServiceTypes || [];

                    // Render regular services
                    allServiceTypes.forEach(serviceType => {
                        const isChecked = existingServiceIds.includes(serviceType.id.toString());
                        const existingData = existingServices[serviceType.id]?.[0];

                        const checkboxDiv = document.createElement('div');
                        checkboxDiv.className = 'flex items-start';
                        checkboxDiv.innerHTML = `
                            <div class="flex items-center h-5">
                                <input 
                                    id="type_${serviceType.id}" 
                                    name="types[]" 
                                    type="checkbox" 
                                    value="${serviceType.id}"
                                    class="service-checkbox focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded"
                                    ${isChecked ? 'checked' : ''}
                                    onchange="toggleServiceDetails(this)"
                                >
                            </div>
                            <div class="ml-3 text-sm">
                                <label for="type_${serviceType.id}" class="font-medium text-gray-700">
                                    ${serviceType.name}
                                </label>
                                ${serviceType.description ? `<p class="text-gray-500">${serviceType.description}</p>` : ''}
                            </div>
                        `;
                        document.getElementById('serviceContainer').appendChild(checkboxDiv);

                        if (isChecked && existingData) {
                            renderServiceDetails(serviceType.id, existingData);
                        }
                    });

                    const extraServicesContainer = document.createElement('div');
                    extraServicesContainer.className = 'mt-4';

                    // Default extra service types that should always be available
                    const defaultExtraServiceTypes = ['wakala', 'ticket']; // Add all your possible types here

                    let extraServicesCheckboxes = '';
                    defaultExtraServiceTypes.forEach(extraType => {
                        const isChecked = extraServiceTypes.includes(extraType) ? 'checked' : '';

                        extraServicesCheckboxes += `
                            <div class="flex items-center">
                                <div class="flex items-center h-5">
                                    <input 
                                        id="type_${extraType}" 
                                        name="extra_types[]" 
                                        type="checkbox" 
                                        value="${extraType}"
                                        class="service-checkbox focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded"
                                        onchange="toggleServiceDetails${capitalizeFirstLetter(extraType)}(this)"
                                        ${isChecked}
                                    >
                                </div>
                                <div class="ml-2 text-sm">
                                    <label for="type_${extraType}" class="font-medium text-gray-700">
                                        ${capitalizeFirstLetter(extraType)}
                                    </label>
                                </div>
                            </div>
                        `;
                    });

                    extraServicesContainer.innerHTML = `
                        <button 
                            type="button" 
                            onclick="showExtraServices()"
                            class="inline-flex items-start px-3 py-1.5 border border-transparent text-xs font-medium rounded shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                        >
                            + Hide Extra Services
                        </button>
                        <div id="extraServicesOptions" class="mt-2">
                            <div class="flex flex-wrap gap-4">
                                ${extraServicesCheckboxes}
                            </div>
                        </div>
                    `;

                    document.getElementById('serviceContainer').appendChild(extraServicesContainer);
                    // If there are existing extra services, populate their data
                    if (response.extraServices && response.extraServices.length > 0) {
                        document.getElementById('extraServicesOptions').classList.remove('hidden');

                        response.extraServices.forEach(extraService => {
                            const checkbox = document.getElementById(`type_${extraService.extratype}`);
                            if (checkbox) {
                                checkbox.checked = true;
                                populateExtraServiceFields(extraService.extratype, extraService);
                            }
                        });
                    }

                },
                error: function(xhr, status, error) {
                    console.error('Error loading services:', error);
                    alert('Failed to load service details');
                }
            });
        }

        function showExtraServices() {
            document.getElementById('extraServicesOptions').classList.toggle('hidden');
            const button = event.target;
            button.textContent = button.textContent.includes('Add') ?
                'Hide Extra Services' : '+ Add Extra Services';
        }

        function renderServiceDetails(serviceTypeId, serviceData) {
            const container = document.getElementById('serviceDetailsContainer');
            const detailDiv = document.createElement('div');
            detailDiv.id = `service_${serviceTypeId}`;
            detailDiv.className = 'p-4 border rounded-lg bg-gray-50 mb-4';
            detailDiv.innerHTML = `
                <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200">
                            <!-- Header with title and close button -->
                            <div class="flex justify-between items-center mb-4 pb-2 border-b border-gray-100">
                                <h4 class="font-semibold text-gray-800 text-base">${serviceData.service_name}</h4>
                
                                <button type="button" onclick="removeServiceDetail('${serviceTypeId}')" class="text-red-500 hover:text-red-700">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </div>
                            <div class = "space-y-4">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label for="date_${serviceTypeId}" class="block text-sm font-medium text-gray-700">Date</label>
                                            <input type="date" id="date_${serviceTypeId}" name="service_dates[${serviceTypeId}]" 
                                                value="${serviceData.date}" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 py-2 px-3 border">
                                        </div>
                                        <div>
                                            <label for="supplier_${serviceTypeId}" class="block text-sm font-medium text-gray-700">Supplier</label>
                                            <select id="supplier_${serviceTypeId}" name="service_suppliers[${serviceTypeId}]" class="block w-full pl-7 pr-12 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 py-2 px-3 border">
                                                <option value="">Select Supplier</option>
                                                ${generateSupplierOptions(serviceData)}
                                            </select>
                                        </div>
                                        <div>
                                            <label for="amount_${serviceTypeId}" class="block text-sm font-medium text-gray-700">Amount</label>
                                            <input type="number" step="0.01" id="amount_${serviceTypeId}" name="service_amounts[${serviceTypeId}]" 
                                                value="${serviceData.allocated_amount}" class="block w-full pl-7 pr-12 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 py-2 px-3 border">
                                        </div>
                                        <div>
                                            <label for="note_${serviceTypeId}" class="block text-sm font-medium text-gray-700">Note</label>
                                            <input type="text" id="note_${serviceTypeId}" name="service_notes[${serviceTypeId}]" 
                                                value="${serviceData.note}" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 py-2 px-3 border">
                                        </div>
                                    </div>
                            </div>
                    
                </div>
            
            `;
            container.appendChild(detailDiv);

            // Set the selected supplier
            const supplierSelect = document.getElementById(`supplier_${serviceTypeId}`);
            if (supplierSelect) {
                supplierSelect.value = `${serviceData.agent_or_supplier}_${serviceData.supplier}`;
            }
        }

        function removeServiceDetail(serviceTypeId) {
            // Uncheck the checkbox
            const checkbox = document.getElementById(`type_${serviceTypeId}`);
            if (checkbox) checkbox.checked = false;

            // Remove the details div
            const detailDiv = document.getElementById(`service_${serviceTypeId}`);
            if (detailDiv) detailDiv.remove();
        }
    </script>

</x-app-layout>
