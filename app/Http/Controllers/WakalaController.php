<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Wakala;
use App\Models\ReturnedWakala;
use App\Models\Agent;
use App\Models\Supplier;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB; // Add this line
use Illuminate\Support\Facades\Validator;

class WakalaController extends Controller
{
    // In your WakalaController
    public function datatable()
    {
        return DataTables::of(Wakala::query()
                ->with(['agentRelation', 'supplierRelation'])
                ->where('user', auth()->id())
                ->select(['wakalas.*']))
            ->addColumn('agent_name', function($wakala) {
                return $wakala->agentRelation->name ?? 'N/A';
            })
            ->addColumn('supplier_name', function($wakala) {
                return $wakala->supplierRelation->name ?? 'N/A';
            })
            ->addColumn('action', function($wakala) {
                return '';
            })
            ->rawColumns(['status', 'action'])
            ->make(true);
    }
    public function index()
    {
       
        $wakalas = Wakala::where('user', auth()->id())->latest()->paginate(10); // Returns LengthAwarePaginator
        // Get agents as ID => Name pairs (more efficient for dropdowns)
        $agents = Agent::where('user', Auth::id())
            ->where('is_delete', 0)
            ->where('is_active', 1)
            ->get();

        // Get suppliers as ID => Name pairs
        $suppliers = Supplier::where('user', Auth::id())
            ->where('is_delete', 0)
            ->where('is_active', 1)
            ->get();

        $latestWakala = Wakala::where('user', Auth::id())->latest()->first();
        $nextInvoiceNumber = $this->generateInvoiceNumber();
        
        if ($latestWakala) {
            $latestId = $latestWakala->id;
            $nextId = $latestId + 1;
            $nextInvoiceNumber = 'WAKL-' . str_pad($nextId, 4, '0', STR_PAD_LEFT);
        }
       
        return view('wakalas.index', compact('wakalas', 'agents', 'suppliers', 'nextInvoiceNumber'));
    }

    protected function generateInvoiceNumber()
    {
        $latest = Wakala::where('user', auth()->id())->latest()->first();
        return 'WAKL-' . str_pad(($latest->id ?? 0) + 1, 4, '0', STR_PAD_LEFT);
    }

    public function create()
    {
        return view('wakalas.create');
    }

    // public function store(Request $request)
    // {
    //     // dd($request->all());
    //     // Manual validation
    //     $errors = [];
        
    //     // Required fields validation
    //     $requiredFields = [
    //         'invoice', 'date', 'visa', 'id_no', 'agent', 
    //         'supplier', 'quantity', 'buying_price', 
    //         'multi_currency', 'total_price', 'selling_price'
    //     ];
        
    //     foreach ($requiredFields as $field) {
    //         if (empty($request->$field)) {
    //             $errors[$field] = "The {$field} field is required.";
    //         }
    //     }

    //     // Validate supplier format
    //     if (!str_contains($request->supplier, '_')) {
    //         $errors['supplier'] = "Invalid supplier format";
    //     } else {
    //         $parts = explode('_', $request->supplier);
    //         if (count($parts) !== 2) {
    //             $errors['supplier'] = "Invalid supplier format";
    //         }
    //     }

    //     // Numeric validation
    //     if (!is_numeric($request->quantity) || $request->quantity < 1) {
    //         $errors['quantity'] = "Quantity must be a number greater than 0";
    //     }

    //     if (!is_numeric($request->buying_price) || $request->buying_price <= 0) {
    //         $errors['buying_price'] = "Buying price must be a positive number";
    //     }

    //     if (!is_numeric($request->total_price) || $request->total_price <= 0) {
    //         $errors['total_price'] = "Total price must be a positive number";
    //     }

    //     if (!empty($errors)) {
    //         return response()->json([
    //             'success' => false,
    //             'errors' => $errors
    //         ], 422); // 422 is Unprocessable Entity
    //     }

    //     // Proceed with creating the record
    //     try {
            
    //         $wakala = new Wakala();
        
