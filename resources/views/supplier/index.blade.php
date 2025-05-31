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


    <div class="main-content" id="main-content">
        <div class="content">
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
                    <h2 class="text-lg font-semibold text-gray-800">Supplier</h2>
                </div>

                <div class="mx-auto hidden " id="account-form-div">
                    <div class="flex justify-between items-center mb-4">
                        <h1 class="text-2xl font-bold text-gray-800">Add Supplier</h1>
                        <button class="text-gray-500 hover:text-gray-700 transition-colors duration-200"
                            onclick="toggleAccountForm()">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <div class="bg-white rounded-lg shadow-md p-5 overflow-hidden">
                        @if (in_array('entry', $permissionsArray))
                            <form autocomplete="off" action="/addsupplier" method="post">
                                @csrf <!-- Add this line to include CSRF protection in Laravel -->
                                <div class="grid grid-cols-2 gap-4">
                                    <div class="mb-4">
                                        <label for="name"
                                            class="block text-sm font-semibold text-gray-600">
                                              <span class="text-red-500">*</span> Name:</label>
                                        <input type="text" class="form-input mt-1 block w-full border p-2"
                                            id="name" name="name" placeholder="Enter your name" required>
                                    </div>
                                    <div class="mb-4">
                                        <label for="phone"
                                            class="block text-sm font-semibold text-gray-600">
                                              <span class="text-red-500">*</span> Phone:</label>
                                        <input type="tel" class="form-input mt-1 block w-full border p-2"
                                            id="phone" name="phone" placeholder="Enter your phone number"
                                            required>
                                    </div>
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div class="mb-4">
                                        <label for="email"
                                            class="block text-sm font-semibold text-gray-600">Email:</label>
                                        <input type="text" class="form-input mt-1 block w-full border p-2"
                                            id="email" name="email" placeholder="Enter an Email">
                                    </div>
                                    <div class="mb-4">
                                        <label for="company"
                                            class="block text-sm font-semibold text-gray-600">Company:</label>
                                        <input type="text" class="form-input mt-1 block w-full border p-2"
                                            id="company" name="company" placeholder="Enter a company">
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

                                <button type="submit"
                                    class="bg-black text-white px-4 py-2 rounded">Submit</button>
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
                                <th class="px-4 py-2">SL</th>
                                <th class="px-4 py-2">Name</th>
                                <th class="px-4 py-2">Phone</th>
                                <th class="px-4 py-2">Email</th>
                                <th class="px-4 py-2">Company</th>
                                <th class="px-4 py-2 text-right">Opening Balance</th>
                                <th class="px-4 py-2 text-right">Due Balance</th>
                                <th class="px-4 py-2">Status</th>
                                <th class="px-4 py-2">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach ($suppliers as $index => $supplier)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-2 w-[30px]">{{ $index + 1 }}</td>
                                    <td class="px-4 py-2 font-medium text-gray-900">{{ $supplier->name }}</td>
                                    <td class="px-4 py-2">
                                        @if ($supplier->phone)
                                            <a href="tel:{{ $supplier->phone }}"
                                                class="hover:text-blue-600">{{ $supplier->phone }}</a>
                                        @else
                                            ☐
                                        @endif
                                    </td>
                                    <td class="px-4 py-2">
                                        @if ($supplier->email)
                                            <a href="mailto:{{ $supplier->email }}"
                                                class="hover:text-blue-600">{{ $supplier->email }}</a>
                                        @else
                                            ☐
                                        @endif
                                    </td>
                                    <td class="px-4 py-2">{{ $supplier->company ?? '☐' }}</td>
                                    <td
                                        class="px-4 py-2 text-right {{ $supplier->opening_balance < 0 ? 'text-red-600' : '' }}">
                                        {{ number_format($supplier->opening_balance ?? 0, 2) }}
                                    </td>
                                    <td
                                        class="px-4 py-2 text-right {{ $supplier->due_amount < 0 ? 'text-red-600' : 'text-green-600' }}">
                                        {{ number_format($supplier->due_amount ?? 0, 2) }}
                                    </td>
                                    <td class="px-4 py-2">
                                        @if ($supplier->is_active)
                                            <span class="text-green-600">✅ Active</span>
                                        @else
                                            <span class="text-red-600">❌ Inactive</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-2 w-[75px] flex gap-2">
                                        @if (in_array('edit', $permissionsArray))
                                            <a href="{{ route('supplier.edit', ['id' => encrypt($supplier->id)]) }}"
                                                class="text-green-500 px-2 py-1 rounded-md hover:bg-green-900">
                                                <i class="text-xl fa fa-pencil fa-fw"></i>
                                            </a>
                                        @endif
                                        @if (in_array('delete', $permissionsArray))
                                            <a href="#"
                                                onclick="confirmDelete('{{ route('supplier.delete', ['id' => $supplier->id]) }}')"
                                                class="text-red-500 px-2 py-1 rounded-md hover:bg-red-900">
                                                <i class=" fa-solid fa-trash"></i>
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        @if ($suppliers->isEmpty())
                            <div class="alert alert-info mt-4">
                                No suppliers found.
                            </div>
                        @endif
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        function confirmDelete(deleteUrl) {
            // Display a confirmation dialog
            const isConfirmed = window.confirm("Are you sure you want to delete?");

            // If the user confirms, proceed with the delete action
            if (isConfirmed) {
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
    </script>

</x-app-layout>
