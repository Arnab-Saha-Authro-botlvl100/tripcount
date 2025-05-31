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
        /* Custom Select2 styling to match your theme */
        .select2-container--default .select2-selection--multiple {
            border: 1px solid #d1d5db;
            border-radius: 0.375rem;
            min-height: 42px;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            background-color: #f3f4f6;
            border: 1px solid #d1d5db;
            border-radius: 0.25rem;
            padding: 0 6px;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
            color: #9ca3af;
            margin-right: 4px;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove:hover {
            color: #6b7280;
        }
    </style>



    <div class="main-content" id="main-content">
        <div class="content">


            <!-- Modal -->
            <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content bg-white rounded-lg shadow-xl">
                        <!-- Modal Header -->
                        <div class="modal-header px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                            <h5 class="modal-title text-xl font-semibold text-gray-800">Add Expenditure Towards</h5>
                            <button type="button" class="close text-gray-500 hover:text-gray-700 focus:outline-none"
                                data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true" class="text-2xl">&times;</span>
                            </button>
                        </div>

                        <!-- Modal Body -->
                        <div class="modal-body p-6">
                            <form method="post" action="{{ route('add_expenditure_towards') }}" class="space-y-4">
                                @csrf
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <!-- Reason Input -->
                                    <div>
                                        <label for="name"
                                            class="block text-sm font-medium text-gray-700 mb-1">Reason*</label>
                                        <input type="text" id="name" name="name"
                                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150"
                                            placeholder="Enter reason" required>
                                    </div>

                                    <!-- Description Input -->
                                    <div>
                                        <label for="description"
                                            class="block text-sm font-medium text-gray-700 mb-1">Amount</label>
                                        <input type="number" id="description" name="description"
                                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150"
                                            placeholder="Enter amount">
                                    </div>
                                </div>

                                <!-- Submit Button -->
                                <div class="pt-2">
                                    <button type="submit"
                                        class="w-full md:w-auto px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-md shadow-sm transition duration-150 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                        Submit
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- Modal Footer -->
                        <div class="modal-footer px-6 py-4 border-t border-gray-200 flex justify-end">
                            <button type="button"
                                class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium rounded-md transition duration-150 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500"
                                data-dismiss="modal">
                                Close
                            </button>
                        </div>
                    </div>
                </div>
            </div>
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
                    <h2 class="text-lg font-bold text-gray-800">Expense
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-red-500" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 9V7a4 4 0 00-8 0v2M5 13h14l-4-4m0 8l4-4" />
                        </svg>
                    </h2>
                </div>

                <div class="mx-auto hidden" id="account-form-div">

                    <div class="flex justify-between items-center mb-4">
                        <h1 class="text-2xl font-bold text-gray-800">Add Expenditure</h1>
                        <button class="text-gray-500 hover:text-gray-700 transition-colors duration-200"
                            onclick="toggleAccountForm()">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <div class="bg-white rounded-lg shadow-md p-5 overflow-hidden p-4">
                        @if (in_array('entry', $permissionsArray))

                            {{-- <form action="/add_expenditure_main" method="POST">
                                @csrf
                                <div class="grid grid-cols-1 md:grid-cols-2 md:gap-10">
                                    <div class="mb-4">
                                        <label for="branch"
                                            class="block text-sm font-medium text-gray-700">Banch:</label>
                                        <input type="text" id="branch" name="branch"
                                            class="mt-1 py-1 px-2 w-full border " placeholder="Enter your name"
                                            value="{{ Auth::user()->name }}" readonly>
                                    </div>

                                    <div class="mb-4">
                                        <label for="transaction_date"
                                            class="block text-sm font-medium text-gray-700">Transaction Date:</label>
                                        <input type="date" id="transaction_date" name="transaction_date"
                                            class="mt-1 px-2 py-1 w-full border " required
                                            placeholder="Enter your phone number" required>
                                    </div>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 md:gap-10">
                                    <div class="mb-4">
                                        <label for="name"
                                            class="block text-sm font-medium text-gray-700">Name:</label>

                                        <div class="flex gap-2">
                                            <select id="from_account" name="account_type"
                                                class="mt-1 px-2 py-1 w-[39%] border"
                                                placeholder="Enter a from_account" required>
                                                <option value="">Select</option>
                                                <option value="admin">Admin</option>
                                                <option value="stuff">Stuff</option>
                                                <option value="others">Others</option>
                                            </select>

                                            <select id="stuff_list" name="from_account"
                                                class="mt-1 px-2 py-1 w-[59%] border "
                                                placeholder="Enter a from_account">
                                                @foreach ($employees as $employee)
                                                    <option value="{{ $employee->id }}">{{ $employee->name }}
                                                    </option>
                                                @endforeach
                                            </select>

                                            <input type="text" id="admin_part" value="admin"
                                                class="mt-1 px-2 py-1 w-full border " readonly>
                                            <input type="text" id="other_part"
                                                class="mt-1 px-2 py-1 w-full border ">

                                            <!-- Hidden input field to hold the selected from_account value -->
                                            <input type="hidden" name="from_account" id="selected_account">
                                        </div>


                                    </div>

                                    <div class="mb-4">
                                        <label for="towards"
                                            class="block text-sm font-medium text-gray-700">Towards:</label>
                                        <div class="flex items-center gap-2">
                                            <select id="towards" name="towards"
                                                class="mt-1 px-2 py-1 w-11/12 border" placeholder="Enter a to_account"
                                                required>
                                                <option value="">Towards</option>
                                                @foreach ($expenditures as $expenditure)
                                                    <option value="{{ $expenditure->id }}">{{ $expenditure->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <button type="button" class="btn btn-primary" data-toggle="modal"
                                                data-target="#exampleModal">
                                                +
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 md:gap-10">
                                    <div class="mb-4">
                                        <label for="amount"
                                            class="block text-sm font-medium text-gray-700">Amount:</label>
                                        <input type="text" id="amount" name="amount"
                                            class="mt-1 px-2 py-1 w-full border " placeholder="Enter a amount"
                                            required>
                                    </div>
                                    <div class="mb-4">
                                        <label for="remarks"
                                            class="block text-sm font-medium text-gray-700">Method:</label>
                                        <select id="method" name="method" class="mt-1 px-2 py-1 w-full border"
                                            placeholder="Enter a method" required>
                                            <option value="">Method</option>
                                            @foreach ($transactions as $transfer)
                                                <option value="{{ $transfer->id }}">{{ $transfer->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>


                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 md:gap-10">

                                    <div class="mb-4">
                                        <label for="remarks"
                                            class="block text-sm font-medium text-gray-700">Remarks:</label>
                                        <textarea id="remarks" name="remarks" class="mt-1 px-2 py-1 w-full border " placeholder="Enter an remarks"></textarea>
                                    </div>


                                </div>

                                <button type="submit"
                                    class="bg-black text-white px-4 py-2 rounded-lg">Submit</button>
                            </form> --}}
                            <form action="/add_expenditure_main" method="POST"
                                class="space-y-6 bg-white p-6 rounded-lg shadow-md">
                                @csrf

                                <!-- Top Section - Branch and Date -->
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                    <!-- Hidden Branch Field -->
                                    <div class="hidden">
                                        <input type="hidden" id="branch" name="branch"
                                            value="{{ Auth::user()->name }}" readonly>
                                    </div>

                                    <!-- Transaction Date -->
                                    <div class="col-span-1">
                                        <label for="transaction_date"
                                            class="block text-sm font-medium text-gray-700 mb-1">Transaction
                                            Date*</label>
                                        <input type="date" id="transaction_date" name="transaction_date"
                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150"
                                            required>
                                    </div>

                                    <!-- Name Selection -->
                                    <div class="col-span-1 md:col-span-2">
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Recipient*</label>
                                        <div class="flex gap-3">
                                            <select id="account_type" name="account_type"
                                                class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150"
                                                required>
                                                <option value="">Select Type</option>
                                                <option value="admin">Admin</option>
                                                <option value="stuff">Staff</option>
                                                <option value="others">Others</option>
                                            </select>

                                            <div id="stuff_container" class="flex-1 hidden">
                                                <select id="stuff_list" name="from_account_staff"
                                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150">
                                                    @foreach ($employees as $employee)
                                                        <option value="{{ $employee->id }}">{{ $employee->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div id="admin_container" class="flex-1 hidden">
                                                <input type="text" id="admin_part" value="Admin"
                                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-100"
                                                    readonly>
                                            </div>

                                            <div id="other_container" class="flex-1 hidden">
                                                <input type="text" id="other_part" name="from_account_other"
                                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150"
                                                    placeholder="Enter name">
                                            </div>
                                        </div>
                                        <input type="hidden" name="from_account" id="selected_account">
                                    </div>
                                </div>

                                <!-- Towards Section -->
                                <div class="space-y-3">
                                    <label class="block text-sm font-medium text-gray-700">Expenditure Items*</label>

                                    <div class="flex items-center gap-3 mb-3">
                                        <select id="towards" name="towards[]"
                                            class="flex-1 select2 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150"
                                            multiple>
                                            <option value="">Select Items</option>
                                            @foreach ($expenditures as $expenditure)
                                                <option value="{{ $expenditure->id }}"
                                                    data-amount="{{ $expenditure->description ?? 0 }}">
                                                    {{ $expenditure->name }}
                                                </option>
                                            @endforeach
                                        </select>

                                        <button type="button"
                                            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-150"
                                            data-toggle="modal" data-target="#exampleModal">
                                            <i class="fas fa-plus"></i> New
                                        </button>
                                    </div>

                                    <!-- Selected Items Table -->
                                    <div class="overflow-hidden border border-gray-200 rounded-lg">
                                        <table class="min-w-full divide-y divide-gray-200" id="selectedExpenditures">
                                            <thead class="bg-gray-50">
                                                <tr>
                                                    <th
                                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                                        Item</th>
                                                    <th
                                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                                        Amount</th>
                                                    <th
                                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                                        Action</th>
                                                </tr>
                                            </thead>
                                            <tbody class="bg-white divide-y divide-gray-200">
                                                <!-- Selected items will appear here -->
                                            </tbody>

                                        </table>
                                    </div>
                                    <input type="hidden" name="selected_towards" id="selected_towards">
                                </div>

                                <!-- Amount and Payment Method -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <!-- Amount -->
                                    <div class="space-y-2">
                                        <label for="amount" class="block text-sm font-medium text-gray-700">Total
                                            Amount*</label>
                                        <div class="relative">
                                            <span
                                                class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">$</span>
                                            <input type="number" id="amount" name="amount"
                                                class="block w-full pl-8 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150"
                                                placeholder="0.00" required>
                                        </div>
                                    </div>

                                    <!-- Payment Method -->
                                    <div class="space-y-2">
                                        <label for="method" class="block text-sm font-medium text-gray-700">Payment
                                            Method*</label>
                                        <select id="method" name="method"
                                            onchange="showRemainingBalance(this, 'from')"
                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 select2">
                                            <option value="">Select Account</option>
                                            @foreach ($transactions as $transaction)
                                                <option value="{{ $transaction->id }}"
                                                    data-balance="{{ $transaction->amount }}"
                                                    data-account-name="{{ $transaction->name }}">
                                                    {{ $transaction->name }}
                                                </option>
                                            @endforeach
                                        </select>

                                        <!-- Account Balance Display -->
                                        <div id="selected-amount"
                                            class="mt-2 p-2 bg-blue-50 text-blue-800 rounded-md hidden">
                                            <span class="font-medium">Available Balance:</span>
                                            <span id="balanceAmount">$0.00</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Remarks -->
                                <div class="space-y-2">
                                    <label for="remarks"
                                        class="block text-sm font-medium text-gray-700">Notes</label>
                                    <textarea id="remarks" name="remarks" rows="3"
                                        class="block w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150"
                                        placeholder="Additional information about this expenditure"></textarea>
                                </div>

                                <!-- Submit Button -->
                                <div class="flex justify-end pt-4">
                                    <button type="submit"
                                        class="px-6 py-3 bg-blue-600 text-white font-medium rounded-lg shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                                        <i class="fas fa-check-circle mr-2"></i> Submit Expenditure
                                    </button>
                                </div>
                            </form>
                        @else
                            <div class="p-6 bg-yellow-50 text-yellow-800 rounded-lg">
                                <p class="font-medium">You don't have permission to add.</p>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="overflow-x-scroll">
                    <table class="w-full text-sm text-left border-collapse datatable">
                        <thead class="bg-gray-200 text-gray-700">
                            <tr>
                                <th class="px-4 py-2 text-left">Serial</th>
                                <th class="px-4 py-2 text-left">Date</th>
                                <th class="px-4 py-2 text-left">From</th>
                                <th class="px-4 py-2 text-left">To Account</th>
                                <th class="px-4 py-2 text-left">Towards</th>
                                <th class="px-4 py-2 text-left">Amount</th>
                                <th class="px-4 py-2 text-left">Method</th>
                                <th class="px-4 py-2 text-left">Remark</th>
                                <th class="px-4 py-2 text-left">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach ($expendituresmain as $index => $expan)
                                <tr>
                                    <td class="px-4 py-2"> {{ $index + 1 }} </td>
                                    <td class="px-4 py-2">{{ $expan->date }}</td>
                                    <td class="px-4 py-2">{{ $expan->receive_from }}</td>
                                    <td class="px-4 py-2">
                                        @foreach ($employees as $index => $emp)
                                            @if ($emp->id == $expan->from_account)
                                                {{ $emp->name }}
                                            @endif
                                        @endforeach
                                    </td>
                                    <td class="px-4 py-2">
                                        @php
                                            $towardIds = explode(',', $expan->toward);
                                        @endphp
                                        @foreach ($expenditures as $ex)
                                            @if (in_array($ex->id, $towardIds))
                                                {{ $ex->name }}@if (!$loop->last)
                                                    ,
                                                @endif
                                            @endif
                                        @endforeach
                                    </td>

                                    <td class="px-4 py-2">{{ $expan->amount }}</td>
                                    <td class="px-4 py-2">
                                        @foreach ($transactions as $index => $tran)
                                            @if ($tran->id == $expan->method)
                                                {{ $tran->name }}
                                            @endif
                                        @endforeach
                                    </td>
                                    <td class="px-4 py-2">{{ $expan->remark }}</td>
                                    <td class="px-4 py-2 text-center">
                                        @if (in_array('delete', $permissionsArray))
                                            <form
                                                action="{{ route('delete_expenditure_main', ['id' => $expan->id]) }}"
                                                method="POST"
                                                onsubmit="return confirm('Are you sure you want to delete this expenditure?');"
                                                class="inline-block">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="p-1.5 text-red-600 hover:text-red-800 hover:bg-red-100 rounded-full transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-opacity-50"
                                                    title="Delete">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                            </form>
                                        @else
                                            <span class="text-gray-400 text-sm">Not authorized</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>


            </div>
            {{-- </div> --}}
        </div>
    </div>




    <script type="text/javascript">
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

        function showRemainingBalance(selectElement, flag) {
            const selectedOption = selectElement.options[selectElement.selectedIndex];
            const balance = selectedOption.getAttribute('data-balance');
            const accountName = selectedOption.getAttribute('data-account-name');

            // Determine the target display element based on flag
            const displayElement = flag === 'from' ?
                document.getElementById('selected-amount') :
                document.getElementById('selected-amount-to');

            // Clear display if no option selected
            if (!selectedOption.value) {
                displayElement.innerHTML = '';
                return;
            }

            // Prepare display content
            let displayContent = '';
            const colorClass = flag === 'from' ? 'blue' : 'green';

            if (balance) {
                try {
                    const formattedBalance = parseFloat(balance).toLocaleString('en-US', {
                        style: 'currency',
                        currency: 'BDT',
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    });

                    displayContent = `
                    <div class="p-2 bg-${colorClass}-50 rounded-md border border-${colorClass}-100">
                        <span class="text-${colorClass}-800">${accountName || 'Account'}</span> - 
                        <span class="font-semibold text-${colorClass}-600">
                            ${flag === 'from' ? 'Remaining' : 'Available'} Balance: ${formattedBalance}
                        </span>
                    </div>
                    `;
                } catch (e) {
                    console.error('Error formatting balance:', e);
                    displayContent = `
                    <div class="p-2 bg-yellow-50 rounded-md border border-yellow-100">
                        <span class="text-yellow-800">Invalid balance format</span>
                    </div>
                    `;
                }
            } else {
                displayContent = `
                <div class="p-2 bg-yellow-50 rounded-md border border-yellow-100">
                    <span class="text-yellow-800">Balance information not available</span>
                </div>
            `;
            }

            displayElement.innerHTML = displayContent;
        }
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const select = document.getElementById('towards');
            const tableBody = document.querySelector('#selectedExpenditures tbody');
            const hiddenInput = document.getElementById('selected_towards');
            let selectedItems = [];

            // Initialize Select2
            $(select).select2({
                placeholder: "Select Towards",
                allowClear: true,
                closeOnSelect: false
            });

            // Handle selection changes
            $(select).on('change', function(e) {
                updateSelectedItems();
                renderSelectedItems();
            });

            function updateSelectedItems() {
                selectedItems = Array.from(select.selectedOptions)
                    .filter(option => option.value)
                    .map(option => ({
                        id: option.value,
                        name: option.text,
                        amount: option.dataset.amount || 0
                    }));

                // Update hidden input with comma-separated IDs
                hiddenInput.value = selectedItems.map(item => item.id).join(',');
            }

            function renderSelectedItems() {
                // Clear existing rows
                tableBody.innerHTML = '';

                // Get initial amount from input field and parse as number
                let totalamount = 0;

                // Add new rows
                selectedItems.forEach(item => {
                    const row = document.createElement('tr');
                    row.className = 'hover:bg-gray-50';
                    row.dataset.id = item.id;

                    // Head column
                    const headCell = document.createElement('td');
                    headCell.className = 'px-4 py-2 whitespace-nowrap';
                    headCell.textContent = item.name;

                    // Amount column
                    const amountCell = document.createElement('td');
                    amountCell.className = 'px-4 py-2 toward_amount whitespace-nowrap';
                    amountCell.textContent = formatCurrency(item.amount);

                    // Accumulate amount
                    totalamount += parseFloat(item.amount) || 0;
                    document.getElementById('amount').value = totalamount;
                    // Action column (remove button)
                    const actionCell = document.createElement('td');
                    actionCell.className = 'px-4 py-2 whitespace-nowrap';

                    const removeBtn = document.createElement('button');
                    removeBtn.type = 'button';
                    removeBtn.className = 'text-red-600 hover:text-red-800 p-1';
                    removeBtn.innerHTML = `
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        `;
                    removeBtn.addEventListener('click', function() {
                        // Subtract amount from total
                        let amountInput = document.getElementById('amount');
                        let currentTotal = parseFloat(amountInput.value) || 0;
                        amountInput.value = (currentTotal - parseFloat(item.amount)).toFixed(2);

                        // Remove from selectedItems array
                        selectedItems = selectedItems.filter(i => i.id !== item.id);

                        // Update select2
                        $(select).val(selectedItems.map(i => i.id));
                        $(select).trigger('change');

                        // Re-render table
                        renderSelectedItems();
                    });


                    actionCell.appendChild(removeBtn);

                    // Append all cells to row
                    row.appendChild(headCell);
                    row.appendChild(amountCell);
                    row.appendChild(actionCell);

                    // Add row to table
                    tableBody.appendChild(row);
                });

                // Update the total amount input field
                document.getElementById('amount').value = totalamount.toFixed(
                    2); // formatted as currency-like decimal
            }


            function formatCurrency(amount) {
                return '$' + parseFloat(amount).toLocaleString('en-US', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });
            }
        });
    </script>


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const accountType = document.getElementById('account_type');
            const stuffContainer = document.getElementById('stuff_container');
            const adminContainer = document.getElementById('admin_container');
            const otherContainer = document.getElementById('other_container');
            const selectedAccount = document.getElementById('selected_account');

            accountType.addEventListener('change', function() {
                // Hide all containers first
                stuffContainer.classList.add('hidden');
                adminContainer.classList.add('hidden');
                otherContainer.classList.add('hidden');

                // Show the relevant container
                if (this.value === 'stuff') {
                    stuffContainer.classList.remove('hidden');
                    selectedAccount.value = document.getElementById('stuff_list').value;
                } else if (this.value === 'admin') {
                    adminContainer.classList.remove('hidden');
                    selectedAccount.value = 'admin';
                } else if (this.value === 'others') {
                    otherContainer.classList.remove('hidden');
                    selectedAccount.value = document.getElementById('other_part').value;
                }
            });

            // Update hidden field when selections change
            document.getElementById('stuff_list').addEventListener('change', function() {
                if (accountType.value === 'stuff') {
                    selectedAccount.value = this.value;
                }
            });

            document.getElementById('other_part').addEventListener('input', function() {
                if (accountType.value === 'others') {
                    selectedAccount.value = this.value;
                }
            });
        });
    </script>
</x-app-layout>
