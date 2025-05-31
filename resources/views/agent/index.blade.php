<x-app-layout>
    @if (session('employee'))
        @php
            $employee = session('employee');
            $permissionString = $employee['permission'];
            $permissionsArray = explode(',', $permissionString);
            $role = $employee['role'];
        @endphp
    @else
        @php
            $permissionsArray = ['entry', 'edit', 'delete', 'print', 'view'];
            $role = 'admin';
        @endphp
    @endif



    <div class="main-content" id="main-content">
        <div class="content">
            {{-- <div class=""> --}}
            <div class="bg-white shadow-md rounded p-6 m-3">
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
                    <h2 class="text-lg font-semibold text-gray-800">Agent/Client</h2>
                </div>

                <div class="mx-auto hidden" id="account-form-div">

                    <div class="flex justify-between items-center mb-4">
                        <h1 class="text-2xl font-bold text-gray-800">Add Agent</h1>
                        <button class="text-gray-500 hover:text-gray-700 transition-colors duration-200"
                            onclick="toggleAccountForm()">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <div class="bg-white rounded-lg shadow-md overflow-hidden p-5">
                        @if (in_array('entry', $permissionsArray))
                            <form action="/addagent" method="POST" autocomplete="off">
                                @csrf <!-- Add this line to include CSRF protection in Laravel -->
                                <div class="grid grid-cols-1 md:grid-cols-2 md:gap-10">
                                    <div class="mb-4">
                                        <label for="name"
                                            class="block text-sm font-medium text-gray-700">
                                              <span class="text-red-500">*</span> 
                                            Name:</label>
                                        <input type="text" id="name" name="name"
                                            class="mt-1 p-2 w-full border " placeholder="Enter your name" required>
                                    </div>

                                    <div class="mb-4">
                                        <label for="phone"
                                            class="block text-sm font-medium text-gray-700">
                                              <span class="text-red-500">*</span> Phone:</label>
                                        <input type="tel" id="phone" name="phone"
                                            class="mt-1 p-2 w-full border " placeholder="Enter your phone number"
                                            required>
                                    </div>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 md:gap-10">
                                    <div class="mb-4">
                                        <label for="email"
                                            class="block text-sm font-medium text-gray-700">Email:</label>
                                        <input type="text" id="email" name="email"
                                            class="mt-1 p-2 w-full border " placeholder="Enter an Email">
                                    </div>

                                    <div class="mb-4">
                                        <label for="district"
                                            class="block text-sm font-medium text-gray-700">District:</label>
                                        <input type="text" id="district" name="district"
                                            class="mt-1 p-2 w-full border " placeholder="Enter a district">
                                    </div>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 md:gap-10">
                                    <div class="mb-4">
                                        <label for="address"
                                            class="block text-sm font-medium text-gray-700">Address:</label>
                                        <textarea id="address" name="address" class="mt-1 p-2 w-full border " placeholder="Enter an address"></textarea>
                                    </div>

                                    <div class="mb-4">
                                        <label for="country"
                                            class="block text-sm font-medium text-gray-700">Country:</label>
                                        <input type="text" id="country" name="country"
                                            class="mt-1 p-2 w-full border " placeholder="Enter a Country">
                                    </div>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 md:gap-10">
                                    <div class="mb-4">
                                        <label for="description"
                                            class="block text-sm font-medium text-gray-700">Description:</label>
                                        <textarea id="description" name="description" class="mt-1 p-2 w-full border " placeholder="Enter a description"></textarea>
                                    </div>

                                    <div class="mb-4">
                                        <label for="opening_balance"
                                            class="block text-sm font-bold text-gray-700">Opening Balance:</label>
                                        <input type="number" id="opening_balance" name="opening_balance"
                                            class="mt-1 p-2 w-full border-2 border-red-600"
                                            placeholder="Enter Opening Balance">
                                    </div>
                                </div>


                                <button type="submit" class="bg-black text-white px-4 py-2 rounded-lg">Submit</button>
                            </form>
                        @else
                            <div class="p-6 bg-yellow-50 text-yellow-800 rounded-lg">
                                <p class="font-medium">You don't have permission to add.</p>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left border-collapse datatable">
                        <thead class="bg-gray-200 text-gray-700">
                            <tr>
                                <th class="px-4 py-2">Sl.</th>
                                <th class="px-4 py-2">Name</th>
                                <th class="px-4 py-2">Mobile</th>
                                <th class="px-4 py-2">Email</th>
                                <th class="px-4 py-2 text-right">Opening Balance</th>
                                <th class="px-4 py-2 text-right">Due Balance</th>
                                <th class="px-4 py-2">Status</th>
                                <th class="px-4 py-2">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach ($agents as $index => $client)
                                <tr>
                                    <td class="px-4 py-2">{{ $index + 1 }}</td>
                                    <td class="px-4 py-2">{{ $client->name }}</td>
                                    <td class="px-4 py-2">
                                        @if ($client->phone)
                                            <a href="tel:{{ $client->phone }}">{{ $client->phone }}</a>
                                        @else
                                            ☐
                                        @endif
                                    </td>
                                    <td class="px-4 py-2">
                                        @if ($client->email)
                                            <a href="mailto:{{ $client->email }}">{{ $client->email }}</a>
                                        @else
                                            ☐
                                        @endif
                                    </td>
                                    <td
                                        class="px-4 py-2 text-right {{ $client->opening_balance < 0 ? 'text-red-600' : '' }}">
                                        {{ number_format($client->opening_balance ?? 0, 2) }}
                                    </td>
                                    <td
                                        class="px-4 py-2 text-right {{ $client->due_amount < 0 ? 'text-red-600' : 'text-green-600' }}">
                                        {{ number_format($client->due_amount ?? 0, 2) }}
                                    </td>
                                    <td class="px-4 py-2">
                                        @if ($client->is_active)
                                            <span class="text-green-600">✅ Active</span>
                                        @else
                                            <span class="text-red-600">❌ Inactive</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-2 flex gap-2">
                                        @if (in_array('edit', $permissionsArray))
                                            <a href="{{ route('agent.edit', ['id' => encrypt($client->id)]) }}"
                                                class="text-blue-500 px-2 py-1 rounded-md hover:bg-blue-900">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                    </path>
                                                </svg>
                                            </a>
                                        @endif
                                        @if (in_array('delete', $permissionsArray))
                                            <a href="#"
                                                onclick="confirmDelete('{{ route('agent.delete', ['id' => $client->id]) }}')"
                                                class="text-red-500 px-2 py-1 rounded-md hover:bg-red-900">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                    </path>
                                                </svg>
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>


            </div>
            {{-- </div> --}}
        </div>
    </div>

    <script>
        function confirmDelete(deleteUrl) {
            if (window.confirm("Are you sure you want to delete?")) {
                window.location.href = deleteUrl;
            }
        }

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

     
        // Format phone numbers
        document.querySelectorAll('td:nth-child(5)').forEach(td => {
            const phone = td.textContent.trim();
            if (phone && phone !== '☐') {
                td.innerHTML = `<a href="tel:${phone}">${phone}</a>`;
            }
        });
    </script>
</x-app-layout>
