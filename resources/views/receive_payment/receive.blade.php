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
        form {
            /* max-width: 900px; */
            margin: auto;
            background: #fff;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            font-size: 24px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
            color: #1d4ed8;
        }

        .form-row {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            margin-bottom: 20px;
        }

        .form-group {
            flex: 1 1 300px;
            display: flex;
            flex-direction: column;
        }

        label {
            font-weight: bold;
            margin-bottom: 6px;
            color: #333;
        }

        input[type="text"],
        input[type="date"],
        select,
        textarea {
            padding: 8px 12px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 14px;
            background: #fff;
            color: #000;
        }

        input[readonly] {
            background: #f9f9f9;
            text-align: center;
        }

        .readonly-box {
            background: #f3f3f3;
            padding: 8px 12px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .note {
            color: red;
            font-size: 13px;
        }

        .rcv_button {
            padding: 10px 20px;
            background: #000;
            color: #fff;
            font-weight: bold;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }

        .rcv_button:hover {
            background: #222;
        }

        @media (max-width: 600px) {
            h1 {
                font-size: 20px;
                flex-direction: column;
                align-items: flex-start;
            }

            .form-group {
                flex: 1 1 100%;
            }
        }
    </style>
    <div class="main-content" id="main-content">
        <div class="content">
            @if (in_array('entry', $permissionsArray))
                <form autocomplete="off" action="{{ route('submit.receive') }}" id="submit_form_receive" method="post">
                    @csrf
                    <div class="flex justify-between">
                        <h1>
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Receive Details
                        </h1>
                        <div class="flex justify-between">
                            <label for="receiveRef" style="padding:10px;">Ref. No.</label>
                            <input type="text" style="padding: 0%; border: none;" id="receiveRef" name="receiveRef"
                                value="{{ $next_index }}" readonly />
                        </div>


                    </div>

                    <div class="form-row">

                        <div class="form-group">
                            <label for="receiveDate"><span class="text-red-500">*</span>Date</label>
                            <input type="date" id="receiveDate" name="receiveDate" required  />
                        </div>

                        <div class="form-group">
                            <label for="transaction_type">Transaction Method</label>
                            <select id="transaction_type" name="transaction_type">
                                <option value="" selected>Select one</option>
                                @foreach ($transaction_types as $type)
                                    <option value="{{ $type }}">{{ $type }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="from_account"><span class="text-red-500">*</span> From Account:</label>
                            <select id="from_account" required name="receiveMethod" onchange="showRemainingBalance(this, 'from')">
                                <option value="">Select Account</option>
                                @foreach ($methods as $method)
                                    <option value="{{ $method->id }}" data-balance="{{ $method->amount }}"
                                        data-account-name="{{ $method->name }}"
                                        data-transaction_type="{{ $method->transaction_type }}">
                                        {{ $method->name }}
                                    </option>
                                @endforeach
                            </select>
                            <div id="selected-amount" class="note mt-1"></div>
                        </div>

                    </div>

                    <div class="form-row">

                        <div class="form-group">
                            <label for="clientName"><span class="text-red-500">*</span>Select Vendor</label>
                            <select id="clientName" name="clientName" required>
                                <option value="" selected>Select one</option>
                                @foreach ($agents as $agent)
                                    <option value="{{ $agent->getTable() }}_{{ $agent->id }}">{{ $agent->name }}
                                    </option>
                                @endforeach
                                @foreach ($suppliers as $supplier)
                                    <option value="{{ $supplier->getTable() }}_{{ $supplier->id }}">{{ $supplier->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Due</label>
                            <div class="readonly-box">1,122,496</div>
                        </div>

                        <div class="form-group">
                            <label>Available Balance</label>
                            <div class="readonly-box">4,486</div>
                        </div>

                        <div class="form-group">
                            <label for="receiveAmount">Amount <span class="text-red-500">*</span></label>
                            <input type="text" id="receiveAmount" required name="receiveAmount" placeholder="Enter amount" />
                            <p class="note">Amount is required</p>
                        </div>

                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="remarks">Note</label>
                            <textarea id="remarks" name="remarks" rows="2" placeholder="Write notes if any..."></textarea>
                        </div>

                    </div>

                    <div class="form-row">

                        <div class="form-group">
                            <label for="transactionCharge">Transaction Charge</label>
                            <input type="text" id="transactionCharge" name="transactionCharge" />
                        </div>
                        <div class="form-group">
                            <label for="receiptNo">Receipt / Trans No</label>
                            <input type="text" id="receiptNo" name="receiptNo" />
                        </div>
                    </div>

                    <div style="text-align: right; margin-top: 20px;">
                        <button type="submit" class="rcv_button">Submit</button>
                    </div>

                </form>
            @else
                <div class="p-6 bg-yellow-50 text-yellow-800 rounded-lg">
                    <p class="font-medium">You don't have permission to add.</p>
                </div>
            @endif
        </div>
    </div>


    <script>
        $('#submit_form_receive').on('submit', function(e) {
            e.preventDefault();

            let form = $(this);
            let formData = new FormData(this);

            $.ajax({
                url: form.attr('action'),
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': $('input[name="_token"]').val()
                },
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: response.message,
                            confirmButtonColor: '#3085d6',
                            confirmButtonText: 'OK'
                        });

                        console.log(response.fullEntry); // Optional: handle returned data
                        form.trigger('reset'); // Optional: clear the form
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Something went wrong!',
                        });
                    }
                },
                error: function(xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Submission Failed',
                        text: 'An error occurred. Please try again later.',
                    });

                    console.error(xhr.responseText);
                }
            });
        });
    </script>


    <script>
        document.getElementById('transaction_type').addEventListener('change', function() {
            const selectedType = this.value;
            const accountSelect = document.getElementById('from_account'); // ✅ Correct ID
            const allOptions = accountSelect.querySelectorAll('option[data-transaction_type]');

            // Hide all options
            allOptions.forEach(option => {
                option.style.display = 'none';
            });

            if (selectedType) {
                allOptions.forEach(option => {
                    if (option.getAttribute('data-transaction_type') === selectedType) {
                        option.style.display = 'block';
                    }
                });
                // Optionally reset selected value
                accountSelect.value = '';
            } else {
                allOptions.forEach(option => {
                    option.style.display = 'block';
                });
            }
        });


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
