<x-app-layout>
    {{-- <div class="container-fluid shadow-lg bg-white">
        <form autocomplete="off" id="reportForm" action="{{ route('payment_report_info') }}" method="POST">
            @csrf
            <div class="flex items-center gap-x-5 py-2">
                
                <div class="w-[250px]">
                    <label for="voucher">Invoice</label>
                    <div class="input-group " style="width: 100%">
                        <input type="text" class="form-control" name="voucher" id="voucher" placeholder="Payment Voucher" />
                    </div>   
                </div>
                <div class="w-fit">
                   
                    <label for="start_date">Start Date</label>
                    <div class="input-group date" style="width: 100%">
                        <input type="text" class="form-control datepicker" name="start_date" id="start_date" placeholder="Start Date" />
                    </div>       
                </div>
                <div class="">
                    <label for="end_date">End Date</label>
                    <div class="input-group date" style="width: 100%">
                        <input type="text" class="form-control datepicker" name="end_date" id="end_date" placeholder="End Date" />
                    </div>      
                </div>
                <div class="">
                    <label for="method">Transaction Method</label><br>
                    <select id="method" name="method" class=" border select2 w-[350px] rounded-md px-2 h-9 text-black bg-gray-200">
                        <option value="">Select Payment Method</option>
                        @foreach ($methods as $method)
                            <option value="{{ $method->id }}">{{ $method->name }}</option>
                        @endforeach
                    </select>     
                </div>
                <div class="w-[300px]">
                    <label for="method">Customer Name</label><br>
                    <select id="customer" name="customer" class="select2 border rounded-md px-2 h-9 text-black bg-gray-200">
                        <option value="">Select Customer</option>
                        @foreach ($agents as $agent)
                            <option value="{{ $agent->getTable() }}_{{ $agent->id }}">{{ $agent->name }}</option>
                        @endforeach
                        @foreach ($suppliers as $supplier)
                            <option value="{{ $supplier->getTable() }}_{{ $supplier->id }}">{{ $supplier->name }}</option>
                        @endforeach
                    </select>     
                </div>
                
                <div class="flex items-end">
                    <button type="submit" class="bg-black px-5 py-2 text-white text-md rounded-md">Submit</button>
                </div>
            </div>
        </form>
    </div> --}}


    <div class="main-content" id="main-content">
        <div class="content">

            <style>
                #reportdiv {
                    /* background-color: rgb(241, 241, 241) !important; */
                    height: 100vh;
                    overflow-y: scroll;
                    box-shadow: none !important;
                    /* Ensure no shadows are rendered */
                }

                * {
                    box-shadow: none !important;
                }

                .scroll-table {
                    -ms-overflow-style: none;
                    /* IE & Edge */
                    scrollbar-width: none;
                    /* Firefox */
                }

                .scroll-table::-webkit-scrollbar {
                    display: none;
                    /* Chrome, Safari, Opera */
                }
            </style>

            <div class="bg-white py-5 px-5 rounded-lg">
                <form autocomplete="off" id="reportForm" action="{{ route('payment_report_info') }}" method="POST">
                    @csrf
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 items-end">


                        <!-- Start Date -->
                        <div class="flex flex-col gap-1">
                            <label for="start_date" class="font-medium text-sm text-gray-700">Start Date</label>
                            <input type="date"
                                class="w-full p-2 border border-gray-300 rounded-md h-[38px] focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                name="start_date" id="start_date"
                                value="{{ old('start_date', now()->subDays(30)->format('Y-m-d')) }}"
                                max="{{ now()->format('Y-m-d') }}">
                            @error('start_date')
                                <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- End Date -->
                        <div class="flex flex-col gap-1">
                            <label for="end_date" class="font-medium text-sm text-gray-700">End Date</label>
                            <input type="date"
                                class="w-full p-2 border border-gray-300 rounded-md h-[38px] focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                name="end_date" id="end_date" value="{{ old('end_date', now()->format('Y-m-d')) }}"
                                max="{{ now()->format('Y-m-d') }}">
                            @error('end_date')
                                <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                            @enderror
                        </div>


                        <!-- Transaction Method -->
                        <div class="flex flex-col gap-1">
                            <label for="method" class=" font-medium text-sm text-gray-700 mb-1">Transaction
                                Method</label>
                            <select id="method" name="method"
                                class="select2 border border-gray-300 rounded-md px-3 py-2 text-gray-700 bg-white focus:ring-2 focus:ring-blue-500">
                                <option value="">Select Payment Method</option>
                                @foreach ($methods as $method)
                                    <option value="{{ $method->id }}"
                                        {{ old('method') == $method->id ? 'selected' : '' }}>
                                        {{ $method->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex flex-col gap-1">
                            <label for="customer" class="block font-medium text-sm text-gray-700 mb-1">Customer
                                Name</label>
                            <select id="customer" name="customer"
                                class="select2 w-full border border-gray-300 rounded-md px-3 py-2 text-gray-700 bg-white focus:ring-2 focus:ring-blue-500">
                                <option value="">Select Customer</option>
                                @foreach ($agents as $agent)
                                    <option value="{{ $agent->getTable() }}_{{ $agent->id }}"
                                        {{ old('customer') == $agent->getTable() . '_' . $agent->id ? 'selected' : '' }}>
                                        {{ $agent->name }} (Agent)
                                    </option>
                                @endforeach
                                @foreach ($suppliers as $supplier)
                                    <option value="{{ $supplier->getTable() }}_{{ $supplier->id }}"
                                        {{ old('customer') == $supplier->getTable() . '_' . $supplier->id ? 'selected' : '' }}>
                                        {{ $supplier->name }} (Supplier)
                                    </option>
                                @endforeach
                            </select>
                        </div>

                    </div>

                    <!-- Customer Selection -->


                    <div
                        class="sm:col-span-4 lg:col-span-4 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mt-2">

                        <div class="flex flex-wrap items-center gap-2">
                            <!-- Filter Controls (Left Side) -->
                            {{-- <div class="flex items-center">
                                    <input type="checkbox" id="show_profit" name="show_profit"
                                        class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                    <label for="show_profit" class="ml-2 text-sm text-gray-700">Show Profit</label>
                                </div>

                                <!-- Show Supplier Checkbox -->
                                <div class="flex items-center">
                                    <input type="checkbox" id="show_supplier" name="show_supplier"
                                        class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                    <label for="show_supplier" class="ml-2 text-sm text-gray-700">Show Supplier</label>
                                </div> --}}
                        </div>

                        <!-- Action Buttons (Right Side) -->
                        <div class="flex flex-wrap items-center gap-2">


                            <!-- Search Button -->
                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 border border-green-600 text-sm font-medium rounded-md shadow-sm text-green-600 bg-white hover:bg-green-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                <i class="fas fa-search mr-2"></i> Search
                            </button>

                            <!-- Download Button -->
                            <button type="button" onclick="downloadReport()"
                                class="inline-flex items-center px-4 py-2 border border-blue-600 text-sm font-medium rounded-md shadow-sm text-blue-600 bg-white hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <i class="fas fa-download mr-2"></i> Download
                            </button>

                            <!-- Print Button -->
                            <button id="printButton"
                                class="inline-flex items-center px-4 py-2 border border-red-600 text-sm font-medium rounded-md shadow-sm text-red-600 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                <i class="fas fa-print mr-2"></i> Print
                            </button>

                            <!-- Back Button -->
                            <button type="button" onclick="goBack()"
                                class="inline-flex items-center px-4 py-2 border border-gray-600 text-sm font-medium rounded-md shadow-sm text-gray-600 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                                <i class="fas fa-arrow-left mr-2"></i> Back
                            </button>
                        </div>
                    </div>

            </div>
            </form>
        </div>


        <div class="reportdiv shadow-lg rounded-lg mt-2" id="reportdiv">

        </div>

    </div>
    </div>


    <script>
        $(document).ready(function() {

            $('#reportForm').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    type: 'POST',
                    url: $(this).attr('action'),
                    data: $(this).serialize(),
                    success: function(response) {
                        // Update the reportdiv with the response
                        $('#reportdiv').html(response.html);
                    },
                    error: function(error) {
                        console.log(error);
                    }
                });
            });
        });
    </script>

    <script>
        // Function to print the content of the reportdiv
        function printReport() {
            var printContents = document.getElementById("reportdiv").innerHTML;
            var originalContents = document.body.innerHTML;
            document.body.innerHTML = printContents;
            window.print();
            document.body.innerHTML = originalContents;
        }

        // Add event listener to the "Print" button
        document.querySelector("#printButton").addEventListener("click", function() {
            printReport();
        });
    </script>
</x-app-layout>
