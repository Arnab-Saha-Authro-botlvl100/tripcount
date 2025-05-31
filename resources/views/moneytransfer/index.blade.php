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
                    <h2 class="text-lg font-bold text-gray-800">Cash
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block" viewBox="0 0 24 24"
                            fill="currentColor">
                            <path
                                d="M11.707 7.707a1 1 0 00-1.414-1.414L5.586 11l4.707 4.707a1 1 0 001.414-1.414L8.414 12l3.293-3.293zM12.293 16.293a1 1 0 001.414 1.414L18.414 13l-4.707-4.707a1 1 0 10-1.414 1.414L15.586 12l-3.293 3.293z" />
                        </svg> Bank Transfer
                    </h2>
                </div>

                <div class="mx-auto hidden" id="account-form-div">

                    <div class="flex justify-between items-center mb-4">
                        <h1 class="text-2xl font-bold text-gray-800">Add Contra</h1>
                        <button class="text-gray-500 hover:text-gray-700 transition-colors duration-200"
                            onclick="toggleAccountForm()">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <div class="bg-white rounded-lg shadow-md overflow-hidden p-4">
                        @if (in_array('entry', $permissionsArray))
                            <form action="{{ route('moneytransfer.add') }}" method="POST">
                                @csrf
                                <div class="grid grid-cols-1 md:grid-cols-2 md:gap-10">
                                    <div class="mb-4">
                                        <label for="branch"
                                            class="block text-sm font-medium text-gray-700">Banch:</label>
                                        <input type="text" id="branch" name="branch"
                                            class="mt-1 p-2 w-full border " placeholder="Enter your name"
                                            value="{{ $company_name }}">
                                    </div>

                                    <div class="mb-4">
                                        <label for="transaction_date" class="block text-sm font-medium text-gray-700">
                                            <span class="text-red-500">*</span> Transaction Date:</label>
                                        <input type="date" id="transaction_date" name="transaction_date"
                                            class="mt-1 p-2 w-full border " placeholder="Enter your phone number"
                                            required>
                                    </div>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 md:gap-10">
                                    <div class="mb-4">
                                        <label for="from_account" class="block text-sm font-medium text-gray-700">
                                            <span class="text-red-500">*</span> From Account:
                                        </label>
                                        <select id="from_account" name="from_account"
                                            onchange="showRemainingBalance(this, 'from')"
                                            class="mt-1 p-2 w-full border border-gray-300 rounded-md shadow-sm 
                                            focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm select2">
                                            <option value="">Select Account</option>
                                            @foreach ($transactions as $transaction)
                                                <option value="{{ $transaction->id }}"
                                                    data-balance="{{ $transaction->amount }}"
                                                    data-account-name="{{ $transaction->name }}">
                                                    {{ $transaction->name }}
                                                </option>
                                            @endforeach
                                        </select>

                                        <!-- Display remaining balance -->
                                        <div id="selected-amount" class="mt-2 text-sm font-medium"></div>
                                    </div>

                                    <div class="mb-4">
                                        <label for="to_account" class="block text-sm font-medium text-gray-700">
                                            <span class="text-red-500">*</span> To
                                            Account:</label>
                                        <select id="to_account" name="to_account"
                                            onchange="showRemainingBalance(this, 'to')"
                                            class="mt-1 p-2 w-full border
                                            border-gray-300 rounded-md shadow-sm 
                                            focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm select2"
                                            placeholder="Enter an to_account" required>
                                            <option value="">To Acount</option>
                                            @foreach ($transactions as $transaction)
                                                <option value="{{ $transaction->id }}"
                                                    data-balance="{{ $transaction->amount }}"
                                                    data-account-name="{{ $transaction->name }}">
                                                    {{ $transaction->name }}
                                                </option>
                                            @endforeach
                                        </select>

                                        <div id="selected-amount-to" class="mt-2 text-sm font-medium"></div>

                                    </div>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 md:gap-10">
                                    <div class="mb-4">
                                        <label for="amount" class="block text-sm font-medium text-gray-700">
                                            <span class="text-red-500">*</span> Amount:</label>
                                        <input type="text" id="amount" name="amount" required
                                            class="mt-1 p-2 w-full border " placeholder="Enter a amount">
                                    </div>
                                    <div class="mb-4">
                                        <label for="remarks"
                                            class="block text-sm font-medium text-gray-700">Remarks:</label>
                                        <textarea id="remarks" name="remarks" class="mt-1 p-2 w-full border " placeholder="Enter an remarks"></textarea>
                                    </div>
                                </div>


                                <button type="submit"
                                    class="bg-black text-white px-4 py-2 rounded-lg">Submit</button>
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
                                <th class="px-4 py-2 ">Serial</th>
                                <th class="px-4 py-2 ">Date</th>
                                <th class="px-4 py-2 ">From</th>
                                <th class="px-4 py-2 ">To</th>
                                <th class="px-4 py-2 ">Amount</th>
                                <th class="px-4 py-2 ">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach ($transfers as $index => $transfer)
                                <tr>
                                    <td class="px-4 py-2"> {{ $index + 1 }} </td>
                                    <td class="px-4 py-2">{{ $transfer->date }}</td>
                                    <td class="px-4 py-2">{{ $transfer->from }}</td>
                                    <td class="px-4 py-2">{{ $transfer->to }}</td>
                                    <td class="px-4 py-2">{{ $transfer->amount }}</td>
                                    <td class="px-4 py-2">
                                       
                                        @if (in_array('delete', $permissionsArray))
                                            <a href="#"
                                                onclick="confirmDelete('{{ route('moneytransfer.delete', ['id' => $transfer->id]) }}')"
                                                class="text-red-500 px-2 py-1 rounded-md hover:bg-red-100 inline-flex items-center"
                                                title="Delete">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5"
                                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </a>
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


    <script src="https://unpkg.com/flowbite@1.4.0/dist/flowbite.js"></script>

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




</x-app-layout>
