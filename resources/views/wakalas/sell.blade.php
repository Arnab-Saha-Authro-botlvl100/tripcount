{{-- <form id="sellWakalaForm" action="{{ route('wakalas.update', $wakala->id) }}" method="POST" class="bg-white rounded-lg">
    @csrf
    @method('PUT')

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
            <select name="supplier" id="supplier" required readonly
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
            <input type="text" name="visa" id="visa" required value="{{ $wakala->visa }}" readonly
                class="block w-full px-3 py-2 rounded border border-gray-300">
        </div>

        <!-- ID Number -->
        <div>
            <label for="id_no" class="block text-sm font-medium text-gray-700 mb-1">ID No</label>
            <input type="text" name="id_no" id="id_no" required value="{{ $wakala->id_no }}" readonly
                class="block w-full px-3 py-2 rounded border border-gray-300">
        </div>

        <!-- Country -->
        <div>
            <label for="country" class="block text-sm font-medium text-gray-700 mb-1">Country</label>
            <input type="text" name="country" id="country" required value="{{ $wakala->country }}" readonly
                class="block w-full px-3 py-2 rounded border border-gray-300">
        </div>

        <!-- Buying Price -->
        <div>
            <label for="buying_price" class="block text-sm font-medium text-gray-700 mb-1">Buying Price</label>
            <input type="text" name="buying_price" id="buying_price" required value="{{ $wakala->buying_price }}" readonly
                class="block w-full px-3 py-2 rounded border border-gray-300">
        </div>
    </div>

    <!-- Pricing Section -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <!-- Currency Rate -->
        <div>
            <label for="multi_currency" class="block text-sm font-medium text-gray-700 mb-1">Currency Rate</label>
            <input type="number" name="multi_currency" id="multi_currency" required readonly
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
            <input type="text" name="selling_price" id="selling_price" required value="{{ $wakala->selling_price }}" readonly
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
        </div>

        <!-- Quantity -->
        <div>
            <label for="quantity" class="block text-sm font-medium text-gray-700 mb-1">Total Quantity Sold</label>
            <input type="number" name="quantity" id="quantity" required min="1" max="{{ $wakala->return_quantity }}"
                value="{{ $wakala->return_quantity }}" class="block w-full px-3 py-2 rounded border border-gray-300">
        </div>
    </div>

    <!-- Sales By Section -->
    <div class="mb-6">
        <label class="block text-sm font-medium text-gray-700 mb-1">Sold By</label>
        <input type="text" name="sales_by" id="sales_by" required
            class="block w-full md:w-1/2 lg:w-1/4 px-3 py-2 rounded border border-gray-300"
            value="{{ $wakala->sales_by }}">
    </div>

    <!-- Additional Notes -->
    <div class="mb-6">
        <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Additional Notes</label>
        <textarea name="notes" id="notes" rows="3"
            class="block w-full px-3 py-2 rounded border border-gray-300">{{ $wakala->note }}</textarea>
    </div>

    <!-- Form Actions -->
    <div class="flex flex-wrap justify-between items-center border-t pt-4">
        <button type="button" onclick="closeSellModal()"
            class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded hover:bg-gray-50 mr-2 mb-2">
            Cancel
        </button>

        <div>
            <button type="submit"
                class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded hover:bg-blue-700 mb-2">
                Sell Wakala Record
            </button>
        </div>
    </div>
</form> --}}


