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

    <div class="main-content" id="main-content">
        <div class="content">
            {{-- <div class=""> --}}
            <div class="bg-white shadow-md rounded p-6 m-3">
                <div class="flex justify-between items-center mb-4">
                    <button onclick="toggleAccountForm()"
                        class="flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                                clip-rule="evenodd" />
                        </svg>
                        Add New
                    </button>
                    <h2 class="text-lg font-semibold text-gray-800">Contract</h2>
                </div>

                <div class="mx-auto hidden" id="account-form-div">

                    <div class="flex justify-between items-center mb-4">
                        <h1 class="text-2xl font-bold text-gray-800">Add Contract</h1>
                        <button class="text-gray-500 hover:text-gray-700 transition-colors duration-200"
                            onclick="toggleAccountForm()">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <div class="bg-white rounded-lg shadow-md overflow-hidden p-5">
                        @if (in_array('entry', $permissionsArray))
                            {{-- <form action="{{ route('contracts.store') }}" method="POST" class="space-y-6">
                                @csrf

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label for="invoice"
                                            class="block text-sm font-medium text-gray-700 mb-1">Invoice</label>
                                        <input type="text" name="invoice" id="invoice"
                                            value="{{ $contract_invoice }}"
                                            class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                            required readonly>
                                    </div>

                                    <div>
                                        <label for="agent_id"
                                            class="block text-sm font-medium text-gray-700 mb-1">Agent</label>
                                        <select name="agent_id" id="agent_id"
                                            class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none 
                                                        focus:ring-2 focus:ring-blue-500 select2">
                                            <option value="">Select Agent</option>
                                            @foreach ($agents as $agent)
                                                <option value="{{ $agent->id }}">{{ $agent->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div>
                                        <label for="total_amount"
                                            class="block text-sm font-medium text-gray-700 mb-1">Total
                                            Amount</label>
                                        <input type="number" name="total_amount" id="total_amount" step="0.01"
                                            class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                            required>
                                    </div>

                                    <div>
                                        <label for="contract_date"
                                            class="block text-sm font-medium text-gray-700 mb-1">Date</label>
                                        <input type="date" name="contract_date" id="contract_date"
                                            class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                            required>
                                    </div>

                                    <div>
                                        <label for="passport_no"
                                            class="block text-sm font-medium text-gray-700 mb-1">Passport
                                            No</label>
                                        <input type="text" name="passport_no" id="passport_no" maxlength="13"
                                            class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                            required>
                                    </div>

                                    <div>
                                        <label for="name"
                                            class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                                        <input type="text" name="name" id="name"
                                            class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                            required>
                                    </div>

                                    <div>
                                        <label for="country"
                                            class="block text-sm font-medium text-gray-700 mb-1">Country</label>
                                        <input type="text" name="country" id="country"
                                            class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    </div>
                                </div>

                                <div>
                                    <label for="notes"
                                        class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                                    <textarea name="notes" id="notes" rows="3"
                                        class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                                </div>

                                <div class="pt-4 text-right">
                                    <button type="submit"
                                        class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2 rounded shadow-md transition">
                                        Create Contract
                                    </button>
                                </div>
                            </form> --}}

                            <form action="{{ route('contracts.store_new') }}" method="POST" class="space-y-6">
                                @csrf

                                <div class="bg-white p-6 rounded-lg shadow-md">
                                    <h1 class="text-xl font-bold mb-6">Visa Contract Entry</h1>

                                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
                                        <!-- Invoice -->
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Invoice
                                                No</label>
                                            <input type="text" name="invoice" value="{{ $contract_invoice }}"
                                                class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                                required readonly>
                                        </div>

                                        <!-- Date -->
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Date</label>
                                            <input type="date" name="contract_date"
                                                class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                                required>
                                        </div>

                                        <!-- Amount -->
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Amount</label>
                                            <input type="number" name="total_amount"
                                                class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                                required>
                                        </div>

                                        <!-- Passenger Name -->
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Passenger
                                                Name</label>
                                            <input type="text" name="name"
                                                class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                                required>
                                        </div>

                                        <!-- Passport Number -->
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Passport
                                                Number</label>
                                            <input type="text" name="passport_no" maxlength="13"
                                                class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                                required>
                                        </div>

                                        <!-- Agent -->
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Agent</label>
                                            <select name="agent_id"
                                                class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 select2">
                                                <option value="">Select Agent</option>
                                                @foreach ($agents as $agent)
                                                    <option value="{{ $agent->id }}">{{ $agent->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <!-- Country -->
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Country</label>
                                            <input type="text" name="country" placeholder="e.g. Saudi Arabia"
                                                class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        </div>
                                    </div>

                                    <!-- Note -->
                                    <div class="mb-6">
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Note</label>
                                        <textarea name="notes" rows="3" placeholder="Any notes..."
                                            class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                                    </div>

                                    <hr class="my-6">

                                    <!-- Services -->
                                    <div class="mb-6">
                                        <h2 class="text-lg font-medium mb-4">Services</h2>

                                        <div class="overflow-x-auto max-h-[40vh] overflow-y-scroll">
                                            <table class="min-w-full bg-white border border-gray-200">
                                                <thead>
                                                    <tr class="bg-gray-50">
                                                        <th
                                                            class="px-4 py-2 text-left text-sm font-medium text-gray-700 border-b">
                                                            Service Name</th>
                                                        <th
                                                            class="px-4 py-2 text-left text-sm font-medium text-gray-700 border-b">
                                                            Date</th>
                                                        <th
                                                            class="px-4 py-2 text-left text-sm font-medium text-gray-700 border-b">
                                                            Supplier</th>
                                                       
                                                        <th
                                                            class="px-4 py-2 text-left text-sm font-medium text-gray-700 border-b">
                                                            Fee</th>
                                                        <th
                                                            class="px-4 py-2 text-left text-sm font-medium text-gray-700 border-b">
                                                            Note</th>
                                                        <th
                                                            class="px-4 py-2 text-left text-sm font-medium text-gray-700 border-b">
                                                            Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="services-container">
                                                    @foreach ($types as $type)
                                                        <tr class="service-row">
                                                            <td class="px-4 py-2 border-b">
                                                                <input type="text" placeholder="{{ $type->name }}"
                                                                    class="w-full border-0 p-3 rounded bg-gray-100"
                                                                    readonly>
                                                                <input type="hidden"
                                                                    name="services[{{ $loop->index }}][name]"
                                                                    value="{{ $type->id }}">
                                                            </td>
                                                            <td class="px-4 py-2 border-b">
                                                                <input type="date"
                                                                    name="services[{{ $loop->index }}][date]"
                                                                    class="w-full border focus:ring-2 focus:ring-blue-500 rounded"
                                                                    >
                                                            </td>
                                                            <td class="px-4 py-2 border-b">
                                                                <select
                                                                    name="services[{{ $loop->index }}][supplier_id]"
                                                                    class="w-full border-0 focus:ring-2 focus:ring-blue-500 select2 rounded">
                                                                    <option value="">Select Supplier</option>
                                                                    @foreach ($suppliers as $supplier)
                                                                        <option value="supplier_{{ $supplier->id }}">
                                                                            {{ $supplier->name }}</option>
                                                                    @endforeach
                                                                    @foreach ($agents as $supplier)
                                                                        <option value="agent_{{ $supplier->id }}">
                                                                            {{ $supplier->name }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </td>
                                                            <td class="px-4 py-2 border-b">
                                                                <input type="number"
                                                                    name="services[{{ $loop->index }}][fee]"
                                                                    step="0.01" value="0"
                                                                    class="w-full border p-3 focus:ring-2 focus:ring-blue-500 rounded service-fee"
                                                                    >
                                                            </td>
                                                            <td class="px-4 py-2 border-b">
                                                                <input type="text"
                                                                    name="services[{{ $loop->index }}][note]"
                                                                    placeholder="Give Notes"
                                                                    class="w-full border p-3 focus:ring-2 focus:ring-blue-500 rounded">
                                                            </td>
                                                           
                                                            <td class="px-4 py-2 border-b">
                                                                <div class="flex flex-col space-y-3">
                                                                    <!-- Remove button with icon -->
                                                                    <div class="flex justify-between items-center">
                                                                        <div class="flex space-x-3">
                                                                            <!-- Visa Delivery -->
                                                                            <label
                                                                                class="flex items-center space-x-1.5 cursor-pointer px-2 py-1 rounded hover:bg-gray-100">
                                                                                <input type="checkbox"
                                                                                    class="service-checkbox h-4 w-4 text-blue-600"
                                                                                    data-type="visa">
                                                                                <span
                                                                                    class="text-sm font-medium">Visa</span>
                                                                            </label>

                                                                            <!-- Police Clearance -->
                                                                            <label
                                                                                class="flex items-center space-x-1.5 cursor-pointer px-2 py-1 rounded hover:bg-gray-100">
                                                                                <input type="checkbox"
                                                                                    class="service-checkbox h-4 w-4 text-blue-600"
                                                                                    data-type="police">
                                                                                <span
                                                                                    class="text-sm font-medium">Police</span>
                                                                            </label>

                                                                            <!-- Medical Status -->
                                                                            <label
                                                                                class="flex items-center space-x-1.5 cursor-pointer px-2 py-1 rounded hover:bg-gray-100">
                                                                                <input type="checkbox"
                                                                                    class="service-checkbox h-4 w-4 text-blue-600"
                                                                                    data-type="medical">
                                                                                <span
                                                                                    class="text-sm font-medium">Medical</span>
                                                                            </label>
                                                                        </div>

                                                                        <button type="button"
                                                                            class="text-white p-3 hover:text-red-500 bg-red-500 hover:bg-red-200 remove-service"
                                                                            title="Remove service">
                                                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                                                class="h-5 w-5" fill="none"
                                                                                viewBox="0 0 24 24"
                                                                                stroke="currentColor">
                                                                                <path stroke-linecap="round"
                                                                                    stroke-linejoin="round"
                                                                                    stroke-width="2"
                                                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                                            </svg>
                                                                        </button>
                                                                    </div>

                                                                    <!-- Additional Fields Container - Flex column layout -->
                                                                    <div class="additional-fields space-y-3 hidden">
                                                                        <!-- Visa Fields -->
                                                                        <div
                                                                            class="visa-fields hidden flex flex-col space-y-2 p-3 bg-blue-50 rounded-lg border border-blue-100">
                                                                            <div
                                                                                class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                                                                <div>
                                                                                    <label
                                                                                        class="block text-xs font-medium text-gray-600 mb-1">Issue
                                                                                        Date</label>
                                                                                    <input type="date"
                                                                                        name="services[{{ $loop->index }}][visa_issue_date]"
                                                                                        class="w-full p-2 text-sm border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                                                                                </div>
                                                                                <div>
                                                                                    <label
                                                                                        class="block text-xs font-medium text-gray-600 mb-1">Expire
                                                                                        Date</label>
                                                                                    <input type="date"
                                                                                        name="services[{{ $loop->index }}][visa_expire_date]"
                                                                                        class="w-full p-2 text-sm border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                                                                                </div>
                                                                            </div>
                                                                        </div>

                                                                        <!-- Police Fields -->
                                                                        <div
                                                                            class="police-fields hidden flex flex-col space-y-2 p-3 bg-green-50 rounded-lg border border-green-100">
                                                                            <div
                                                                                class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                                                                <div>
                                                                                    <label
                                                                                        class="block text-xs font-medium text-gray-600 mb-1">Clearance
                                                                                        Date</label>
                                                                                    <input type="date"
                                                                                        name="services[{{ $loop->index }}][police_date]"
                                                                                        class="w-full p-2 text-sm border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                                                                                </div>
                                                                                <div>
                                                                                    <label
                                                                                        class="block text-xs font-medium text-gray-600 mb-1">Clearance
                                                                                        No</label>
                                                                                    <input type="text"
                                                                                        name="services[{{ $loop->index }}][police_clearance_no]"
                                                                                        placeholder="Enter number"
                                                                                        class="w-full p-2 text-sm border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                                                                                </div>
                                                                            </div>
                                                                        </div>

                                                                        <!-- Medical Fields -->
                                                                        <div
                                                                            class="medical-fields hidden flex flex-col space-y-2 p-3 bg-purple-50 rounded-lg border border-purple-100">
                                                                            <div
                                                                                class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                                                                <div>
                                                                                    <label
                                                                                        class="block text-xs font-medium text-gray-600 mb-1">Medical
                                                                                        Date</label>
                                                                                    <input type="date"
                                                                                        name="services[{{ $loop->index }}][medical_date]"
                                                                                        class="w-full p-2 text-sm border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                                                                                </div>
                                                                                <div>
                                                                                    <label
                                                                                        class="block text-xs font-medium text-gray-600 mb-1">Status</label>
                                                                                    <select
                                                                                        name="services[{{ $loop->index }}][medical_status]"
                                                                                        class="w-full p-2 text-sm border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                                                                                        <option value="">Select
                                                                                            status</option>
                                                                                        <option value="fit">Fit
                                                                                        </option>
                                                                                        <option value="unfit">Unfit
                                                                                        </option>
                                                                                    </select>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                        <div id="serviceDetailsContainer" class="mt-6 space-y-4">
                                        </div>

                                      
                                        <div class="relative">
                                            <button type="button" id="add-service"
                                                class="mt-4 text-blue-600 hover:text-blue-800">
                                                + Add Service
                                            </button>
                                            <button 
                                                type="button" 
                                                onclick="showExtraServices(event)"
                                                class="inline-flex items-start ms-4 px-3 py-1.5 border border-transparent text-xs font-medium rounded shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                            >
                                                + Show Extra Services
                                            </button>

                                            <div id="extraServicesOptions" class="hidden absolute z-10 mt-1 w-48 rounded-md shadow-lg bg-white p-2 border border-gray-200">
                                                <div class="space-y-2">
                                                     <label class="flex items-center space-x-2 cursor-pointer px-2 py-1 rounded hover:bg-gray-50">
                                                        <input type="checkbox" 
                                                            id="type_wakala"
                                                            name="extra_services[]" 
                                                            value="wakala" 
                                                            class="h-4 w-4 text-indigo-600 rounded"
                                                            onchange="handleExtraServiceCheckbox(this, 'wakala')">
                                                        <span class="text-sm text-gray-700">Wakala</span>
                                                    </label>
                                                    <label class="flex items-center space-x-2 cursor-pointer px-2 py-1 rounded hover:bg-gray-50">
                                                        <input type="checkbox" 
                                                            id="type_ticket"
                                                            name="extra_services[]" 
                                                            value="ticket" 
                                                            class="h-4 w-4 text-indigo-600 rounded"
                                                            onchange="handleExtraServiceCheckbox(this, 'ticket')">
                                                        <span class="text-sm text-gray-700">Ticket</span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <hr class="my-6">

                                    <!-- Total Contract Amount -->
                                    <div class="flex justify-between items-center mb-6">
                                        <span class="font-medium">Total Contract Amount:</span>
                                        <span id="total-amount" class="font-bold">0</span>
                                        <input type="hidden" name="total_amount" id="total-amount-input"
                                            value="0">
                                    </div>

                                    <!-- Submit Button -->
                                    <div class="pt-4 text-right">
                                        <button type="submit"
                                            class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2 rounded shadow-md transition">
                                            Submit Contract
                                        </button>
                                    </div>
                                </div>
                            </form>
                        @else
                            <div class="p-6 bg-yellow-50 text-yellow-800 rounded-lg">
                                <p class="font-medium">You don't have permission to add.</p>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="bg-white shadow-md rounded-lg p-6 mt-4">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">All Contracts</h3>
                    <div class="overflow-x-auto">
                        <table id="contracts-table"
                            class="min-w-full divide-y divide-gray-200 text-sm text-left datatable">
                            <thead class="bg-gray-100 text-gray-700">
                                <tr>
                                    <th class="px-4 py-2">SL</th>
                                    <th class="px-4 py-2">Agent</th>
                                    <th class="px-4 py-2">Name</th>
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
                                        <td class="px-4 py-2">{{ $contract->agent_name ?? 'N/A' }}
                                        </td>
                                        <td class="px-4 py-2">{{ $contract->name ?? '' }}
                                            <br><strong class="text-sm">{{ $contract->passport_no }}</strong>
                                        </td>
                                        <td class="px-4 py-2">
                                            {{ number_format($contract->total_amount, 2) }}</td>
                                        <td class="px-4 py-2">{{ $contract->contract_date }}</td>
                                        <td class="px-4 py-2">{{ $contract->notes }}</td>
                                        <td class="px-4 py-2 text-center">
                                            <div class="flex justify-center space-x-2">
                                                <a href="#" data-modal-id="modal_{{ $contract->id }}"
                                                    onclick="openUniqueModal('{{ $contract->id }}')"
                                                    class="text-blue-600 hover:text-blue-800 font-medium">
                                                    View
                                                </a>

                                                <button
                                                    onclick="openModal('{{ $contract->id }}', '{{ $contract->total_amount }}', '{{ $contract->notes }}')"
                                                    class="text-blue-600 hover:underline text-sm font-medium">
                                                    Edit
                                                </button>


                                            </div>
                                        </td>
                                    </tr>

                                    {{-- modal for view contract --}}
                                    <div id="modal_{{ $contract->id }}"
                                        class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
                                        <div
                                            class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl mx-auto relative p-6">
                                            <!-- Close Button -->
                                            <button onclick="closeViewModal('modal_{{ $contract->id }}')"
                                                class="absolute top-3 right-4 text-gray-400 hover:text-red-600 text-2xl font-bold focus:outline-none">
                                                &times;
                                            </button>

                                            <!-- Modal Header -->
                                            <h2 class="text-2xl font-bold text-gray-800 mb-4 border-b pb-2">
                                                Contract
                                                Details</h2>

                                            <!-- Static Info -->
                                            <div class="space-y-2 mb-4">
                                                <p><span class="font-medium text-gray-700">Total
                                                        Amount:</span>
                                                    <span
                                                        class="text-blue-600 font-semibold">{{ $contract->total_amount }}</span>
                                                </p>
                                                <p><span class="font-medium text-gray-700">Notes:</span>
                                                    <span class="text-gray-600">{{ $contract->notes }}</span>
                                                </p>
                                            </div>

                                            <!-- AJAX Content Placeholder -->
                                            <div class="modal-content space-y-3 text-sm text-gray-700">
                                                <div class="text-center text-gray-400">Loading service
                                                    details...
                                                </div>
                                                <!-- Contract services will be dynamically injected here -->
                                            </div>

                                            <!-- Optional Footer -->
                                            <div class="mt-6 text-right">
                                                <button onclick="closeViewModal('modal_{{ $contract->id }}')"
                                                    class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium px-4 py-2 rounded-md">
                                                    Close
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                @endforeach

                            </tbody>
                        </table>


                        <!--Edit Modal Contract-->
                        <div id="contractModal"
                            class="fixed inset-0 bg-gray-800 bg-opacity-50 hidden flex items-center justify-center z-50 overflow-y-auto">
                            <div
                                class="bg-white w-full max-h-[90vh] max-w-[90vh] rounded-lg shadow-lg p-6 relative overflow-hidden">

                                <!-- Close Button -->
                                <button onclick="document.getElementById('contractModal').classList.add('hidden')"
                                    class="absolute top-2 right-2 text-gray-500 hover:text-gray-700 text-2xl z-10">&times;</button>

                                <!-- Scrollable Body -->
                                <div class="overflow-y-auto max-h-[75vh] pr-2">
                                    <h2 class="text-2xl font-semibold mb-6 text-gray-800">Edit Contract
                                    </h2>

                                    <!-- Hidden ID field for editing the contract -->

                                    <form action="{{ route('contracts.update') }}" method="POST">
                                        @csrf
                                    
                                        <input type="hidden" id="contract_id" name="contract_id">

                                        <div class="mb-4">
                                            <label for="total_amount"
                                                class="block text-sm font-medium text-gray-700 mb-1">Total
                                                Amount</label>
                                            <input type="number" name="total_amount" id="total_amount_edit"
                                                step="0.01" required
                                                class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        </div>

                                        <!-- Notes -->
                                        <div class="mb-4">
                                            <label for="notes"
                                                class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                                            <textarea name="notes" id="notes_edit" rows="3"
                                                class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                                        </div>

                                        <!-- Service Breakdown -->
                                      

                                        <div id="serviceDetailsContainer" class="mt-6 space-y-4">
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

                    </div>
                </div>

            </div>
            {{-- </div> --}}
        </div>
    </div>


    <script>
        window.allServiceTypes = @json($types); // All possible service types
        window.suppliers = @json($suppliers); // All suppliers
        window.agents = @json($agents); // All agents
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Delegate events for dynamically added rows
            document.addEventListener('click', function(e) {
                if (e.target.classList.contains('service-checkbox')) {
                    const checkbox = e.target;
                    const type = checkbox.dataset.type;
                    const row = checkbox.closest('tr');
                    const additionalFields = row.querySelector('.additional-fields');
                    const typeFields = row.querySelector(`.${type}-fields`);

                    // Show/hide additional fields container
                    if (checkbox.checked) {
                        additionalFields.classList.remove('hidden');
                        typeFields.classList.remove('hidden');
                    } else {
                        typeFields.classList.add('hidden');

                        // Hide container if no checkboxes are checked
                        const anyChecked = row.querySelector('.service-checkbox:checked');
                        if (!anyChecked) {
                            additionalFields.classList.add('hidden');
                        }
                    }
                }
            });

            // For dynamically added rows (if using the "Add Service" button)
            function setupCheckboxes(row) {
                row.querySelectorAll('.service-checkbox').forEach(checkbox => {
                    checkbox.addEventListener('change', function() {
                        const type = this.dataset.type;
                        const additionalFields = row.querySelector('.additional-fields');
                        const typeFields = row.querySelector(`.${type}-fields`);

                        if (this.checked) {
                            additionalFields.classList.remove('hidden');
                            typeFields.classList.remove('hidden');
                        } else {
                            typeFields.classList.add('hidden');

                            const anyChecked = row.querySelector('.service-checkbox:checked');
                            if (!anyChecked) {
                                additionalFields.classList.add('hidden');
                            }
                        }
                    });
                });
            }

            // Initialize for existing rows
            document.querySelectorAll('.service-row').forEach(row => {
                setupCheckboxes(row);
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Calculate total amount
            function calculateTotal() {
                let total = 0;
                document.querySelectorAll('.service-fee').forEach(input => {
                    total += parseFloat(input.value) || 0;
                });
                document.getElementById('total-amount').textContent = total.toFixed(2);
                document.getElementById('total-amount-input').value = total.toFixed(2);
            }

            // Add event listeners to all fee inputs
            document.querySelectorAll('.service-fee').forEach(input => {
                input.addEventListener('change', calculateTotal);
                input.addEventListener('input', calculateTotal);
            });

            document.getElementById('add-service').addEventListener('click', function() {
                const container = document.getElementById('services-container');
                const index = container.querySelectorAll('.service-row').length;

                const newRow = document.createElement('tr');
                newRow.className = 'service-row';
                newRow.innerHTML = `
                    <td class="px-4 py-2 border-b">
                        <input type="text" placeholder="Service Name" name="services[${index}][name]"
                            class="w-full border-0 p-3 rounded bg-gray-100">
                    
                    </td>
        
                    <td class="px-4 py-2 border-b">
                        <input type="date"
                            name="services[${index}][date]"
                            class="w-full border focus:ring-2 focus:ring-blue-500 rounded"
                            required>
                    </td>
                    <td class="px-4 py-2 border-b">
                        <select name="services[${index}][supplier_id]"
                            class="w-full border-0 focus:ring-2 focus:ring-blue-500 select2 rounded">
                            <option value="">Select Supplier</option>
                            ${generateSupplierOptions()}
                        </select>
                    </td>
                    <td class="px-4 py-2 border-b">
                        <input type="number"
                            name="services[${index}][fee]"
                            step="0.01" value="0"
                            class="w-full border p-3 focus:ring-2 focus:ring-blue-500 rounded service-fee"
                            required>
                    </td>
                    <td class="px-4 py-2 border-b">
                        <input type="text"
                            name="services[${index}][note]"
                            placeholder="Give Notes"
                            class="w-full border p-3 focus:ring-2 focus:ring-blue-500 rounded">
                    </td>
                    <td class="px-4 py-2 border-b">
                        <div class="flex flex-col space-y-3">
                            <!-- Remove button with icon and checklist -->
                            <div class="flex justify-between items-center">
                                <div class="flex space-x-3">
                                    <!-- Visa Delivery -->
                                    <label class="flex items-center space-x-1.5 cursor-pointer px-2 py-1 rounded hover:bg-gray-100">
                                        <input type="checkbox" class="service-checkbox h-4 w-4 text-blue-600" data-type="visa">
                                        <span class="text-sm font-medium">Visa</span>
                                    </label>
                                    <!-- Police Clearance -->
                                    <label class="flex items-center space-x-1.5 cursor-pointer px-2 py-1 rounded hover:bg-gray-100">
                                        <input type="checkbox" class="service-checkbox h-4 w-4 text-blue-600" data-type="police">
                                        <span class="text-sm font-medium">Police</span>
                                    </label>
                                    <!-- Medical Status -->
                                    <label class="flex items-center space-x-1.5 cursor-pointer px-2 py-1 rounded hover:bg-gray-100">
                                        <input type="checkbox" class="service-checkbox h-4 w-4 text-blue-600" data-type="medical">
                                        <span class="text-sm font-medium">Medical</span>
                                    </label>
                                </div>
                                <button type="button" class="text-white p-3 hover:text-red-500 bg-red-500 hover:bg-red-200 remove-service" title="Remove service">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </div>
                            <!-- Additional Fields Container -->
                            <div class="additional-fields space-y-3 hidden">
                                <!-- Visa Fields -->
                                <div class="visa-fields hidden flex flex-col space-y-2 p-3 bg-blue-50 rounded-lg border border-blue-100">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                        <div>
                                            <label class="block text-xs font-medium text-gray-600 mb-1">Issue Date</label>
                                            <input type="date" name="services[${index}][visa_issue_date]" class="w-full p-2 text-sm border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-gray-600 mb-1">Expire Date</label>
                                            <input type="date" name="services[${index}][visa_expire_date]" class="w-full p-2 text-sm border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                                        </div>
                                    </div>
                                </div>
                                <!-- Police Fields -->
                                <div class="police-fields hidden flex flex-col space-y-2 p-3 bg-green-50 rounded-lg border border-green-100">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                        <div>
                                            <label class="block text-xs font-medium text-gray-600 mb-1">Clearance Date</label>
                                            <input type="date" name="services[${index}][police_date]" class="w-full p-2 text-sm border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-gray-600 mb-1">Clearance No</label>
                                            <input type="text" name="services[${index}][police_clearance_no]" placeholder="Enter number" class="w-full p-2 text-sm border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                                        </div>
                                    </div>
                                </div>
                                <!-- Medical Fields -->
                                <div class="medical-fields hidden flex flex-col space-y-2 p-3 bg-purple-50 rounded-lg border border-purple-100">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                        <div>
                                            <label class="block text-xs font-medium text-gray-600 mb-1">Medical Date</label>
                                            <input type="date" name="services[${index}][medical_date]" class="w-full p-2 text-sm border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-gray-600 mb-1">Status</label>
                                            <select name="services[${index}][medical_status]" class="w-full p-2 text-sm border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                                                <option value="">Select status</option>
                                                <option value="fit">Fit</option>
                                                <option value="unfit">Unfit</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </td>
                `;

                container.appendChild(newRow);

                // Initialize Select2 if used
                if ($().select2) {
                    $(newRow).find('.select2').select2();
                }

                // Add event listeners
                newRow.querySelector('.service-fee').addEventListener('change', calculateTotal);
                newRow.querySelector('.service-fee').addEventListener('input', calculateTotal);

                newRow.querySelector('.remove-service').addEventListener('click', function() {
                    newRow.remove();
                    calculateTotal();
                });

                // Setup checkbox functionality
                setupCheckboxes(newRow);
            });

            // Helper function to setup checkbox interactions
            function setupCheckboxes(row) {
                row.querySelectorAll('.service-checkbox').forEach(checkbox => {
                    checkbox.addEventListener('change', function() {
                        const type = this.dataset.type;
                        const additionalFields = row.querySelector('.additional-fields');
                        const typeFields = row.querySelector(`.${type}-fields`);

                        if (this.checked) {
                            additionalFields.classList.remove('hidden');
                            typeFields.classList.remove('hidden');
                        } else {
                            typeFields.classList.add('hidden');

                            // Hide container if no checkboxes are checked
                            const anyChecked = row.querySelector('.service-checkbox:checked');
                            if (!anyChecked) {
                                additionalFields.classList.add('hidden');
                            }
                        }
                    });
                });
            }

            // Add event listeners to existing remove buttons
            document.querySelectorAll('.remove-service').forEach(button => {
                button.addEventListener('click', function() {
                    this.closest('.service-row').remove();
                    calculateTotal();
                });
            });

            // Initial calculation
            calculateTotal();
        });

        function showExtraServices(event) {
            const optionsDiv = document.getElementById('extraServicesOptions');
            optionsDiv.classList.toggle('hidden');
            
            const button = event.target;
            button.textContent = button.textContent.includes('Hide') ? 
                '+ Show Extra Services' : '- Hide Extra Services';
        }
        function handleExtraServiceCheckbox(checkbox, serviceType) {
            if (checkbox.checked) {
                const data = {
                    serviceName: serviceType,
                    checked: true,
                    // Add any other relevant data you want to pass
                    timestamp: new Date().toISOString()
                };
                populateExtraServiceFields(serviceType, data);
                checkbox.parentElement.classList.add('bg-blue-50', 'border', 'border-blue-200');
            } else {
                checkbox.parentElement.classList.remove('bg-blue-50', 'border', 'border-blue-200');
            }
        }
    </script>

    <script>
        function confirmDelete(deleteUrl) {
            if (window.confirm("Are you sure you want to delete?")) {
                window.location.href = deleteUrl;
            }
        }

        function toggleAccountForm() {
            const formDiv = document.getElementById('account-form-div');

            // Toggle with animation
            if (formDiv.classList.contains('hidden')) {
                formDiv.classList.remove('hidden');
                formDiv.classList.add('animate-fade-in');
                formDiv.scrollIntoView({
                    behavior: 'smooth'
                });
            } else {
                formDiv.classList.add('animate-fade-out');
                setTimeout(() => {
                    formDiv.classList.add('hidden');
                    formDiv.classList.remove('animate-fade-out');
                }, 300);
            }
        }


        // Format phone numbers
        document.querySelectorAll('td:nth-child(5)').forEach(td => {
            const phone = td.textContent.trim();
            if (phone && phone !== '☐') {
                td.innerHTML = `<a href="tel:${phone}">${phone}</a>`;
            }
        });
    </script>

    <script>
        function toggleServiceDetails(checkbox) {
            const container = document.getElementById('serviceDetailsContainer');
            const typeId = checkbox.value;
            const typeName = checkbox.parentElement.nextElementSibling.querySelector('label').textContent;

            if (checkbox.checked) {
                // Create new service details section
                const detailDiv = document.createElement('div');
                detailDiv.id = `service_${typeId}`;
                detailDiv.className = 'p-4 border rounded-lg bg-gray-50';
                detailDiv.innerHTML = `
                    <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200">
                        <!-- Header with title and close button -->
                        <div class="flex justify-between items-center mb-4 pb-2 border-b border-gray-100">
                            <h4 class="font-semibold text-gray-800 text-base">${typeName}</h4>
                            <button type="button" onclick="removeServiceDetail('${typeId}')" 
                                    class="text-gray-400 hover:text-red-500 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </div>
                        
                        <!-- Input fields grid -->
                        <div class="space-y-4">
                            <!-- Date and Supplier row -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <!-- Date input -->
                                <div class="space-y-1">
                                    <label for="date_${typeId}" class="block text-sm font-medium text-gray-700">Date</label>
                                    <input type="date" id="date_${typeId}" name="service_dates[${typeId}]" 
                                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 py-2 px-3 border">
                                </div>
                                
                                <!-- Supplier dropdown -->
                                <div class="space-y-1">
                                    <label for="supplier_${typeId}" class="block text-sm font-medium text-gray-700">Supplier</label>
                                    <select id="supplier_${typeId}" name="service_suppliers[${typeId}]" 
                                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 py-2 px-3 border">
                                        <option value="">Select Supplier</option>
                                        @foreach ($suppliers as $supplier)
                                            <option value="supplier_{{ $supplier->id }}">{{ $supplier->name }}</option>
                                        @endforeach
                                        @foreach ($agents as $agent)
                                            <option value="agent_{{ $agent->id }}">{{ $agent->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            
                            <!-- Amount and Note row -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <!-- Amount input -->
                                <div class="space-y-1">
                                    <label for="amount_${typeId}" class="block text-sm font-medium text-gray-700">Amount</label>
                                    <div class="relative rounded-md shadow-sm">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        
                                        </div>
                                        <input type="number" step="0.01" id="amount_${typeId}" name="service_amounts[${typeId}]" 
                                            class="block w-full pl-7 pr-12 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 py-2 px-3 border">
                                    </div>
                                </div>
                                
                                <!-- Note input -->
                                <div class="space-y-1">
                                    <label for="note_${typeId}" class="block text-sm font-medium text-gray-700">Note</label>
                                    <input type="text" id="note_${typeId}" name="service_notes[${typeId}]" 
                                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 py-2 px-3 border">
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                container.appendChild(detailDiv);
            } else {
                // Remove service details section
                const detailDiv = document.getElementById(`service_${typeId}`);
                if (detailDiv) {
                    container.removeChild(detailDiv);
                }
            }
        }

        function removeServiceDetail(typeId) {
            const checkbox = document.getElementById(`type_${typeId}`);
            if (checkbox) {
                checkbox.checked = false;
                toggleServiceDetails(checkbox);
            }
        }
    </script>

    <script>
        // Auto-calculation function for Wakala
        function calculateWakalaTotal() {
            const buyingPrice = parseFloat(document.getElementById('wakala_buying_price').value) || 0;
            const multiCurrency = parseFloat(document.getElementById('wakala_multi_currency').value) || 1;
            const totalPrice = buyingPrice * multiCurrency;
            document.getElementById('wakala_total_price').value = totalPrice.toFixed(2);
        }


        function generateSupplierOptions(type, selectedValue = '') {
            const suppliers = window.suppliers || [];
            const agents = window.agents || [];

            const allOptions = [
                ...agents.map(agent => ({
                    id: agent.id,
                    name: agent.name,
                    type: 'agent'
                })),
                ...suppliers.map(supplier => ({
                    id: supplier.id,
                    name: supplier.name,
                    type: 'supplier'
                }))
            ];

            return allOptions.map(option => {
                let isSelected = false;

                if (typeof selectedValue === 'string') {
                    isSelected = selectedValue === `${option.type}_${option.id}`;
                } else if (typeof selectedValue === 'object' && selectedValue !== null) {
                    isSelected = (option.id == selectedValue.supplier &&
                        option.type == selectedValue.agent_or_supplier);
                }

                return `
                    <option value="${option.type}_${option.id}" ${isSelected ? 'selected' : ''}>
                        ${option.name} (${option.type === 'agent' ? 'Agent' : 'Supplier'})
                    </option>
                `;
            }).join('');
        }
    </script>


    <script>
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

        function capitalizeFirstLetter(string) {
            return string.charAt(0).toUpperCase() + string.slice(1);
        }

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
            // if (!checkbox.checked) {
            //     document
            // }

            checkbox.checked = true;

            const funcName = `toggleServiceDetails${capitalizeFirstLetter(serviceType)}`;
           
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

        function openUniqueModal(contractId) {
            const modalId = `modal_${contractId}`;
            const modalElement = document.getElementById(modalId);

            // Show the modal with loading state
            modalElement.classList.remove('hidden');
            const contentContainer = modalElement.querySelector('.modal-content');
            contentContainer.innerHTML = `
                <div class="flex justify-center items-center h-64">
                    <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-blue-500"></div>
                </div>
            `;

            $.ajax({
                url: `/contracts_service/details/${contractId}`,
                method: 'GET',
                success: function(data) {
                    let html = '';

                    if (Object.keys(data.services).length === 0) {
                        html = `
                            <div class="text-center py-8">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <h3 class="mt-2 text-lg font-medium text-gray-900">No services found</h3>
                                <p class="mt-1 text-gray-500">This contract doesn't have any services yet.</p>
                            </div>
                        `;
                    } else {
                        html = `
                            <div class="space-y-6 p-{4}">
                                <h2 class="text-xl font-bold text-gray-800 mb-4 px-3 py-2">Contract Services</h2>
                                <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 rounded-lg">
                                    <table class="min-w-full divide-y divide-gray-300 ">
                                        <thead class="bg-gray-200">
                                            <tr>
                                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Service</th>
                                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Supplier/Agent</th>
                                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                        `;

                        let totalAmount = 0;
                        $.each(data.services, function(serviceType, entries) {
                            entries.forEach(item => {
                                totalAmount += parseFloat(item.allocated_amount) || 0;
                            });
                        });

                        $.each(data.services, function(serviceType, entries) {
                            entries.forEach((item, index) => {
                                html += `
                                    <tr class="${index % 2 === 0 ? 'bg-white' : 'bg-gray-50'} hover:bg-gray-100">
                                        <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900">
                                            ${item.service_name}
                                            ${item.note ? `
                                                                                            <p class="text-xs text-gray-500 mt-1 whitespace-normal break-words">
                                                                                                Note: ${item.note}
                                                                                            </p>
                                                                                            ` : ''}
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">
                                            <div class="flex items-center">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${item.agent_or_supplier === 'agent' ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800'}">
                                                    ${item.agent_or_supplier === 'agent' ? 'Agent' : 'Supplier'}
                                                </span>
                                            </div>
                                            <span class="ml-2">${item.supplier_name}</span>

                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">
                                            ${formatCurrency(item.allocated_amount)}
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">
                                            ${item.date ? formatDateDMY(item.date) : 'N/A'}
                                        </td>
                                    </tr>
                                `;
                            });
                        });
                        html += `
                            <tfoot>
                                <tr class="bg-gray-50 font-medium">
                                    <td class="px-4 py-3 text-sm text-gray-900" colspan="3">Total Amount</td>
                                    <td class="px-4 py-3 text-sm text-gray-900">${formatCurrency(totalAmount)}</td>
                                </tr>
                            </tfoot>
                        `;
                        html += `
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        `;
                    }

                    contentContainer.innerHTML = html;
                },
                error: function(xhr, status, error) {
                    contentContainer.innerHTML = `
                        <div class="rounded-md bg-red-50 p-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-red-800">Failed to load services</h3>
                                    <div class="mt-2 text-sm text-red-700">
                                        <p>${error}</p>
                                        <p class="mt-1">Please try again later.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                }
            });
        }

        function closeViewModal(modalId) {
            document.getElementById(modalId).classList.add('hidden');
        }

        // Helper function to format currency
        function formatCurrency(amount) {
            return new Intl.NumberFormat('en-US', {
                style: 'currency',
                currency: 'BDT',
                minimumFractionDigits: 2
            }).format(amount);
        }

        function formatDateDMY(dateString) {
            const date = new Date(dateString);
            const day = date.getDate().toString().padStart(2, '0');
            const month = (date.getMonth() + 1).toString().padStart(2, '0'); // Months are 0-indexed
            const year = date.getFullYear();
            return `${day}/${month}/${year}`;
        }
    </script>


</x-app-layout>
