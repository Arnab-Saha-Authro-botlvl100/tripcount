<x-app-layout>
 
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
                <form autocomplete="off" id="reportForm" action="{{ route('ait_report_info') }}" method="POST">
                    @csrf
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 items-end">

                        <!-- Start Date -->
                        <div class="flex flex-col gap-1">
                            <label for="start_date" class="font-medium text-sm text-gray-700">Start Date</label>
                            <input type="date"
                                class="form-control w-full p-2 border border-gray-300 rounded-md h-[38px]"
                                name="start_date" id="start_date" placeholder="Date" value="{{ old('start_date') }}" />
                            @error('start_date')
                                <span class="text-red-500 text-xs">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- End Date -->
                        <div class="flex flex-col gap-1">
                            <label for="end_date" class="font-medium text-sm text-gray-700">End Date</label>
                            <input type="date"
                                class="form-control w-full p-2 border border-gray-300 rounded-md h-[38px]"
                                name="end_date" id="end_date" placeholder="Date" value="{{ old('end_date') }}" />
                            @error('end_date')
                                <span class="text-red-500 text-xs">{{ $message }}</span>
                            @enderror
                        </div>



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



    {{-- <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script> --}}
    <script>
        $(document).ready(function() {
           
            $('#reportForm').submit(function (e) {
                e.preventDefault();
                $.ajax({
                    type: 'POST',
                    url: $(this).attr('action'),
                    data: $(this).serialize(),
                    success: function (response) {
                        $('#reportdiv').html(response.html);
                    },
                    error: function (error) {
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