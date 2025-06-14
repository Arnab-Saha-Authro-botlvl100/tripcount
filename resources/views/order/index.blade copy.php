<x-app-layout>
    @if(session('employee'))
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
    <div class="">
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif


    </div>


    <!-- Alpine.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

<div x-data="{ tab: 'home' }">
  <!-- Tabs -->
  <ul class="flex border-b">
    <li class="mr-1">
      <button
        @click="tab = 'home'"
        :class="tab === 'home' ? 'bg-white border-l border-t border-r rounded-t text-blue-700 font-semibold' : 'text-gray-500 hover:text-blue-500'"
        class="px-4 py-2"
      >Invoice</button>
    </li>
    <li class="mr-1">
      <button
        @click="tab = 'profile'"
        :class="tab === 'profile' ? 'bg-white border-l border-t border-r rounded-t text-blue-700 font-semibold' : 'text-gray-500 hover:text-blue-500'"
        class="px-4 py-2"
      >Contract</button>
    </li>
    <li class="mr-1">
      <button
        @click="tab = 'contact'"
        :class="tab === 'contact' ? 'bg-white border-l border-t border-r rounded-t text-blue-700 font-semibold' : 'text-gray-500 hover:text-blue-500'"
        class="px-4 py-2"
      >Wakala</button>
    </li>
  </ul>

  <!-- Tab Contents -->
  <div class="p-4 border border-t-0">
    <div x-show="tab === 'home'">
        <div id="tab1">
            <div class="mb-2">
                <button class="py-2 px-7 border-green-700 hover:bg-green-700 hover:text-white duration-300 border-2 text-green-700  rounded-2xl font-bold" onchange="toggleVisibility()" id="addnew">Add
                    New
                </button>
            </div>
            <h2 class="text-2xl py-4 flex  w-full mx-auto font-bold
            px-2  text-gray-900">
            Invoicing
            </h2>
            @if(in_array('entry', $permissionsArray))
                <form action="/addorder" method="POST" id="addorder" class="w-[80%] p-5 bg-white shadow-lg" id="addorder">
                    @csrf
                    <div class="flex flex-wrap gap-x-10 -mx-4 mb-4">
                        <div class="w-full md:w-[47%] px-4 mb-2 flex items-center">
                            <label for="invoice" class="block w-full md:w-[40%] text-gray-700 text-sm mb-2">Invoice
                                No.</label>
                            <input type="text" id="invoice" readonly
                                class="text-center bg-gray-50 border border-gray-300 text-gray-900 text-sm  focus:ring-blue-500 focus:border-blue-500 block w-full p-2"
                                name="invoice">
                        </div>
                        <div class="w-full md:w-[47%] px-4 mb-2 flex items-center">
                            <label for="date" class="block w-full md:w-[40%]  text-gray-700 text-sm mb-2">Invoice
                                Date</label>
                            <input type="date" id="date"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm  focus:ring-blue-500 focus:border-blue-500 block w-full p-1"
                                name="date" value="<?php echo date('Y-m-d'); ?>">
                        </div>
    
                    </div>
                    <div class="flex flex-wrap gap-x-10 -mx-4 mb-4">
    
                        <div class="w-full md:w-[47%] px-4 mb-2 flex items-center">
                            <label for="date" class="block w-full md:w-[40%]  text-gray-700 text-sm mb-2">Quantity
                            </label>
                            <input type="number" id="num"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm  focus:ring-blue-500 focus:border-blue-500 block w-full p-1"
                                name="">
                        </div>
                        <div class="w-full md:w-[47%] px-4 mb-2 flex items-center">
                            <label for="type" class="block w-full md:w-[40%]  text-gray-700 text-sm mb-2">Invoice
                                Type</label>
    
                            <select
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm  focus:ring-blue-500 focus:border-blue-500 block p-1 w-auto select2"
                                name="type" id="type" placeholder="Select type" required>
                                <option value="">Select Type</option>
                                @foreach ($types as $type)
                                    <option value="{{ $type->id }}">{{ $type->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
    
    
    
                    <div class="flex flex-wrap gap-x-10 -mx-4 mb-4">
    
    
                        <div class="w-full md:w-[47%] px-4 mb-2 flex items-center">
                            <label for="agent" class="block w-full md:w-[40%]  text-gray-700 text-sm mb-2">Client
                                Name</label>
    
                            <select name="agent" id="agent" required
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm  focus:ring-blue-500  focus:border-blue-500 block w-auto p-1 select2">
                                <option value="">Select Client</option>
                                @foreach ($agents as $agent)
                                    <option value="{{ $agent->id }}">{{ $agent->name }}</option>
                                @endforeach
                            </select>
    
                        </div>
                        <div class="w-full md:w-[47%] px-4 mb-2 flex items-center">
                            <label for="seller" class="block w-full md:w-[40%]  text-gray-700 text-sm mb-2">Supplier</label>
    
                            <select name="supplier" id="supplier" required
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm  focus:ring-blue-500 focus:border-blue-500 block max-w-full select2 p-1">
                                <option value="">Select Supplier</option>
                                @foreach ($suppliers as $supplier)
                                    <option value="supplier_{{ $supplier->id }}">{{ $supplier->name }} {{ $supplier->company }}</option>
                                @endforeach
                                @foreach ($agents as $agent)
                                    <option value="agent_{{ $agent->id }}">{{ $agent->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
    
    
    
                    <div class="flex flex-wrap gap-x-10 -mx-4 mb-4">
                        <div class="w-full md:w-[47%] px-4 mb-2 flex items-center">
                            <label for="name" class="block w-full md:w-[40%]  text-gray-700 text-sm mb-2">Passenger
                                Name</label>
                            <input type="text" id="name"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm  focus:ring-blue-500 focus:border-blue-500 block w-full p-1"
                                name="name[]">
                        </div>
                        <div class="w-full md:w-[47%] px-4 mb-2 flex items-center">
                            <label for="passport_no" class="block w-full md:w-[40%]  text-gray-700 text-sm mb-2">Passport
                                No</label>
    
                            <input type="text" maxlength="9" id="passport_no"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm  focus:ring-blue-500 focus:border-blue-500 block w-full p-1"
                                name="passport_no[]">
                        </div>
                    </div>
    
    
    
                    <div class="flex flex-wrap gap-x-10 -mx-4 mb-4">
                        <div class="w-full md:w-[47%] px-4 mb-2 flex items-center">
                            <label for="contact_amount" class="block w-full md:w-[40%]  text-gray-700 text-sm mb-2">Agent
                                Price</label>
                            <input type="text" id="contact_amount"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm  focus:ring-blue-500 focus:border-blue-500 block w-full p-1"
                                name="contact_amount" required>
                        </div>
                        <div class="w-full md:w-[47%] px-4 mb-2 flex items-center">
                            <label for="payable_amount" class="block w-full md:w-[40%]  text-gray-700 text-sm mb-2">Supplier
                                Price</label>
                            <input type="text" id="payable_amount"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm  focus:ring-blue-500 focus:border-blue-500 block w-full p-1"
                                name="payable_amount" required>
                        </div>
                    </div>
                    <div class="flex flex-wrap gap-x-10 -mx-4 mb-4">
                        <div class="w-full md:w-[47%] px-4 mb-2 flex items-center">
                            <label for="country" class="block w-full md:w-[40%]  text-gray-700 text-sm mb-2">Country</label>
                            <select required
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm focus:ring-blue-500 focus:border-blue-500 block w-auto p-1 select2 "
                                name="country" id="country" required>
                                <option value="">Select Country</option>
                                <option value="Afghanistan">Afghanistan</option>
                                <option value="Albania">Albania</option>
                                <option value="Algeria">Algeria</option>
                                <option value="Andorra">Andorra</option>
                                <option value="Angola">Angola</option>
                                <option value="Antigua and Barbuda">Antigua and Barbuda</option>
                                <option value="Argentina">Argentina</option>
                                <option value="Armenia">Armenia</option>
                                <option value="Australia">Australia</option>
                                <option value="Austria">Austria</option>
                                <option value="Azerbaijan">Azerbaijan</option>
                                <option value="Bahamas">Bahamas</option>
                                <option value="Bahrain">Bahrain</option>
                                <option value="Bangladesh">Bangladesh</option>
                                <option value="Barbados">Barbados</option>
                                <option value="Belarus">Belarus</option>
                                <option value="Belgium">Belgium</option>
                                <option value="Belize">Belize</option>
                                <option value="Benin">Benin</option>
                                <option value="Bhutan">Bhutan</option>
                                <option value="Bolivia">Bolivia</option>
                                <option value="Bosnia and Herzegovina">Bosnia and Herzegovina</option>
                                <option value="Botswana">Botswana</option>
                                <option value="Brazil">Brazil</option>
                                <option value="Brunei">Brunei</option>
                                <option value="Bulgaria">Bulgaria</option>
                                <option value="Burkina Faso">Burkina Faso</option>
                                <option value="Burundi">Burundi</option>
                                <option value="Cabo Verde">Cabo Verde</option>
                                <option value="Cambodia">Cambodia</option>
                                <option value="Cameroon">Cameroon</option>
                                <option value="Canada">Canada</option>
                                <option value="Central African Republic">Central African Republic</option>
                                <option value="Chad">Chad</option>
                                <option value="Chile">Chile</option>
                                <option value="China">China</option>
                                <option value="Colombia">Colombia</option>
                                <option value="Comoros">Comoros</option>
                                <option value="Congo">Congo</option>
                                <option value="Costa Rica">Costa Rica</option>
                                <option value="Côte d'Ivoire">Côte d'Ivoire</option>
                                <option value="Croatia">Croatia</option>
                                <option value="Cuba">Cuba</option>
                                <option value="Cyprus">Cyprus</option>
                                <option value="Czechia">Czechia</option>
                                <option value="Denmark">Denmark</option>
                                <option value="Djibouti">Djibouti</option>
                                <option value="Dominica">Dominica</option>
                                <option value="Dominican Republic">Dominican Republic</option>
                                <option value="Ecuador">Ecuador</option>
                                <option value="Egypt">Egypt</option>
                                <option value="El Salvador">El Salvador</option>
                                <option value="Equatorial Guinea">Equatorial Guinea</option>
                                <option value="Eritrea">Eritrea</option>
                                <option value="Estonia">Estonia</option>
                                <option value="Eswatini">Eswatini</option>
                                <option value="Ethiopia">Ethiopia</option>
                                <option value="Fiji">Fiji</option>
                                <option value="Finland">Finland</option>
                                <option value="France">France</option>
                                <option value="Gabon">Gabon</option>
                                <option value="Gambia">Gambia</option>
                                <option value="Georgia">Georgia</option>
                                <option value="Germany">Germany</option>
                                <option value="Ghana">Ghana</option>
                                <option value="Greece">Greece</option>
                                <option value="Grenada">Grenada</option>
                                <option value="Guatemala">Guatemala</option>
                                <option value="Guinea">Guinea</option>
                                <option value="Guinea-Bissau">Guinea-Bissau</option>
                                <option value="Guyana">Guyana</option>
                                <option value="Haiti">Haiti</option>
                                <option value="Holy See">Holy See</option>
                                <option value="Honduras">Honduras</option>
                                <option value="Hungary">Hungary</option>
                                <option value="Iceland">Iceland</option>
                                <option value="India">India</option>
                                <option value="Indonesia">Indonesia</option>
                                <option value="Iran">Iran</option>
                                <option value="Iraq">Iraq</option>
                                <option value="Ireland">Ireland</option>
                                <option value="Israel">Israel</option>
                                <option value="Italy">Italy</option>
                                <option value="Jamaica">Jamaica</option>
                                <option value="Japan">Japan</option>
                                <option value="Jordan">Jordan</option>
                                <option value="Kazakhstan">Kazakhstan</option>
                                <option value="Kenya">Kenya</option>
                                <option value="Kiribati">Kiribati</option>
                                <option value="Kuwait">Kuwait</option>
                                <option value="Kyrgyzstan">Kyrgyzstan</option>
                                <option value="Laos">Laos</option>
                                <option value="Latvia">Latvia</option>
                                <option value="Lebanon">Lebanon</option>
                                <option value="Lesotho">Lesotho</option>
                                <option value="Liberia">Liberia</option>
                                <option value="Libya">Libya</option>
                                <option value="Liechtenstein">Liechtenstein</option>
                                <option value="Lithuania">Lithuania</option>
                                <option value="Luxembourg">Luxembourg</option>
                                <option value="Madagascar">Madagascar</option>
                                <option value="Malawi">Malawi</option>
                                <option value="Malaysia">Malaysia</option>
                                <option value="Maldives">Maldives</option>
                                <option value="Mali">Mali</option>
                                <option value="Malta">Malta</option>
                                <option value="Marshall Islands">Marshall Islands</option>
                                <option value="Mauritania">Mauritania</option>
                                <option value="Mauritius">Mauritius</option>
                                <option value="Mexico">Mexico</option>
                                <option value="Micronesia">Micronesia</option>
                                <option value="Moldova">Moldova</option>
                                <option value="Monaco">Monaco</option>
                                <option value="Mongolia">Mongolia</option>
                                <option value="Montenegro">Montenegro</option>
                                <option value="Morocco">Morocco</option>
                                <option value="Mozambique">Mozambique</option>
                                <option value="Myanmar">Myanmar</option>
                                <option value="Namibia">Namibia</option>
                                <option value="Nauru">Nauru</option>
                                <option value="Nepal">Nepal</option>
                                <option value="Netherlands">Netherlands</option>
                                <option value="New Zealand">New Zealand</option>
                                <option value="Nicaragua">Nicaragua</option>
                                <option value="Niger">Niger</option>
                                <option value="Nigeria">Nigeria</option>
                                <option value="North Korea">North Korea</option>
                                <option value="North Macedonia">North Macedonia</option>
                                <option value="Norway">Norway</option>
                                <option value="Oman">Oman</option>
                                <option value="Pakistan">Pakistan</option>
                                <option value="Palau">Palau</option>
                                <option value="Palestine State">Palestine State</option>
                                <option value="Panama">Panama</option>
                                <option value="Papua New Guinea">Papua New Guinea</option>
                                <option value="Paraguay">Paraguay</option>
                                <option value="Peru">Peru</option>
                                <option value="Philippines">Philippines</option>
                                <option value="Poland">Poland</option>
                                <option value="Portugal">Portugal</option>
                                <option value="Qatar">Qatar</option>
                                <option value="Romania">Romania</option>
                                <option value="Russia">Russia</option>
                                <option value="Rwanda">Rwanda</option>
                                <option value="Saint Kitts and Nevis">Saint Kitts and Nevis</option>
                                <option value="Saint Lucia">Saint Lucia</option>
                                <option value="Saint Vincent and the Grenadines">Saint Vincent and the Grenadines
                                </option>
                                <option value="Samoa">Samoa</option>
                                <option value="San Marino">San Marino</option>
                                <option value="Sao Tome and Principe">Sao Tome and Principe</option>
                                <option value="Saudi Arabia">Saudi Arabia</option>
                                <option value="Senegal">Senegal</option>
                                <option value="Serbia">Serbia</option>
                                <option value="Seychelles">Seychelles</option>
                                <option value="Sierra Leone">Sierra Leone</option>
                                <option value="Singapore">Singapore</option>
                                <option value="Slovakia">Slovakia</option>
                                <option value="Slovenia">Slovenia</option>
                                <option value="Solomon Islands">Solomon Islands</option>
                                <option value="Somalia">Somalia</option>
                                <option value="South Africa">South Africa</option>
                                <option value="South Korea">South Korea</option>
                                <option value="South Sudan">South Sudan</option>
                                <option value="Spain">Spain</option>
                                <option value="Sri Lanka">Sri Lanka</option>
                                <option value="Sudan">Sudan</option>
                                <option value="Suriname">Suriname</option>
                                <option value="Sweden">Sweden</option>
                                <option value="Switzerland">Switzerland</option>
                                <option value="Syria">Syria</option>
                                <option value="Tajikistan">Tajikistan</option>
                                <option value="Tanzania">Tanzania</option>
                                <option value="Thailand">Thailand</option>
                                <option value="Timor-Leste">Timor-Leste</option>
                                <option value="Togo">Togo</option>
                                <option value="Tonga">Tonga</option>
                                <option value="Trinidad and Tobago">Trinidad and Tobago</option>
                                <option value="Tunisia">Tunisia</option>
                                <option value="Turkey">Turkey</option>
                                <option value="Turkmenistan">Turkmenistan</option>
                                <option value="Tuvalu">Tuvalu</option>
                                <option value="Uganda">Uganda</option>
                                <option value="Ukraine">Ukraine</option>
                                <option value="United Arab Emirates">United Arab Emirates</option>
                                <option value="United Kingdom">United Kingdom</option>
                                <option value="United States of America">United States of America</option>
                                <option value="Uruguay">Uruguay</option>
                                <option value="Uzbekistan">Uzbekistan</option>
                                <option value="Vanuatu">Vanuatu</option>
                                <option value="Vatican City">Vatican City</option>
                            </select>
                        </div>
                        <div class="w-full md:w-[47%] px-4 mb-2 flex items-center">
                            <label for="remark" class="block w-full md:w-[40%]  text-gray-700 text-sm mb-2">Remark</label>
                            <textarea id="remark"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm  focus:ring-blue-500 focus:border-blue-500 block w-full p-1"
                                name="remark"></textarea>
                        </div>
    
                    </div>
                    <div class="col-span-2 gap-4 px-8 flex justify-end">
    
                        <button type="submit" id="submit_invoice"
                            class="bg-black text-xl text-white font-medium py-2 px-5 rounded">Submit</button>
                    </div>
    
    
                </form>
                @else
                <div class="alert alert-warning">
                    Don't have permission to entry
                </div>
            @endif
    
                <div class="p-6 rounded-lg mt-5 bg-white py-3">
                    <table class="table divide-y divide-gray-200 table-hover no-wrap" id="ordertable">
                        <thead class="bg-[#7CB0B2]">
                            <tr class="text-md font-normal">
                                <th class="">S.L</th>
                                <th class="">Inv No</th>
                                <th class="">Booking Date</th>
                                <th class="">Passenger Name</th>
                                <th class="">Passport No</th>
                                <th class="">Country</th>
                                <th class="">Client</th>
                                <th class="">Supplier</th>
                                <th class="">Client Amount</th>
                                <th class="">Supplier Amount</th>
                                <th class="">Remark</th>
                                <th class="">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($orders as $key => $order)
                                <tr class="text-md">
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $order->invoice }}</td>
                                    <td class="text-sm w-[150px]">{{ $order->date }}</td>
                                    <td class="w-[180px]">{{ $order->name }}</td>
                                    <td class="w-[75px]">{{ $order->passport_no }}</td>
                                    <td class="text-sm w-[150px]">{{ $order->country }}</td>
                                    <td>
                                        @foreach ($agents as $agent)
                                            @if ($order->agent == $agent->id)
                                                {{ $agent->name }}
                                            @endif
                                        @endforeach
                                    </td>
                                    <td class="text-sm w-[150px]">
                                        @if (isset($order->who) && Str::startsWith($order->who, 'agent_'))
                                            @php
                                                $agentId = intval(explode('_', $order->who)[1]); // Extract the agent ID from 'agent_14'
                                                $agent = $agents->firstWhere('id', $agentId); // Find the agent from the collection
                                            @endphp
                                            @if ($agent)
                                                {{ $agent->name }}
                                            @endif
                                        @else
                                            @foreach ($suppliers as $supplier)
                                                @if ($order->supplier == $supplier->id)
                                                    {{ $supplier->name }} <span class="">{{ $supplier->company }}</span>
                                                @endif
                                            @endforeach
                                        @endif
                                    </td>
                                    <td>{{ $order->contact_amount }}</td>
                                    <td>{{ $order->payable_amount }}</td>
                                    <td>{{ $order->remark }}</td>
                                    <td>
                                        <section class="flex gap-2 text-lg">
                                            @if(in_array('edit', $permissionsArray))
                                                <a href="{{ route('order.edit', ['id' => encrypt($order->id)]) }}"><i class="fa fa-pencil fa-fw"></i> </a>
                                            @endif
                                            @if(in_array('delete', $permissionsArray))
                                                <a href="{{ route('order.delete', ['id' => $order->id]) }}" id="deleteOrderLink"
                                                    data-toggle="modal" data-target="#confirmDeleteModal"><i class="fa fa-trash-o fa-fw"></i></a>
                                            @endif
                                            @if(in_array('view', $permissionsArray))
                                                <a href="{{ route('order.viewInv', ['id' => $order->id]) }}"
                                                    class=" hover:text-green-700 mr-1">
                                                    <i class="fa fa-eye fa-fw text-xl"></i>
                                                </a>
                                            @endif
                                        </section>
                                    </td>
                                </tr>
                            @endforeach
    
                        </tbody>
                    </table>
    
                </div>
    
                <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog " role="document" style="max-width: 1200px;">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Modal Title</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div id="tableContainer"></div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>
    
                        <!-- Confirm Delete Modal -->
                <div class="modal fade" id="confirmDeleteModal" tabindex="-1" role="dialog" aria-labelledby="confirmDeleteLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="confirmDeleteLabel">Confirm Delete</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                Are you sure you want to delete this order?
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                <button type="button" class="btn btn-danger" id="confirmDeleteButton">Delete</button>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <div x-show="tab === 'profile'" x-cloak>
        <div class="mb-2">
            <button class="py-2 px-7 border-green-700 hover:bg-green-700 hover:text-white duration-300 border-2 text-green-700  rounded-2xl font-bold" onchange="toggleVisibilitycontract()" id="addnewcontract">Add
                New Contract
            </button>
        </div>
        <h2 class="text-2xl py-4 flex  w-full mx-auto font-bold
        px-2  text-gray-900">
        Contracts
        </h2>
        @if(in_array('entry', $permissionsArray))
        <div class="bg-white shadow-md rounded-lg p-6 mb-10" id="contractform">
            {{-- <h2 class="text-2xl font-bold text-gray-800 mb-6">Create New Contract</h2> --}}
            <form action="{{ route('contracts.store') }}" method="POST" class="space-y-6">
                @csrf
            
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Invoice -->
                    <div>
                        <label for="invoice" class="block text-sm font-medium text-gray-700 mb-1">Invoice</label>
                        <input type="text" name="invoice" id="invoice" value="{{ $contract_invoice }}"
                            class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                            required readonly>
                    </div>
            
                    <!-- Agent -->
                    <div>
                        <label for="agent_id" class="block text-sm font-medium text-gray-700 mb-1">Agent</label>
                        <select name="agent_id" id="agent_id"
                            class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @foreach($agents as $agent)
                                <option value="{{ $agent->id }}">{{ $agent->name }}</option>
                            @endforeach
                        </select>
                    </div>
            
                    <!-- Total Amount -->
                    <div>
                        <label for="total_amount" class="block text-sm font-medium text-gray-700 mb-1">Total Amount</label>
                        <input type="number" name="total_amount" id="total_amount" step="0.01"
                            class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                            required>
                    </div>
            
                    <!-- Contract Date -->
                    <div>
                        <label for="contract_date" class="block text-sm font-medium text-gray-700 mb-1">Date</label>
                        <input type="date" name="contract_date" id="contract_date"
                            class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                            required>
                    </div>
            
                    <!-- Passport No -->
                    <div>
                        <label for="passport_no" class="block text-sm font-medium text-gray-700 mb-1">Passport No</label>
                        <input type="text" name="passport_no" id="passport_no" maxlength="13"
                            class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                            required>
                    </div>
            
                    <!-- Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                        <input type="text" name="name" id="name"
                            class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                            required>
                    </div>

                    {{-- country --}}
                    <div>
                        <label for="country" class="block text-sm font-medium text-gray-700 mb-1">Country</label>
                        <input type="text" name="country" id="country"
                            class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>
            
                <!-- Notes (Full Width) -->
                <div>
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                    <textarea name="notes" id="notes" rows="3"
                        class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                </div>
            
                <!-- Submit Button -->
                <div class="pt-4 text-right">
                    <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2 rounded shadow-md transition">
                        Create Contract
                    </button>
                </div>
            </form>
            
        </div>
        @else
            <div class="alert alert-warning">
                Don't have permission to entry
            </div>
        @endif
        <div class="bg-white shadow-md rounded-lg p-6 mt-4">
            <h3 class="text-xl font-semibold text-gray-800 mb-4">All Contracts</h3>
            <div class="overflow-x-auto">
                <table id="contracts-table" class="min-w-full divide-y divide-gray-200 text-sm text-left">
                    <thead class="bg-gray-100 text-gray-700">
                        <tr>
                            <th class="px-4 py-2">SL</th>
                            <th class="px-4 py-2">Agent</th>
                            <th class="px-4 py-2">Name</th>
                            <th class="px-4 py-2">Total Amount</th>
                            <th class="px-4 py-2">Date</th>
                            <th class="px-4 py-2">Note</th>
                            <th class="px-4 py-2 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach ($contracts as $key => $contract)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-2">{{ $key + 1 }}</td>
                                <td class="px-4 py-2">{{ $contract->agent_name ?? 'N/A' }}</td>
                                <td class="px-4 py-2">{{ $contract->name ?? '' }}
                                    <br><strong class="text-sm">{{$contract->passport_no}}</strong>
                                </td>
                                <td class="px-4 py-2">{{ number_format($contract->total_amount, 2) }}</td>
                                <td class="px-4 py-2">{{ $contract->contract_date }}</td>
                                <td class="px-4 py-2">{{ $contract->notes }}</td>
                                <td class="px-4 py-2 text-center">
                                    <div class="flex justify-center space-x-2">
                                        <a href="#" 
                                            data-modal-id="modal_{{ $contract->id }}" 
                                            onclick="openUniqueModal('{{ $contract->id }}')" 
                                            class="text-blue-600 hover:text-blue-800 font-medium">
                                            View
                                        </a>
                                                                            
                                        <button onclick="openModal('{{ $contract->id }}', '{{ $contract->total_amount }}', '{{ $contract->notes }}')" 
                                                class="text-blue-600 hover:underline text-sm font-medium">
                                            Edit
                                        </button>
                                    
                                    
                                    </div>
                                </td>
                            </tr>
                              {{-- modal for view contract --}}
                              <div id="modal_{{ $contract->id }}" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
                                <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl mx-auto relative p-6">
                                    <!-- Close Button -->
                                    <button onclick="closeViewModal('modal_{{ $contract->id }}')" class="absolute top-3 right-4 text-gray-400 hover:text-red-600 text-2xl font-bold focus:outline-none">
                                        &times;
                                    </button>
                            
                                    <!-- Modal Header -->
                                    <h2 class="text-2xl font-bold text-gray-800 mb-4 border-b pb-2">Contract Details</h2>
                            
                                    <!-- Static Info -->
                                    <div class="space-y-2 mb-4">
                                        <p><span class="font-medium text-gray-700">Total Amount:</span> <span class="text-blue-600 font-semibold">{{ $contract->total_amount }}</span></p>
                                        <p><span class="font-medium text-gray-700">Notes:</span> <span class="text-gray-600">{{ $contract->notes }}</span></p>
                                    </div>
                            
                                    <!-- AJAX Content Placeholder -->
                                    <div class="modal-content space-y-3 text-sm text-gray-700">
                                        <div class="text-center text-gray-400">Loading service details...</div>
                                        <!-- Contract services will be dynamically injected here -->
                                    </div>
                            
                                    <!-- Optional Footer -->
                                    <div class="mt-6 text-right">
                                        <button onclick="closeViewModal('modal_{{ $contract->id }}')" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium px-4 py-2 rounded-md">
                                            Close
                                        </button>
                                    </div>
                                </div>
                            </div>
                            
                            
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

      
        
        <!--Edit Modal Contract-->
        <div id="contractModal" class="fixed inset-0 bg-gray-800 bg-opacity-50 hidden flex items-center justify-center z-50 overflow-y-auto">
            <div class="bg-white w-full max-w-3xl max-h-[90vh] rounded-lg shadow-lg p-6 relative overflow-hidden">

                <!-- Close Button -->
                <button onclick="document.getElementById('contractModal').classList.add('hidden')" 
                        class="absolute top-2 right-2 text-gray-500 hover:text-gray-700 text-2xl z-10">&times;</button>

                <!-- Scrollable Body -->
                <div class="overflow-y-auto max-h-[75vh] pr-2">
                    <h2 class="text-2xl font-semibold mb-6 text-gray-800">Edit Contract</h2>

                    <!-- Hidden ID field for editing the contract -->

                    <form action="{{ route('contracts.update') }}" method="POST">
                        @csrf
                        {{-- @method('PUT') --}}
                    
                        <!-- Total Amount -->
                        <input type="hidden" id="contract_id" name="contract_id">

                        <div class="mb-4">
                            <label for="total_amount" class="block text-sm font-medium text-gray-700 mb-1">Total Amount</label>
                            <input type="number" name="total_amount" id="total_amount_edit" step="0.01" required
                                class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                    
                        <!-- Notes -->
                        <div class="mb-4">
                            <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                            <textarea name="notes" id="notes_edit" rows="3"
                                    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                        </div>
                    
                        <!-- Service Breakdown -->
                        <h3 class="text-lg font-medium text-gray-800 mt-6 mb-4">Service Breakdown</h3>
                        <div id="serviceContainer" class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            @foreach($types as $type)
                                <div class="flex flex-col bg-gray-300 shadow-sm rounded-lg p-4 border border-gray-200 hover:shadow-lg transition duration-200">
                                    <div class="flex items-center space-x-3 mb-4">
                                        {{-- <input type="checkbox" name="services_checked[]" value="{{ $type }}" id="service_checkbox_{{ $type }}" class="mr-2 h-5 w-5 text-blue-600 focus:ring-blue-500"> --}}
                                        <label for="service_checkbox_{{ $type }}" class="text-sm font-medium text-gray-700">{{ $type->name }}</label>
                                    </div>
                                    <div class="flex flex-col space-y-3">
                                        {{-- <label for="amount_{{ $type }}" class="block text-sm font-medium text-gray-700">Amount</label> --}}
                                        <input type="number" name="services_amount[{{ $type }}]" id="amount_{{ $type->id }}" step="0.01" placeholder="amount"
                                            class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    </div>
                                    <div>
                                        <label for="supplier_{{ $type->id }}" class="block text-sm font-medium text-gray-700 mb-1 mt-2">Select Supplier</label>
                                        <select name="suppliers[{{ $type->id }}]" id="supplier_{{ $type->id }}"
                                            class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                            <option value="">-- Select Supplier --</option>
                                            @foreach($suppliers as $supplier)
                                                <option value="supplier_{{ $supplier->id }}">{{ $supplier->name }}</option>
                                            @endforeach
                                            @foreach($agents as $agent)
                                                <option value="agent_{{ $agent->id }}">{{ $agent->name }}</option>
                                            @endforeach

                                        </select>
                                    </div>
                                </div>
                            @endforeach

                            {{-- supplier selection also collection suppliers type wise supplier will be different --}}
                        </div>
                    
                        <!-- Submit Button -->
                        <div class="mt-6 text-right">
                            <button type="submit"
                                    class="bg-blue-600 text-white px-5 py-2 rounded hover:bg-blue-700 transition duration-200">
                                Update Contract
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
    <div x-show="tab === 'contact'" x-cloak>
      <p>This is the Contact tab content.</p>
    </div>
  </div>
</div>


    
<script>

   function openModal(id, totalAmount, notes) {
    // Set the values in the modal fields
    document.getElementById('contract_id').value = id;
    document.getElementById('total_amount_edit').value = totalAmount;
    document.getElementById('notes_edit').value = notes;

    // Reset all service fields before fetching new data
    $('input[name^="services_amount"]').val('');
    $('select[name^="suppliers"]').val('');

    // Show the modal
    document.getElementById('contractModal').classList.remove('hidden');

    // Load contract services
    $.ajax({
        url: `/contracts_service/details/${id}`,
        method: 'GET',
        success: function (data) {
            $.each(data.services, function (serviceType, entries) {
                $.each(entries, function (index, item) {
                    let supplierValue = item.agent_or_supplier + '_' + item.supplier; // e.g., "agent_85"

                    const allocatedAmount = parseFloat(item.allocated_amount);
                    const amountField = $(`#amount_${serviceType}`);
                    const supplierSelect = $(`#supplier_${serviceType}`);

                    if (amountField.length) {
                        // Instead of accumulating old data, overwrite it
                        amountField.val(allocatedAmount); 
                    }

                    if (supplierSelect.length) {
                        supplierSelect.val(supplierValue);
                    }
                });
            });
        },
        error: function (xhr, status, error) {
            console.error('AJAX error:', error);
        }
    });
    }

</script>

<script>
    function openUniqueModal(contractId) {
        const modalId = `modal_${contractId}`;
        const modalElement = document.getElementById(modalId);

        // Show the modal
        modalElement.classList.remove('hidden');
        const contentContainer = modalElement.querySelector('.modal-content');
        contentContainer.innerHTML = 'Loading...';
        $.ajax({
            url: `/contracts_service/details/${contractId}`,
            method: 'GET',
            success: function (data) {
                // console.log(serviceType[0]);
                let html = '';

                if (Object.keys(data.services).length === 0) {
                    html += '<p class="text-gray-600">No contract services found.</p>';
                } else {
                    $.each(data.services, function (serviceType, entries) {
                        // console.log(serviceType, );
                        html += `<div class="mb-4 border-b border-gray-200 pb-2">
                                    <h3 class="text-md font-semibold text-gray-800">${entries[0]['service_name']}</h3>`;
                        $.each(entries, function (index, item) {
                            html += `<p class="text-sm text-gray-700">Supplier: ${entries[0]['supplier_name']} | Amount: ${item.allocated_amount}</p>`;
                        });
                        html += '</div>';
                    });
                }

                contentContainer.innerHTML = html;
            },
            error: function (xhr, status, error) {
                contentContainer.innerHTML = `<p class="text-red-500">Error fetching contract services: ${error}</p>`;
                console.error('AJAX error:', error);
            }
        });
    }
    
    function closeViewModal(modalId) {
        document.getElementById(modalId).classList.add('hidden');
    }
</script>
    
    <script type="text/javascript">
        var addnew = document.getElementById('addnew');
        var addnewcontract = document.getElementById('addnewcontract');
        var addorder = document.getElementById('addorder');
        var contractform = document.getElementById('contractform');
        
        addorder.style.display = 'none';
        contractform.style.display = 'none';

        addnew.addEventListener('click', function() {
            toggleVisibility();
        });
        addnewcontract.addEventListener('click', function() {
            toggleVisibilitycontract();
        });

        document.addEventListener('DOMContentLoaded', function() {
            // Your code here
            document.getElementById('deleteOrderLink').addEventListener('click', function(event) {
                event.preventDefault(); // Prevents the default link behavior (navigating to the href)

                var isConfirmed = confirm('Are you sure you want to delete this order?');
                // If user confirms, navigate to the delete route
                if (isConfirmed) {
                    window.location.href = document.getElementById('deleteOrderLink').getAttribute('href');
                }
            });
        });

                // Capture the delete link and pass it to the modal's delete button
        $(document).on('click', '#deleteOrderLink', function (e) {
            e.preventDefault(); // Prevent default link action

            // Get the delete link
            var deleteUrl = $(this).attr('href');

            // When the delete button in the modal is clicked
            $('#confirmDeleteButton').on('click', function () {
                // Redirect to the delete route
                window.location.href = deleteUrl;
            });

            // Show the modal (this should already work if you have Bootstrap properly initialized)
            $('#confirmDeleteModal').modal('show');
        });



        function toggleVisibility() {
            if (addorder.style.display === 'none') {
                addorder.style.display = 'block';
            } else {
                addorder.style.display = 'none';
            }

           
        }
        function toggleVisibilitycontract(){
            if(contractform.style.display === 'none'){
                contractform.style.display = 'block';
            } else{
                contractform.style.display = 'none';
            }
        }
    </script>
    <script>
       
       
        $(document).ready(function() {
            $("#type").select2({
                width: '100%'
            });
            $("#country").select2({
                width: '100%'
            });
            $('.datepicker').datepicker({
                autoclose: true
            });

            $('.select2').select2();

            // $('#ordertable').DataTable();
            new DataTable('#ordertable', {
                responsive: true,
                rowReorder: {
                    selector: 'td:nth-child(2)'
                }
            });
            $('#num').on('change', function(event) {
                var number = parseInt($('#num').val());
                if (number > 1) {
                    $('#name').prop('disabled', true);
                } else {
                    $('#name').prop('disabled', false);
                }
            });  
            function generateRandomString() {
                return new Promise((resolve, reject) => {
                    $.ajax({
                        url: '/get-last-id-order', // Replace with the actual URL to fetch the last ID
                        method: 'GET',
                        success: function(response) {
                            console.log(response);
                            let invoice = response.invoice;
                            
                            resolve(invoice);
                        },
                        error: function(error) {
                            console.error('Error fetching last ID:', error);
                            // Reject the promise with the error
                            reject(error);
                        }
                    });
                });
            }

            // Example usage:
            generateRandomString()
                .then(randomString => {
                    $('#invoice').val(randomString);
                    // Do something with the random string here
                })
                .catch(error => {
                    console.error('Failed to generate random string:', error);
            });


        });
        
            $('#ticket_code').on('change', function() {
                var ticketCodeValue = $(this).val();

                // Make an AJAX call
                $.ajax({
                    url: '/search_airline', // Replace with the actual endpoint URL
                    method: 'POST', // Specify the HTTP method (POST, GET, etc.)
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        ticketCode: ticketCodeValue
                    }, // Data to be sent to the server
                    dataType: 'json', // Expected data type of the response
                    success: function(response) {
                        if (response.message == 'Success') {
                            $('#airlines_name').val(response.airline.Full);
                            $('#airlines_code').val(response.airline.Short);
                        } else {
                            alert(response.message);
                        }
                    },
                    error: function(error) {
                        // Handle errors during the AJAX call
                        console.error('Error:', error);
                    }
                });
            });
            $('#num').on('change', function(event) {
                var number_of_orders = parseInt($('#num').val());
                if (number_of_orders > 1) {
                    $('#name').prop('disabled', true);
                    $('#passport_no').prop('disabled', true);
                    $('#name').addClass('bg-gray-300');
                    $('#passport_no').addClass('bg-gray-300');
                } else {
                    $('#name').prop('disabled', false);
                    $('#name').removeClass('bg-gray-300');
                    $('#passport_no').removeClass('bg-gray-300');
                    $('#passport_no').removeClass('bg-gray-300');
                }
            });

            $('#submit_invoice').on('click', function(event) {

                event.preventDefault();
                var invoiceNo = $("#invoice").val();
                var invoiceDate = $("#date").val();
                var invoiceType = $("#type").val();
                var clientName = $("#agent").val();
                var invoiceNumber = $("#num").val();
                var passengerName = $("#name").val();
                var passportNo = $("#passport_no").val();
                var country = $("#country").val();
                var supplier = $("#supplier").val();
                var agentPrice = $("#contact_amount").val();
                var supplierPrice = $("#payable_amount").val();
                var remark = $("#remark").val();

                // console.log("qdw");
                if (invoiceNumber == 1 || invoiceNumber == '') {
                    $("#addorder").submit();
                } else if (invoiceNumber > 1) {

                    if (invoiceNo && invoiceDate && invoiceType && clientName &&
                        country && supplier && agentPrice &&
                        supplierPrice) {

                        var csrfToken = "{{ csrf_token() }}";
                        var tableHtml =
                            '<form id="visa_form" method="post" action="{{ route('addorder.multiple') }}">';
                        tableHtml += '<input type="hidden" name="_token" value="' + csrfToken + '">';
                        tableHtml += '<table class="table">';
                        tableHtml += '<thead>';
                        tableHtml += '<tr>';
                        tableHtml += '<th>Invoice No</th>';
                        tableHtml += '<th>Invoice Date</th>';
                        tableHtml += '<th>Invoice Type</th>';
                        tableHtml += '<th>Pessanger</th>';
                        tableHtml += '<th>Passport</th>';
                        tableHtml += '<th>Agent Price</th>';
                        tableHtml += '<th>Supplier Price</th>';
                        tableHtml += '<th>Remark</th>';
                        // Add more headers as needed
                        tableHtml += '</tr>';
                        tableHtml += '</thead>';
                        tableHtml += '<tbody>';

                        // Populate table rows with data
                        for (var i = 0; i < parseInt(invoiceNumber); i++) {
                            tableHtml += '<tr>';

                            tableHtml += '<td>' + invoiceNo + '</td>';
                            tableHtml += '<td>' + invoiceDate + '</td>';
                            tableHtml += '<td>' + invoiceType + '</td>';

                            tableHtml += '<td>' +
                                '<input type="text" class="form-control" name="passenger[]" id="passenger_' +
                                i + '"></td>';
                            tableHtml += '<td>' +
                                '<input type="text" class="form-control" maxlength="10" name="passport[]" id="passport' +
                                i +
                                '" value=""></td>';

                            tableHtml += '<td>' + agentPrice + '</td>';
                            tableHtml += '<td>' + supplierPrice + '</td>';
                            tableHtml += '<td>' + remark + '</td>';
                            // Add more cells as needed
                            tableHtml += '</tr>';
                        }
                        tableHtml += '<input type="hidden" name="agent" value="' + clientName + '">';
                        tableHtml += '<input type="hidden" name="remark" value="' + remark + '">';
                        tableHtml += '<input type="hidden" name="supplier" value="' + supplier + '">';
                        tableHtml += '<input type="hidden" name="agent_price" value="' + agentPrice + '">';
                        tableHtml += '<input type="hidden" name="supplier_price" value="' + supplierPrice +
                            '">';
                        tableHtml += '<input type="hidden" name="country" value="' + country + '">';
                        tableHtml += '<input type="hidden" name="invoice_no" value="' + invoiceNo +
                            '">';
                        tableHtml += '<input type="hidden" name="invoice_type" value="' + invoiceType +
                            '">';
                        tableHtml += '<input type="hidden" name="invoice_date" value="' + invoiceDate + '">';

                        tableHtml += '</tbody>';
                        tableHtml += '</table>';
                        tableHtml += '<td colspan="10" class="text-center">';
                        tableHtml +=
                            '<button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">';
                        tableHtml += 'Submit';
                        tableHtml += '</button>';
                        tableHtml += '</td>';
                        tableHtml += '</form>';

                        $('#tableContainer').html(tableHtml);

                        $('#myModal').modal('show');

                    } else {}
                }

            });
    </script>

</x-app-layout>
