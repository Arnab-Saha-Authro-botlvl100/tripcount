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
        .animate-fade-in {
            animation: fadeIn 0.3s ease-out forwards;
        }

        .animate-fade-out {
            animation: fadeOut 0.3s ease-out forwards;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeOut {
            from {
                opacity: 1;
                transform: translateY(0);
            }

            to {
                opacity: 0;
                transform: translateY(-10px);
            }
        }
    </style>
    <style>
        /* Responsive design adjustments */
        @media (min-width: 640px) {
            .addagent form {
                max-width: 600px;
                margin: 0 auto;
            }
        }


        @media (min-width: 768px) {
            .addagent form {
                max-width: 700px;
            }

            /* For larger screens, you could arrange some fields side by side */
            .grid-cols-2 {
                display: grid;
                grid-template-columns: repeat(2, minmax(0, 1fr));
                gap: 1rem;
            }
        }

        /* General styling */
        .hidden {
            display: none;
        }

        .alert-warning {
            padding: 0.75rem;
            background-color: #fef3c7;
            color: #92400e;
            border-radius: 0.375rem;
        }
    </style>



    <div class="main-content" id="main-content">
        <div class="content">
            <div class="bg-white shadow-md rounded p-6">
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
                    <h2 class="text-lg font-semibold text-gray-800">Accounts</h2>
                </div>

                <div class="mx-auto hidden" id="account-form-div">
                    <div class="flex justify-between items-center mb-4">
                        <h1 class="text-2xl font-bold text-gray-800">Add Airline</h1>
                        <button class="text-gray-500 hover:text-gray-700 transition-colors duration-200"
                            onclick="toggleAccountForm()">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    <div class="bg-white rounded-lg shadow-md overflow-hidden">
                        @if (in_array('entry', $permissionsArray))
                            <form action="/addairline" method="POST" autocomplete="off">
                                @csrf <!-- Add this line to include CSRF protection in Laravel -->
                                <div class="grid grid-cols-1 md:grid-cols-2 md:gap-10">
                                    <div class="mb-4">
                                        <label for="name"
                                            class="block text-sm font-medium text-gray-700">Code:</label>
                                        <input type="text" id="code" name="code"
                                            class="mt-1 p-2 w-full border " placeholder="Enter code" required>
                                    </div>

                                    <div class="mb-4">
                                        <label for="phone" class="block text-sm font-medium text-gray-700">Short
                                            Name:</label>
                                        <input type="text" id="short_name" name="short_name"
                                            class="mt-1 p-2 w-full border " placeholder="Enter short name" required>
                                    </div>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 md:gap-10">
                                    <div class="mb-4">
                                        <label for="email" class="block text-sm font-medium text-gray-700">Full
                                            Name:</label>
                                        <input type="text" id="full_name" name="full_name"
                                            class="mt-1 p-2 w-full border " placeholder="Enter an full name" required>
                                    </div>
                                </div>


                                <button type="submit" class="bg-black text-white px-4 py-2 rounded-lg">Submit</button>
                            </form>
                        @else
                            <div class="p-6 bg-yellow-50 text-yellow-800 rounded-lg">
                                <p class="font-medium">You don't have permission to add airlines.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

    

            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left border-collapse datatable">
                    <thead class="bg-gray-200 text-gray-700">
                        <tr>
                            <th class="px-4 py-2">Serial</th>
                            <th class="px-4 py-2">Code</th>
                            <th class="px-4 py-2">Short Name</th>
                            <th class="px-4 py-2">Full Name</th>

                            <th class="px-4 py-2">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach ($airlines as $index => $airline)
                            <tr>
                                <td class="px-4 py-2">{{ $index + 1 }}</td>
                                <td class="px-4 py-2">{{ $airline->ID }}</td>
                                <td class="px-4 py-2">{{ $airline->Short }}</td>
                                <td class="px-4 py-2">{{ $airline->Full }}</td>

                                <td class="px-4 py-2 flex space-x-2">

                                    <a href="{{ route('airline.edit', ['id' => encrypt($airline->ID)]) }}"
                                        class="text-blue-600 hover:text-blue-900">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                            </path>
                                        </svg>
                                    </a>
                                    {{-- <a href="{{ route('airline.delete', ['id' => $airline->ID]) }}" class="text-red-900 px-2 py-1 rounded-md"><i class="text-xl fa fa-trash-o fa-fw"></i></a> --}}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>


            </div>
        </div>



        <script>
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
        </script>

        <script>
            $(document).ready(function() {
                $('#code').on('change', function() {

                    var codeValue = $(this).val().trim();

                    if (codeValue !== '') {
                        $.ajax({
                            url: '/findairlinefree',
                            method: 'GET', // or 'GET', 'PUT', 'DELETE', etc.
                            data: {
                                code: codeValue
                            },
                            success: function(response) {
                                if (response.is_free === false) {

                                    $('#code').val('');
                                    alert('This Code is occupied by ' + response.airline_name)
                                }
                            },
                            error: function(xhr, status, error) {
                                // Handle error
                                console.error(xhr.responseText);
                            }
                        });
                    }
                });
            });
        </script>

</x-app-layout>
