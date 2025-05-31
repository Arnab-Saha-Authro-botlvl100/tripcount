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
    <style>
        .animate-fade-in {
            animation: fadeIn 0.3s ease-out forwards;
        }

        .animate-fade-out {
            animation: fadeOut 0.3s ease-out forwards;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeOut {
            from {
                opacity: 1;
                transform: translateY(0);
            }

            to {
                opacity: 0;
                transform: translateY(-10px);
            }
        }
    </style>
    <style>
        /* Responsive design adjustments */
        @media (min-width: 640px) {
            .addagent form {
                max-width: 600px;
                margin: 0 auto;
            }
        }


        @media (min-width: 768px) {
            .addagent form {
                max-width: 700px;
            }

            /* For larger screens, you could arrange some fields side by side */
            .grid-cols-2 {
                display: grid;
                grid-template-columns: repeat(2, minmax(0, 1fr));
                gap: 1rem;
            }
        }

        /* General styling */
        .hidden {
            display: none;
        }

        .alert-warning {
            padding: 0.75rem;
            background-color: #fef3c7;
            color: #92400e;
            border-radius: 0.375rem;
        }
    </style>


    <div class="main-content" id="main-content">
        <div class="content">

            <div class="">
                <div class="bg-white shadow-md rounded p-6">
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
                        <h2 class="text-lg font-semibold text-gray-800">Accounts</h2>
                    </div>

                    <div class="mx-auto hidden" id="account-form-div">
                        <div class="flex justify-between items-center mb-4">
                            <h1 class="text-2xl font-bold text-gray-800">Add Transaction Method</h1>
                            <button class="text-gray-500 hover:text-gray-700 transition-colors duration-200"
                                onclick="toggleAccountForm()">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                        
                        <div class="bg-white rounded-lg shadow-md overflow-hidden">
                            @if (in_array('entry', $permissionsArray))
                                <form autocomplete="off" action="/transaction_add" method="post" class="p-6">
                                    @csrf

                                    <div class="mb-6">
                                        <label for="account_type" class="block mb-2 text-sm font-medium text-gray-700">
                                            <span class="text-red-500">*</span> Account Type
                                        </label>
                                        <select id="account_type" name="account_type"
                                            class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                                            required>
                                            <option value="">Select Account Type</option>
                                            <option value="Cash">Cash</option>
                                            <option value="Bank">Bank</option>
                                            <option value="Mobile Banking">Mobile banking</option>
                                            <option value="Credit Card">Credit Card</option>
                                        </select>
                                    </div>

                                    <!-- Common Fields -->
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                        <div>
                                            <label for="account_name"
                                                class="block mb-2 text-sm font-medium text-gray-700">
                                                <span class="text-red-500">*</span> Account Name
                                            </label>
                                            <input type="text" id="account_name" name="account_name"
                                                class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                                                placeholder="e.g. My Primary Account" required>
                                        </div>

                                        <div>
                                            <label for="current_balance"
                                                class="block mb-2 text-sm font-medium text-gray-700">
                                                Current Last Balance
                                            </label>
                                            <input type="number" step="0.01" id="current_balance"
                                                name="current_balance"
                                                class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                                                placeholder="0.00">
                                        </div>
                                    </div>

                                    <!-- Bank Account Fields -->
                                    <div id="bank_fields" class="hidden mb-6">
                                        <h3 class="mb-4 text-lg font-semibold text-gray-700 border-b pb-2">Bank Account
                                            Details</h3>
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                            <div>
                                                <label for="account_number"
                                                    class="block mb-2 text-sm font-medium text-gray-700">
                                                    <span class="text-red-500">*</span> Account Number
                                                </label>
                                                <input type="text" id="account_number" name="account_number"
                                                    data-required
                                                    class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                                                    placeholder="1234567890">
                                            </div>
                                            <div>
                                                <label for="bank_name"
                                                    class="block mb-2 text-sm font-medium text-gray-700">
                                                    <span class="text-red-500">*</span> Bank Name
                                                </label>
                                                <input type="text" id="bank_name" name="bank_name" data-required
                                                    class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                                                    placeholder="e.g. National Bank">
                                            </div>
                                            <div>
                                                <label for="branch"
                                                    class="block mb-2 text-sm font-medium text-gray-700">
                                                    <span class="text-red-500">*</span> Branch
                                                </label>
                                                <input type="text" id="branch" name="branch" data-required
                                                    class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                                                    placeholder="Main Branch">
                                            </div>
                                            <div>
                                                <label for="routing_no"
                                                    class="block mb-2 text-sm font-medium text-gray-700">
                                                    Routing Number
                                                </label>
                                                <input type="text" id="routing_no" name="routing_no"
                                                    class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                                                    placeholder="123456789">
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Mobile Banking Fields -->
                                    <div id="mobile_banking_fields" class="hidden mb-6">
                                        <h3 class="mb-4 text-lg font-semibold text-gray-700 border-b pb-2">Mobile
                                            Banking
                                            Details</h3>
                                        <div class="grid grid-cols-1 gap-6">
                                            <div>
                                                <label for="mobile_account_number"
                                                    class="block mb-2 text-sm font-medium text-gray-700">
                                                    <span class="text-red-500">*</span> Account Number
                                                </label>
                                                <input type="text" id="mobile_account_number" data-required
                                                    name="mobile_account_number"
                                                    class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                                                    placeholder="Enter mobile account number">
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Credit Card Fields -->
                                    <div id="credit_card_fields" class="hidden mb-6">
                                        <h3 class="mb-4 text-lg font-semibold text-gray-700 border-b pb-2">Credit Card
                                            Details
                                        </h3>
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                            <div>
                                                <label for="card_number"
                                                    class="block mb-2 text-sm font-medium text-gray-700">
                                                    <span class="text-red-500">*</span> Card Number
                                                </label>
                                                <input type="text" id="card_number" name="card_number"
                                                    data-required
                                                    class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                                                    placeholder="4242 4242 4242 4242">
                                            </div>
                                            <div>
                                                <label for="card_csv"
                                                    class="block mb-2 text-sm font-medium text-gray-700">
                                                    <span class="text-red-500">*</span> Security Code (CSV)
                                                </label>
                                                <input type="text" id="card_csv" name="card_csv"
                                                    class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                                                    placeholder="123">
                                            </div>
                                            <div class="md:col-span-2">
                                                <label for="card_expiry"
                                                    class="block mb-2 text-sm font-medium text-gray-700">
                                                    <span class="text-red-500">*</span> Expiry Date
                                                </label>
                                                <input type="date" id="card_expiry" name="card_expiry"
                                                    data-required
                                                    class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                                                    placeholder="MM/YY">
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Submit Button -->
                                    <div class="mt-8">
                                        <button type="submit"
                                            class="md:w-auto px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg shadow-md transition duration-200">
                                            Create Account
                                        </button>
                                    </div>
                                </form>
                            @else
                                <div class="p-6 bg-yellow-50 text-yellow-800 rounded-lg">
                                    <p class="font-medium">You don't have permission to add transaction methods.</p>
                                </div>
                            @endif
                        </div>
                    </div>


                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left border-collapse datatable">
                            <thead class="bg-gray-200 text-gray-700">
                                <tr>
                                    <th class="px-4 py-2">SL.</th>
                                    <th class="px-4 py-2">Name</th>
                                    <th class="px-4 py-2">Account Type</th>
                                    <th class="px-4 py-2">Account No</th>
                                    <th class="px-4 py-2">Bank Name</th>
                                    <th class="px-4 py-2">Routing No.</th>
                                    <th class="px-4 py-2">Card No</th>
                                    <th class="px-4 py-2">Branch</th>
                                    <th class="px-4 py-2">Last Balance</th>
                                    <th class="px-4 py-2">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach ($transactions as $index => $transaction)
                                    <tr class="{{ $index % 2 === 0 ? 'bg-white' : 'bg-gray-50' }} hover:bg-gray-100">
                                        <td class="px-4 py-2">{{ $loop->iteration }}</td>
                                        <td class="px-4 py-2 font-medium text-gray-900">
                                            {{ $transaction->name }}
                                            @if ($transaction->description)
                                                <p class="text-xs text-gray-500">{{ $transaction->description }}</p>
                                            @endif
                                        </td>

                                        <td class="px-4 py-2">
                                            @if ($transaction->account)
                                                {{ $transaction->account->account_type }}
                                            @else
                                                <span class="text-gray-400">N/A</span>
                                            @endif
                                        </td>

                                        <td class="px-4 py-2">
                                            @if ($transaction->account)
                                                {{ $transaction->account->account_number ?? ($transaction->account->mobile_account_number ?? 'N/A') }}
                                            @else
                                                <span class="text-gray-400">N/A</span>
                                            @endif
                                        </td>

                                        <td class="px-4 py-2">
                                            @if ($transaction->account && $transaction->account->bank_name)
                                                {{ $transaction->account->bank_name }}
                                            @else
                                                <span class="text-gray-400">N/A</span>
                                            @endif
                                        </td>

                                        <td class="px-4 py-2">
                                            @if ($transaction->account && $transaction->account->routing_no)
                                                {{ $transaction->account->routing_no }}
                                            @else
                                                <span class="text-gray-400">N/A</span>
                                            @endif
                                        </td>

                                        <td class="px-4 py-2">
                                            @if ($transaction->account && $transaction->account->card_number)
                                                {{-- ****-****-****-{{ substr($transaction->account->card_number, -4) }} --}}
                                                {{ $transaction->account->card_number }}
                                            @else
                                                <span class="text-gray-400">N/A</span>
                                            @endif
                                        </td>

                                        <td class="px-4 py-2">
                                            @if ($transaction->account && $transaction->account->branch)
                                                {{ $transaction->account->branch }}
                                            @else
                                                <span class="text-gray-400">N/A</span>
                                            @endif
                                        </td>

                                        <td
                                            class="px-4 py-2 font-medium {{ $transaction->amount >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                            {{ number_format($transaction->amount, 2) }}
                                        </td>

                                        <td class="px-4 py-2 flex space-x-2">
                                            <form action="{{ route('bank_book_report') }}" method="POST"
                                                class="inline" id="bankBookForm">
                                                @csrf
                                                <input type="hidden" name="method" value="{{ $transaction->id }}">
                                                <button type="submit"
                                                    class="text-green-500 px-2 py-1 rounded-md hover:bg-green-100 inline-flex items-center"
                                                    title="Bank Book Report">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5"
                                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M9 12h6m-6 4h6m2-10v8m0 0v4a2 2 0 01-2 2H7a2 2 0 01-2-2V6a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V16z" />
                                                    </svg>
                                                </button>
                                            </form>

                                            <a href="{{ route('transaction.edit', $transaction->id) }}"
                                                class="text-blue-600 hover:text-blue-900">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                    </path>
                                                </svg>
                                            </a>
                                            <form action="{{ route('transaction.delete', $transaction->id) }}"
                                                method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900"
                                                    onclick="return confirm('Are you sure you want to delete this transaction?')">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                        </path>
                                                    </svg>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>


            </div>
        </div>
    </div>

    <script>
        document.addEventListener('click', function(e) {
            if (e.target.closest('#bankBookForm button[type="submit"]')) {
                e.preventDefault();
                const form = e.target.closest('form');
                const formData = new FormData(form);
                const reportWindow = window.open('', '_blank');

                fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    reportWindow.document.open();
                    reportWindow.document.write(data.html);
                    reportWindow.document.close();
                })
                .catch(error => {
                    reportWindow.close();
                    console.error('Error:', error);
                    alert('Error generating report');
                });
            }
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const accountTypeSelect = document.getElementById('account_type');
            const bankFields = document.getElementById('bank_fields');
            const mobileBankingFields = document.getElementById('mobile_banking_fields');
            const creditCardFields = document.getElementById('credit_card_fields');

            // Hide all specific fields initially
            bankFields.classList.add('hidden');
            mobileBankingFields.classList.add('hidden');
            creditCardFields.classList.add('hidden');

            // Function to set required attributes
            function setRequiredFields(accountType) {
                // Reset all required fields first
                document.querySelectorAll('[data-required]').forEach(field => {
                    field.removeAttribute('required');
                });

                // Set required fields based on account type
                switch (accountType) {
                    case 'Bank':
                        document.getElementById('account_number').required = true;
                        document.getElementById('bank_name').required = true;
                        document.getElementById('branch').required = true;
                        break;
                    case 'Mobile banking':
                        document.getElementById('mobile_account_number').required = true;
                        break;
                    case 'Credit Card':
                        document.getElementById('card_number').required = true;
                        document.getElementById('card_csv').required = true;
                        document.getElementById('card_expiry').required = true;
                        break;
                        // Cash doesn't need additional required fields
                }
            }

            accountTypeSelect.addEventListener('change', function() {
                // Hide all specific fields first
                bankFields.classList.add('hidden');
                mobileBankingFields.classList.add('hidden');
                creditCardFields.classList.add('hidden');

                // Show fields based on selected account type
                switch (this.value) {
                    case 'Bank':
                        bankFields.classList.remove('hidden');
                        break;
                    case 'Mobile banking':
                        mobileBankingFields.classList.remove('hidden');
                        break;
                    case 'Credit Card':
                        creditCardFields.classList.remove('hidden');
                        break;
                        // Cash doesn't need additional fields
                }

                // Update required fields
                setRequiredFields(this.value);
            });

            // Initialize required fields on page load if a type is already selected
            if (accountTypeSelect.value) {
                setRequiredFields(accountTypeSelect.value);
            }
        });


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
    </script>

    <script type="text/javascript">
        function confirmDelete(deleteUrl) {
            // Display a confirmation dialog
            const isConfirmed = window.confirm("Are you sure you want to delete?");

            // If the user confirms, proceed with the delete action
            if (isConfirmed) {
                window.location.href = deleteUrl;
            }
        }
        $('#transaction_table').DataTable();
    </script>
</x-app-layout>
