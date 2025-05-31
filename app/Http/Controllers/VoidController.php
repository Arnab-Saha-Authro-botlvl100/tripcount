<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Add this line
use App\Models\Supplier;
use App\Models\Agent;
use App\Models\Type;
use App\Models\Ticket;
use App\Models\Order;
use App\Models\VoidTicket;
use Illuminate\View\View;
use DateTime;
use Illuminate\Support\Facades\DB;
use SebastianBergmann\Type\VoidType;

class VoidController extends Controller
{
    public function view(Request $request)
    {
        if(Auth::user()){
            $user = Auth::id();
            $suppliers = Supplier::where([['is_delete',0],['is_active',1],['user',$user]])->get();
            $agents = Agent::where([['is_delete',0],['is_active',1],['user',$user]])->get();
            
            $query = VoidTicket::where([['user',$user]]);
    
            // Add search functionality
            if ($request->has('search')) {
                $searchTerm = $request->input('search');
                $query->where(function ($query) use ($searchTerm) {
                    $query->where('ticket_no', 'like', '%' . $searchTerm . '%')
                          ->orWhere('date', 'like', '%' . $searchTerm . '%');
                });
            }
    
            $void_tickets = $query->paginate(10);
            foreach($void_tickets as $order){
               
                $order->agent = Agent::where('id', $order->agent)->value('name');
                $order->supplier = Supplier::where('id', $order->supplier)->value('name');
            }
            return view('ticket.void', compact('void_tickets'));
        }
        else{
            return view('welcome');
        }
       
    }  

    public function void_entry(Request $request)
    {
        
        if(Auth::user()){
            try {
                DB::beginTransaction();
                // dd($request->all());
                $ticket = Ticket::where('id', $request->ticket)->first();
                $agent = Agent::find($ticket->agent);
                $supplier = Supplier::find($ticket->supplier);

                $agent->amount -= $ticket->agent_price;
                $agent->amount += $request->agent_refundfare;
                $agent->save();

                $supplier->amount -= $ticket->supplier_price;
                $supplier->amount += $request->supplier_refundfare;
                $supplier->save();
                // dd($request->all(),$ticket);
                $ticket->is_active = 0;
                $ticket->agent_price = $request->agent_refundfare;
                $ticket->supplier_price = $request->supplier_refundfare;
                $ticket->save();

                $flag = $this->voidTicket($request);
                // dd($flag);
                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                return redirect()->back()->with('error', 'Error voiding ticket: ' . $e->getMessage());
            }
    
            $message = $flag ? 'Void Ticket added successfully' : 'Adding Void Ticket failed';
            $type = $flag ? 'success' : 'error';
    
            return redirect()->route('void.view')->with($type, $message);
        }
        else{
            return view('welcome');
        }
       
    }

    private function voidTicket(Request $request)
    {
        if(Auth::user()){
            $ticket = Ticket::where('id', $request->ticket)->first();

            if (!$ticket) {
                // dd('Ticket not found');
                return false; // Ticket not found
            }

            $voidticket = new VoidTicket();
            $voidticket->ticket_no = $ticket->ticket_no;
            $voidticket->date = now();
            $voidticket->ticket_code = $request->ticket_code;
            $voidticket->passenger_name = $request->name;
            $voidticket->agent = $request->agent;
            $voidticket->supplier = $request->supplier;
            $voidticket->prev_agent_amount = $request->agent_fare;
            $voidticket->prev_supply_amount = $request->supplier_fare;
            $voidticket->now_agent_fare = $request->agent_refundfare; // fixed typo
            $voidticket->now_supplier_fare = $request->supplier_refundfare;
            $voidticket->user = Auth::id();

            $agentRefundFare = $request->input('agent_refundfare');
            $supplierRefundFare = $request->input('supplier_refundfare');
            $profit = $agentRefundFare - $supplierRefundFare;

            $voidticket->void_profit = $profit;

            $agent = Agent::where('id', $request->agent)->first();
            $agent_prev_tk = $agent->amount;
            $agent_now_tk = ($agent_prev_tk - $request->agent_fare) + $request->agent_refundfare;

            $supplier = Supplier::where('id', $request->supplier)->first();
            $supplier_prev_tk = $supplier->amount;
            $supplier_now_tk = $request->supplier_refundfare + ($supplier_prev_tk - $request->supplier_fare);

            $voidticket->prev_agent_tk = $agent_prev_tk;
            $voidticket->prev_supplier_tk = $supplier_prev_tk;
            $voidticket->now_agent_amount = $agent_now_tk;
            $voidticket->now_supplier_amount = $supplier_now_tk;

            
            $ticketParams = [
                'voidTicket' => $voidticket,
                'ticket' => $ticket,
                'agentFare' => $request->agent_fare,
                'supplierFare' => $request->supplier_fare,
                'agentId' => $request->agent,
                'supplierId' => $request->supplier,
                'profit' => $profit,
                'agentRefundFare' => $request->agent_refundfare,
                'supplierRefundFare' => $request->supplier_refundfare,
            ];
            
            // ✅ ADD THIS:
            // dd([
            //     'about to call updateTicket with',
            //     'ticketParams' => [
            //         'voidTicket' => $voidticket->toArray(),
            //         'ticket' => $ticket->toArray(),
            //         'agentFare' => $request->agent_fare,
            //         'supplierFare' => $request->supplier_fare,
            //         'agentId' => $request->agent,
            //         'supplierId' => $request->supplier,
            //         'profit' => $profit,
            //         'agentRefundFare' => $request->agent_refundfare,
            //         'supplierRefundFare' => $request->supplier_refundfare,
            //     ],
            //     $ticketParams
            // ]);

            $flag = $this->updateTicket($ticketParams);

            return $flag;
        } else {
            return view('welcome');
        }
    }


    private function updateTicket(array $params)
    {
        if(Auth::user()){
            $voidticket = $params['voidTicket'];
            $ticket = $params['ticket'];
            $agentFare = $params['agentFare'];
            $supplierFare = $params['supplierFare'];
            $agent = $params['agentId'];
            $supplier = $params['supplierId'];
            $profit = $params['profit'];
            $agentRefundFare = $params['agentRefundFare'];
            $supplierRefundFare = $params['supplierRefundFare'];

            $ticket->is_void = 1;
            $ticket->void_profit = $profit;
    
            $agent = Agent::where('id', $agent)->first();
            $agent->amount -= $agentFare;
            $agent->amount += $agentRefundFare;
            
            $supplier = Supplier::where('id', $supplier)->first();
            $supplier->amount -= $supplierFare;
            $supplier->amount += $supplierRefundFare;

            // dd([
            //     'ticket' => $ticket->toArray(),
            //     'agent' => $agent->toArray(),
            //     'supplier' => $supplier->toArray(),
            //     'voidticket' => $voidticket->toArray(),
            // ]);
    
            return $ticket->save() && $agent->save() && $supplier->save() &&  $voidticket->save();
        }
        else{
            return view('welcome');
        }
       
    }

}