    //         // Set the attributes individually
    //         $wakala->invoice = $request->invoice;
    //         $wakala->date = $request->date;
    //         $wakala->visa = $request->visa;
    //         $wakala->id_no = $request->id_no;
    //         $wakala->agent = $request->agent;
    //         $wakala->agent_supplier = $parts[0];
    //         $wakala->supplier = $parts[1];
    //         $wakala->quantity = $request->quantity;
    //         $wakala->buying_price = $request->buying_price;
    //         $wakala->multi_currency = $request->multi_currency;
    //         $wakala->total_price = $request->total_price;
    //         $wakala->selling_price = $request->selling_price;
    //         $wakala->country = $request->country;
    //         $wakala->sales_by = $request->sales_by;
    //         $wakala->user = Auth::id();
    //         $wakala->created_at = now();
    //         $wakala->updated_at = now();
    //         $wakala->note = $request->notes;

    //         // Validate return fields if any return data is provided
    //         if ($request->filled('return_quantity') || $request->filled('return_date') || 
    //             $request->filled('return_reason') || $request->filled('return_status')) {
                
    //             // Only update return fields if quantity is provided and valid
    //             if ($request->return_quantity > 0) {
    //                 $wakala->is_returned = 1;
    //                 $wakala->return_quantity = $request->return_quantity;
    //                 $wakala->return_date = $request->return_date;  // Fixed: was incorrectly using return_quantity
    //                 $wakala->return_reason = $request->return_reason;
    //                 $wakala->return_status = $request->return_status;
    //             } else {
    //                 // If return quantity is 0 or negative, clear return fields
    //                 $wakala->return_quantity = null;
    //                 $wakala->return_date = null;
    //                 $wakala->return_reason = null;
    //                 $wakala->return_status = null;
    //             }
    //         }
    //     try {
    //         // Save the main wakala record
    //         $wakala->save();

    //         // Check if there are returned items
    //         if ($wakala->is_returned == 1 && $request->return_quantity > 0) {
    //             // dd('ko');
    //             if ($request->return_quantity > $wakala->quantity) {
    //                 throw new \Exception('Return quantity cannot exceed original quantity');
    //             }

    //             // Calculate single price safely
    //             $single_price = $wakala->quantity > 0 
    //                 ? $wakala->selling_price / $wakala->quantity
    //                 : 0;

    //             // Create returned wakala record
    //             $returnedwakala = new ReturnedWakala();
    //             $returnedwakala->wakala_id = $wakala->id();
    //             $returnedwakala->quantity = $request->return_quantity;
    //             $single_price =  parseFloat($request->selling_price/$request->quantity);
    //             $returnedwakala->single_price = $single_price;
    //             $returnedwakala->price = $single_price *  $request->return_quantity;
    //             $returnedwakala->user = Auth::id();
    //             $returnedwakala->updated_at = now();
    //             $returnedwakala->created_at = now();
               
    //             $returnedWakala->save();

    //         }

    //         return redirect()->back()->with('success', 'Operation completed successfully');

    //         } catch (\Exception $e) {
    //             return redirect()->back()
    //                 ->withInput()
    //                 ->with('error', 'Error processing request: ' . $e->getMessage());
    //         }
    //         return response()->json([
    //             'success' => true,
    //             'message' => 'Wakala record created successfully!',
    //             'invoice' => $request->invoice
    //         ]);

    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'success' => false,
    //             'error' => 'Failed to create record: '.$e->getMessage()
    //         ], 500); // 500 is Internal Server Error
    //     }
    // }

