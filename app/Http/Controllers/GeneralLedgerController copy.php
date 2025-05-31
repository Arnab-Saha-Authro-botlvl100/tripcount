<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\Supplier;
use App\Models\Agent;
use App\Models\AIT;
use App\Models\Type;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Receiver;
use App\Models\Refund;
use App\Models\ReissueTicket;
use App\Models\Ticket;
use App\Models\Transaction;
use App\Models\VoidTicket;
use App\Models\Contract;

use Illuminate\Support\Facades\Auth; // Add this line
use DateTime;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\View as ViewFacade;

class GeneralLedgerController extends Controller
{
    //

    public function general_ledger()
    {
        if(Auth::user()){
            $user = Auth::id();
            $suppliers = Supplier::where([['is_delete', 0], ['is_active', 1], ['user', $user]])->get();
            $agents = Agent::where([['is_delete', 0], ['is_active', 1], ['user', $user]])->get();
            return view('report.general_ledger.index', compact('agents', 'suppliers'));
            
        }
        else{
            return view('welcome');
        }
    }


    public function separateTransactions($sortedTransactions, $startDate, $opening_balance, $endDate = null, $agentSupplier, $agentSupplierId)
    {
        // dd('asda');
        $transactions_before_start = $sortedTransactions->filter(function($transaction) use ($startDate) {
            $transactionDate = $transaction->date ?? $transaction->created_at; // Use `date` or fallback to `created_at`
            return strtotime($transactionDate) < strtotime($startDate);
        });
        
        $transactions_from_start = $sortedTransactions->filter(function($transaction) use ($startDate) {
            $transactionDate = $transaction->date ?? $transaction->created_at; // Use `date` or fallback to `created_at`
            return strtotime($transactionDate) >= strtotime($startDate);
        });
        
        // If an end date is provided, further filter the transactions from start date until the end date
        if ($endDate) {
            $transactions_from_start = $transactions_from_start->filter(function($transaction) use ($endDate) {
                return strtotime($transaction->date) <= strtotime($endDate); // Filter transactions until end date
            });
        }
        $final_opening_balance = 0;
        $final_opening_balance += $opening_balance;
        // dd($final_opening_balance);

        // dd($transactions_before_start, $transactions_from_start);
        if($agentSupplier === 'agent'){

            // if (is_null($item->supplier) && $item->who === 'agent_' . $agentSupplierId) {
                // if (is_null($item->supplier) && $item->who === 'agent_' . $agentSupplierId) {

            foreach($transactions_before_start as $transaction) {
                // dd($transaction->table_name);
                if($transaction->table_name == 'receive'){
                    $final_opening_balance -= $transaction->amount;
                }
                // else if($transaction->table_name == 'tickets'){
                //     $final_opening_balance += $transaction->agent_price;
                // }
                else if($transaction->table_name == 'tickets'){
                    if(is_null($transaction->supplier) && $transaction->who === 'agent_'. $agentSupplierId){
                        $final_opening_balance -= $transaction->supplier_price;
                    }else{
                        $final_opening_balance += $transaction->agent_price;
                    }
                }
                // else if($transaction->table_name == 'order'){
                //     $final_opening_balance += $transaction->contact_amount;
                // }
                else if($transaction->table_name == 'order'){
                    if(is_null($transaction->supplier) && $transaction->who === 'agent_'. $agentSupplierId){
                        $final_opening_balance -= $transaction->payable_amount;
                    }
                    else{
                        $final_opening_balance += $transaction->contact_amount;
                    }
                    
                }
                else if($transaction->table_name == 'payment'){
                    $final_opening_balance += $transaction->amount;
                }
                else if($transaction->table_name == 'refund'){
                    $final_opening_balance -= $transaction->now_agent_fere;
                }
                else if($transaction->table_name == 'reissue'){
                    $final_opening_balance += $transaction->now_agent_fere;
                }
                else if($transaction->table_name == 'voidTicket'){
                    $final_opening_balance += $transaction->now_agent_fere;
                }
            }
        }
        else{
            foreach($transactions_before_start as $transaction) {
                // dd($transaction->table_name);
                if($transaction->table_name == 'receive'){
                    $final_opening_balance += $transaction->amount;
                }
                else if($transaction->table_name == 'tickets'){
                    $final_opening_balance += $transaction->supplier_price;
                }
                else if($transaction->table_name == 'order'){
                    $final_opening_balance += $transaction->payable_amount;
                }
                else if($transaction->table_name == 'payment'){
                    // dd($transaction, $final_opening_balance -= $transaction->amount);
                    $final_opening_balance -= $transaction->amount;
                }
                else if($transaction->table_name == 'refund'){
                    $final_opening_balance -= $transaction->now_supplier_fare;
                }
                else if($transaction->table_name == 'reissue'){
                    $final_opening_balance += $transaction->now_supplier_fare;
                }
                else if($transaction->table_name == 'voidTicket'){
                    $final_opening_balance += $transaction->now_supplier_fare;
                }
            }
        }
        // dd($final_opening_balance);
        return [
            'final_opening_balance' => $final_opening_balance,
            'from_start_date' => $transactions_from_start
        ];
        
      
       
    }


