<x-app-layout>

    <div class="main-content" id="main-content">
        <div class="content">
            {{-- <div class="container">
         
                <form autocomplete="off" action="{{ route('transaction.update', ['id' => $transaction->id]) }}"
                    method="post">
                    @csrf 
                    <div class="row">
                        <div class="form-group col">
                            <label for="name">Name:</label>
                            <input type="text" class="form-control" id="name" name="name"
                                placeholder="Enter your name" value="{{ $transaction->name }}" required>
                        </div>

                    </div>

                    <div class="row">
                        <div class="form-group col">
                            <label for="description">Description:</label>
                            <textarea class="form-control" id="description" name="description" placeholder="Enter a description" required>{!! $transaction->description !!}</textarea>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div> --}}
            <div class="mx-auto p-6">
                <div class="bg-white rounded-lg shadow-md overflow-hidden p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h1 class="text-2xl font-bold text-gray-800">Edit Transaction</h1>
                        <a href="{{ route('transaction.view') }}" class="text-gray-500 hover:text-gray-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </a>
                    </div>

                    @if ($errors->any() || session('error'))
                        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                            <p class="font-bold">Error</p>
                            @foreach ($errors->all() as $error)
                                <p>{{ $error }}</p>
                            @endforeach
                            @if (session('error'))
                                <p>{{ session('error') }}</p>
                            @endif
                        </div>
                    @endif

                    <form autocomplete="off" action="{{ route('transaction.update', $transaction->id) }}" method="post"
                        class="space-y-6">
                        @csrf
                        @method('PUT')

                        <!-- Account Type (readonly since we shouldn't change type after creation) -->
                        <div class="mb-6">
                            <label class="block mb-2 text-sm font-medium text-gray-700">
                                Account Type
                            </label>
                            <div class="p-3 bg-gray-100 rounded-lg">
                                {{ $transaction->transaction_type ?? 'N/A' }}
                            </div>
                            <input type="hidden" name="account_type"
                                value="{{ $transaction->transaction_type ?? '' }}">
                        </div>

                        <!-- Common Fields -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="account_name" class="block mb-2 text-sm font-medium text-gray-700">
                                    <span class="text-red-500">*</span> Account Name
                                </label>
                                <input type="text" id="account_name" name="account_name"
                                    value="{{ $transaction->account->account_name ?? old('account_name') }}"
                                    class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                                    required>
                            </div>

                            <div>
                                <label for="current_balance" class="block mb-2 text-sm font-medium text-gray-700">
                                    <span class="text-red-500"></span> Current Balance
                                </label>
                                <input type="number" step="0.01" id="current_balance" name="current_balance"
                                    value="{{ $transaction->amount ?? old('current_balance') }}"
                                    class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                            </div>
                        </div>

                        <!-- Bank Account Fields (shown only if account type is Bank) -->
                        <div id="bank_fields"
                            class="{{ $transaction->account->account_type != 'Bank' ? 'hidden' : '' }} mb-6">
                            <h3 class="mb-4 text-lg font-semibold text-gray-700 border-b pb-2">Bank Account Details</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="account_number" class="block mb-2 text-sm font-medium text-gray-700">
                                        <span class="text-red-500">*</span> Account Number
                                    </label>
                                    <input type="text" id="account_number" name="account_number"
                                        value="{{ $transaction->account->account_number ?? old('account_number') }}"
                                        class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                                        {{ $transaction->account->account_type == 'Bank' ? 'required' : '' }}>
                                </div>
                                <div>
                                    <label for="bank_name" class="block mb-2 text-sm font-medium text-gray-700">
                                        <span class="text-red-500">*</span> Bank Name
                                    </label>
                                    <input type="text" id="bank_name" name="bank_name"
                                        value="{{ $transaction->account->bank_name ?? old('bank_name') }}"
                                        class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                                        {{ $transaction->account->account_type == 'Bank' ? 'required' : '' }}>
                                </div>
                                <div>
                                    <label for="branch" class="block mb-2 text-sm font-medium text-gray-700">
                                        <span class="text-red-500">*</span> Branch
                                    </label>
                                    <input type="text" id="branch" name="branch"
                                        value="{{ $transaction->account->branch ?? old('branch') }}"
                                        class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                                        {{ $transaction->account->account_type == 'Bank' ? 'required' : '' }}>
                                </div>
                                <div>
                                    <label for="routing_no" class="block mb-2 text-sm font-medium text-gray-700">
                                        Routing Number
                                    </label>
                                    <input type="text" id="routing_no" name="routing_no"
                                        value="{{ $transaction->account->routing_no ?? old('routing_no') }}"
                                        class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                                </div>
                            </div>
                        </div>

                        <!-- Mobile Banking Fields (shown only if account type is Mobile banking) -->
                        <div id="mobile_banking_fields"
                            class="{{ $transaction->account->account_type != 'Mobile Banking' ? 'hidden' : '' }} mb-6">
                            <h3 class="mb-4 text-lg font-semibold text-gray-700 border-b pb-2">Mobile Banking Details
                            </h3>
                            <div class="grid grid-cols-1 gap-6">
                                <div>
                                    <label for="mobile_account_number"
                                        class="block mb-2 text-sm font-medium text-gray-700">
                                        <span class="text-red-500">*</span> Account Number
                                    </label>
                                    <input type="text" id="mobile_account_number" name="mobile_account_number"
                                        value="{{ $transaction->account->mobile_account_number ?? old('mobile_account_number') }}"
                                        class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                                        {{ $transaction->account->account_type == 'Mobile banking' ? 'required' : '' }}>
                                </div>
                            </div>
                        </div>

                        <!-- Credit Card Fields (shown only if account type is Credit Card) -->
                        <div id="credit_card_fields"
                            class="{{ $transaction->account->account_type != 'Credit Card' ? 'hidden' : '' }} mb-6">
                            <h3 class="mb-4 text-lg font-semibold text-gray-700 border-b pb-2">Credit Card Details</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="card_number" class="block mb-2 text-sm font-medium text-gray-700">
                                        <span class="text-red-500">*</span> Card Number
                                    </label>
                                    <input type="text" id="card_number" name="card_number"
                                        value="{{ $transaction->account->card_number ?? old('card_number') }}"
                                        class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                                        {{ $transaction->account->account_type == 'Credit Card' ? 'required' : '' }}>
                                </div>
                                <div>
                                    <label for="card_csv" class="block mb-2 text-sm font-medium text-gray-700">
                                        <span class="text-red-500">*</span> Security Code (CSV)
                                    </label>
                                    <input type="text" id="card_csv" name="card_csv"
                                        value="{{ $transaction->account->card_csv ?? old('card_csv') }}"
                                        class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                                        {{ $transaction->account->account_type == 'Credit Card' ? 'required' : '' }}>
                                </div>
                                <div class="md:col-span-2">
                                    <label for="card_expiry" class="block mb-2 text-sm font-medium text-gray-700">
                                        <span class="text-red-500">*</span> Expiry Date
                                    </label>
                                    <input type="text" id="card_expiry" name="card_expiry"
                                        value="{{ $transaction->account->card_expiry ?? old('card_expiry') }}"
                                        class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                                        placeholder="MM/YY"
                                        {{ $transaction->account->account_type == 'Credit Card' ? 'required' : '' }}>
                                </div>
                            </div>
                        </div>


                        <div class="flex justify-end space-x-4">
                            <a href="{{ route('transaction.view') }}"
                                class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                                Cancel
                            </a>
                            <button type="submit"
                                class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                                Update Transaction
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Set required fields based on initial account type
            setRequiredFields('{{ $transaction->account->account_type ?? '' }}');

            function setRequiredFields(accountType) {
                // Reset all required fields first
                document.querySelectorAll('[data-required]').forEach(field => {
                    field.required = false;
                });

                // Set required fields based on account type
                switch (accountType) {
                    case 'Bank':
                        document.getElementById('account_number').required = true;
                        document.getElementById('bank_name').required = true;
                        document.getElementById('branch').required = true;
                        break;
                    case 'Mobile Banking':
                        document.getElementById('mobile_account_number').required = true;
                        break;
                    case 'Credit Card':
                        document.getElementById('card_number').required = true;
                        document.getElementById('card_csv').required = true;
                        document.getElementById('card_expiry').required = true;
                        break;
                }
            }
        });
    </script>


</x-app-layout>
