<x-app-layout>
    
    
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

    <div class="main-content" id="main-content">
        <div class="content">
            <div class="bg-white py-5 px-5 rounded-lg">
                <form autocomplete="off" id="reportForm" class="px-2" action="{{ route('profitreport.info') }}"
                    method="POST">
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
                            class="sm:col-span-4 lg:col-span-4 flex justify-center sm:justify-end mt-2 flex flex-wrap justify-end gap-3">
                            <button type="submit"
                                class="border border-green-600 text-green-600 hover:bg-green-50 px-6 py-2 rounded-md font-medium text-sm h-[38px] flex items-center transition-colors gap-2">
                                <i class="fas fa-search"></i> Search
                            </button>
                            <button onclick="downloadReport()"
                                class="border border-blue-600 text-blue-600 hover:bg-blue-50 font-medium text-sm py-2 px-4 rounded-md h-[38px] flex items-center transition-colors gap-2">
                                <i class="fas fa-download"></i> Download
                            </button>
                            <button id="printButton"
                                class="border border-red-600 text-red-600 hover:bg-red-50 font-medium text-sm py-2 px-4 rounded-md h-[38px] flex items-center transition-colors gap-2">
                                <i class="fas fa-print"></i> Print
                            </button>
                            <button onclick="goBack()"
                                class="border border-gray-600 text-gray-600 hover:bg-gray-50 font-medium text-sm py-2 px-4 rounded-md h-[38px] flex items-center transition-colors gap-2">
                                <i class="fas fa-arrow-left"></i> Back
                            </button>
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
                        // Update the reportdiv with the response
                        // console.log(response);
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