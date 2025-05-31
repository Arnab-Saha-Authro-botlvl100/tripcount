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
                <form autocomplete="off" id="reportForm" class="px-2" action="{{ route('general_ledger_report') }}"
                    method="POST">
                    @csrf
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 items-end">
                        <!-- Agent/Supplier -->
                        <div class="flex flex-col gap-1">
                            <label for="agent_supplier" class="font-medium text-sm text-gray-700">
                                Agent/Supplier <span class="text-red-500">*</span>
                            </label>
                            <select id="agent_supplier" name="agent_supplier"
                                class="text-gray-900 text-sm bg-white border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 w-full p-2 h-[38px]"
                                required>
                                <option value="">Select Agent</option>
                                <option value="agent" {{ old('agent_supplier') == 'agent' ? 'selected' : '' }}>Agent
                                </option>
                                <option value="supplier" {{ old('agent_supplier') == 'supplier' ? 'selected' : '' }}>
                                    Supplier</option>
                            </select>
                            @error('agent_supplier')
                                <span class="text-red-500 text-xs">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Candidate -->
                        <div class="flex flex-col gap-1">
                            <label for="agent_supplier_id" class="font-medium text-sm text-gray-700">Candidate name
                                <span class="text-red-500">*</span>
                            </label>
                            <select id="agent_supplier_id" name="agent_supplier_id"
                                class="text-gray-900 text-sm bg-white border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 w-full p-2 h-[38px] select2"
                                required>
                                <option value="">Select Candidate</option>
                            </select>

                        </div>

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


    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>



    <script>
        $(document).ready(function() {


            $("#agent_supplier").change(function() {
                var selectedValue = $(this).val();
                if (selectedValue) {
                    $.ajax({
                        url: "/get_agent_supplier",
                        method: "GET",
                        data: {
                            who: selectedValue
                        },
                        success: function(response) {
                            updateOptions(response);
                        },
                        error: function(error) {
                            alert(error);
                        }
                    });
                }
            });

            function updateOptions(options) {
                var selectElement = $("#agent_supplier_id");
                selectElement.empty();
                selectElement.append("<option value=''>Select One</option>");
                options.forEach(function(option) {
                    selectElement.append("<option value='" + option.id + "'>" + option.name + "</option>");
                });
            }

            $('#reportForm').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    type: 'POST',
                    url: $(this).attr('action'),
                    data: $(this).serialize(),
                    success: function(response) {

                        $('#reportdiv').html('');
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
        function printReport() {
            const reportDiv = document.getElementById("reportdiv");
            if (!reportDiv) {
                console.error("Error: Report div not found");
                return;
            }

            // Create a clone to modify without affecting the original
            const reportClone = reportDiv.cloneNode(true);

            // Apply print-specific styling
            const printCSS = `
                <style>
                    @page {
                        size: A4 portrait;
                        margin: 10mm;
                    }
                    body {
                        font-family: Arial, sans-serif;
                        font-size: 10pt;
                        line-height: 1.3;
                        color: #000;
                        background: none;
                    }
                    table {
                        width: 100% !important;
                        max-width: 100% !important;
                        border-collapse: collapse;
                        font-size: 9pt;
                        page-break-inside: auto;
                    }
                    th, td {
                        padding: 4px 6px !important;
                        border: 1px solid #ddd !important;
                        vertical-align: top;
                    }
                    th {
                        background-color: #f2f2f2 !important;
                        font-weight: bold;
                    }
                    tr {
                        page-break-inside: avoid;
                        page-break-after: auto;
                    }
                    .no-print {
                        display: none !important;
                    }
                    .total-row {
                        font-weight: bold;
                        background-color: #f8f8f8 !important;
                    }
                    small {
                        font-size: 8pt !important;
                    }
                    @media print {
                        .page-break {
                            page-break-before: always;
                        }
                    }
                </style>
            `;

            // Process the table for better printing
            const tables = reportClone.querySelectorAll('table');
            tables.forEach(table => {
                // Ensure table fits page width
                table.style.width = '100%';

                // Add zebra striping for readability
                const rows = table.querySelectorAll('tr:not(:first-child)');
                rows.forEach((row, index) => {
                    if (index % 2 === 0) {
                        row.style.backgroundColor = '#f9f9f9';
                    }
                });
            });

            const printWindow = window.open("", "_blank", "width=900,height=700");
            if (!printWindow) {
                // Fallback: Print in current window
                const originalHTML = document.body.innerHTML;
                document.body.innerHTML = printCSS + reportClone.outerHTML;
                setTimeout(() => {
                    window.print();
                    document.body.innerHTML = originalHTML;
                }, 1000);
                return;
            }

            printWindow.document.write(`
                <!DOCTYPE html>
                <html>
                    <head>
                        <title>General Ledger Report</title>
                        ${printCSS}
                    </head>
                    <body>
                    
                        ${reportClone.outerHTML}
                    </body>
                </html>
            `);
            printWindow.document.close();

            printWindow.onload = function() {
                setTimeout(() => {
                    printWindow.print();
                    setTimeout(() => {
                        printWindow.close();
                    }, 1000);
                }, 500);
            };
        }

        // Safe event listener
        document.addEventListener("DOMContentLoaded", () => {
            const printButton = document.getElementById("printButton");
            if (printButton) {
                printButton.addEventListener("click", printReport);
            }
        });

        function downloadReport() {
            const {
                jsPDF
            } = window.jspdf;
            const reportDiv = document.getElementById("reportdiv");

            // First try to use html2pdf for better text-based PDF
            if (window.html2pdf) {
                const opt = {
                    margin: 10,
                    filename: 'general_ledger_report.pdf',
                    image: {
                        type: 'jpeg',
                        quality: 0.98
                    },
                    html2canvas: {
                        scale: 2,
                        logging: true,
                        useCORS: true,
                        allowTaint: true,
                        backgroundColor: '#FFFFFF'
                    },
                    jsPDF: {
                        unit: 'mm',
                        format: 'a4',
                        orientation: 'portrait'
                    },
                    pagebreak: {
                        mode: ['avoid-all', 'css', 'legacy']
                    }
                };

                return html2pdf()
                    .set(opt)
                    .from(reportDiv)
                    .save();
            }

            // Fallback to html2canvas if html2pdf not available
            html2canvas(reportDiv, {
                scale: 2,
                logging: true,
                useCORS: true,
                allowTaint: true,
                backgroundColor: '#FFFFFF',
                scrollX: 0,
                scrollY: 0,
                windowWidth: reportDiv.scrollWidth,
                windowHeight: reportDiv.scrollHeight
            }).then((canvas) => {
                const imgData = canvas.toDataURL('image/png');
                const pdf = new jsPDF('p', 'mm', 'a4');
                const imgWidth = 190; // Max width for A4
                const imgHeight = (canvas.height * imgWidth) / canvas.width;

                // Calculate how many pages we need
                const pageHeight = pdf.internal.pageSize.getHeight();
                let heightLeft = imgHeight;
                let position = 10; // Top margin

                // Add first page
                pdf.addImage(imgData, 'PNG', 10, position, imgWidth, imgHeight);
                heightLeft -= pageHeight;

                // Add additional pages if needed
                while (heightLeft > 0) {
                    position = heightLeft - imgHeight;
                    pdf.addPage();
                    pdf.addImage(imgData, 'PNG', 10, position, imgWidth, imgHeight);
                    heightLeft -= pageHeight;
                }

                pdf.save('general_ledger_report.pdf');
            }).catch(error => {
                console.error('Error generating PDF:', error);
                alert('Error generating PDF. Please try again or use the print function.');
            });
        }
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
</x-app-layout>
