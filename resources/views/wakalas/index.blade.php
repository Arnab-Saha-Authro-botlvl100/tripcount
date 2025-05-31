{{-- <x-app-layout> --}}
<!-- Button to toggle Wakala entry form -->
{{-- <button onclick="toggleWakalaContainer()" 
class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
Wakala Entry
</button> --}}
@php
    use App\Models\Agent;
    use App\Models\Supplier;

    $agentsname = Agent::where('user', Auth::id())
        ->where('is_delete', 0)
        ->where('is_active', 1)
        ->pluck('name', 'id')
        ->toArray();

    // Get suppliers as ID => Name pairs
    $suppliersname = Supplier::where('user', Auth::id())
        ->where('is_delete', 0)
        ->where('is_active', 1)
        ->pluck('name', 'id')
        ->toArray();

    // dd($agentsname);

@endphp

{{-- <div class="main-content" id="main-content">
    <div class="content"> --}}

<div class=" mx-auto px-4 py-8 hidden" id="wakalacontainer">
    <div class=" mx-auto bg-white rounded-lg shadow-md p-6">

        <form id="wakalaForm" action="{{ route('wakalas.store') }}" method="POST"
            class="bg-white shadow-sm rounded-lg p-6 border border-gray-200">
            @csrf
            <h2 class="text-xl font-bold text-gray-900 mb-6 border-b pb-2">Wakala Sale + Return Tracker</h2>

            <!-- Main Information Section -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                <!-- Invoice Number -->
                <div>
                    <label for="invoice" class="block text-sm font-medium text-gray-700 mb-1">Invoice
                        No</label>
                    <input type="text" name="invoice" id="invoice" required readonly
                        value="{{ $nextInvoiceNumber }}"
                        class="block w-full px-3 py-2 rounded border border-gray-300 bg-gray-100 text-gray-700">
                </div>

                <!-- Date -->
                <div>
                    <label for="date" class="block text-sm font-medium text-gray-700 mb-1">Date</label>
                    <input type="date" name="date" id="date" required
                        class="block w-full px-3 py-2 rounded border border-gray-300">
                </div>

                <!-- Agent -->
                <div>
                    <label for="agent" class="block text-sm font-medium text-gray-700 mb-1">Agent</label>
                    <select name="agent" id="agent" required
                        class="block w-full px-3 py-2 rounded border border-gray-300 select2">
                        <option value="">Select Agent</option>
                        @foreach ($agents as $agent)
                            <option value="{{ $agent->id }}">{{ $agent->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Supplier -->
                <div>
                    <label for="supplier" class="block text-sm font-medium text-gray-700 mb-1">Supplier</label>
                    <select name="supplier" id="supplier" required
                        class="block w-full px-3 py-2 rounded border border-gray-300 select2">
                        <option value="">Select Supplier</option>
                        {{-- @foreach ($suppliers as $supplier)
                                <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                            @endforeach --}}
                        <optgroup label="Suppliers">
                            @foreach ($suppliers as $supplier)
                                <option value="supplier_{{ $supplier->id }}">{{ $supplier->name }}</option>
                            @endforeach
                        </optgroup>
                        <optgroup label="Agents">
                            @foreach ($agents as $agent)
                                <option value="agent_{{ $agent->id }}">{{ $agent->name }}</option>
                            @endforeach
                        </optgroup>
                    </select>
                </div>
            </div>

            <!-- Visa and Pricing Section -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                <!-- Visa -->
                <div>
                    <label for="visa" class="block text-sm font-medium text-gray-700 mb-1">Visa No</label>
                    <input type="text" name="visa" id="visa" required
                        class="block w-full px-3 py-2 rounded border border-gray-300">
                </div>

                <!-- ID Number -->
                <div>
                    <label for="id_no" class="block text-sm font-medium text-gray-700 mb-1">ID No</label>
                    <input type="text" name="id_no" id="id_no" required
                        class="block w-full px-3 py-2 rounded border border-gray-300">
                </div>

                <!-- Country -->
                <div>
                    <label for="country" class="block text-sm font-medium text-gray-700 mb-1">Country</label>
                    <input type="text" name="country" id="country" required
                        class="block w-full px-3 py-2 rounded border border-gray-300">
                </div>

                <!-- Buying Price -->
                <div>
                    <label for="buying_price" class="block text-sm font-medium text-gray-700 mb-1">Buying
                        Price</label>
                    <input type="text" name="buying_price" id="buying_price" required
                        class="block w-full px-3 py-2 rounded border border-gray-300">
                </div>
            </div>

            <!-- Pricing Section -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                <!-- Currency Rate -->
                <div>
                    <label for="multi_currency" class="block text-sm font-medium text-gray-700 mb-1">Currency
                        Rate</label>
                    <input type="number" name="multi_currency" id="multi_currency" required value="1.00"
                        step="0.01" min="0" class="block w-full px-3 py-2 rounded border border-gray-300">
                </div>

                <!-- Total Buying Price -->
                <div>
                    <label for="total_price" class="block text-sm font-medium text-gray-700 mb-1">Total Buying
                        Price</label>
                    <input type="text" name="total_price" id="total_price" readonly
                        class="block w-full px-3 py-2 rounded border border-gray-300 bg-gray-100">
                </div>

                <!-- Selling Price -->
                <div>
                    <label for="selling_price" class="block text-sm font-medium text-gray-700 mb-1">Selling
                        Price</label>
                    <input type="text" name="selling_price" id="selling_price" required
                        class="block w-full px-3 py-2 rounded border border-gray-300">
                </div>

                <!-- Quantity -->
                <div>
                    <label for="quantity" class="block text-sm font-medium text-gray-700 mb-1">Total Quantity
                        Sold</label>
                    <input type="number" name="quantity" id="quantity" required min="1"
                        class="block w-full px-3 py-2 rounded border border-gray-300">
                </div>
            </div>

            <!-- Sales By Section -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-1">Sold By</label>
                <input type="text" name="sales_by" id="sales_by" required
                    class="block w-full md:w-1/2 lg:w-1/4 px-3 py-2 rounded border border-gray-300" value="">
            </div>

            <!-- Return Information Section -->
            <div class="border-t pt-4 mb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Return Information</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <!-- Return Quantity -->
                    <div>
                        <label for="return_quantity" class="block text-sm font-medium text-gray-700 mb-1">Return
                            Quantity</label>
                        <input type="number" name="return_quantity" id="return_quantity" min="0"
                            class="block w-full px-3 py-2 rounded border border-gray-300">
                    </div>

                    <!-- Return Date -->
                    <div>
                        <label for="return_date" class="block text-sm font-medium text-gray-700 mb-1">Return
                            Date</label>
                        <input type="date" name="return_date" id="return_date"
                            class="block w-full px-3 py-2 rounded border border-gray-300">
                    </div>

                    <!-- Return Reason -->
                    <div>
                        <label for="return_reason" class="block text-sm font-medium text-gray-700 mb-1">Return
                            Reason</label>
                        <select name="return_reason" id="return_reason"
                            class="block w-full px-3 py-2 rounded border border-gray-300">
                            <option value="">Select Reason</option>
                            <option value="Cancelled by client">Cancelled by client</option>
                            <option value="Visa rejected">Visa rejected</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>

                    <!-- Return Status -->
                    <div>
                        <label for="return_status" class="block text-sm font-medium text-gray-700 mb-1">Return
                            Status</label>
                        <select name="return_status" id="return_status"
                            class="block w-full px-3 py-2 rounded border border-gray-300">
                            <option value="">Status</option>
                            <option value="Available for Resale">Available for Resale</option>
                            <option value="Processed">Processed</option>
                            <option value="Cancelled">Cancelled</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Additional Notes -->
            <div class="mb-6">
                <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Additional
                    Notes</label>
                <textarea name="notes" id="notes" rows="3"
                    class="block w-full px-3 py-2 rounded border border-gray-300"></textarea>
            </div>

            <!-- Form Actions -->
            <div class="flex flex-wrap justify-between items-center border-t pt-4">
                <button type="button" onclick="resetForm()"
                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 border border-gray-300 rounded hover:bg-gray-200 mr-2 mb-2">
                    Reset
                </button>

                <div>
                    <button type="button" onclick="toggleWakalaContainer()"
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded hover:bg-gray-50 mr-2 mb-2">
                        Cancel
                    </button>

                    <button type="submit"
                        class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded hover:bg-blue-700 mb-2">
                        Save Wakala Record
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="mx-auto px-4 py-8 mt-4">
    <div class="bg-white shadow-sm rounded-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h2 class="text-lg font-semibold text-gray-900">Wakala Records</h2>
            <button onclick="toggleWakalaContainer()"
                class="px-4 py-2 bg-blue-600 text-white text-sm rounded-md hover:bg-blue-700 transition-colors">
                + New Wakala
            </button>
        </div>

        <div class="overflow-x-auto">
            <table class="divide-y divide-gray-200 datatable" id="wakalatable">
                <thead class="bg-gray-200 font-bold">
                    <tr>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-700 font-bold uppercase tracking-wider">
                            Invoice
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-700 font-bold uppercase tracking-wider">
                            Date
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-700 font-bold uppercase tracking-wider">
                            Agent
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-700 font-bold uppercase tracking-wider">
                            Supplier
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-700 font-bold uppercase tracking-wider">
                            Visa No
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-700 font-bold uppercase tracking-wider">
                            ID No
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-700 font-bold uppercase tracking-wider">
                            Country
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-700 font-bold uppercase tracking-wider">
                            Quantity
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-700 font-bold uppercase tracking-wider">
                            Buying Price
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-700 font-bold uppercase tracking-wider">
                            Selling Price
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-700 font-bold uppercase tracking-wider">
                            Sold By
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-700 font-bold uppercase tracking-wider">
                            Status
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-right text-xs font-medium text-gray-700 font-bold uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($wakalas as $wakala)
                        @if ($wakala->is_returned == 0)
                            <tr class="bg-blue-50">
                            @else
                            <tr class="bg-red-50">
                        @endif
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ $wakala->invoice }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ \Carbon\Carbon::parse($wakala->date)->format('d M Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <div class="flex items-center">
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $agentsname[$wakala->agent] ?? 'N/A' }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    <span
                                        class="inline-flex items-center justify-center h-10 w-10 rounded-full bg-blue-100">
                                        <span
                                            class="text-blue-600">{{ strtoupper(substr($wakala->agent_supplier, 0, 1)) }}</span>
                                    </span>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">
                                        @if ($wakala->agent_supplier == 'agent')
                                            {{ $agentsname[$wakala->supplier] ?? 'N/A' }}
                                        @else
                                            {{ $suppliersname[$wakala->supplier] ?? 'N/A' }}
                                        @endif
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        {{ ucfirst($wakala->agent_supplier) }}
                                    </div>
                                </div>
                            </div>
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $wakala->visa }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $wakala->id_no }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $wakala->country }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <div class="flex flex-col">
                                @if ($wakala->is_returned == 1)
                                    <span class="text-xs text-green-500 mt-1">
                                        (Available: {{ $wakala->quantity - $wakala->return_quantity }})
                                    </span>
                                @endif
                                <span class=" text-lg text-green-800 mt-1 text-sm md:text-base tracking-tight">
                                    Total: {{ $wakala->quantity }}
                                </span>
                            </div>
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <div class="flex flex-col items-start">
                                <!-- Currency Rate -->
                                <div class="flex items-center">
                                    <span class="text-sm font-medium text-gray-600 mr-1">Rate:</span>
                                    <span
                                        class="text-sm font-mono">{{ number_format($wakala->multi_currency, 2) }}</span>
                                </div>

                                <!-- Total Price - Highlighted -->
                                <div class="mt-1">
                                    <span
                                        class="text-xs font-semibold px-2 py-1 rounded 
                                        @if ($wakala->total_price >= 0) bg-green-100 text-green-800
                                        @else
                                            bg-red-100 text-red-800 @endif">
                                        {{ number_format(abs($wakala->total_price), 2) }}
                                        @if ($wakala->total_price < 0)
                                            (Debit)
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </td>


                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <span class="text-sm font-medium text-gray-600 mr-1">{{ $wakala->selling_price }}</span>
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $wakala->sales_by }}
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap">
                            @if ($wakala->return_status === null)
                                <span
                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    Completed
                                </span>
                            @else
                                <span @class([
                                    'px-2 inline-flex text-xs leading-5 font-semibold rounded-full',
                                    'bg-blue-100 text-blue-800' =>
                                        $wakala->return_status === 'Available for Resale',
                                    'bg-yellow-100 text-yellow-800' => $wakala->return_status === 'Processed',
                                    'bg-red-100 text-red-800' => $wakala->return_status === 'Cancelled',
                                ])>
                                    {{ $wakala->return_status }}
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex justify-end space-x-2">
                                @if($wakala->is_returned == 1)
                                    <button type="button" 
                                            onclick="openSellModal('{{ route('wakalas.sell', $wakala->id) }}')"
                                            class="p-1 rounded-full text-green-600 hover:text-green-800 hover:bg-green-50 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-1"
                                            title="Sell Returned Item"
                                            aria-label="Sell returned item">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                @endif

                                <a href="#" onclick="openEditModal('{{ route('wakalas.edit', $wakala->id) }}')"
                                    class="text-blue-600 hover:text-blue-900">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                        fill="currentColor">
                                        <path
                                            d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                    </svg>
                                </a>
                                <form action="{{ route('wakalas.destroy', $wakala->id) }}" method="POST"
                                    class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900"
                                        onclick="return confirm('Are you sure?')">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                            fill="currentColor">
                                            <path fill-rule="evenodd"
                                                d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                </form>
                                <a href="#" class="text-gray-600 hover:text-gray-900"
                                    onclick="openViewModalWakala({{ $wakala->id }})">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                        fill="currentColor">
                                        <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                        <path fill-rule="evenodd"
                                            d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </a>

                            </div>
                        </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- After (working version) -->
        @if ($wakalas instanceof \Illuminate\Pagination\AbstractPaginator && $wakalas->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $wakalas->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Success Modal -->
<div id="successModal" class="fixed inset-0 z-50 flex items-center justify-center hidden">
    <div class="absolute inset-0 bg-black opacity-50"></div>
    <div class="bg-white rounded-lg p-6 z-10 max-w-md w-full">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-bold text-green-600">Success!</h3>
            <button onclick="document.getElementById('successModal').classList.add('hidden')"
                class="text-gray-500 hover:text-gray-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <p class="text-gray-700 mb-4">Wakala record has been created successfully.</p>
        <div class="flex justify-end">
            <button onclick="document.getElementById('successModal').classList.add('hidden')"
                class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                OK
            </button>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div id="editModal" 
     class="hidden fixed inset-0 z-50 overflow-y-scroll" 
     aria-labelledby="modal-title" 
     aria-modal="true" 
     role="dialog"
     style="top: 9.25rem">
     
    <!-- Background overlay with click-to-close functionality -->
    <div class="fixed inset-0 bg-gray-600 bg-opacity-50 transition-opacity" onclick="closeEditModal()"></div>
    
    <!-- Modal container -->
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="relative w-full max-w-2xl mx-auto">
            <!-- Modal content -->
            <div class="relative bg-white rounded-lg shadow-xl" style="max-height: 700px; overflow-y: scroll;">
                
                <!-- Modal body -->
                <div class="p-6">
                   
                    <!-- Form container -->
                    <div id="editFormContainer" class="space-y-4"></div>
                </div>
             
            </div>
        </div>
    </div>
</div>

{{-- sell modal --}}
<div id="sellModal" class="hidden fixed inset-0 bg-gray-600/50 overflow-y-auto z-50 transition-opacity duration-300">
    <!-- Modal container with responsive sizing -->
    <div class="flex items-center justify-center min-h-screen px-4 py-6 sm:p-6">
        <!-- Modal content -->
        <div class="relative bg-white rounded-lg shadow-xl w-full max-w-2xl mx-auto overflow-hidden">
            <!-- Close button -->
            <button onclick="closeSellModal()" 
                    class="absolute top-3 right-3 p-1 rounded-full text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>

            <!-- Modal header -->
            <div class="bg-green-50 px-6 py-4 border-b border-green-100">
                <h3 class="text-lg font-medium text-green-800 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                    </svg>
                    Sell Returned Wakala
                </h3>
            </div>

            <!-- Modal body -->
            <div class="p-4 sm:p-6">
                <div id="sellFormContainer" class="space-y-4"></div>
            </div>

           
        </div>
    </div>
</div>


{{-- view modal --}}
<div id="viewWakalaModal" class="fixed inset-0 z-50 hidden overflow-y-auto bg-black/30 backdrop-blur-sm">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-4xl overflow-hidden">
            <!-- Modal Header -->
            <div class="bg-gradient-to-r from-blue-600 to-blue-800 px-6 py-4 flex justify-between items-center">
                <div>
                    <h2 class="text-xl font-bold text-white">Wakala Transaction #<span id="view-invoice"></span></h2>
                    <p id="view-date" class="text-blue-100 text-sm mt-1"></p>
                </div>
                <button onclick="closeViewModalWakala()" class="text-white hover:text-blue-200 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="p-6 space-y-6">
                <!-- Main Information Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Transaction Card -->
                    <div class="bg-gray-50 rounded-lg p-5 border border-gray-200">
                        <h3
                            class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b border-gray-200 flex items-center">
                            <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                                </path>
                            </svg>
                            Transaction Details
                        </h3>
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-sm font-medium text-gray-500">Visa Number</span>
                                <span id="view-visa" class="text-sm font-medium text-gray-900"></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm font-medium text-gray-500">ID Number</span>
                                <span id="view-id_no" class="text-sm font-medium text-gray-900"></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm font-medium text-gray-500">Country</span>
                                <span id="view-country" class="text-sm font-medium text-gray-900"></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm font-medium text-gray-500">Sold By</span>
                                <span id="view-sales_by" class="text-sm font-medium text-gray-900"></span>
                            </div>
                        </div>
                    </div>

                    <!-- Financial Card -->
                    <div class="bg-gray-50 rounded-lg p-5 border border-gray-200">
                        <h3
                            class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b border-gray-200 flex items-center">
                            <svg class="w-5 h-5 text-green-600 mr-2" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                </path>
                            </svg>
                            Financial Information
                        </h3>
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-sm font-medium text-gray-500">Quantity</span>
                                <span id="view-quantity" class="text-sm font-medium text-gray-900"></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm font-medium text-gray-500">Buying Price</span>
                                <span id="view-buying_price" class="text-sm font-medium text-gray-900"></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm font-medium text-gray-500">Selling Price</span>
                                <span id="view-selling_price" class="text-sm font-medium text-gray-900"></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm font-medium text-gray-500">Total Amount</span>
                                <span id="view-total_price" class="text-sm font-semibold text-blue-600"></span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Parties Information -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Agent Card -->
                    <div class="bg-gray-50 rounded-lg p-5 border border-gray-200">
                        <h3
                            class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b border-gray-200 flex items-center">
                            <svg class="w-5 h-5 text-purple-600 mr-2" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                                </path>
                            </svg>
                            Agent Details
                        </h3>
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-sm font-medium text-gray-500">Agent Name</span>
                                <span id="view-agent" class="text-sm font-medium text-gray-900"></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm font-medium text-gray-500">Contact</span>
                                <span id="view-agent-contact" class="text-sm font-medium text-gray-900"></span>
                            </div>
                        </div>
                    </div>

                    <!-- Supplier Card -->
                    <div class="bg-gray-50 rounded-lg p-5 border border-gray-200">
                        <h3
                            class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b border-gray-200 flex items-center">
                            <svg class="w-5 h-5 text-orange-600 mr-2" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                                </path>
                            </svg>
                            Supplier Details
                        </h3>
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-sm font-medium text-gray-500">Supplier Name</span>
                                <span id="view-supplier" class="text-sm font-medium text-gray-900"></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm font-medium text-gray-500">Contact</span>
                                <span id="view-supplier-contact" class="text-sm font-medium text-gray-900"></span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Return Information (Conditional) -->
                <div id="return-info-container" class="hidden">
                    <div class="bg-red-50 rounded-lg p-5 border border-red-200">
                        <h3
                            class="text-lg font-semibold text-red-800 mb-4 pb-2 border-b border-red-200 flex items-center">
                            <svg class="w-5 h-5 text-red-600 mr-2" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                                </path>
                            </svg>
                            Return Information
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="flex justify-between">
                                <span class="text-sm font-medium text-red-600">Return Quantity</span>
                                <span id="view-return_quantity" class="text-sm font-medium text-red-800"></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm font-medium text-red-600">Return Date</span>
                                <span id="view-return_date" class="text-sm font-medium text-red-800"></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm font-medium text-red-600">Return Reason</span>
                                <span id="view-return_reason" class="text-sm font-medium text-red-800"></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm font-medium text-red-600">Return Status</span>
                                <span id="view-return_status" class="text-sm font-semibold"></span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Notes Section -->
                <div id="notes-container" class="hidden">
                    <div class="bg-blue-50 rounded-lg p-5 border border-blue-200">
                        <h3 class="text-lg font-semibold text-blue-800 mb-3 flex items-center">
                            <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                </path>
                            </svg>
                            Additional Notes
                        </h3>
                        <p id="view-notes" class="text-sm text-gray-700"></p>
                    </div>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="bg-gray-50 px-6 py-4 border-t flex justify-end space-x-3">
                <button onclick="closeViewModalWakala()"
                    class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-100">
                    Close
                </button>
                <button onclick="printWakalaDetails()"
                    class="px-4 py-2 bg-blue-600 rounded-md text-sm font-medium text-white hover:bg-blue-700 flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z">
                        </path>
                    </svg>
                    Print
                </button>
            </div>
        </div>
    </div>
</div>
{{-- 
    </div>
</div> --}}




<script>
    function resetForm() {
        document.getElementById('wakalaForm').reset();
        // Reset any calculated fields if needed
        document.getElementById('total_price').value = '';
    }
</script>

<script>
   

    function openSellModal(url) {
        // Fetch form content via AJAX
        fetch(url)
            .then(response => response.text())
            .then(html => {
                document.getElementById('sellFormContainer').innerHTML = html;
                initResellWakala();
                document.getElementById('sellModal').classList.remove('hidden');
                document.body.style.overflow = 'hidden'; // Prevent background scrolling
            });
    }

    function openViewModalWakala(id) {
        fetch(`/wakalas/show/${id}`)
            .then(response => {
                if (!response.ok) throw new Error('Failed to fetch details');
                return response.json();
            })
            .then(data => {
                // Format dates
                const formatDate = (dateString) => {
                    if (!dateString) return 'N/A';
                    const options = {
                        year: 'numeric',
                        month: 'short',
                        day: 'numeric'
                    };
                    return new Date(dateString).toLocaleDateString(undefined, options);
                };

                // Format currency
                const formatCurrency = (amount) => {
                    return parseFloat(amount || 0).toLocaleString(undefined, {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    });
                };

                // Set basic information
                document.getElementById('view-invoice').textContent = data.invoice;
                document.getElementById('view-date').textContent = formatDate(data.date);
                document.getElementById('view-visa').textContent = data.visa || 'N/A';
                document.getElementById('view-id_no').textContent = data.id_no || 'N/A';
                document.getElementById('view-country').textContent = data.country || 'N/A';
                document.getElementById('view-sales_by').textContent = data.sales_by || 'N/A';

                // Set financial information
                document.getElementById('view-quantity').textContent = data.quantity;
                document.getElementById('view-buying_price').textContent = formatCurrency(data.buying_price);
                document.getElementById('view-selling_price').textContent = formatCurrency(data.selling_price);
                document.getElementById('view-total_price').textContent = formatCurrency(data.total_price);

                // Set agent and supplier information
                document.getElementById('view-agent').textContent = data.agentname || 'N/A';
                document.getElementById('view-agent-contact').textContent = data.agent_contact || 'N/A';
                document.getElementById('view-supplier').textContent = data.suppliername || 'N/A';
                document.getElementById('view-supplier-contact').textContent = data.supplier_contact || 'N/A';

                // Handle return information
                const returnContainer = document.getElementById('return-info-container');
                if (data.return_quantity) {
                    returnContainer.classList.remove('hidden');
                    document.getElementById('view-return_quantity').textContent = data.return_quantity;
                    document.getElementById('view-return_date').textContent = formatDate(data.return_date);
                    document.getElementById('view-return_reason').textContent = data.return_reason || 'N/A';

                    const statusElement = document.getElementById('view-return_status');
                    statusElement.textContent = data.return_status || 'N/A';
                    // Color coding for status
                    if (data.return_status === 'Available for Resale') {
                        statusElement.classList.add('text-blue-600');
                    } else if (data.return_status === 'Processed') {
                        statusElement.classList.add('text-green-600');
                    } else if (data.return_status === 'Cancelled') {
                        statusElement.classList.add('text-red-600');
                    }
                } else {
                    returnContainer.classList.add('hidden');
                }

                // Handle notes
                const notesContainer = document.getElementById('notes-container');
                if (data.notes) {
                    notesContainer.classList.remove('hidden');
                    document.getElementById('view-notes').textContent = data.notes;
                } else {
                    notesContainer.classList.add('hidden');
                }

                // Show modal
                document.getElementById('viewWakalaModal').classList.remove('hidden');
            })
            .catch(error => {
                console.error('Error:', error);
                // Show error in modal
                document.getElementById('wakalaDetails').innerHTML = `
                <div class="text-center py-8">
                    <svg class="mx-auto h-12 w-12 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <h3 class="mt-2 text-lg font-medium text-gray-900">Failed to load details</h3>
                    <p class="mt-1 text-sm text-gray-500">${error.message}</p>
                </div>
            `;
                document.getElementById('viewWakalaModal').classList.remove('hidden');
            });
    }


    function printWakalaDetails() {
        // Implement print functionality
        window.print();
    }
    // Helper function for status colors
    function getStatusColorClass(status) {
        switch (status) {
            case 'Available for Resale':
                return 'text-blue-600';
            case 'Processed':
                return 'text-yellow-600';
            case 'Cancelled':
                return 'text-red-600';
            default:
                return 'text-gray-600';
        }
    }

    function closeViewModalWakala() {
        const modal = document.getElementById('viewWakalaModal');

        if (modal) {
            modal.style.opacity = 0; // Fade out
            modal.style.visibility = 'hidden'; // Hide the modal after fading out
            modal.style.transition = 'opacity 0.5s ease'; // Add transition for fade effect
        } else {
            console.error('Modal not found: viewWakalaModal');
        }
    }
</script>

<script>
    function openEditModal(url) {
        // Show modal
        document.getElementById('editModal').classList.remove('hidden');

        fetch(url)
            .then(response => {
                // console.log(response.wakala);
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.text();
            })
            .then(html => {
                // console.log('Received HTML:', html); // Debugging
                document.getElementById('editFormContainer').innerHTML = html;

                initEditForm();
            })
            .catch(error => {
                console.error('Error loading edit form:', error);
                // Show error to user
                document.getElementById('editFormContainer').innerHTML = `
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                            Error loading form: ${error.message}
                        </div>
                    `;
            });
    }

    function closeEditModal() {
        document.getElementById('editModal').classList.add('hidden');
        document.getElementById('editFormContainer').innerHTML = '';
    }
    function closeSellModal() {
        document.getElementById('sellModal').classList.add('hidden');
        document.getElementById('sellFormContainer').innerHTML = '';
    }


    function initEditForm() {
        const form = document.getElementById('editWakalaForm');
        if (form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();

                const formData = new FormData(form);
                formData.append('_method', 'PUT'); // 🛠 Add _method=PUT manually

                fetch(form.action, {
                        method: 'POST', // 🛠 Use POST, not PUT
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            closeEditModal();
                            alert('Record updated successfully!');
                            location.reload();
                        } else {
                            console.error('Validation Errors:', data.errors);
                            alert('Failed to update record.');
                        }
                    })

                    .catch(error => console.error('Error:', error));
            });

            // 🛠 Your calculateTotalPrice script
            const buyingPrice = document.getElementById('buying_price_edit');
            const currencyRate = document.getElementById('multi_currency_edit');
            const totalPrice = document.getElementById('total_price_edit');

            function calculateTotalPrice() {
                const price = parseFloat(buyingPrice.value) || 0;
                const rate = parseFloat(currencyRate.value) || 1;
                totalPrice.value = (price * rate).toFixed(2);
            }

            if (buyingPrice && currencyRate) {
                buyingPrice.addEventListener('input', calculateTotalPrice);
                currencyRate.addEventListener('input', calculateTotalPrice);
                calculateTotalPrice(); // initialize once
            }
        }

    }


    function initResellWakala() {
    const form = document.getElementById('sellWakalaForm');
    if (!form) return;

    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const submitBtn = form.querySelector('button[type="submit"]');
        submitBtn.disabled = true;
        submitBtn.innerHTML = 'Processing...';

        // Create FormData and append _method
        const formData = new FormData(form);
        formData.append('_method', 'POST'); // This makes Laravel treat it as PUT request

        fetch(form.action, {
            method: 'POST', // Always use POST for FormData submissions
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                // 'Accept': 'application/json'
            }
        })
        // fetch(form.action, {
        //     method: 'POST',
        //     body: formData,
        //     headers: {
        //         'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        //         'Accept': 'application/json',
        //         'Content-Type': 'application/json'
        //     }
        // })
        .then(async response => {
            const data = await response.json().catch(() => ({}));
            
            if (!response.ok) {
                throw new Error(data.message || 'Request failed');
            }
            
            if (data.success) {
                showToast({
                    message: data.message || 'Wakala resold successfully!',
                    type: 'success'
                });
                setTimeout(() => window.location.reload(), 2000);
            } else {
                showFormErrors(data.errors || {});
            }
            return data;
        })
        .catch(error => {
            console.error('Error:', error);
            showErrorToast(error.message || 'An error occurred during resale');
        })
        .finally(() => {
            submitBtn.disabled = false;
            submitBtn.innerHTML = 'Confirm Resell';
        });

    });
}
function showErrorToast(message) {
    const toastEl = document.getElementById('errorToast');
    const toastMessage = document.getElementById('toastMessage');
    toastMessage.textContent = message;
    
    if (!toastEl._toast) {
        toastEl._toast = new bootstrap.Toast(toastEl, {
            autohide: true,
            delay: 5000
        });
    }
    
    toastEl._toast.show();
}
// Helper function to show errors
function showFormErrors(errors) {
    // Clear previous errors
    document.querySelectorAll('.is-invalid').forEach(el => {
        el.classList.remove('is-invalid');
    });
    document.querySelectorAll('.invalid-feedback').forEach(el => {
        el.remove();
    });

    // Add new errors
    for (const [field, messages] of Object.entries(errors)) {
        const input = document.querySelector(`[name="${field}"]`);
        if (input) {
            const parent = input.closest('.form-group') || input.parentElement;
            input.classList.add('is-invalid');
            
            const errorDiv = document.createElement('div');
            errorDiv.className = 'invalid-feedback text-danger mt-1';
            errorDiv.innerHTML = messages.join('<br>');
            parent.appendChild(errorDiv);
        }
    }
}
</script>

