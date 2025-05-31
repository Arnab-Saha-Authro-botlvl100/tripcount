<x-app-layout>
    <style>
        .main {
            background-color: "white";
        }
    </style>
    <main class="flex-1 m-4 mx-auto max-w-[1060px] px-10">
        <div class="buttons justify-end flex gap-3 shadow-lg p-5">
            <button class="text-white bg-red-600 font-bold text-md py-2 px-4 focus:outline-none" id="printBtn">
                <i class="fas fa-print mr-2"></i>Print
            </button>

            <button onclick="openVoidModal()"
                class="text-white bg-yellow-600 font-bold text-md py-2 px-4 focus:outline-none">
                <i class="fas fa-ban mr-2"></i>Void
            </button>

            <button id="openRefundModal" class="text-white bg-blue-600 font-bold text-md py-2 px-4 focus:outline-none">
              <i class="fas fa-undo-alt mr-2"></i>Refund
            </button>          

            <button id="openReissueModal" class="text-white bg-purple-600 font-bold text-md py-2 px-4 rounded focus:outline-none">
              <i class="fas fa-redo mr-2"></i>Reissue
            </button>            

            <button class="text-white bg-black font-bold text-md py-2 px-4" onclick="goBack()">
                <i class="fas fa-arrow-left mr-2"></i>GO BACK
            </button>
        </div>
        <div class="my-4 bg-white shadow-lg p-5" id="printSection">
            <div class="flex justify-between items-center py-5">
                <div class=""><img src="{{ url(Auth::user()->company_logo) }}" alt="logo" width="150px"
                        height="180px" /></div>

                <div class="w-[350px]">
                    <h3 class="company-name font-bold text-3xl ">{{ Auth::user()->name }}</h3>
                    <p class="company-address text-lg font-medium">{{ Auth::user()->company_address }}</p>
                    <p class="company-phone text-lg font-medium">Mob : {{ Auth::user()->mobile_no }}</p>
                    <p class="company-email text-lg font-medium">Email : {{ Auth::user()->email }}</p>
                </div>
            </div>
            <hr class="mb-3 h-[3px] bg-gray-400 border-none" />
            <div class="font-bold text-3xl text-center">INVOICE DETAILS</div>
            <div class="flex justify-between items-center text-lg">
                <div>
                    <p><span class="font-bold">Date</span>: {{ \Carbon\Carbon::now()->format('d/m/y') }}</p>
                    <p><span class="font-bold">Service Type : </span> Ticket Booking</p>
                </div>
                <div class="w-[350px] mt-3">
                    <h3 class="font-bold text-xl">Agent Details</h3>
                    <p>Name: {{ $agent->name }}</p>
                    <p>Address: {{ $agent->address }}</p>
                    <p>Email : {{ $agent->email }}</p>
                    <p>Mob : {{ $agent->phone }}</p>
                </div>
            </div>
            <div class="w-full overflow-hidden mt-7">
                <table class="min-w-full bg-white border rounded shadow overflow-hidden">
                    <thead class="text-black ">
                        <tr class="bg-gray-300">
                            <td class="py-1 font-bold text-md px-4">Invoice No</td>
                            <td class="py-1 font-bold text-md px-4">Pax Name</td>
                            <td class="py-1 font-bold text-md px-4">Details</th>
                            <td class="py-1 font-bold text-md px-4">Total Balance</th>
                                <!-- Add more columns as needed -->
                        </tr>
                    </thead>
                    <tbody class="text-gray-700 border-black border-b-2">
                        <tr>
                            <td class="py-2 px-4">{{ $ticket->invoice }}</td>
                            <td class="py-2 px-4 font-bold uppercase">{{ $ticket->passenger }}</td>
                            <td class="py-2 px-4">
                                <p>Ticket No: {{ $ticket->ticket_code }}/{{ $ticket->ticket_no }}</p>
                                <p id="flight-date" data-flight-date="{{ $ticket->flight_date }}">Flight Date: </p>
                                <p>Sector : {{ $ticket->sector }}
                                </p>
                                <p>Airline Name : {{ $ticket->airline_name }} / {{ $ticket->airline_code }} </p>

                                <p>Remarks: {{ $ticket->remark }}</p>
                            </td>
                            <td class="py-2 px-4 font-bold">{{ $ticket->agent_price }}</td>
                            <!-- Add more rows and data as needed -->
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td class="py-2 px-4"></td>
                            <td class="py-2 px-4"></td>
                            <td class="py-2 px-4 bg-red-200 font-bold">
                                Total
                            </td>
                            <td class="py-2 px-4 bg-red-200 font-bold">{{ $ticket->agent_price }}</td>
                            <!-- Add more rows and data as needed -->
                        </tr>
                        <tr class="">
                            <td class="py-2 px-4"></td>
                            <td class="py-2 px-4"></td>
                            <td class="py-2 px-4 bg-gray-200 font-bold">
                                Net Amount
                            </td>
                            <td class="py-2 px-4 bg-gray-200 font-bold">{{ $ticket->agent_price }}</td>
                            <!-- Add more rows and data as needed -->
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>


    </main>


    <!-- Void Modal -->
    <div id="voidModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6">
            <h2 class="text-xl font-bold mb-4">Void Confirmation</h2>
            <form id="voidForm" action="{{ route('ticket_void') }}" method="post">
                @csrf
                <input type="hidden" name="ticket" value="{{ $ticket->id }}">

                <input type="hidden" class="form-input mt-1 block text-sm w-[65%] border p-1" id="name"
                    name="name" value="{{ $ticket->passenger }}" required>
                <input type="hidden" class="form-input mt-1 block text-sm w-[65%] border p-1" id="ticket_code"
                    name="ticket_code" value="{{ $ticket->code }}" readonly>
                <input type="hidden" readonly class="form-input mt-1 block text-sm w-[65%] border p-1" id="sector"
                    name="sector" value="{{ $ticket->sector }}">
                <input type="hidden" class="form-control" name="agent" id="agent_id" value="{{ $ticket->agent }}"
                    required>
                <input type="hidden" class="form-control" name="supplier" id="supplier_id"
                    value="{{ $ticket->supplier }}" required>
                <input type="hidden" readonly class="form-input mt-1 block text-sm w-[65%] border p-1" id="agent_fare"
                    name="agent_fare" value="{{ $ticket->agent_price }}" required>
                <input type="hidden" readonly class="form-input mt-1 block text-sm w-[65%] border p-1"
                    id="supplier_fare" name="supplier_fare" value="{{ $ticket->supplier_price }}" required>

                <div class="mb-4">
                    <label for="agent_refundfare" class="block text-sm font-medium text-gray-700">Agent Void
                        Price</label>
                    <input type="number" class="form-input mt-1 w-full block text-sm w-[65%] border p-1"
                        id="agent_refundfare" name="agent_refundfare" required>
                </div>
                <div class="mb-4">
                    <label for="supplier_refundfare" class="block text-sm font-medium text-gray-700">Supplier Void
                        Price</label>
                    <input type="number" class="form-input mt-1 w-full block text-sm w-[65%] border p-1"
                        id="supplier_refundfare" name="supplier_refundfare" required>
                </div>
                <div class="mb-4">
                    <label for="reason" class="block text-sm font-medium text-gray-700">Reason for void</label>
                    <textarea id="reason" name="reason" rows="4"
                        class="mt-1 p-2 block w-full border border-gray-300 rounded-md"></textarea>
                </div>

                <div class="flex justify-end gap-3">
                    <button type="button" onclick="closeVoidModal()"
                        class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">Cancel</button>
                    <button type="submit"
                        class="px-4 py-2 bg-yellow-600 text-white rounded hover:bg-yellow-700">Submit</button>
                </div>
            </form>
        </div>
    </div>

    {{-- refund modal --}}
    <div id="refundModal" class="fixed inset-0 z-50 hidden bg-black bg-opacity-50 flex items-center justify-center">
      <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-lg relative">
        <h2 class="text-2xl font-semibold text-gray-800 mb-4">Refund Ticket</h2>
    
        <form id="voidForm" action="{{ route('refund_ticket_entry') }}" method="post">
          @csrf
    
          <!-- Hidden fields -->
          <input type="hidden" name="ticket" value="{{ $ticket->ticket_no }}">
          <input type="hidden" id="name" name="name" value="{{ $ticket->passenger }}">
          <input type="hidden" id="ticket_code" name="ticket_code" value="{{ $ticket->code }}">
          <input type="hidden" id="sector" name="sector" value="{{ $ticket->sector }}">
          <input type="hidden" id="agent_id" name="agent" value="{{ $ticket->agent }}">
          <input type="hidden" id="supplier_id" name="supplier" value="{{ $ticket->supplier }}">
          <input type="hidden" id="agent_fare" name="agent_fare" value="{{ $ticket->agent_price }}">
          <input type="hidden" id="supplier_fare" name="supplier_fare" value="{{ $ticket->supplier_price }}">
    
          <!-- Refund Date -->
          <div class="mb-4">
            <label for="refund_date" class="block text-sm font-medium text-gray-700 mb-1">Refund Date</label>
            <input type="date" id="refund_date" name="refund_date" required
              class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring focus:ring-blue-200">
          </div>
    
          <!-- Agent Refund Price -->
          <div class="mb-4">
            <label for="agent_refundfare" class="block text-sm font-medium text-gray-700 mb-1">Agent Refund Price</label>
            <input type="number" id="agent_refundfare" name="agent_refundfare" required
              class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring focus:ring-blue-200">
          </div>
    
          <!-- Supplier Refund Price -->
          <div class="mb-4">
            <label for="supplier_refundfare" class="block text-sm font-medium text-gray-700 mb-1">Supplier Refund Price</label>
            <input type="number" id="supplier_refundfare" name="supplier_refundfare" required
              class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring focus:ring-blue-200">
          </div>
    
          <!-- Footer Buttons -->
          <div class="flex justify-end gap-3 mt-6">
            <button type="button" id="closeRefundModal"
              class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600 focus:outline-none">
              Close
            </button>
            <button type="submit"
              class="px-4 py-2 bg-yellow-600 text-white rounded hover:bg-yellow-700 focus:outline-none">
              Submit
            </button>
          </div>
        </form>
      </div>
    </div>
    
    <div id="reissueModal" class="fixed inset-0 z-50 hidden bg-black bg-opacity-50 flex items-center justify-center">
      <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-lg relative">
        <h2 class="text-2xl font-semibold text-gray-800 mb-4">Reissue Ticket</h2>
    
        <form id="reissueForm" action="{{ route('ticket_reissue') }}" method="POST">
          @csrf
    
          <!-- Hidden ticket info -->
          <input type="hidden" name="ticket" value="{{ $ticket->ticket_no }}">
          <input type="hidden" id="name" name="name" value="{{ $ticket->passenger }}">
          <input type="hidden" id="ticket_code" name="ticket_code" value="{{ $ticket->ticket_code }}">
          <input type="hidden" id="sector" name="sector" value="{{ $ticket->sector }}">
          <input type="hidden" id="invoice" name="invoice" value="{{ $nextReissueID }}">
          <input type="hidden" id="flight_date" name="flight_date" value="{{ $ticket->flight_date }}">
          <input type="hidden" id="flight" name="flight" value="{{ $ticket->flight_no }}">
          <input type="hidden" id="agent_id" name="agent" value="{{ $ticket->agent }}">
          <input type="hidden" id="supplier_id" name="supplier" value="{{ $ticket->supplier }}">
          <input type="hidden" id="agent_fare" name="agent_fare" value="{{ $ticket->agent_price }}">
          <input type="hidden" id="supplier_fare" name="supplier_fare" value="{{ $ticket->supplier_price }}">
    
          <!-- Reissue Date -->
          <div class="mb-4">
            <label for="reissue_date" class="block text-sm font-medium text-gray-700 mb-1">Reissue Date</label>
            <input type="date" id="reissue_date" name="reissue_date" required
                   class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring focus:ring-purple-200">
          </div>
          <!-- New Flight  Date -->
          <div class="mb-4">
            <label for="new_flight_date" class="block text-sm font-medium text-gray-700 mb-1">New Flight Date</label>
            <input type="date" id="new_flight_date" name="new_flight_date" required
                   class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring focus:ring-purple-200">
          </div>
    
          <!-- New Ticket Code -->
          <div class="mb-4">
            <label for="new_ticket_code" class="block text-sm font-medium text-gray-700 mb-1">New Ticket Code</label>
            <input type="text" id="new_ticket_code" name="new_ticket_number" required
                   class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring focus:ring-purple-200">
          </div>
    
          <!-- Additional Fare (if any) -->
           <!-- Agent Refund Price -->
           <div class="mb-4">
            <label for="agent_reissuefare" class="block text-sm font-medium text-gray-700 mb-1">Agent Reissue Price</label>
            <input type="number" id="agent_reissuefare" name="agent_reissuefare" required
              class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring focus:ring-blue-200">
          </div>
    
          <!-- Supplier Refund Price -->
          <div class="mb-4">
            <label for="supplier_reissuefare" class="block text-sm font-medium text-gray-700 mb-1">Supplier Reissue Price</label>
            <input type="number" id="supplier_reissuefare" name="supplier_reissuefare" required
              class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring focus:ring-blue-200">
          </div>
    
          <!-- Footer Buttons -->
          <div class="flex justify-end gap-3 mt-6">
            <button type="button" id="closeReissueModal"
                    class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600 focus:outline-none">
              Close
            </button>
            <button type="submit"
                    class="px-4 py-2 bg-purple-600 text-white rounded hover:bg-purple-700 focus:outline-none">
              Submit
            </button>
          </div>
        </form>
      </div>
    </div>
    

    <script type="text/javascript">
        function goBack() {
            window.history.back(); // Navigate back
            setTimeout(function() {
                location.reload(); // Reload the page after going back
            }, 100); // Add a short delay to ensure the navigation completes before reloading
        }
    </script>
    <script>
        function formatDate(dateStr) {
            let date = new Date(dateStr);
            let day = String(date.getDate()).padStart(2, '0');
            let month = String(date.getMonth() + 1).padStart(2, '0');
            let year = date.getFullYear();
            return `${day}-${month}-${year}`;
        }

        // Get the flight date from the data attribute
        let flightDateElement = document.getElementById('flight-date');
        let flightDateStr = flightDateElement.getAttribute('data-flight-date');

        // Format the date
        let formattedDate = formatDate(flightDateStr);

        // Update the element's text content
        flightDateElement.textContent = `Flight Date: ${formattedDate}`;
        document.addEventListener('DOMContentLoaded', function() {
            // Get the "Print" button
            const printButton = document.getElementById('printBtn');
            const printSection = document.getElementById('printSection');

            // Attach a click event listener to the "Print" button
            printButton.addEventListener('click', function() {
                // Open the print dialog for the printSection
                window.print();
            });
        });
    </script>

    <style>
        @media print {
            body * {
                visibility: hidden;
            }

            #printSection,
            #printSection * {
                visibility: visible;
            }

            #printSection {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                max-width: 100%;
                box-sizing: border-box;
                padding: 10px;
                /* Adjust padding as needed */
            }
        }
    </style>


    <script>
        function openVoidModal() {
            document.getElementById('voidModal').classList.remove('hidden');
        }

        function closeVoidModal() {
            document.getElementById('voidModal').classList.add('hidden');
        }
    </script>
    
    <script>
      document.getElementById('openRefundModal').addEventListener('click', function () {
          document.getElementById('refundModal').classList.remove('hidden');
      });
  
      document.getElementById('closeRefundModal').addEventListener('click', function () {
          document.getElementById('refundModal').classList.add('hidden');
      });
  
      // Optional: Close modal on backdrop click
      document.getElementById('refundModal').addEventListener('click', function (e) {
          if (e.target === this) {
              this.classList.add('hidden');
          }
      });
    </script>
  
    <script>
      document.getElementById('openReissueModal').addEventListener('click', function () {
        document.getElementById('reissueModal').classList.remove('hidden');
      });
    
      document.getElementById('closeReissueModal').addEventListener('click', function () {
        document.getElementById('reissueModal').classList.add('hidden');
      });
    </script>
  
</x-app-layout>