    public function general_ledger_report(Request $request){
        if(Auth::user()){
            // dd($request->all());
            $agentSupplier = $request->input('agent_supplier'); // "agent"
            $agentSupplierId = $request->input('agent_supplier_id'); // "82"
            $startDate = $request->input('start_date'); // "12/05/2024"
            $endDate = $request->input('end_date'); // "12/19/2024"
        
            if ($startDate) {
                $startDate = Carbon::createFromFormat('m/d/Y', $startDate)->format('Y-m-d');
            } else {
                $startDate = null; // Set to null if not provided
            }
        
            if ($endDate) {
                $endDate = Carbon::createFromFormat('m/d/Y', $endDate)->format('Y-m-d');
            } else {
                $endDate = null; // Set to null if not provided
            }
            
            if($agentSupplier === 'agent'){
               
                // Agent-related data retrieval
                $tickets = DB::table('tickets')
                ->where(function ($query) use ($agentSupplierId) {
                    $query->where('agent', $agentSupplierId)
                        ->orWhere('who', "agent_{$agentSupplierId}");
                })
                ->where('is_delete', 0)
                ->where('is_active', 1)
                ->where('user', Auth::id());

                // Orders related to the agent
                $orders = DB::table('order')
                ->where(function ($query) use ($agentSupplierId) {
                    $query->where('agent', $agentSupplierId)
                        ->orWhere('who', "agent_{$agentSupplierId}");
                })
                ->where('is_delete', 0)
                ->where('is_active', 1)
                ->where('user', Auth::id());

                // Receives
                $receive = DB::table('receive')
                ->where('receive_from', 'agent')
                ->where('agent_supplier_id', $agentSupplierId)
                ->where('user', Auth::id());

                // Payments
                $payment = DB::table('payment')
                ->where('receive_from', 'agent')
                ->where('agent_supplier_id', $agentSupplierId)
                ->where('user', Auth::id());

                // Refunds
                $refund = DB::table('refund')
                ->where('agent', $agentSupplierId)
                ->where('user', Auth::id());

                // Void Tickets
                $void_ticket = DB::table('voidTicket')
                ->where('user', Auth::id())
                ->where('agent', $agentSupplierId);

                // Reissues
                $reissue = DB::table('reissue')
                ->where('agent', $agentSupplierId)
                ->where('user', Auth::id());

                // wakala
                $wakala = DB::table('wakala')
                ->where('agent', $agentSupplierId)
                ->where('user', Auth::id());

                $wakala_agent_supplier = DB::table('wakala')
                ->where('agent_supplier', '=', 'agent')
                ->where('supplier', $agentSupplierId)
                ->where('user', Auth::id());
              
                $contracts = DB::table('contracts')
                ->where('agent', $agentSupplierId)
                ->where('user', Auth::id())
                ->get();

                // 2. Get services for these contracts
                $services = DB::table('contract_services')
                ->whereIn('contract_id', $contracts->pluck('id'))
                ->get()
                ->groupBy('contract_id');

                // 3. Get extra_types for these contracts
                $extraTypes = DB::table('extra_types')
                ->whereIn('contract_service_id', $contracts->pluck('id')) // Assuming extra_types has contract_id
                ->get()
                ->groupBy('contract_service_id');

                $servicesForAgentAsSupplier = DB::table('contract_services')
                    ->join('contracts', 'contract_services.contract_id', '=', 'contracts.id')
                    ->where('contract_services.agent_or_supplier', 'agent')
                    ->where('contract_services.supplier', $agentSupplierId)
                    ->select([
                        'contract_services.*',
                        'contracts.passport_no',
                        'contracts.name',
                        'contracts.country'
                    ]);

                $extraTypesForAgentAsSupplier = DB::table('extra_types')
                    ->join('contracts', 'extra_types.contract_service_id', '=', 'contracts.id')
                    ->where('agent_supplier', 'agent')
                    ->where('supplier', $agentSupplierId)
                    ->select(
                        'extra_types.*',
                        'contracts.passport_no', // Add any columns you need from contracts
                        'contracts.name',
                        'contracts.country'
                    );
               

                // 4. Build the structured response 
                $contract = $contracts->map(function ($contract) use ($services, $extraTypes) {
                    return (object) [ // Convert to object
                        'contract_id' => $contract->id,
                        'date' => $contract->contract_date,
                        'contract_data' => $contract,
                        'services' => $services->get($contract->id, collect())->values(),
                        'extra_types' => $extraTypes->get($contract->id, collect())->values(),
                        'table_name' => 'contract'
                    ];
                });
                
                // dd($contract);
                // Use get() to fetch the data, and then apply map()
                $tickets = $tickets->get()->map(function ($item) {
                $item->table_name = 'tickets';  // Add a table_name property
                return $item;
                });

                $orders = $orders->get()->map(function ($item) {
                $item->table_name = 'order';  // Add a table_name property
                return $item;
                });

                $receive = $receive->get()->map(function ($item) {
                $item->table_name = 'receive';  // Add a table_name property
                return $item;
                });

                $payment = $payment->get()->map(function ($item) {
                $item->table_name = 'payment';  // Add a table_name property
                return $item;
                });

                $refund = $refund->get()->map(function ($item) {
                $item->table_name = 'refund';  // Add a table_name property
                return $item;
                });

                $void_ticket = $void_ticket->get()->map(function ($item) {
                $item->table_name = 'voidTicket';  // Add a table_name property
                return $item;
                });

                $reissue = $reissue->get()->map(function ($item) {
                $item->table_name = 'reissue';  // Add a table_name property
                return $item;
                });

                $wakala = $wakala->get()->map(function ($item) {
                $item->table_name = 'wakala';  // Add a table_name property
                return $item;
                });

                $wakala_agent_supplier = $wakala_agent_supplier->get()->map(function ($item) {
                $item->table_name = 'wakala_agent_supplier';  // Add a table_name property
                return $item;
                });

                
                $servicesForAgentAsSupplier = $servicesForAgentAsSupplier->get()->map(function ($item) {
                $item->table_name = 'servicesForAgentAsSupplier';  // Add a table_name property
                return $item;
                });
                
                $extraTypesForAgentAsSupplier = $extraTypesForAgentAsSupplier->get()->map(function ($item) {
                $item->table_name = 'extraTypesForAgentAsSupplier';  // Add a table_name property
                return $item;
                });
    

                        
                // Merge all collections into a single collection
                $mergedCollection = $tickets->merge($orders)
                    ->merge($receive)
                    ->merge($payment)
                    ->merge($refund)
                    ->merge($void_ticket)
                    ->merge($reissue)
                    ->merge($wakala)
                    ->merge($wakala_agent_supplier)
                    ->merge($servicesForAgentAsSupplier)
                    ->merge($extraTypesForAgentAsSupplier)
                    ->merge($contract);

                // Normalize the `date` field, ensuring only the date part is kept
                $normalizedCollection = $mergedCollection->map(function ($item) {
                    try {
                        if (isset($item->date)) {
                            // Parse the date and normalize to `YYYY-MM-DD` format
                            $item->date = Carbon::parse($item->date)->toDateString();
                        } else {
                            $item->date = null; // Set null if no date is present
                        }
                    } catch (\Exception $e) {
                        $item->date = null; // Handle invalid dates
                    }
                    return $item;
                });

                // Sort the collection by `date` or `created_at`
                $sortedCollection = $normalizedCollection
                    ->sortBy(function ($item) {
                        if ($item->date) {
                            // Parse `date` (as a string) and return its timestamp
                            return Carbon::parse($item->date)->timestamp;
                        } elseif ($item->created_at) {
                            // Parse `created_at` as a fallback and return its timestamp
                            return Carbon::parse($item->created_at)->timestamp;
                        } else {
                            // Push items without `date` or `created_at` to the end
                            return PHP_INT_MAX;
                        }
                    })
                    ->values(); // Re-index the collection


                $final_opening_balance = Agent::where('id', $agentSupplierId)->value('opening_balance');
               
                if ($startDate) {
                    ['final_opening_balance' => $final_opening_balance, 'from_start_date' => $sortedCollection] = 
                        $this->separateTransactions($sortedCollection, $startDate, $final_opening_balance, $endDate, $agentSupplier, $agentSupplierId);
                   
                }
                

                $activeTransactionMethods = Transaction::where([['is_active', 1],['is_delete',0],['user', Auth::id()]])->pluck('name', 'id')->toArray();
                $activeTypes = Type::where([['is_active', 1],['is_delete',0],['user', Auth::id()]])->pluck('name', 'id')->toArray();
                // dd($activeTransactionMethods);
                $debit = 0;
                $balance = $final_opening_balance;
                $credit = 0;
                $total_ticket = 0;
                $html = '';
                // dd($sortedCollection, $agentSupplierId, Auth::id());
                foreach ($sortedCollection as $index => $item) {
                    // dd($item);
                    if ($item->table_name == "tickets") {
                        $total_ticket++;
                        if (is_null($item->supplier) && $item->who === 'agent_' . $agentSupplierId) {
                                  // Handle logic specific to Ticket model
                                  $balance -= $item->supplier_price;
                                  $currentAmount = $balance >= 0 ? $balance . ' DR' : $balance . ' CR';
                                  $credit += $item->supplier_price;
                                  $ticket = Ticket::where([['user', Auth::id()], ['ticket_no', $item->ticket_no]])->first();
                                  if($item->reissued_new_ticket == 1){
                                      $html .= <<<HTML
                                          <tr>
                                                                                                  <td class="w-[10%]">$item->invoice  <br><small><strong>$item->invoice_date</strong></small></td>
                                          <td class="w-[15%]"> {$ticket->ticket_code}-{$item->ticket_no} </td>

                                          <td class="w-[11%]"> $item->flight_date </td>
                                              <td class="w-[28%] pr-3">
                                                  PAX NAME: <span class=""> $item->passenger </span><br>
                                                  PNR:  $item->pnr ,  $item->sector <br>
                                                  $item->airline_code -  $item->airline_name <br>
                                                  Remarks:  $item->remark 
                                              </td>
                                              
                                              <td class="w-[12%] totaldebit"> </td>
                                              <td class="w-[12%] totalcredit"> $item->supplier_price</td>
                                              <td class="w-[12%] totaltotal"> $currentAmount </td>
                                          </tr>
                                      HTML;
                                  }
                                  else{
      
                                      
                                      $html .= <<<HTML
                                                                  <tr>
                                                                                                                          <td class="w-[10%]">$item->invoice  <br><small><strong>$item->invoice_date</strong></small></td>
                                                                  <td class="w-[15%]"> {$ticket->ticket_code}-{$item->ticket_no} </td>

                                                                  <td class="w-[11%]"> $item->flight_date </td>
                                                                      <td class="w-[28%] pr-3">
                                                                          PAX NAME: <span class=""> $item->passenger </span><br>
                                                                          PNR:  $item->pnr ,  $item->sector <br>
                                                                                                  $item->airline_code -  $item->airline_name <br>
                                                                          Remarks:  Reissue
                                                                      </td>
                                                                      
                                                                      <td class="w-[12%] totaldebit"></td>
                                                                      <td class="w-[12%] totalcredit">$item->supplier_price</td>
                                                                      <!-- <td class="w-[12%] text-center"> $item->previous_amount  Dr</td> -->
                                                                      <td class="w-[12%] totaltotal"> $currentAmount </td>
                                                                  </tr>
                                          HTML;
                                      }
                              
                        }
                        else{
                       
                            // Handle logic specific to Ticket model
                            $balance += $item->agent_price;
                            $currentAmount = $balance >= 0 ? $balance . ' DR' : $balance . ' CR';
                            $debit += $item->agent_price;
                            $ticket = Ticket::where([['user', Auth::id()], ['ticket_no', $item->ticket_no]])->first();
                            if($item->reissued_new_ticket == 1){
                                $html .= <<<HTML
                                    <tr>
                                        <td class="w-[10%]">$item->invoice  <br><small><strong>$item->invoice_date</strong></small></td>
                                    <td class="w-[15%]"> {$ticket->ticket_code}-{$item->ticket_no} </td>

                                    <td class="w-[11%]"> $item->flight_date </td>
                                        <td class="w-[28%] pr-3">
                                            PAX NAME: <span class=""> $item->passenger </span><br>
                                            PNR:  $item->pnr ,  $item->sector <br>
                                            $item->airline_code -  $item->airline_name <br>
                                            Remarks:  $item->remark 
                                        </td>
                                        
                                        <td class="w-[12%] totaldebit"> $item->agent_price </td>
                                        <td class="w-[12%] totalcredit"></td>
                                        <td class="w-[12%] totaltotal"> $currentAmount </td>
                                    </tr>
                                HTML;
                            }
                            else{
                                $html .= <<<HTML
                                                            <tr>
                                                                                                                        <td class="w-[10%]">$item->invoice  <br><small><strong>$item->invoice_date</strong></small></td>
                                                                <td class="w-[15%]"> {$ticket->ticket_code}-{$item->ticket_no} </td>

                                                                <td class="w-[11%]"> $item->flight_date </td>
                                                                <td class="w-[28%] pr-3">
                                                                    PAX NAME: <span class=""> $item->passenger </span><br>
                                                                    PNR:  $item->pnr ,  $item->sector <br>
                                                                                      $item->airline_code -  $item->airline_name <br>
                                                                    Remarks:  Reissue
                                                                </td>
                                                                
                                                                <td class="w-[12%] totaldebit"> $item->agent_price </td>
                                                                <td class="w-[12%] totalcredit"></td>
                                                                <td class="w-[12%] totaltotal"> $currentAmount </td>
                                                            </tr>
                                    HTML;
                                }
                        }
                       
                    } elseif ($item->table_name == "receive") {
                        // dd($item);
                        // $currentAmount = $item->current_amount >= 0 ? $item->current_amount . ' DR' : $item->current_amount . ' CR';
                        $balance -= $item->amount;
                        $currentAmount = $balance >= 0 ? $balance . ' DR' : $balance . ' CR';
                        $credit += $item->amount;
                        $id = $item->id ?? null; // Ensure $id is initialized
                        $methodDisplay = isset($activeTransactionMethods[$item->method]) 
                                        ? $activeTransactionMethods[$item->method] 
                                        : 'Deleted Method';
                        $html .= <<<HTML
                        <tr>
                            <td class="w-[10%]">$item->invoice  <br><small><strong>$item->date</strong></small></td>
                            <td class="w-[11%]">  </td>
                            <td class="w-[15%]"> </td>
                            <td class="w-[28%]">
                                Remarks: {$item->remark} <br>
                               
                                Received by {$methodDisplay}
                            </td>
                            <td class="w-[12%] totaldebit"></td>
                            <td class="w-[12%] totalcredit">{$item->amount}</td>
                            <td class="w-[12%] totaltotal">{$currentAmount}</td>
                        </tr>
                        HTML;

                    } elseif ($item->table_name == "payment") {
                        // $currentAmount = $item->current_amount >= 0 ? $item->current_amount . ' DR' : $item->current_amount . ' CR';
    
                        $balance += $item->amount;
                        $currentAmount = $balance >= 0 ? $balance . ' DR' : $balance . ' CR';
                        $debit += $item->amount;
    
                        $html .= <<<HTML
                                                 <tr>
                                                 <td class="w-[10%]">$item->invoice  <br><small><strong>$item->date</strong></small></td>
                                                 <td class="w-[11%]">  </td>
                                                    <td class="w-[15%]">  </td>
                                                    <td class="w-[28%]">
                                                        Remarks:  {$item->remark} <br>
                                                        <b>Payment by {$activeTransactionMethods[$item->method]}<b>
                                                    </td>
                                                    <td class="w-[12%] totaldebit">{$item->amount}</td>
                                                    <td class="w-[12%] totalcredit"></td>
                                                    <td class="w-[12%] totaltotal">{$currentAmount}</td>
                                                </tr>
                                                HTML;
                    } elseif ($item->table_name == "reissue") {
                        // $currentAmount = $item->now_agent_amount;
                        // $currentAmount = $currentAmount >= 0 ? $currentAmount . ' DR' : $currentAmount . ' CR';
    
                        $balance += $item->now_agent_fere;
                        $currentAmount = $balance >= 0 ? $balance . ' DR' : $balance . ' CR';
                        $debit += $item->now_agent_fere;
    
                        $agentname = Agent::where('id', $agentSupplierId)->value('name');
                        $ticket = Ticket::where([['user', Auth::id()], ['ticket_no', $item->ticket_no]])->first();
                        // dd($ticket);
                      
                        $html .= <<<HTML
                        <tr>
                       
                        HTML;
                        
                        if ($ticket) {
                            $html .= <<<HTML
                            
                                <td class="w-[10%]"> $item->invoice<br><small><strong> $item->date </strong></small></td>

                                <td class="w-[11%]"> {$ticket->airline_code}-{$ticket->ticket_no} </td>
                                <td class="w-[15%]"> {$ticket->flight_date} </td>
                                <td class="w-[28%]">
                                    <b>Reissue</b> to Customer: $agentname,  
                                    {$item->invoice}<br> Ticket No: {$ticket->airline_code}/{$ticket->ticket_no}, <br>
                                    Sector: {$ticket->sector},<br> on {$item->date} <b> PAX Name: {$ticket->passenger}</b>
                                </td>
                            HTML;
                        } else {
                            $html .= '<td class="w-[28%]"></td>';
                        }
                        
                        $html .= <<<HTML
                            <td class="w-[12%] totaldebit">{$item->now_agent_fere}</td>
                            <td class="w-[12%] totalcredit"></td>
                            <td class="w-[12%] totaltotal">{$currentAmount}</td>
                        </tr>
                        HTML;
                        
                    } elseif ($item->table_name == "refund") {
                        // dd($item);
                        $balance -= $item->now_agent_fere;
                        $currentAmount = $balance >= 0 ? $balance . ' DR' : $balance . ' CR';
                        $credit += $item->now_agent_fere;
    
                        $agentname = Agent::where('id', $agentSupplierId)->value('name');
                        $ticket = Ticket::where([['user', Auth::id()], ['ticket_no', $item->ticket_no]])->first();
                        if ($ticket) {
                            $html .= <<<HTML
                                <tr>
                                <td class="w-[10%]">$ticket->invoice<br><small><strong> $item->date </strong></small></td>
                                <td class="w-[11%]"> {$ticket->ticket_code}/{$ticket->ticket_no}  </td>
                                    <td class="w-[15%]"> {$ticket->flight_date} </td>
                                    <td class="w-[28%]">
                                        
                                        <b>Refund</b> to Customer : $agentname ,  
                                        {$ticket->invoice}<br> Ticket No : {$ticket->airline_code}/{$ticket->ticket_no}, <br>
                                        Sector :{$ticket->sector} ,<br> on {$item->date} <b> PAX Name : {$ticket->passenger}</b>
                                    </td>
                                    <td class="w-[12%] totaldebit"></td>
                                    <td class="w-[12%] totalcredit">{$item->now_agent_fere}</td>
                                    <td class="w-[12%] totaltotal">{$currentAmount}</td>
                                </tr>
                                HTML;
                        } else {
                            $html .= <<<HTML
                                <tr>
                                    <td class="w-[10%]"> {$item->date} </td>
                                    <td class="w-[11%]"> N/A </td>
                                    <td class="w-[15%]"> N/A </td>
                                    <td class="w-[28%]">
                                        <!-- Remarks: Refund -->
                                        <b>Refund</b> to Customer : $agentname <br>
                                        on {$item->date}
                                    </td>
                                    <td class="w-[12%] totaldebit"></td>
                                    <td class="w-[12%] totalcredit">{$item->now_agent_fere}</td>
                                    <td class="w-[12%] totaltotal">{$currentAmount}</td>
                                </tr>
                                HTML;
                        }
                        
                    } elseif ($item->table_name == "order") {
                        // $currentAmount = $item->agent_new_amount;
                        // $currentAmount = $currentAmount >= 0 ? $currentAmount . ' DR' : $currentAmount . ' CR';
                        
                        if (is_null($item->supplier) && $item->who === 'agent_' . $agentSupplierId) {
                            $balance -= $item->payable_amount;
                            $currentAmount = $balance >= 0 ? $balance . ' DR' : $balance . ' CR';
                            $credit += $item->payable_amount;
        
                            $typeneme = Type::where('id', $item->type)->value('name');
                            $html .= <<<HTML
                                                <tr>
                                                <td class="w-[10%]">$item->invoice <br><small><strong> $item->date</strong></small></td>
                                                <td class="w-[11%]"> {$typeneme} </td>
                                                    <td class="w-[15%]">  </td>
                                                    <td class="w-[28%]">
                                                        Passenger: {$item->name} <br>
                                                        Passport: {$item->passport_no}<br>
                                                        Remarks:  {$item->remark}
                                                    </td>
                                                    <td class="w-[12%] totaldebit"></td>
                                                    <td class="w-[12%] totalcredit">{$item->payable_amount}</td>
                                                    <td class="w-[12%] totaltotal">{$currentAmount}</td>
                                                </tr>
                                HTML;


                           
                        }
                        else{
                            $balance += $item->contact_amount;
                            $currentAmount = $balance >= 0 ? $balance . ' DR' : $balance . ' CR';
                            $debit += $item->contact_amount;
        
                            $typeneme = Type::where('id', $item->type)->value('name');
                            $html .= <<<HTML
                                                <tr>
                                                <td class="w-[10%]">$item->invoice <br><small><strong>$item->date </strong></small></td>
                                                    <td class="w-[11%]"> {$typeneme} </td>
                                                    <td class="w-[15%]">  </td>
                                                    <td class="w-[28%]">
                                                        Passenger: {$item->name} <br>
                                                        Passport: {$item->passport_no}<br>
                                                        Remarks:  {$item->remark}
                                                    </td>
                                                    <td class="w-[12%] totaldebit">{$item->contact_amount}</td>
                                                    <td class="w-[12%] totalcredit"></td>
                                                    <td class="w-[12%] totaltotal">{$currentAmount}</td>
                                                </tr>
                                                HTML;
                        }
                        
                    } elseif ($item->table_name == "voidTicket") {
                        // $currentAmount = $item->now_agent_amount;
                        // $currentAmount = $currentAmount >= 0 ? $currentAmount . ' DR' : $currentAmount . ' CR';
    
                        $balance += $item->now_agent_fere;
                        $currentAmount = $balance >= 0 ? $balance . ' DR' : $balance . ' CR';
                        $debit += $item->now_agent_fere;
                        // dd($item->date, $currentAmount);
    
                        $agentname = Agent::where('id', $agentSupplierId)->value('name');
                        $ticket = Ticket::where([['user', Auth::id()], ['ticket_no', $item->ticket_no]])->first();
    
                        if ($ticket) {
                            $html .= <<<HTML
                                 <tr >
                                                    <td class="w-[10%]">$ticket->invoice <br><small><strong>$item->date </strong></small></td>
                                                    <td class="w-[11%]"> {$ticket->ticket_code}-{$ticket->ticket_no} </td>
                                                    <td class="w-[15%]"> {$ticket->flight_date} </td>
                                                    <td class="w-[28%]">
                                                        <b>Void</b> to Customer : $agentname ,  
                                                        <br> Ticket No : {$ticket->airline_code}/{$ticket->ticket_no}, <br>
                                                        Sector :{$ticket->sector} ,<br> on {$item->date} <b> PAX Name : {$ticket->passenger}</b>
                                                        <b>Remarks</b>:  {$ticket->remark}
                                                    </td>
                                                    <td class="w-[12%] totaldebit">{$item->now_agent_fere}</td>
                                                    <td class="w-[12%] totalcredit"></td>
                                                    <td class="w-[12%] totaltotal">{$currentAmount}</td>
                                                </tr>
                            HTML;
                        }else {
                            $html .= <<<HTML
                            <tr >
                                <td class="w-[10%]"> {$item->date} </td>
                                <td class="w-[11%]">  </td>
                                <td class="w-[15%]">  </td>
                                <td class="w-[28%]">
                                    <b>Void</b> to Customer : $agentname ,  
                                   
                                </td>
                                <td class="w-[12%] totaldebit">{$item->now_agent_fere}</td>
                                <td class="w-[12%] totalcredit"></td>
                                <td class="w-[12%] totaltotal">{$currentAmount}</td>
                            </tr>
                            HTML;

                        }
                        
                    }
                   
                    elseif ($item->table_name == "contract") {
                        $balance += $item->contract_data->total_amount;
                        $currentAmount = $balance >= 0 ? $balance . ' DR' : abs($balance) . ' CR';
                        $debit += $item->contract_data->total_amount;
                        
                        // Generate a unique ID for this contract's details
                        $detailsId = 'contract-details-' . $item->contract_data->id;
                        
                        // Main contract row - now more compact
                        $html .= <<<HTML
                            <tr class="bg-gray-200 hover:bg-gray-300 cursor-pointer" onclick="toggleDetails('{$detailsId}')" id="contract-details">
                                <td class="w-[10%] py-2">
                                    <div class="font-medium">{$item->contract_data->invoice}</div>
                                    <div class="text-xs text-gray-600">{$item->date}</div>
                                </td>
                                <td class="w-[11%]">
                                    <div class="text-sm font-medium">Contract</div>
                                    <div class="text-sm text-gray-600">Passport: {$item->contract_data->passport_no}</div>
                                </td>
                                <td class="w-[15%]">
                                    <div class="text-sm font-medium">{$item->contract_data->name}</div>
                                    <div class="text-sm text-gray-600">{$item->contract_data->country}</div>
                                </td>
                                <td class="w-[28%] px-2 text-sm truncate" title="{$item->contract_data->notes}">
                                    {$item->contract_data->notes}
                                </td>
                                <td class="w-[12%] totaldebit text-left pr-4">{$item->contract_data->total_amount}</td>
                                <td class="w-[12%] totalcredit"></td>
                                <td class="w-[12%] totaltotal text-left pr-4">{$currentAmount}</td>
                            </tr>
                            <tr id="{$detailsId}" >
                                <td colspan="7" class="bg-gray-50 p-0">
                                    <div class="pl-4 pr-2 py-2">
                        HTML;
                        
                        // Services and Extra Types sections - side by side
                        if (!empty($item->services) || !empty($item->extra_types)) {
                            $html .= <<<HTML
                                <div class="flex flex-wrap -mx-2">
                            HTML;

                            // Services section - left column
                            if (!empty($item->services)) {
                                $html .= <<<HTML
                                    <div class="w-full md:w-1/2 px-2 mb-2">
                                        <div class="bg-white rounded shadow-sm border border-gray-200">
                                            <h4 class="text-sm font-semibold p-2 bg-gray-100 border-b text-gray-700">Services</h4>
                                            <table class="w-full text-xs">
                                                <thead>
                                                    <tr class="bg-gray-50">
                                                        <th class="text-left py-1 px-2 w-1/6">Date</th>
                                                        <th class="text-left py-1 px-2 w-1/6">Type</th>
                                                        <th class="text-right py-1 px-2">Details</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                HTML;
                                
                                foreach ($item->services as $service) {
                                    $serviceName = $activeTypes[$service->service_type] ?? 'Unknown Service';
                                    $html .= <<<HTML
                                        <tr class="border-b border-gray-100 hover:bg-gray-50">
                                            <td class="py-1 px-2">{$service->date}</td>
                                            <td class="py-1 px-2">{$serviceName}</td>
                                            <td class="text-right py-1 px-2">{$service->note}</td>
                                        </tr>
                                    HTML;
                                }
                                
                                $html .= <<<HTML
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                HTML;
                            }

                            // Extra types section - right column
                            if (!empty($item->extra_types)) {
                                $html .= <<<HTML
                                    <div class="w-full md:w-1/2 px-2 mb-2">
                                        <div class="bg-white rounded shadow-sm border border-gray-200">
                                            <h4 class="text-sm font-semibold p-2 bg-gray-100 border-b text-gray-700">Additional Services</h4>
                                            <table class="w-full text-xs">
                                                <thead>
                                                    <tr class="bg-gray-50">
                                                        <th class="text-left py-1 px-2 w-1/6">Date</th>
                                                        <th class="text-left py-1 px-2 w-1/6">Type</th>
                                                        <th class="text-right py-1 px-2">Details</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                HTML;
                                
                                foreach ($item->extra_types as $extra) {
                                    $extraName = ucfirst($extra->extratype) ?? 'Unknown Extra Service';
                                    
                                    if ($extra->extratype == 'wakala') {
                                        $date = $extra->wakala_date;
                                        $note = $extra->wakala_note;
                                        $details = "Visa No: {$extra->wakala_visa_no}, ID NO: {$extra->wakala_id_no}, Sales By: {$extra->wakala_sales_by}";
                                    } else {
                                        $date = $extra->ticket_invoice_date;
                                        $note = $extra->ticket_note;
                                        $details = "Flight: {$extra->ticket_travel_date}, {$extra->ticket_sector}, Airline: {$extra->ticket_airline}";
                                    }

                                    $html .= <<<HTML
                                        <tr class="border-b border-gray-100 hover:bg-gray-50">
                                            <td class="py-1 px-2">{$date}</td>
                                            <td class="py-1 px-2">{$extraName}</td>
                                            <td class="text-right py-1 px-2">
                                                {$details}
                                                <div class="text-gray-500 mt-1">Remarks: {$note}</div>
                                            </td>
                                        </tr>
                                    HTML;
                                }
                                
                                $html .= <<<HTML
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                HTML;
                            }

                            $html .= <<<HTML
                                </div>
                            HTML;
                        }
                    }
                    elseif ($item->table_name == "wakala") {
                        $balance += $item->total_price;
                        $currentAmount = $balance >= 0 ? $balance . ' DR' : abs($balance) . ' CR';
                        $debit += $item->total_price;
                    
                        $html .= <<<HTML
                            <tr>
                                <td class="w-[10%]">{$item->invoice}<br><small><strong>{$item->date}</strong></small></td>
                                <td class="w-[11%]"></td>
                                <td class="w-[15%]">Quantity: {$item->quantity}</td>
                                <td class="w-[28%] px-2 py-1 text-sm">
                                    <div class="space-y-1">
                                        <div class="flex items-start">
                                            <span class="font-medium text-gray-600 min-w-[70px]">ID NO:</span>
                                            <span class="text-gray-800">{$item->id_no}</span>
                                        </div>
                                        <div class="flex items-start">
                                            <span class="font-medium text-gray-600 min-w-[70px]">Visa:</span>
                                            <span class="text-gray-800 font-semibold">{$item->visa}</span>
                                        </div>
                                        <div class="flex items-start">
                                            <span class="font-medium text-gray-600 min-w-[70px]">Country:</span>
                                            <span class="text-gray-800 font-semibold">{$item->country}</span>
                                        </div>
                                        <div class="flex items-start">
                                            <span class="font-medium text-gray-600 min-w-[70px]">Currency Rate:</span>
                                            <span class="text-gray-800">
                                                <span class="font-semibold">{$item->multi_currency}</span>
                                            </span>
                                        </div>
                                        <div class="flex items-start">
                                            <span class="font-medium text-gray-600 min-w-[70px]">Sales By:</span>
                                            <span class="text-gray-800">
                                                <span class="font-semibold">{$item->sales_by}</span>
                                            </span>
                                        </div>
                                    </div>
                                    
                                </td>
                                <td class="w-[12%] totaldebit">{$item->total_price}</td>
                                <td class="w-[12%] totalcredit"></td>
                                <td class="w-[12%] totaltotal">{$currentAmount}</td>
                            </tr>
                        HTML;
                    
                    } 
                    elseif ($item->table_name == "wakala_agent_supplier") {
                        $balance -= $item->selling_price;
                        $currentAmount = $balance >= 0 ? $balance . ' DR' : abs($balance) . ' CR';
                        $credit += $item->selling_price;
                    
                        $html .= <<<HTML
                            <tr>
                                <td class="w-[10%]">{$item->invoice}<br><small><strong>{$item->date}</strong></small></td>
                                <td class="w-[11%]"></td>
                                <td class="w-[15%]">Quantity: {$item->quantity}</td>
                                <td class="w-[28%] px-2 py-1 text-sm">
                                    <div class="space-y-1">
                                        <div class="flex items-start">
                                            <span class="font-medium text-gray-600 min-w-[70px]">ID NO:</span>
                                            <span class="text-gray-800">{$item->id_no}</span>
                                        </div>
                                        <div class="flex items-start">
                                            <span class="font-medium text-gray-600 min-w-[70px]">Visa:</span>
                                            <span class="text-gray-800 font-semibold">{$item->visa}</span>
                                        </div>
                                        <div class="flex items-start">
                                            <span class="font-medium text-gray-600 min-w-[70px]">Country:</span>
                                            <span class="text-gray-800 font-semibold">{$item->country}</span>
                                        </div>
                                        <div class="flex items-start">
                                            <span class="font-medium text-gray-600 min-w-[70px]">Currency Rate:</span>
                                            <span class="text-gray-800">
                                                <span class="font-semibold">{$item->multi_currency}</span>
                                            </span>
                                        </div>
                                        <div class="flex items-start">
                                            <span class="font-medium text-gray-600 min-w-[70px]">Sales By:</span>
                                            <span class="text-gray-800">
                                                <span class="font-semibold">{$item->sales_by}</span>
                                            </span>
                                        </div>
                                    </div>
                                    
                                </td>
                                <td class="w-[12%] totaldebit"></td>
                                <td class="w-[12%] totalcredit">{$item->selling_price}</td>
                                <td class="w-[12%] totaltotal">{$currentAmount}</td>
                            </tr>
                        HTML;
                    
                    } 
                    elseif($item->table_name == "servicesForAgentAsSupplier"){
                        $balance -= $item->allocated_amount;
                        $currentAmount = $balance >= 0 ? $balance . ' DR' : abs($balance) . ' CR';
                        $credit += $item->allocated_amount;
                        $serviceName = $activeTypes[$item->service_type] ?? 'Unknown Service';

                        $html .= <<<HTML
                            <tr>
                                <td class="w-[10%]"><small>{$item->date}</small></td>
                                <td class="w-[11%]">{$serviceName}</td>
                                <td class="w-[15%]"></td>
                                <td class="w-[28%] px-2 py-1 text-sm">
                                    <div class="space-y-1">
                                        <div class="flex items-start">
                                            <span class="font-small text-gray-600 min-w-[70px]">Name:</span>
                                            <span class="text-gray-800">{$item->name}</span>//
                                            <span class="font-small text-gray-600 min-w-[70px]">Passport:</span>
                                            <span class="text-gray-800 font-semibold">{$item->passport_no}</span>
                                        </div>
                                       
                                        <div class="flex items-start">
                                            <span class="font-small text-gray-600 min-w-[70px]">Country:</span>
                                            <span class="text-gray-800 font-semibold">{$item->country}</span><br>
                                            <span class="font-small text-gray-600 min-w-[70px]">Remark:</span>
                                            <span class="text-gray-800">
                                                <span class="font-semibold">{$item->note}</span>
                                            </span>
                                        </div>
                                        
                                        
                                    </div>
                                    
                                </td>
                                <td class="w-[12%] totaldebit"></td>
                                <td class="w-[12%] totalcredit">{$item->allocated_amount}</td>
                                <td class="w-[12%] totaltotal">{$currentAmount}</td>
                            </tr>
                        HTML;
                    }
                    elseif($item->table_name == "extraTypesForAgentAsSupplier"){
                        $balance -= $item->amount;
                        $currentAmount = $balance >= 0 ? $balance . ' DR' : abs($balance) . ' CR';
                        $credit += $item->amount;
                        $serviceName = $item->extratype ?? 'Unknown Service';

                        $details = '';
                        if ($item->extratype == 'wakala') {
                            $date = $item->wakala_date;
                            $note = $item->wakala_note;
                            $details = "Visa No: {$item->wakala_visa_no}, ID NO: {$item->wakala_id_no}, Sales By: {$item->wakala_sales_by}";
                        } else {
                            $date = $item->ticket_invoice_date;
                            $note = $item->ticket_note;
                            $details = "Flight: {$item->ticket_travel_date}, {$item->ticket_sector}, Airline: {$item->ticket_airline}";
                        }
                        $html .= <<<HTML
                            <tr>
                                <td class="w-[10%]"><small>{$date}</small></td>
                                <td class="w-[11%]">{$serviceName}</td>
                                <td class="w-[15%]"></td>
                                <td class="w-[28%] px-2 py-1 text-sm">
                                    <div class="space-y-1">
                                        {$details}
                                        
                                        <div class="flex items-start">
                                            <span class="font-medium text-gray-600 min-w-[70px]">Remark:</span>
                                            <span class="text-gray-800">
                                                <span class="font-semibold">{$note}</span>
                                            </span>
                                        </div>
                                    </div>
                                    
                                </td>
                                <td class="w-[12%] totaldebit"></td>
                                <td class="w-[12%] totalcredit">{$item->amount}</td>
                                <td class="w-[12%] totaltotal">{$currentAmount}</td>
                            </tr>
                        HTML;
                    }
                }
                
                $balance = $balance >= 0 ? $balance . ' DR' : $balance . ' CR';
                $agentName = Agent::where('id', $agentSupplierId)->value('name');

                $htmlpart = ViewFacade::make('report.general_ledger.GeneralLadger', [
              
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                    'html'   => $html,
                    'balance' => $balance,
                    'debit' => $debit,
                    'credit' => $credit,
                    'holdername' => $agentName,
                   
                    'opening_balance' => $final_opening_balance,
                    'total_ticket' => $total_ticket,
                    // $opening_balance_debit = $opening_balance_credit = $opening_balance = 0;

                   
                ])->render();

                return response()->json(['html' => $htmlpart]);
            }

            else{
                
                   // Agent-related data retrieval
                   $tickets = DB::table('tickets')
                   ->where('supplier', $agentSupplierId)
                   ->where('is_delete', 0)
                   ->where('is_active', 1)
                   ->where('user', Auth::id());
   
                   // Orders related to the agent
                   $orders = DB::table('order')
                   ->where('supplier', $agentSupplierId)
                   ->where('is_delete', 0)
                   ->where('is_active', 1)
                   ->where('user', Auth::id());
   
                   // Receives
                   $receive = DB::table('receive')
                   ->where('receive_from', 'supplier')
                   ->where('agent_supplier_id', $agentSupplierId)
                   ->where('user', Auth::id());
   
                   // Payments
                   $payment = DB::table('payment')
                   ->where('receive_from', 'supplier')
                   ->where('agent_supplier_id', $agentSupplierId)
                   ->where('user', Auth::id());
   
                   // Refunds
                   $refund = DB::table('refund')
                   ->where('supplier', $agentSupplierId)
                   ->where('user', Auth::id());
   
                   // Void Tickets
                   $void_ticket = DB::table('voidTicket')
                   ->where('user', Auth::id())
                   ->where('supplier', $agentSupplierId);
   
                   // Reissues
                   $reissue = DB::table('reissue')
                   ->where('supplier', $agentSupplierId)
                   ->where('user', Auth::id());

                    // wakala
                    $wakala = DB::table('wakala')
                    ->where('agent_supplier', '=', 'supplier')
                    ->where('supplier', $agentSupplierId)
                    ->where('user', Auth::id());

                     // 2. Get services for these contracts
                    $services = DB::table('contract_services')
                    ->where('agent_or_supplier', '=', 'supplier')
                    ->where('supplier', $agentSupplierId)
                    ->get();

                    // 3. Get extra_types for these contracts
                    $extraTypes = DB::table('extra_types')
                    ->where('agent_supplier', '=', 'supplier')
                    ->where('supplier', $agentSupplierId)
                    ->get();

                  // Get all contract IDs that have services
                    $contractids = collect();
                    foreach ($services as $service) {
                        if (!$contractids->contains($service->contract_id)) {
                            $contractids->push($service->contract_id);
                        }
                    }
                    foreach($extraTypes as $extra){
                        if (!$contractids->contains($extra->contract_service_id)) {
                            $contractids->push($extra->contract_service_id);
                        }
                    }
                  
                    $contracts = DB::table('contracts')
                    ->where('user', Auth::id())
                    ->whereIn('id', $contractids)
                    ->get();

                    // dd($contracts);
                    $services = $services->groupBy('contract_id');
                    $extraTypes = $extraTypes->groupBy('contract_service_id');

                    // 4. Build the structured response 
                    $contract = $contracts->map(function ($contract) use ($services, $extraTypes) {
                        return (object) [ // Convert to object
                            'contract_id' => $contract->id,
                            'date' => $contract->contract_date,
                            'contract_data' => $contract,
                            'services' => $services->get($contract->id, collect())->values() ?? '',
                            'extra_types' => $extraTypes->get($contract->id, collect())->values() ?? '',
                            'table_name' => 'contract'
                        ];
                    });
   
                //    dd($contract);
   
                   // Use get() to fetch the data, and then apply map()
                   $tickets = $tickets->get()->map(function ($item) {
                   $item->table_name = 'tickets';  // Add a table_name property
                   return $item;
                   });
   
                   $orders = $orders->get()->map(function ($item) {
                   $item->table_name = 'order';  // Add a table_name property
                   return $item;
                   });
   
                   $receive = $receive->get()->map(function ($item) {
                   $item->table_name = 'receive';  // Add a table_name property
                   return $item;
                   });
   
                   $payment = $payment->get()->map(function ($item) {
                   $item->table_name = 'payment';  // Add a table_name property
                   return $item;
                   });
   
                   $refund = $refund->get()->map(function ($item) {
                   $item->table_name = 'refund';  // Add a table_name property
                   return $item;
                   });
   
                   $void_ticket = $void_ticket->get()->map(function ($item) {
                   $item->table_name = 'voidTicket';  // Add a table_name property
                   return $item;
                   });
   
                   $reissue = $reissue->get()->map(function ($item) {
                   $item->table_name = 'reissue';  // Add a table_name property
                   return $item;
                   });
   
                   $wakala = $wakala->get()->map(function ($item) {
                   $item->table_name = 'wakala';  // Add a table_name property
                   return $item;
                   });
   
                    // Merge all collections into a single collection
                    $mergedCollection = $tickets->merge($orders)
                    ->merge($receive)
                    ->merge($payment)
                    ->merge($refund)
                    ->merge($void_ticket)
                    ->merge($contract)
                    ->merge($wakala)
                    ->merge($reissue);

                    // Normalize the `date` field, ensuring only the date part is kept
                    $normalizedCollection = $mergedCollection->map(function ($item) {
                    try {
                        if (isset($item->date)) {
                            // Parse the date and normalize to `YYYY-MM-DD` format
                            $item->date = Carbon::parse($item->date)->toDateString();
                        } else {
                            $item->date = null; // Set null if no date is present
                        }
                    } catch (\Exception $e) {
                        $item->date = null; // Handle invalid dates
                    }
                    return $item;
                    });

                    // Sort the collection by `date` or `created_at`
                    $sortedCollection = $normalizedCollection
                    ->sortBy(function ($item) {
                        if ($item->date) {
                            // Parse `date` (as a string) and return its timestamp
                            return Carbon::parse($item->date)->timestamp;
                        } elseif ($item->created_at) {
                            // Parse `created_at` as a fallback and return its timestamp
                            return Carbon::parse($item->created_at)->timestamp;
                        } else {
                            // Push items without `date` or `created_at` to the end
                            return PHP_INT_MAX;
                        }
                    })
                    ->values(); // Re-index the collection


                    $final_opening_balance = Agent::where('id', $agentSupplierId)->value('opening_balance');
                
                    // dd($startDate);
                    if ($startDate) {
                        ['final_opening_balance' => $final_opening_balance, 'from_start_date' => $sortedCollection] = 
                            $this->separateTransactions($sortedCollection, $startDate, $final_opening_balance, $endDate, $agentSupplier, $agentSupplierId);
                        // dd($final_opening_balance, $sortedCollection);
                    }
                   $activeTransactionMethods = Transaction::where([['is_active', 1],['is_delete',0],['user', Auth::id()]])->pluck('name', 'id')->toArray();
                   $activeTypes = Type::where([['is_active', 1],['is_delete',0],['user', Auth::id()]])->pluck('name', 'id')->toArray();

                $debit = 0;
                $balance = $final_opening_balance;
                $credit = 0;
                $total_ticket = 0;
                $html = '';

                $supplierName = Supplier::where('id', $agentSupplierId)->value('name');

                
                foreach ($sortedCollection as $index => $item) {
                    // dd($item->getTable());
                  
                    if ($item->table_name == "tickets") {
                        // Handle logic specific to Ticket model
                        $credit += $item->supplier_price;
                        $balance += $item->supplier_price;
                        $currentAmount = $balance >= 0 ? $balance . ' CR' : $balance . ' DR';
                        $ticket = Ticket::where([['user', Auth::id()], ['ticket_no', $item->ticket_no]])->first();
                        $total_ticket++;
                        $html .= <<<HTML
                                                    <tr>
                                                        <td class="w-[10%]">$item->invoice  <br><small><strong>$item->invoice_date</strong></small></td>
                                                        <td class="w-[15%]"> {$ticket->ticket_code}-{$item->ticket_no} </td>

                                                        <td class="w-[11%]"> $item->flight_date </td>
                                                        <td class="w-[28%] pr-3">
                                                            PAX NAME: <span class=""> $item->passenger </span><br>
                                                            PNR:  $item->pnr ,  $item->sector <br>
                                                           
                                                            $item->airline_code -  $item->airline_name <br>
                                                            Remarks:  $item->remark 
                                                        </td>
                                                        <td class="w-[12%] totaldebit"> </td>
                                                        <td class="w-[12%] totalcredit">$item->supplier_price </td>
                                                        <td class="w-[12%] totaltotal">$currentAmount</td>
                                                    </tr>
                                                HTML;
                    }
                    elseif ($item->table_name == "refund") {
                        // dd($item);
                        $balance -= $item->now_supplier_fare;
                        $currentAmount = $balance >= 0 ? $balance . ' CR' : $balance . ' DR';
                        $debit += $item->now_supplier_fare;
    
                        $agentname = Agent::where('id', $agentSupplierId)->value('name');
                        $ticket = Ticket::where([['user', Auth::id()], ['ticket_no', $item->ticket_no]])->first();
                        $invoiceshow = isset($item->invoice) && !empty($item->invoice) ? $item->invoice : '';
                        // dd($ticket, $item);
                       
                        
                        if ($ticket) {
                            // dd($ticket->invoice);
                            $html .= <<<HTML
                              <td class="w-[10%]">$ticket->invoice  <br><small><strong>$item->date</strong></small></td>
                              <td class="w-[15%]"> {$ticket->ticket_code}-{$item->ticket_no} </td>
                              <td class="w-[11%]"> $ticket->flight_date </td>
                            
                            <td class="w-[28%]">
                                <b>Refund</b> to Customer: $agentname,  
                                {$invoiceshow}<br> 
                                Ticket No: {$ticket->airline_code}/{$ticket->ticket_no}, <br>
                                Sector: {$ticket->sector}, <br> 
                                on {$item->date} PAX Name: {$ticket->passenger}<br>
                            </td>
                        HTML;
                        } else {
                            $html .= <<<HTML
                            <td class="w-[15%]">Deleted Ticket </td>
                            <td class="w-[28%]">
                                <b>Refund</b> to Customer: $agentname,  
                                {$invoiceshow}<br> 
                                Deleted Ticket
                            </td>
                        HTML;
                        }
                        
                        $html .= <<<HTML
                            <td class="w-[12%] totaldebit">{$item->now_supplier_fare}</td>
                            <td class="w-[12%] totalcredit"></td>
                            <td class="w-[12%] totaltotal">{$currentAmount}</td>
                        </tr>
                        HTML;
                        } 
                        elseif ($item->table_name == "receive") {
                        // dd($item);
                        $balance += $item->amount;
                        $currentAmount = $balance >= 0 ? $balance . ' CR' : $balance . ' DR';
                        $credit += $item->amount;
                        // $ticket = Ticket::where([['user', Auth::id()], ['ticket_no', $item->ticket_no]])->first();
                        $html .= <<<HTML
                                                <tr>
                                                    <td class="w-[10%]">$item->invoice  <br><small><strong>$item->date</strong></small></td>
                                                    <td class="w-[11%]">  </td>
                                                    <td class="w-[15%]">  </td>
                                                    <td class="w-[28%]">
                                                        Remarks:  {$item->remark} <br>
                                                        <b>Receive from {$activeTransactionMethods[$item->method]}</b>

                                                    </td>
                                                    <td class="w-[12%] totaldebit"></td>
                                                    <td class="w-[12%] totalcredit">{$item->amount}</td>
                                                    <td class="w-[12%] totaltotal">{$currentAmount}</td>
                                                </tr>
                                                HTML;
                        }
                        elseif ($item->table_name == "payment") {
    
                        $balance -= $item->amount;
                        $currentAmount = $balance >= 0 ? $balance . ' CR' : $balance . ' DR';
                        $debit += $item->amount;
    
                        $html .= <<<HTML
                                                <tr>
                                                    <td class="w-[10%]">$item->invoice  <br><small><strong>$item->date</strong></small></td>
                                                    <td class="w-[11%]">  </td>
                                                    <td class="w-[15%]"> </td>
                                                    <td class="w-[28%]">
                                                        Remarks:  {$item->remark} <br>
                                                        <b>Payment by {$activeTransactionMethods[$item->method]}</b>

                                                    </td>
                                                    <td class="w-[12%] totaldebit">{$item->amount}</td>
                                                    <td class="w-[12%] totalcredit"></td>
                                                    <td class="w-[12%] totaltotal">{$currentAmount}</td>
                                                </tr>
                                                HTML;
                    } elseif ($item->table_name == "reissue") {
                        // $currentAmount = $item->now_supplier_amount;
                        // $currentAmount = $currentAmount >= 0 ? $currentAmount . ' DR' : $currentAmount . ' CR';
                        // dd($item);
                        $balance += $item->now_supplier_fare;
                        $currentAmount = $balance >= 0 ? $balance . ' CR' : $balance . ' DR';
                        $ticket = Ticket::where([['user', Auth::id()], ['ticket_no', $item->ticket_no]])->first();
                        $credit += $item->now_supplier_fare;
                        $invoiceshow = isset($item->invoice) && !empty($item->invoice) ? $item->invoice : '';

                        if ($ticket) {
                            $html .= <<<HTML
                              <td class="w-[10%]">$item->invoice  <br><small><strong>$item->date</strong></small></td>
                              <td class="w-[15%]"> {$ticket->ticket_code}-{$item->ticket_no} </td>
                              <td class="w-[11%]"> $ticket->flight_date </td>
                            
                              <td class="w-[28%]">
                                
                                <b>Reissue</b> to Customer : $supplierName ,  
                                {$item->invoice}<br> Ticket No : {$ticket->airline_code}/{$ticket->ticket_no}, <br>
                                Sector :{$ticket->sector} ,<br> on {$item->date} <b> PAX Name : {$ticket->passenger}</b><br/>
                              </td>
                        HTML;
                        } else {
                            $html .= <<<HTML
                            <td class="w-[10%]"> $item->date</td>

                            <td class="w-[15%]">Deleted Ticket </td>
                            <td></td>
                            <td class="w-[28%]">
                                <b>Refund</b> to Customer: $supplierName,  
                                {$invoiceshow}<br> 
                                Deleted Ticket
                            </td>
                        HTML;
                        }

                        $html .= <<<HTML
                                    
                                        <td class="w-[12%] totaldebit"></td>
                                        <td class="w-[12%] totalcredit">{$item->now_supplier_fare}</td>
                                        <td class="w-[12%] totaltotal">{$currentAmount}</td>
                                    </tr>
                                    HTML;
                    } elseif ($item->table_name == "voidTicket") {
                        $balance += $item->now_supplier_fare;
                        $currentAmount = $balance >= 0 ? $balance . ' CR' : $balance . ' DR';
                        $credit += $item->now_supplier_fare;
                        $ticket = Ticket::where([['user', Auth::id()], ['ticket_no', $item->ticket_no]])->first();
    
                      
                        if ($ticket) {
                            // If ticket is available, show additional ticket information
                            $html .= <<<HTML
                                <tr >
                                    <td class="w-[10%]">$ticket->invoice  <br><small><strong>$item->date</strong></small></td>
                                
                                    <td class="w-[15%]"> {$ticket->ticket_code}/{$ticket->ticket_no} </td>
                                    <td class="w-[11%]"> {$ticket->flight_date} </td>
                                    <td class="w-[28%]">
                                        <b>Void</b> to Customer : $supplierName ,  
                                        <br> Ticket No : {$ticket->airline_code}/{$ticket->ticket_no}, <br>
                                        Sector :{$ticket->sector} ,<br> on {$item->date} <b> PAX Name : {$ticket->passenger}</b><br>
                                    </td>
                                    <td class="w-[12%] totaldebit"></td>
                                    <td class="w-[12%] totalcredit">{$item->now_supplier_fare}</td>
                                    <td class="w-[12%] totaltotal">{$currentAmount}</td>
                                </tr>
                            HTML;
                        } else {
                            // If ticket is not available, show a placeholder message
                            $html .= <<<HTML
                                 <tr >
                                    <td class="w-[10%]"> {$item->date} </td>
                                    <td class="w-[11%]">  </td>
                                    <td class="w-[15%]">  </td>
                                    <td class="w-[28%]">
                                        <b>Void</b> to Customer : $supplierName ,  
                                       </td>
                                    <td class="w-[12%] totaldebit"></td>
                                    <td class="w-[12%] totalcredit">{$item->now_supplier_fare}</td>
                                    <td class="w-[12%] totaltotal">{$currentAmount}</td>
                                </tr>
                            HTML;
                        }
                    } elseif ($item->table_name == "order") {
                        
                        $balance += $item->payable_amount;
                        $currentAmount = $balance >= 0 ? $balance . ' CR' : $balance . ' DR';
                        $credit += $item->payable_amount;
    
                        $typeneme = Type::where('id', $item->type)->value('name');
                            $html .= <<<HTML
                                                <tr>
                                                    <td class="w-[10%]">$item->invoice <br><small><strong> $item->date</strong></small></td>
                                                    <td class="w-[15%]"> {$typeneme} </td>
                                                    <td class="w-[11%]">  </td>
                                                    <td class="w-[28%]">
                                                        Passenger: {$item->name} <br>
                                                        Passport: {$item->passport_no}<br>
                                                        Remarks:  {$item->remark}
                                                    </td>
                                                    <td class="w-[12%] totaldebit"></td>
                                                    <td class="w-[12%] totalcredit">{$item->payable_amount}</td>
                                                    <td class="w-[12%] totaltotal">{$currentAmount}</td>
                                                </tr>
                                                HTML;
                    }
                    elseif ($item->table_name == "contract") {
                        $detailsId = 'contract-details-' . $item->contract_data->id;
                        
                        $html .= <<<HTML
                            <tr class="bg-gray-200 hover:bg-gray-300 cursor-pointer" onclick="toggleDetailsSupplier('{$detailsId}')" id="contract-details-dsa">
                                <td class="w-[10%] py-2">
                                    <div class="font-medium">{$item->contract_data->invoice}</div>
                                    <div class="text-xs text-gray-600">{$item->date}</div>
                                </td>
                                <td class="w-[11%]">
                                    <div class="text-sm font-medium">Contract</div>
                                    <div class="text-sm text-gray-600">Passport: {$item->contract_data->passport_no}</div>
                                </td>
                                <td class="w-[15%]">
                                    <div class="text-sm font-medium">{$item->contract_data->name}</div>
                                    <div class="text-sm text-gray-600">{$item->contract_data->country}</div>
                                </td>
                                <td class="w-[28%] px-2 text-sm truncate" title="{$item->contract_data->notes}">
                                    {$item->contract_data->notes}
                                </td>
                                <td class="w-[12%] totaldebit text-left pr-4"></td>
                                <td class="w-[12%] totalcredit"></td>
                                <td class="w-[12%] totaltotal text-left pr-4">{$currentAmount}</td>
                            </tr>
                            <div id="{$detailsId}" style="display: block;">
                                <div class="bg-gray-50 p-0">
                                    <div class="pl-4 pr-2 py-2">
                        HTML;
                        
                        // Services section
                        if (!empty($item->services)) {
                           
                            
                            foreach ($item->services as $service) {
                                $serviceName = $activeTypes[$service->service_type] ?? 'Unknown Service';
                                $balance += $service->allocated_amount;
                                $currentAmount = $balance >= 0 ? $balance . ' DR' : abs($balance) . ' CR';
                                $debit += $service->allocated_amount;
                                
                                $html .= <<<HTML
                                    <tr class="border-b border-gray-100 hover:bg-gray-50">
                                        <td class="py-1 ">{$service->date}</td>
                                        <td class="py-1  font-medium">{$serviceName}</td>
                                        <td></td>
                                        <td class="py-1">{$service->note}</td>
                                        <td class="py-1 text-left pr-2 font-medium totaldebit">{$service->allocated_amount}</td>
                                        <td class=" totalcredit"></td>
                                        <td class=" totaltotal">{$currentAmount}</td>
                                        <td>
                                    </tr>
                                HTML;
                            }
                            
                            $html .= <<<HTML
                                           </div>
                                    </div>
                                </div>
                            HTML;
                        }
                    
                        // Extra types section
                        if (!empty($item->extra_types)) {
                            
                            foreach ($item->extra_types as $extra) {
                                $extraName = ucfirst($extra->extratype) ?? 'Unknown Extra Service';
                                $balance += $extra->amount;
                                $currentAmount = $balance >= 0 ? $balance . ' DR' : abs($balance) . ' CR';
                                $debit += $extra->amount;
                                
                                if ($extra->extratype == 'wakala') {
                                    $date = $extra->wakala_date;
                                    $details = "Visa No: {$extra->wakala_visa_no}, <br> ID NO: {$extra->wakala_id_no}, Sales By: {$extra->wakala_sales_by}";
                                    $note = $extra->wakala_note;
                                } else {
                                    $date = $extra->ticket_invoice_date;
                                    $details = "Flight: {$extra->ticket_travel_date},<br>Sector: {$extra->ticket_sector}, Airline: {$extra->ticket_airline}";
                                    $note = $extra->ticket_note;
                                }
                    
                                $html .= <<<HTML
                                    <tr class="border-b border-gray-100 hover:bg-gray-50">
                                        <td class="py-1 ">{$date}</td>
                                        <td class="py-1 font-medium">{$extraName}</td>
                                        <td class="py-1 text-xs">{$details}</td>
                                        <td class="py-1">
                                            <div class="text-s text-gray-500">Remarks: {$note}</div>
                                        </td>
                                        <td class="py-1 text-left totaldebit">{$extra->amount}</td>
                                        <td class="py-1  totalcredit"></td>
                                        <td class=" totaltotal">{$currentAmount}</td>
                                    </tr>
                                HTML;
                            }
                            
                            $html .= <<<HTML
                                         
                                    </div>
                                </div>
                            HTML;
                        }
                    
                        $html .= <<<HTML
                                    </div>
                                </td>
                            </tr>
                        HTML;
                    }
                    elseif ($item->table_name == "wakala") {
                        $balance += (float) ($item->selling_price ?? 0);
                        $currentAmount = $balance >= 0 ? $balance . ' DR' : abs($balance) . ' CR';
                        $credit += (float) ($item->selling_price ?? 0);
                    
                        $html .= <<<HTML
                            <tr>
                                <td class="w-[10%]">{$item->invoice}<br><small><strong>{$item->date}</strong></small></td>
                                <td class="w-[11%]"></td>
                                <td class="w-[15%]">Quantity: {$item->quantity}</td>
                                <td class="w-[28%] px-2 py-1 text-sm">
                                    <div class="space-y-1">
                                        <div class="flex items-start">
                                            <span class="font-medium text-gray-600 min-w-[70px]">ID NO:</span>
                                            <span class="text-gray-800">{$item->id_no}</span>
                                        </div>
                                        <div class="flex items-start">
                                            <span class="font-medium text-gray-600 min-w-[70px]">Visa:</span>
                                            <span class="text-gray-800 font-semibold">{$item->visa}</span>
                                        </div>
                                        <div class="flex items-start">
                                            <span class="font-medium text-gray-600 min-w-[70px]">Country:</span>
                                            <span class="text-gray-800 font-semibold">{$item->country}</span>
                                        </div>
                                        <div class="flex items-start">
                                            <span class="font-medium text-gray-600 min-w-[70px]">Currency Rate:</span>
                                            <span class="text-gray-800">
                                                <span class="font-semibold">{$item->multi_currency}</span>
                                            </span>
                                        </div>
                                        <div class="flex items-start">
                                            <span class="font-medium text-gray-600 min-w-[70px]">Sales By:</span>
                                            <span class="text-gray-800">
                                                <span class="font-semibold">{$item->sales_by}</span>
                                            </span>
                                        </div>
                                    </div>
                                    
                                </td>
                                <td class="w-[12%] totaldebit"></td>
                                <td class="w-[12%] totalcredit">{$item->selling_price}</td>
                                <td class="w-[12%] totaltotal">{$currentAmount}</td>
                            </tr>
                        HTML;
                    
                    } 
                }
                $balance = $balance >= 0 ? $balance . ' CR' : $balance . ' DR';
                
                // $balance = $balance >= 0 ? $balance . ' DR' : $balance . ' CR';
                $agentName = Supplier::where('id', $agentSupplierId)->value('name');

                $htmlpart = ViewFacade::make('report.general_ledger.GeneralLadger', [
              
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                    'html'   => $html,
                    'balance' => $balance,
                    'debit' => $debit,
                    'credit' => $credit,
                    'holdername' => $agentName,
                    
                    'opening_balance' => $final_opening_balance,
                    'total_ticket' => $total_ticket,
                    // $opening_balance_debit = $opening_balance_credit = $opening_balance = 0;

                   
                ])->render();

                return response()->json(['html' => $htmlpart]);
   
            }
        }
            

        
        else{
            return view('welcome');
        }
    }

}
