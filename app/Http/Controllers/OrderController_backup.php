<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Add this line
use App\Models\Supplier;
use App\Models\Agent;
use App\Models\Type;
use App\Models\Order;
use Illuminate\View\View;
use DateTime;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;


class OrderController extends Controller
{
    public function index()
    {
        if(Auth::user()){
            $user = Auth::id();
            $suppliers = Supplier::where([['is_delete',0],['is_active',1],['user',$user]])->get();
            $agents = Agent::where([['is_delete',0],['is_active',1],['user',$user]])->get();
            $types = Type::where([['is_delete',0],['is_active',1],['user',$user]])->get();
            $orders = Order::where([['is_delete',0],['is_active',1],['user', $user]])->get();
     
            return view('order/index', compact('suppliers', 'agents', 'types', 'orders'));
        }
        else{
            return view('welcome');
        }
       
    }

    public function store(Request $request)
    {
        // dd("d");
        // dd($request->all());
        if(Auth::user()){
            try {
                $validatedData = $request->validate([
                    'date' => 'required|date',
                    'type' => 'required|integer|exists:type,id',
                    'agent' => 'required|integer|exists:agent,id',
                    'seller' => 'nullable|string|max:255',
                    'phone' => 'nullable|string|max:15',
                    // 'name' => 'required|string|max:255',
                    'name.*' => 'required|string|max:255',
                    // 'passport_no' => 'required|string|max:255',
                    'passport_no.*' => 'required|string|max:255',
                    'contact_amount' => 'required|numeric',
                    'payable_amount' => 'required|numeric',
                    'other_expense' => 'nullable|numeric',
                    'country' => 'required|string',
                    'supplier' => 'required|exists:supplier,id',
                    'remark' => 'nullable|string',
                    'invoice' => 'required|string|max:255',
                ]);
                DB::beginTransaction();
                $order = new Order();
                $order->name = implode(',', $validatedData['name']); // Concatenate multiple names into a single string
                $order->date = $validatedData['date'];
                $order->type = $validatedData['type'];
                $order->agent = $validatedData['agent'];
                $order->passport_no = implode(',', $validatedData['passport_no']); // Concatenate multiple passport numbers
                $order->contact_amount = $validatedData['contact_amount'];
                $order->payable_amount = $validatedData['payable_amount'];
                $order->country = $validatedData['country'];
                $order->supplier = $validatedData['supplier'];
                $order->remark = $validatedData['remark'];
                $order->invoice = $validatedData['invoice'];
            
                // Calculate profit
                if ($request->has('other_expense')) {
                    $profit = $request->contact_amount - ($request->payable_amount + $request->other_expense);
                } else {
                    $profit = $request->contact_amount - $request->payable_amount;
                }
                $order->profit = $profit;
            
                // Get current user ID
                $validatedData['user'] = Auth::id();
                $order->user = $validatedData['user'];
            
                // Update agent's amount
                $agent = Agent::find($validatedData['agent']);
    
                $agent_prev_amount = $agent->amount;
                $agent_new_amount = floatval($agent->amount) + floatval($request->contact_amount);
    
                $validatedData['agent_prev_amount'] = $agent_prev_amount;
                $validatedData['agent_new_amount'] = $agent_new_amount;
    
                $order->agent_previous_amount = $agent_prev_amount;
                $order->agent_new_amount = $agent_new_amount;
    
                $agent->amount += $request->contact_amount;
                $agent->save();
            
                // Update supplier's amount
                $supplier = Supplier::find($validatedData['supplier']);
                $supplier_prev_amount = $supplier->amount;
                $supplier_new_amount = floatval($supplier->amount) + floatval($request->payable_amount);
    
                $validatedData['supplier_prev_amount'] = $supplier_prev_amount;
                $validatedData['supplier_new_amount'] = $supplier_new_amount;
    
                $order->supplier_previous_amount = $validatedData['supplier_prev_amount'];
                $order->supplier_new_amount = $validatedData['supplier_new_amount'];
    
                $supplier->amount += $request->payable_amount;
                $supplier->save();
            
                // dd($order, $validatedData, $order->save());
                // Save the order
                $isdone = $order->save();
                if ($isdone) {
                    DB::commit(); // Commit the transaction if saving is successful
                    return redirect()->route('order.view')->with('success', 'Order added successfully');
                }
            } catch (\Throwable $e) {
                DB::rollBack(); // Roll back the transaction if an exception occurs
                return redirect()->route('order.view')->with('error', $e->getMessage());
            }
            
        }
        else{
            return view('welcome');
        }
    }

