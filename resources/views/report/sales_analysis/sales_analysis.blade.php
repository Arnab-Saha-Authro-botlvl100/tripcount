@php
$totalSales = 0;
$totalPurchase = 0;
$totalProfit = 0;
$totalReceive = 0;
$totalPayment = 0;
foreach($tableData as $row) {
    $totalSales += $row['salestotalAmount'];
    $totalPurchase += $row['purchasetotalAmount'];
    $totalProfit += $row['profittotalAmount'];
    $totalReceive += $row['receivetotalAmount'];
    $totalPayment += $row['paymenttotalAmount'];
}
@endphp
<!doctype html>
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
      .bold-row td {
        font-weight: bold;
    }

  </style>
</head>

<body class="flex ">
  
  <main class="mx-auto w-full shadow-xl">
    <h2 class="text-center font-bold text-3xl my-2">Sales Analysis</h2>
    <div class="flex items-center justify-between mb-2">
        <div class="text-lg">
            <h2 class="font-semibold">Company Name: {{ Auth::user()->name }}</h2>
            <p><span class="font-semibold">Period Date:</span> {{ $start_date }} to {{ $end_date }}</p>
        </div>
        <div class="flex items-center">
            <!-- You can add any additional content here -->
        </div>
    </div>


      <div class="overflow-x-auto">
        <table class="min-w-full bg-white border border-gray-200">
            <thead>
                <tr class="bg-gray-100 text-gray-700 uppercase text-xs leading-tight">
                    <th class="py-3 px-4 text-left border-b border-gray-200">SL</th>
                    <th class="py-3 px-4 text-left border-b border-gray-200">Trans. Date</th>
                    <th class="py-3 px-4 text-left border-b border-gray-200">Sale Amount</th>
                    <th class="py-3 px-4 text-left border-b border-gray-200">Purchase Amount</th>
                    <th class="py-3 px-4 text-left border-b border-gray-200">Profit Amount</th>
                    <th class="py-3 px-4 text-left border-b border-gray-200">Receive Amount</th>
                    <th class="py-3 px-4 text-left border-b border-gray-200">Payment Amount</th>
                </tr>
            </thead>
            <tbody class="">
                @foreach($tableData as $index => $row)
                <tr class="hover:bg-gray-50 border-b border-gray-200">
                    <td class="py-3 px-4">{{ $index + 1 }}</td>
                    <td class="py-3 px-4">{{ (new \DateTime($row['date']))->format('d-m-Y') }}</td>
                    <td class="py-3 px-4">{{ number_format($row['salestotalAmount']) }}</td>
                    <td class="py-3 px-4">{{ number_format($row['purchasetotalAmount']) }}</td>
                    <td class="py-3 px-4">{{ number_format($row['profittotalAmount']) }}</td>
                    <td class="py-3 px-4">{{ number_format($row['receivetotalAmount']) }}</td>
                    <td class="py-3 px-4">{{ number_format($row['paymenttotalAmount']) }}</td>
                </tr>
                @endforeach
                <tr class="border-2 border-black text-black font-bold">
                    <td class="py-3 px-4">Total Amount</td>
                    <td></td> <!-- No date for total -->
                    <td>{{ number_format($totalSales) }}</td>
                    <td>{{ number_format($totalPurchase) }}</td>
                    <td>{{ number_format($totalProfit) }}</td>
                    <td>{{ number_format($totalReceive) }}</td>
                    <td>{{ number_format($totalPayment) }}</td>
                </tr>
            </tbody>
        </table>
    </div>
    
 </main>
  <!-- <script type="text/javascript">
    
    const rows = document.querySelectorAll('#data tr');
    for (let i = 0; i < rows.length; i += 2) {
      rows[i].classList.add('bg-gray-200');
    }
  </script> -->
  <script src="https://unpkg.com/flowbite@1.4.0/dist/flowbite.js"></script>
</body>

</html>
