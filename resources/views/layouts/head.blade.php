
{{--  
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

<!-- CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.20/dist/sweetalert2.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />

<style>
  .select2-container { width: 100% !important; }
  .select2-container .select2-selection--single { height: 38px !important; padding-top: 4px; }
</style>

<!-- Bootstrap Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

<!-- Other Scripts -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>

<!-- ✅ Correct DataTables JS -->
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>

<!-- DataTables Responsive -->
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.20/dist/sweetalert2.all.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/alpinejs@2.8.2/dist/alpine.min.js" defer></script>


<script src="https://unpkg.com/flowbite@1.3.4/dist/flowbite.js"></script>

<script src="https://cdn.tailwindcss.com"></script>
<script>
  
  function goBack() {
    window.history.back();

  }

</script> --}}

<!-- CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.20/dist/sweetalert2.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">
<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.3/themes/smoothness/jquery-ui.css">

<!-- JavaScript Libraries -->
{{-- <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script> --}}
<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.20/dist/sweetalert2.all.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/alpinejs@2.8.2/dist/alpine.min.js" defer></script>
<script src="https://unpkg.com/flowbite@1.3.4/dist/flowbite.js"></script>
<script src="https://code.jquery.com/ui/1.13.3/jquery-ui.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<!-- Add this in your head section -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
<!-- Tailwind CSS -->
<script src="https://cdn.tailwindcss.com"></script>

<style>
  /* Custom Styles */
  .select2-container { 
    width: 100% !important; 
  }
  .select2-container .select2-selection--single { 
    height: 38px !important; 
    padding-top: 4px; 
  }
  .datepicker {
    z-index: 9999 !important;
  }
</style>
<script>
  $( ".datepicker" ).datepicker();
</script>
   
<script>
  // Initialize components when DOM is ready
  $(document).ready(function() {
  
    // Initialize DataTables
    $('.datatable').DataTable({
      responsive: true,
      columnDefs: [
          { responsivePriority: 1, targets: 0 }, // Serial
          { responsivePriority: 2, targets: 1 }, // Serial
          { responsivePriority: 3, targets: -1 } // Action
      ]
    });
  });

  function goBack() {
    window.history.back();
  }
</script>

<script>
    async function downloadReport() {
        try {
            const reportDiv = document.getElementById("reportdiv");
            
            // Create a deep clone of the content
            const clone = reportDiv.cloneNode(true);
            
            // Remove interactive elements
            clone.querySelectorAll('button, [onclick], .no-export').forEach(el => el.remove());
            
            // Create a container with proper styling
            const container = document.createElement('div');
            container.style.width = '100%';
            container.style.padding = '20px';
            container.style.backgroundColor = 'white';
            container.appendChild(clone);
            
            // Add to body temporarily (fixes rendering issues)
            document.body.appendChild(container);
            
            // PDF configuration
            const options = {
                filename: 'Sales_Report_' + new Date().toISOString().slice(0, 10) + '.pdf',
                margin: 10,
                image: { type: 'jpeg', quality: 1 },
                html2canvas: { 
                    scale: 2,
                    scrollX: 0,
                    scrollY: 0,
                    windowWidth: container.scrollWidth,
                    windowHeight: container.scrollHeight,
                    useCORS: true,
                    logging: true,
                    backgroundColor: '#FFFFFF'
                },
                jsPDF: { 
                    unit: 'mm', 
                    format: 'a4', 
                    orientation: 'portrait',
                    compress: true
                }
            };
            
            // Ask for format
            const format = confirm("Download as PDF? (OK for PDF, Cancel for Excel)");
            
            if (format) {
                // Generate PDF
                await html2pdf().set(options).from(container).save();
            } else {
                // Generate Excel
                generateExcel(clone);
            }
            
            // Clean up
            document.body.removeChild(container);
        } catch (error) {
            console.error('Export failed:', error);
            alert('Export failed. Please try again or use print function.');
        }
}

    function generateExcel(element) {
        try {
            const table = element.querySelector('table');
            if (!table) throw new Error('No table found');
            
            let csv = [];
            const rows = table.querySelectorAll('tr');
            
            rows.forEach(row => {
                let rowData = [];
                const cols = row.querySelectorAll('th, td');
                
                cols.forEach(col => {
                    let text = col.textContent.trim();
                    text = text.replace(/"/g, '""');
                    if (/^[\d,.]+$/.test(text)) {
                        text = text.replace(/,/g, '');
                    }
                    rowData.push(`"${text}"`);
                });
                
                csv.push(rowData.join(','));
            });
            
            const blob = new Blob([csv.join('\n')], { type: 'text/csv;charset=utf-8;' });
            const url = URL.createObjectURL(blob);
            const link = document.createElement('a');
            link.href = url;
            link.download = 'Sales_Report_' + new Date().toISOString().slice(0, 10) + '.csv';
            link.click();
        } catch (error) {
            console.error('Excel export failed:', error);
            alert('Excel export failed. Please try PDF export instead.');
        }
    }
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