    public function store_multiple(Request $request)
    {
        // dd("d", $request->all());
        if(Auth::user()){
            try {
                DB::beginTransaction();
                $passengerCount = count($request->passenger);
                
                for ($i = 0; $i < $passengerCount; $i++) {
                    
                    $order = new Order();
                    $order->name = $request->passenger[$i]; // Assuming $request->passenger is an array
                    $order->date = $request->invoice_date;
                    $order->type = $request->invoice_type;
                    $order->agent = $request->agent;
                    $order->passport_no = $request->passport[$i]; // Assuming $request->passport is an array
                    $order->contact_amount = $request->agent_price;
                    $order->payable_amount = $request->supplier_price;
                    $order->country = $request->country;
                    $order->supplier = $request->supplier;
                    $order->remark = $request->remark;
                    $order->invoice = $request->invoice_no;
                
                    // Calculate profit
                    if ($request->has('other_expense')) {
                        $profit = $request->agent_price - ($request->supplier_price + $request->other_expense);
                    } else {
                        $profit = $request->agent_price - $request->supplier_price;
                    }
                    $order->profit = $profit;
                    $order->user = Auth::id();
              
                    
                    // dd("sa", $order);
    
                    // Update agent's amount
                    $agent = Agent::find($request->agent);
        
                    $agent_prev_amount = $agent->amount;
                    $agent_new_amount = floatval($agent->amount) + floatval($request->agent_price);
        
                    $order->agent_previous_amount = $agent_prev_amount;
                    $order->agent_new_amount = $agent_new_amount;
        
                    $agent->amount += $request->agent_price;
                    $agent->save();
                    
                    // Update supplier's amount
                    $supplier = Supplier::find($request->supplier);
    
                    $supplier_prev_amount = $supplier->amount;
                    $supplier_new_amount = floatval($supplier->amount) + floatval($request->supplier_price);
    
                    $order->supplier_previous_amount = $supplier_prev_amount;
                    $order->supplier_new_amount = $supplier_new_amount;
    
                    $supplier->amount += $request->supplier_price;
                    $supplier->save();
                    // dd($order->save(), $request->all());
                    $isdone = $order->save();
                }
    
            
                if ($isdone) {
                    DB::commit(); // Commit the transaction if saving is successful
                    return redirect()->route('order.view')->with('success', 'Order added successfully');
                }
            } catch (\Throwable $e) {
                DB::rollBack(); // Roll back the transaction if an exception occurs
                return redirect()->route('order.view')->with('error', $e->getMessage());
            }
            
        }
        else{
            return view('welcome');
        }

        // $validatedData['user'] = Auth::id();
        // Order::create($validatedData);
    }
    public function getlastid(){
      if(Auth::user()){
        try {
            $lastId = Order::latest('id')->value('id');
            $newInvoice = 0;

            if ($lastId) {
                $order = Order::find($lastId);
                if ($order) {
                    $invoice = $order->invoice;
                    $parts = explode("-", $invoice);
                    $partAfterHyphen = end($parts); // Extract part after hyphen
                    $newPartAfterHyphen = floatval($partAfterHyphen) + 1; // Increment invoice number
                    $newInvoice = $parts[0] . "-" . str_pad($newPartAfterHyphen, strlen($partAfterHyphen), '0', STR_PAD_LEFT); // Concatenate back to original format
                    
                } else {
                   
                }
            }
            else{
                $lastId = 0;
                $newInvoice = "VS-00001";
            }
       
            return response()->json(['lastId' => $lastId, 'invoice' => $newInvoice]);

        } catch (\Exception $e) {
            // Handle any exceptions that might occur during the process
            return response()->json(['error' => 'Error fetching last ID'], 500);
        }     
      }
      else{
        return view('welcome');
      }
    }
    public function edit($id)
    {
        if(Auth::user()){
            $id = decrypt($id);
            $user = Auth::id();
            $suppliers = Supplier::where([['is_delete',0],['is_active',1],['user',$user]])->get();
            $agents = Agent::where([['is_delete',0],['is_active',1],['user',$user]])->get();
            $types = Type::where([['is_delete',0],['is_active',1],['user',$user]])->get();
            $order = Order::findOrFail($id);
            return view('order.edit', compact('order','suppliers','types', 'agents'));
        }
        else{
            return view('welcome');
        }
        
    }
        public function update(Request $request, $id)
        {
            // dd($request->all(), $id);
            if(Auth::user()){
                $validatedData = $request->validate([
                    'date' => 'required|date',
                    'type' => 'required|integer|exists:type,id',
                    'agent' => 'required|integer|exists:agent,id',
                    'seller' => 'nullable|string|max:255',
                    'phone' => 'nullable|string|max:15',
                    'name' => 'required|string|max:255',
                    'passport_no' => 'required|string|max:255',
                    'contact_amount' => 'required|numeric',
                    'payable_amount' => 'required|numeric',
                    'other_expense' => 'nullable|numeric',
                    'country' => 'required|string',
                    'supplier' => 'required|exists:supplier,id',
                    'remark' => 'nullable|string',
                    'invoice' => 'required|string|max:255',
                ]);
    
                if($validatedData){
                    $order = Order::findOrFail($id);
                    $order->name = $validatedData['name'];
                    $order->date = (new DateTime($validatedData['date']))->format('Y-m-d');
                    $order->type = $validatedData['type'];
                    $order->agent = $validatedData['agent'];
                    // $order->seller = $validatedData['seller'];
                    // $order->phone = $validatedData['phone'];
                    $order->passport_no = $validatedData['passport_no'];
                    $order->contact_amount = $validatedData['contact_amount'];
                    $order->payable_amount = $validatedData['payable_amount'];
                    // $order->other_expense = $validatedData['other_expense'];
                    $order->country = $validatedData['country'];
                    $order->supplier = $validatedData['supplier'];
                    $order->remark = $validatedData['remark'];
                    $order->invoice = $validatedData['invoice'];
        
                    if (isset($validatedData['other_expense'])) {
                        $profit = $validatedData['contact_amount'] - ($validatedData['payable_amount'] + $validatedData['other_expense']);
                    } else {
                        $profit = $validatedData['contact_amount'] - $validatedData['payable_amount'];
                    }
                    
                    // Now $profit contains the calculated profit based on the conditions
                    $order->profit = $profit;
                    $order->user = Auth::id();
                    
    
                    if($order->save()){
                        return redirect()->route('order.view')->with('success', 'Order updated successfully');
                    }
                    else{
                        return redirect()->route('order.view')->with('error', 'Order updated failed');
                    }
                }         
    
                return redirect()->route('order.view')->with('error', 'Order updated failed');
            }
            else{
                return view('welcome');
            }
           
        }
        public function view($id){
            if(Auth::user()){
                $order = Order::findOrFail($id); 
                $agent = Agent::where('id', $order->agent)->first();
                $type = Type::where('id', $order->type)->value('name');
                return view('order.view', compact('order', 'agent','type'));
            }
            else{
                return view('welcome');
            }
          
        }
        public function delete($id)
        {
            if(Auth::user()){
                DB::beginTransaction();
            
                try {
                    $order = Order::findOrFail($id);
                    $order->is_delete = 1;
            
                    $agent = Agent::findOrFail($order->agent);
                    $agent->amount -= $order->contact_amount;
            
                    $supplier = Supplier::findOrFail($order->supplier);
                    $supplier->amount -= $order->payable_amount;
            
                    $flag = $agent->save() && $supplier->save();
            
                    if ($flag) {
                        $order->save();
                        DB::commit(); // Commit the transaction if everything is successful
                        return redirect()->route('order.view')->with('success', 'Order deleted successfully');
                    } else {
                        DB::rollBack(); // Rollback the transaction if any operation fails
                        return redirect()->route('order.view')->with('error', 'Order deletion failed');
                    }
                } catch (\Exception $e) {
                    DB::rollBack(); // Rollback the transaction in case of any exception
                    return redirect()->route('order.view')->with('error', 'Order deletion failed');
                }
            }
            else{
                return view('welcome');
            }
            
        }
}

?>