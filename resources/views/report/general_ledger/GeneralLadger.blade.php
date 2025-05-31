<!Doctype html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.0/font/bootstrap-icons.css" />
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        clifford: '#da373d',
                    }
                }
            }
        }
    </script>
    <style>
        @media (max-width: 555px) {
            #infodiv {
                flex-direction: column!important;
            }
        }
       
    </style>
    
</head>

<body class="flex bg-white">

    <main class="flex-1 mx-auto px-2 shadow-lg bg-white py-2">

            <h2 class="text-center font-semibold text-2xl my-2">General Ledger</h2>
            <div class="flex items-center justify-between mb-2" id="infodiv">
                <div class="text-lg">
                    <h2 class="font-semibold">Account Name : {{ $holdername }}</h2>
                    <p><span class="font-semibold">Period Date :</span> {{ $start_date }} to {{ $end_date }} </p>
                </div>
                <div class="flex items-center">
                    <div class="mb-8 sm:max-w-none">
                        <h2 class="font-bold text-xl">{{ Auth::user()->name }}</h2>
                        <p>{{ Auth::user()->company_address }}</p>
                    </div>
                </div>
            </div>
            <div class="scroll-table">
                <table border="1" cellpadding="8" cellspacing="0" style="width: 100%; border-collapse: collapse;">
                    <thead>
                      <tr style="background-color: #f2f2f2;">
                        <th>Invoice Title</th>
                        <th>Ticket No</th>
                        <th>Flight Date</th>
                        <th>Client Details</th>
                        <th>Debit</th>
                        <th>Credit</th>
                        <th>Balance</th>
                      </tr>
                    </thead>
                    <thead class="mt-4 ">
                        <tr class="border-b border-black">
                            <td class="py-2" colspan="4"><b>Opening balance</b></td>
                            <td class="py-2"><b> </b></td>
                            <td class="py-2"><b> </b></td>
                            <td class="py-2" style="margin-top: 20px;"><b>{{ $opening_balance }}</b></td>
                        </tr>
                    </thead>
                    <tbody class="divide-y-2 ">
    
                        {!! $html !!}
                        <tr class="border-t border-black">
                            <td colspan="3"><b>Tickets: {{ $total_ticket }}</b></td>
                            <td><b>Total</b></td>
    
                            <td><b>{{ $debit }}</b></td>
                            <td><b>{{ $credit }}</b></td>
                            <td><b>{{ $balance }}</b></td>
                        </tr>
                    </tbody>
    
                </table>
            </div>
    </main>
    <script type="text/javascript">
        function dropdown() {
            document.querySelector("#submenu").classList.toggle("hidden");
            document.querySelector("#arrow").classList.toggle("rotate-0");
        }
        dropdown();

        function openSidebar() {
            document.querySelector(".sidebar").classList.toggle("hidden");
        }


        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('tr[id^="contract-details-"]').forEach(row => {
            });
        });

        function toggleDetails(id) {
            const detailsRow = document.getElementById(id);
            detailsRow.classList.toggle('hidden');

            const mainRow = detailsRow.previousElementSibling;
            mainRow.classList.toggle('details-shown', !detailsRow.classList.contains('hidden'));
        }

        function toggleDetailsSupplier(id) {
            const detailsRow = document.getElementById(id);
            detailsRow.classList.toggle('hidden');
        }
        function togglePriceVisibility(button, event) {
            // Stop the event from bubbling up to the row click handler
            event.stopPropagation();
            
            const container = button.closest('div');
            const priceValue = container.querySelector('.price-value');
            
            if (priceValue.classList.contains('hidden')) {
                priceValue.classList.remove('hidden');
                button.innerHTML = `
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                    </svg>
                `;
            } else {
                priceValue.classList.add('hidden');
                button.innerHTML = `
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                `;
            }
        }
    </script>
</body>

</html>