    public function store(Request $request)
{
    // dd($request->all());
    // Validation
    $errors = [];
    
    $requiredFields = [
        'invoice', 'date', 'visa', 'id_no', 'agent', 
        'supplier', 'quantity', 'buying_price', 
        'multi_currency', 'total_price', 'selling_price'
    ];
    
    foreach ($requiredFields as $field) {
        if (empty($request->$field)) {
            $errors[$field] = "The {$field} field is required.";
        }
    }

    // Validate supplier format
    if (!str_contains($request->supplier, '_') || count(explode('_', $request->supplier)) !== 2) {
        $errors['supplier'] = "Supplier must be in format 'agent_supplier'";
    }

    // Numeric validation
    if (!is_numeric($request->quantity) || $request->quantity < 1) {
        $errors['quantity'] = "Quantity must be a number greater than 0";
    }

    if (!is_numeric($request->buying_price) || $request->buying_price <= 0) {
        $errors['buying_price'] = "Buying price must be a positive number";
    }

    if (!is_numeric($request->total_price) || $request->total_price <= 0) {
        $errors['total_price'] = "Total price must be a positive number";
    }

    if (!empty($errors)) {
        return response()->json([
            'success' => false,
            'errors' => $errors
        ], 422);
    }

    try {
        DB::beginTransaction();

        // Create main Wakala record
        $parts = explode('_', $request->supplier);
        $wakala = new Wakala();
        $wakala->fill([
            'invoice' => $request->invoice,
            'date' => $request->date,
            'visa' => $request->visa,
            'id_no' => $request->id_no,
            'agent' => $request->agent,
            'agent_supplier' => $parts[0],
            'supplier' => $parts[1],
            'quantity' => $request->quantity,
            'buying_price' => $request->buying_price,
            'multi_currency' => $request->multi_currency,
            'total_price' => $request->total_price,
            'selling_price' => $request->selling_price,
            'country' => $request->country,
            'sales_by' => $request->sales_by,
            'user' => Auth::id(),
            'note' => $request->notes,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Handle returns
        if ($request->filled('return_quantity') && $request->return_quantity > 0) {
            $wakala->is_returned = 1;
            $wakala->return_quantity = $request->return_quantity;
            $wakala->return_date = $request->return_date;
            $wakala->return_reason = $request->return_reason;
            $wakala->return_status = $request->return_status;
        }

        $wakala->save();

        // Handle returned items
        if ($wakala->is_returned && $request->return_quantity > 0) {
            if ($request->return_quantity > $wakala->quantity) {
                throw new \Exception('Return quantity cannot exceed original quantity');
            }

            $single_price = $wakala->quantity > 0 
                ? $wakala->selling_price / $wakala->quantity
                : 0;

            ReturnedWakala::create([
                'wakala_id' => $wakala->id,
                'quantity' => $request->return_quantity,
                'single_price' => $single_price,
                'price' => $single_price * $request->return_quantity,
                'user' => Auth::id(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        DB::commit();

        return response()->json([
            'success' => true,
            'message' => 'Wakala record created successfully!',
            'invoice' => $wakala->invoice
        ]);

    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json([
            'success' => false,
            'error' => 'Failed to create record: '.$e->getMessage()
        ], 500);
    }
}
    public function show(Wakala $wakala)
    {
        // dd($wakala->agent);
        $wakala->agentname = Agent::where('id',$wakala->agent)->value('name');
        if($wakala->agent_supplier == 'supplier'){
            $wakala->suppliername = Supplier::where('id',$wakala->supplier)->value('name');
        }
        else{
            $wakala->suppliername = Agent::where('id',$wakala->supplier)->value('name');
        }
        return response()->json($wakala);
    }

    public function edit(Wakala $wakala)
    {
        // dd($wakala);
        // Verify ownership
        if ($wakala->user != auth()->id()) {
            abort(403);
        }
    
        $agents = Agent::where('user', auth()->id())
        ->where('is_delete', 0)  // Fixed typo here
        ->where('is_active', 1)
        ->pluck('name', 'id');

        $suppliers = Supplier::where('user', auth()->id())
        ->where('is_delete', 0)  // Fixed typo here
        ->where('is_active', 1)
        ->pluck('name', 'id');
        
        return view('wakalas.edit', [
            'wakala' => $wakala,
            'agents' => $agents,
            'suppliers' => $suppliers
        ]);
    }

    public function sell(Wakala $wakala)
    {
        // dd($wakala);
        // Verify ownership
        if ($wakala->user != auth()->id()) {
            abort(403);
        }
    
        $agents = Agent::where('user', auth()->id())
        ->where('is_delete', 0)  // Fixed typo here
        ->where('is_active', 1)
        ->pluck('name', 'id');

        $suppliers = Supplier::where('user', auth()->id())
        ->where('is_delete', 0)  // Fixed typo here
        ->where('is_active', 1)
        ->pluck('name', 'id');
        
        return view('wakalas.sell', [
            'wakala' => $wakala,
            'agents' => $agents,
            'suppliers' => $suppliers
        ]);
    }

    public function sell_wakala(Wakala $wakala, Request $request)
    {
       
        // dd($request->all(), $wakala->agent);
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // Authorization check
        if ($wakala->user != auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        // Validate request
        $validated = $request->validate([
            'quantity' => [
                'required',
                'numeric',
                'min:1',
                'max:'.$wakala->return_quantity
            ],
            'selling_price' => [
                'required',
                'numeric',
                'min:0'
            ],
            'notes' => [
                'nullable',
                'string',
                'max:500'
            ]
        ]);

        try {
            DB::beginTransaction();

            $price = str_replace(',', '', $request->single_selling_price);
            $singlePrice = floatval($price); 

            $totalPrice = $singlePrice * $validated['quantity'];


            $wakala->update([
                'selling_price' => $wakala->selling_price + $totalPrice,
                'agent' => implode(',', array_unique(array_filter(array_merge(
                    explode(',', $wakala->agent),
                    [$request->agent]
                )))),
                'return_quantity' => $wakala->return_quantity - $validated['quantity'],
                'is_returned' => ($wakala->return_quantity - $validated['quantity']) > 0 ? 1 : 0,
                'note' => $validated['notes'] ?? $wakala->note,
                'updated_at' => now()
            ]);

            // Create sale record
            $sale = ReturnedWakala::create([
                'wakala_id' => $wakala->id,
                'quantity' => $validated['quantity'],
                'selling_price' => $singlePrice,
                'total_amount' => $totalPrice,
                'user' => auth()->id(),
                'sold_at' => now()
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Wakala successfully resold!',
                'data' => [
                    'wakala' => $wakala,
                    'sale' => $sale
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to resell wakala: '.$e->getMessage()
            ], 500);
        }
    }
    // public function update(Request $request, Wakala $wakala)
    // {
    //     // dd($request->all());
    //     // Manual validation
    //     $errors = [];

    //     // Required fields validation
    //     $requiredFields = [
    //         'invoice', 'date', 'visa', 'id_no', 'agent', 
    //         'supplier', 'quantity', 'buying_price', 
    //         'multi_currency', 'total_price', 'selling_price'
    //     ];
        
    //     foreach ($requiredFields as $field) {
    //         if (empty($request->$field)) {
    //             $errors[$field] = "The {$field} field is required.";
    //         }
    //     }

    //     // Validate supplier format
    //     if (!str_contains($request->supplier, '_')) {
    //         $errors['supplier'] = "Invalid supplier format";
    //     } else {
    //         $parts = explode('_', $request->supplier);
    //         if (count($parts) !== 2) {
    //             $errors['supplier'] = "Invalid supplier format";
    //         }
    //     }

    //     // Numeric validation
    //     if (!is_numeric($request->quantity) || $request->quantity < 1) {
    //         $errors['quantity'] = "Quantity must be a number greater than 0";
    //     }

    //     if (!is_numeric($request->buying_price) || $request->buying_price <= 0) {
    //         $errors['buying_price'] = "Buying price must be a positive number";
    //     }

    //     if (!is_numeric($request->total_price) || $request->total_price <= 0) {
    //         $errors['total_price'] = "Total price must be a positive number";
    //     }

    //     if (!empty($errors)) {
    //         return response()->json([
    //             'success' => false,
    //             'errors' => $errors
    //         ], 422); // 422 Unprocessable Entity
    //     }

    //     // Proceed with updating the record
    //     try {
    //         // Update attributes individually
    //         $wakala->invoice = $request->invoice;
    //         $wakala->date = $request->date;
    //         $wakala->visa = $request->visa;
    //         $wakala->id_no = $request->id_no;
    //         $wakala->agent = $request->agent;
    //         $wakala->agent_supplier = $parts[0];
    //         $wakala->supplier = $parts[1];
    //         $wakala->quantity = $request->quantity;
    //         $wakala->buying_price = $request->buying_price;
    //         $wakala->multi_currency = $request->multi_currency;
    //         $wakala->total_price = $request->total_price;
    //         $wakala->selling_price = $request->selling_price;
    //         $wakala->country = $request->country;
    //         $wakala->sales_by = $request->sales_by;
    //         $wakala->updated_at = now();
    //         $wakala->note = $request->notes;

    //         if ($request->filled('return_quantity') || $request->filled('return_date') || 
    //             $request->filled('return_reason') || $request->filled('return_status')) {
                
    //             // Only update return fields if quantity is provided and valid
    //             if ($request->return_quantity > 0) {
    //                 $wakala->is_returned = 1;
    //                 $wakala->return_quantity = $request->return_quantity;
    //                 $wakala->return_date = $request->return_date;  // Fixed: was incorrectly using return_quantity
    //                 $wakala->return_reason = $request->return_reason;
    //                 $wakala->return_status = $request->return_status;
    //             } else {
    //                 // If return quantity is 0 or negative, clear return fields
    //                 $wakala->return_quantity = null;
    //                 $wakala->return_date = null;
    //                 $wakala->return_reason = null;
    //                 $wakala->return_status = null;
    //             }
    //         }
    //         $wakala->save();

    //         return response()->json([
    //             'success' => true,
    //             'message' => 'Wakala record updated successfully!',
    //             'invoice' => $request->invoice
    //         ]);

    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'success' => false,
    //             'error' => 'Failed to update record: '.$e->getMessage()
    //         ], 500); // 500 Internal Server Error
    //     }
    // }

    public function update(Request $request, Wakala $wakala)
    {
        // Validate request
        $validator = Validator::make($request->all(), [
            'invoice' => 'required',
            'date' => 'required',
            'visa' => 'required',
            'id_no' => 'required',
            'agent' => 'required',
            'supplier' => ['required', 'regex:/^[^_]+\_[^_]+$/'], // Ensures exactly one underscore
            'quantity' => 'required|numeric|min:1',
            'buying_price' => 'required|numeric|min:0',
            'multi_currency' => 'required',
            'total_price' => 'required|numeric|min:0',
            'selling_price' => 'required',
            'return_quantity' => 'nullable|numeric|min:0|max:'.$wakala->quantity,
            'return_date' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            // Split supplier
            $supplierParts = explode('_', $request->supplier);

            // Update main wakala record
            $wakala->update([
                'invoice' => $request->invoice,
                'date' => $request->date,
                'visa' => $request->visa,
                'id_no' => $request->id_no,
                'agent' => $request->agent,
                'agent_supplier' => $supplierParts[0],
                'supplier' => $supplierParts[1],
                'quantity' => $request->quantity,
                'buying_price' => $request->buying_price,
                'multi_currency' => $request->multi_currency,
                'total_price' => $request->total_price,
                'selling_price' => $request->selling_price,
                'country' => $request->country,
                'sales_by' => $request->sales_by,
                'note' => $request->notes,
                'updated_at' => now(),
                'is_returned' => $request->return_quantity > 0 ? 1 : 0,
                'return_quantity' => $request->return_quantity > 0 ? $request->return_quantity : null,
                'return_date' => $request->return_quantity > 0 ? $request->return_date : null,
                'return_reason' => $request->return_quantity > 0 ? $request->return_reason : null,
                'return_status' => $request->return_quantity > 0 ? $request->return_status : null,
            ]);

            // Handle returned items if applicable
            if ($request->return_quantity > 0) {
                
                $singlePrice = $wakala->quantity > 0 
                    ? $wakala->selling_price / $wakala->quantity
                    : 0;
                // dd($singlePrice, $singlePrice * $request->return_quantity);
                // Update or create returned record
                ReturnedWakala::updateOrCreate(
                    ['wakala_id' => $wakala->id],
                    [
                        'quantity' => $request->return_quantity,
                        'single_price' => $singlePrice,
                        'price' => $singlePrice * $request->return_quantity,
                        'user' => Auth::id(),
                        'updated_at' => now(),
                    ]
                );
                $wakala->update([
                    'selling_price' => $wakala->selling_price - ($singlePrice * $request->return_quantity)
                ]);
            } else {
                // Delete any existing returned record if return quantity is 0
                ReturnedWakala::where('wakala_id', $wakala->id)->delete();
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Wakala record updated successfully!',
                'invoice' => $wakala->invoice
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'error' => 'Failed to update record: '.$e->getMessage()
            ], 500);
        }
    }

    public function destroy(Wakala $wakala)
    {
        $wakala->delete();
        return redirect()->route('order.view')->with('success', 'Wakala record deleted successfully.');
    }
}