<script>
    function toggleWakalaContainer(parameter = null) {
        if (parameter == null) {
            const container = document.getElementById('wakalacontainer');
            container.classList.toggle('hidden');

            // Optional: Scroll to the container when shown
            if (!container.classList.contains('hidden')) {
                container.scrollIntoView({
                    behavior: 'smooth'
                });
            }
        }
        if (parameter == 'hide') {
            const container = document.getElementById('wakalacontainer');
            container.classList.toggle('hidden');

            // Optional: Scroll to the container when shown
            if (!container.classList.contains('hidden')) {
                container.scrollIntoView({
                    behavior: 'smooth'
                });
            }
        }

    }
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const buyingPrice = document.getElementById('buying_price');
        const currencyRate = document.getElementById('multi_currency');
        const totalPrice = document.getElementById('total_price');

        function calculateTotalPrice() {
            const price = parseFloat(buyingPrice.value) || 0;
            const rate = parseFloat(currencyRate.value) || 1;
            const total = (price * rate).toFixed(2);
            totalPrice.value = isNaN(total) ? '0.00' : total;
        }

        // Calculate on input changes
        buyingPrice.addEventListener('input', calculateTotalPrice);
        currencyRate.addEventListener('input', calculateTotalPrice);

        // Initial calculation
        calculateTotalPrice();
    });
