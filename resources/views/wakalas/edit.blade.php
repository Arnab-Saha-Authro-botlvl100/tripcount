<form id="editWakalaForm" action="{{ route('wakalas.update', $wakala->id) }}" method="POST" class="bg-white rounded-lg">
    @csrf
    @method('PUT')

    <h2 class="text-xl font-bold text-gray-900 mb-6 border-b pb-2">Edit Wakala Record</h2>

    <!-- Main Information Section -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <!-- Invoice Number -->
        <div>
            <label for="invoice" class="block text-sm font-medium text-gray-700 mb-1">Invoice No</label>
            <input type="text" name="invoice" id="invoice" required readonly value="{{ $wakala->invoice }}"
                class="block w-full px-3 py-2 rounded border border-gray-300 bg-gray-100 text-gray-700">
        </div>

        <!-- Date -->
        <div>
            <label for="date" class="block text-sm font-medium text-gray-700 mb-1">Date</label>
            <input type="date" name="date" id="date" required
                value="{{ \Carbon\Carbon::parse($wakala->date)->format('Y-m-d') }}"
                class="block w-full px-3 py-2 rounded border border-gray-300">
        </div>

        <!-- Agent -->
        <div>
            <label for="agent" class="block text-sm font-medium text-gray-700 mb-1">Agent</label>
            <select name="agent" id="agent" required
                class="block w-full px-3 py-2 rounded border border-gray-300 select2">
                <option value="">Select Agent</option>
                @foreach ($agents as $id => $name)
                    <option value="{{ $id }}" {{ $wakala->agent == $id ? 'selected' : '' }}>
                        {{ $name }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Supplier -->
        <div>
            <label for="supplier" class="block text-sm font-medium text-gray-700 mb-1">Supplier</label>
            <select name="supplier" id="supplier" required
                class="block w-full px-3 py-2 rounded border border-gray-300 select2">
                <option value="">Select Supplier</option>

                <optgroup label="Suppliers">
                    @foreach ($suppliers as $id => $name)
                        <option value="supplier_{{ $id }}"
                            {{ $wakala->agent_supplier == 'supplier' && $wakala->supplier == $id ? 'selected' : '' }}>
                            {{ $name }}
                        </option>
                    @endforeach
                </optgroup>
                <optgroup label="Agents">
                    @foreach ($agents as $id => $name)
                        <option value="agent_{{ $id }}"
                            {{ $wakala->agent_supplier == 'agent' && $wakala->supplier == $id ? 'selected' : '' }}>
                            {{ $name }}
                        </option>
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
            <input type="text" name="visa" id="visa" required value="{{ $wakala->visa }}"
                class="block w-full px-3 py-2 rounded border border-gray-300">
        </div>

        <!-- ID Number -->
        <div>
            <label for="id_no" class="block text-sm font-medium text-gray-700 mb-1">ID No</label>
            <input type="text" name="id_no" id="id_no" required value="{{ $wakala->id_no }}"
                class="block w-full px-3 py-2 rounded border border-gray-300">
        </div>

        <!-- Country -->
        <div>
            <label for="country" class="block text-sm font-medium text-gray-700 mb-1">Country</label>
            <input type="text" name="country" id="country" required value="{{ $wakala->country }}"
                class="block w-full px-3 py-2 rounded border border-gray-300">
        </div>

        <!-- Buying Price -->
        <div>
            <label for="buying_price" class="block text-sm font-medium text-gray-700 mb-1">Buying Price</label>
            <input type="text" name="buying_price" id="buying_price" required value="{{ $wakala->buying_price }}"
                class="block w-full px-3 py-2 rounded border border-gray-300">
        </div>
    </div>

    <!-- Pricing Section -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <!-- Currency Rate -->
        <div>
            <label for="multi_currency" class="block text-sm font-medium text-gray-700 mb-1">Currency Rate</label>
            <input type="number" name="multi_currency" id="multi_currency" required
                value="{{ $wakala->multi_currency }}" step="0.01" min="0"
                class="block w-full px-3 py-2 rounded border border-gray-300">
        </div>

        <!-- Total Buying Price -->
        <div>
            <label for="total_price" class="block text-sm font-medium text-gray-700 mb-1">Total Buying Price</label>
            <input type="text" name="total_price" id="total_price" readonly value="{{ $wakala->total_price }}"
                class="block w-full px-3 py-2 rounded border border-gray-300 bg-gray-100">
        </div>

        <!-- Selling Price -->
        <div>
            <label for="selling_price" class="block text-sm font-medium text-gray-700 mb-1">Selling Price</label>
            <input type="text" name="selling_price" id="selling_price" required value="{{ $wakala->selling_price }}"
                class="block w-full px-3 py-2 rounded border border-gray-300">
        </div>
        <!-- Individual Selling Price -->
        <div class="mb-4">
            <label for="single_selling_price" class="block text-sm font-medium text-gray-700 mb-1">
                Single Selling Price
            </label>
            <div class="relative rounded-md shadow-sm">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <span class="text-gray-500 sm:text-sm"></span>
                </div>
                <input type="text" name="single_selling_price" id="single_selling_price" required readonly
                    value="{{ number_format($wakala->selling_price / $wakala->quantity, 2) }}"
                    class="block w-full pl-12 pr-3 py-2 rounded-md border border-gray-300 bg-gray-100 focus:outline-none focus:ring-1 focus:ring-blue-500">
            </div>
            @error('single_selling_price')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Quantity -->
        <div>
            <label for="quantity" class="block text-sm font-medium text-gray-700 mb-1">Total Quantity Sold</label>
            <input type="number" name="quantity" id="quantity" required min="1"
                value="{{ $wakala->quantity }}" class="block w-full px-3 py-2 rounded border border-gray-300">
        </div>
    </div>

    <!-- Sales By Section -->
    <div class="mb-6">
        <label class="block text-sm font-medium text-gray-700 mb-1">Sold By</label>
        <input type="text" name="sales_by" id="sales_by" required
            class="block w-full md:w-1/2 lg:w-1/4 px-3 py-2 rounded border border-gray-300"
            value="{{ $wakala->sales_by }}">
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
                    value="{{ $wakala->return_quantity }}"
                    class="block w-full px-3 py-2 rounded border border-gray-300">
            </div>

            <!-- Return Date -->
            <div>
                <label for="return_date" class="block text-sm font-medium text-gray-700 mb-1">Return Date</label>
                <input type="date" name="return_date" id="return_date"
                    value="{{ $wakala->return_date ? \Carbon\Carbon::parse($wakala->return_date)->format('Y-m-d') : '' }}"
                    class="block w-full px-3 py-2 rounded border border-gray-300">
            </div>

            <!-- Return Reason -->
            <div>
                <label for="return_reason" class="block text-sm font-medium text-gray-700 mb-1">Return Reason</label>
                <select name="return_reason" id="return_reason"
                    class="block w-full px-3 py-2 rounded border border-gray-300">
                    <option value="">Select Reason</option>
                    <option value="Cancelled by client"
                        {{ $wakala->return_reason == 'Cancelled by client' ? 'selected' : '' }}>Cancelled by client
                    </option>
                    <option value="Visa rejected" {{ $wakala->return_reason == 'Visa rejected' ? 'selected' : '' }}>
                        Visa rejected</option>
                    <option value="Other" {{ $wakala->return_reason == 'Other' ? 'selected' : '' }}>Other</option>
                </select>
            </div>

            <!-- Return Status -->
            <div>
                <label for="return_status" class="block text-sm font-medium text-gray-700 mb-1">Return Status</label>
                <select name="return_status" id="return_status"
                    class="block w-full px-3 py-2 rounded border border-gray-300">
                    <option value="">Status</option>
                    <option value="Available for Resale"
                        {{ $wakala->return_status == 'Available for Resale' ? 'selected' : '' }}>Available for Resale
                    </option>
                    <option value="Processed" {{ $wakala->return_status == 'Processed' ? 'selected' : '' }}>Processed
                    </option>
                    <option value="Cancelled" {{ $wakala->return_status == 'Cancelled' ? 'selected' : '' }}>Cancelled
                    </option>
                </select>
            </div>
        </div>
    </div>

    <!-- Additional Notes -->
    <div class="mb-6">
        <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Additional Notes</label>
        <textarea name="notes" id="notes" rows="3"
            class="block w-full px-3 py-2 rounded border border-gray-300">{{ $wakala->note }}</textarea>
    </div>

    <!-- Form Actions -->
    <div class="flex flex-wrap justify-between items-center border-t pt-4">
        <button type="button" onclick="closeEditModal()"
            class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded hover:bg-gray-50 mr-2 mb-2">
            Cancel
        </button>

        <div>
            <button type="submit"
                class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded hover:bg-blue-700 mb-2">
                Update Wakala Record
            </button>
        </div>
    </div>
</form>