<form id="sellWakalaForm" action="{{ route('wakalas.resell', $wakala->id) }}" method="POST" class="space-y-6">
    @csrf
    @method('POST')

    <!-- Main Information Section -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Invoice Number -->
        <div>
            <label for="invoice" class="block text-sm font-medium text-gray-700 mb-1">Invoice No</label>
            <input type="text" name="invoice" id="invoice" required readonly value="{{ $wakala->invoice }}"
                class="readonly-field block w-full px-3 py-2 rounded border border-gray-300 bg-gray-100 text-gray-700">
        </div>

        <!-- Date -->
        <div>
            <label for="date" class="block text-sm font-medium text-gray-700 mb-1">Date</label>
            <input type="date" name="date" id="date" required
                value="{{ \Carbon\Carbon::parse($wakala->date)->format('Y-m-d') }}"
                class="standard-field block w-full px-3 py-2 rounded border border-gray-300 text-gray-700">
        </div>

        <!-- Agent -->
        <div>
            <label for="agent" class="block text-sm font-medium text-gray-700 mb-1">Agent</label>
            <select name="agent" id="agent" required
                class="standard-field select2 block w-full px-3 py-2 rounded border border-gray-300 text-gray-700">
                <option value="">Select Agent</option>
                @foreach ($agents as $id => $name)
                    <option value="{{ $id }}" {{ $wakala->agent == $id ? 'selected' : '' }}>
                        {{ $name }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Supplier (Fixed readonly) -->
        <div class="">
            <label for="supplier" class="block text-sm font-medium text-gray-700 mb-1">Supplier</label>
            <input type="text" readonly
                   value="{{ $wakala->agent_supplier == 'supplier' ? $suppliers[$wakala->supplier] ?? '' : $agents[$wakala->supplier] ?? '' }}" 
                   class="readonly-field w-full block w-full px-3 py-2 rounded border border-gray-300 bg-gray-100 text-gray-700">
            <input type="hidden" name="supplier" value="{{ $wakala->supplier }}">
            <input type="hidden" name="agent_supplier" value="{{ $wakala->agent_supplier }}">
        </div>

        <!-- Visa -->
        <div>
            <label for="visa" class="block text-sm font-medium text-gray-700 mb-1">Visa No</label>
            <input type="text" name="visa" id="visa" required value="{{ $wakala->visa }}" readonly
                class="readonly-field block w-full px-3 py-2 rounded border border-gray-300 bg-gray-100 text-gray-700">
        </div>

        <!-- ID Number -->
        <div>
            <label for="id_no" class="block text-sm font-medium text-gray-700 mb-1">ID No</label>
            <input type="text" name="id_no" id="id_no" required value="{{ $wakala->id_no }}" readonly
                class="readonly-field block w-full px-3 py-2 rounded border border-gray-300 bg-gray-100 text-gray-700">
        </div>

        <!-- Country -->
        <div>
            <label for="country" class="block text-sm font-medium text-gray-700 mb-1">Country</label>
            <input type="text" name="country" id="country" required value="{{ $wakala->country }}" readonly
                class="readonly-field block w-full px-3 py-2 rounded border border-gray-300 bg-gray-100 text-gray-700">
        </div>
    </div>

    <!-- Pricing Section -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 bg-gray-50 rounded-lg">
        <!-- Buying Price -->
        <div>
            <label for="buying_price" class="block text-sm font-medium text-gray-700 mb-1">Buying Price</label>
            <input type="text" name="buying_price" id="buying_price" required value="{{ $wakala->buying_price }}" readonly
                class="readonly-field block w-full px-3 py-2 rounded border border-gray-300 bg-gray-100 text-gray-700 ">
        </div>

        <!-- Currency Rate -->
        <div>
            <label for="multi_currency" class="block text-sm font-medium text-gray-700 mb-1">Currency Rate</label>
            <input type="number" name="multi_currency" id="multi_currency" required readonly
                value="{{ $wakala->multi_currency }}" step="0.01" min="0"
                class="readonly-field block w-full px-3 py-2 rounded border border-gray-300 bg-gray-100 text-gray-700">
        </div>

        <!-- Total Buying Price -->
        <div>
            <label for="total_price" class="block text-sm font-medium text-gray-700 mb-1">Total Buying Price</label>
            <input type="text" name="total_price" id="total_price" readonly value="{{ $wakala->total_price }}"
                class="readonly-field block w-full px-3 py-2 rounded border border-gray-300 bg-gray-100 text-gray-700">
        </div>

        <!-- Selling Price -->
        <div>
            <label for="selling_price" class="block text-sm font-medium text-gray-700 mb-1">Selling Price</label>
            <input type="text" name="selling_price" id="selling_price" required value="{{ $wakala->selling_price }}" readonly
                class="readonly-field block w-full px-3 py-2 rounded border border-gray-300 bg-gray-100 text-gray-700">
        </div>

        <!-- Individual Selling Price -->
        <div>
            <label for="single_selling_price" class="block text-sm font-medium text-gray-700 mb-1">
                Single Selling Price
            </label>
            <div class="relative">
                <input type="text" name="single_selling_price" id="single_selling_price" required readonly
                    value="{{ number_format($wakala->selling_price / $wakala->quantity, 2) }}"
                    class="readonly-field block w-full px-3 py-2 rounded border border-gray-300 bg-gray-100 text-gray-700 pl-10">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">TK</span>
            </div>
        </div>

        <!-- Quantity -->
        <div>
            <label for="quantity" class="block text-sm font-medium text-gray-700 mb-1">Quantity to Sell</label>
            <input type="number" name="quantity" id="quantity" required min="1" max="{{ $wakala->return_quantity }}"
                value="{{ $wakala->return_quantity }}" 
                class="standard-field focus:ring-green-500 focus:border-green-500 block w-full px-3 py-2 rounded border border-gray-300 text-gray-700">
        </div>
    </div>

    <!-- Sales Info Section -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Sold By -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Sold By</label>
            <input type="text" name="sales_by" id="sales_by" required
                class="standard-field w-full block w-full px-3 py-2 rounded border border-gray-300 text-gray-700"
                value="{{ $wakala->sales_by }}">
        </div>

        <!-- Notes -->
        {{-- <div>
            <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Additional Notes</label>
            <textarea name="notes" id="notes" rows="3"
                class="standard-field w-full block w-full px-3 py-2 rounded border border-gray-300 bg-gray-100 text-gray-700">{{ $wakala->note }}</textarea>
        </div> --}}
    </div>

    <!-- Form Actions -->
    <div class="flex flex-wrap justify-end gap-4 border-t pt-6">
        <button type="button" onclick="closeSellModal()"
            class="px-6 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
            Cancel
        </button>
        <button type="submit"
            class="px-6 py-2 text-sm font-medium text-white bg-green-600 rounded-md shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
            Confirm Sale
        </button>
    </div>
</form>

<style>
    .readonly-field {
        @apply block w-full px-3 py-2 rounded-md border border-gray-200 bg-gray-100 text-gray-600 cursor-not-allowed;
    }
    .standard-field {
        @apply block w-full px-3 py-2 rounded-md border border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500;
    }
    .select2 {
        @apply block w-full px-3 py-2 rounded-md border border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500;
    }
</style>

<script>
    // Make sure select2 is initialized properly
    $(document).ready(function() {
        $('.select2').select2({
            width: '100%',
            dropdownParent: $('#sellModal')
        });
    });
</script>