</script>

<script>
    document.getElementById('wakalaForm').addEventListener('submit', function(e) {
        e.preventDefault();

        // Show loading state if needed
        const submitBtn = this.querySelector('button[type="submit"]');
        submitBtn.disabled = true;
        submitBtn.innerHTML = 'Processing...';

        fetch(this.action, {
                method: this.method,
                body: new FormData(this),
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(err => {
                        throw err;
                    });
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    // Show success modal
                    document.getElementById('successModal').classList.remove('hidden');

                    // Redirect after delay
                    setTimeout(() => {
                        window.location.href = "{{ route('order.view') }}";
                    }, 2000);
                } else {
                    // Handle validation errors
                    if (data.errors) {
                        let errorHtml = '<ul class="text-red-600">';
                        for (const [field, message] of Object.entries(data.errors)) {
                            errorHtml += `<li>${message}</li>`;
                            // Highlight problematic fields
                            const input = document.querySelector(`[name="${field}"]`);
                            if (input) {
                                input.classList.add('border-red-500');
                            }
                        }
                        errorHtml += '</ul>';

                        // Show errors in a div with id "formErrors"
                        const errorContainer = document.getElementById('formErrors');
                        if (errorContainer) {
                            errorContainer.innerHTML = errorHtml;
                            errorContainer.classList.remove('hidden');
                        }
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert(error.error || 'An error occurred');
            })
            .finally(() => {
                submitBtn.disabled = false;
                submitBtn.innerHTML = 'Submit';
            });
    });
</script>
