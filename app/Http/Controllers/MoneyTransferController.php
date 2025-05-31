<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Expenditure;
use App\Models\ExpenditureMain;
use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\MoneyTransfer;
use App\Models\Account;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use \Carbon\Carbon;
use Illuminate\Support\Facades\View as ViewFacade;


use DateTime;


class MoneyTransferController extends Controller
{
    public function index(){
        if(Auth::user()){
            
            $transactions = Transaction::with('account')
                ->where('user', Auth::id())
                ->where('is_delete', 0)
                ->where('is_active', 1)
                 ->where(function($query) {
                    $query->whereNull('account_id') // Transactions without an account
                        ->orWhereHas('account', function($q) {
                            $q->where('is_delete', 0)
                                ->where('is_active', 1);
                        });
                })
                ->get();

            $transfers = MoneyTransfer::where([
                ['user', Auth::id()]
            ])->get();

            $company_name = Auth::user()->name;
            // dd($company_name);

            foreach ($transfers as $transfer) {
                // Fetch the name of the transaction associated with the 'from' ID
                $fromTransaction = Transaction::find($transfer->from);
                $transfer->from = $fromTransaction ? $fromTransaction->name : 'Unknown';
            
                // Fetch the name of the transaction associated with the 'to' ID
                $toTransaction = Transaction::find($transfer->to);
                $transfer->to = $toTransaction ? $toTransaction->name : 'Unknown';
            }
            
            // dd($transactions);
            return view('moneytransfer.index', compact('transactions', 'transfers', 'company_name'));
            
        }
        else{
            return view('welcome');
        }
        
    }


    public function expanditure_index(){
        if(Auth::user()){
            $transactions = Transaction::where([
                ['is_delete', 0],
                ['is_active', 1],
                ['user', Auth::id()]
            ])->get();

            $employees = Employee::where([
                ['is_delete',0],
                ['user', Auth::id()],
            ])->get();

            $expenditures = Expenditure::where([
                ['user', Auth::id()],
            ])->get();

            $expendituresmain = ExpenditureMain::where([
                ['user', Auth::id()],
            ])->get();


            $transfers = MoneyTransfer::where([
                ['user', Auth::id()]
            ])->get();

            $company_name = Auth::user()->name;
            // dd($company_name);

            foreach ($transfers as $transfer) {
                // Fetch the name of the transaction associated with the 'from' ID
                $fromTransaction = Transaction::find($transfer->from);
                $transfer->from = $fromTransaction ? $fromTransaction->name : 'Unknown';
            
                // Fetch the name of the transaction associated with the 'to' ID
                $toTransaction = Transaction::find($transfer->to);
                $transfer->to = $toTransaction ? $toTransaction->name : 'Unknown';
            }
            
            // dd($transfers);
            return view('expanditure.index', compact('transactions', 'transfers', 'company_name', 'expenditures','expendituresmain', 'employees'));
            
        }
        else{
            return view('welcome');
        }
        
    }

    
   
    public function store(Request $request)
    {
        // Validate user authentication
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please login to perform money transfers.');
        }

        // Validate request data
        $validated = $request->validate([
            'from_account' => 'required|integer|exists:transaction,id',
            'to_account' => 'required|integer|exists:transaction,id|different:from_account',
            'amount' => 'required|numeric|min:0.01',
            'transaction_date' => 'required|date',
        ]);

        // Begin transaction
        DB::beginTransaction();

        try {
            // Retrieve sender and receiver transactions with locking
            $senderTransaction = Transaction::lockForUpdate()->findOrFail($request->from_account);
            $receiverTransaction = Transaction::lockForUpdate()->findOrFail($request->to_account);

            // Handle cases where account_id might be 0 (newly added accounts)
            $senderAccount = $senderTransaction->account_id ? 
                Account::lockForUpdate()->findOrFail($senderTransaction->account_id) : null;
            $receiverAccount = $receiverTransaction->account_id ? 
                Account::lockForUpdate()->findOrFail($receiverTransaction->account_id) : null;

            // Validate sender balance
            if ($senderTransaction->amount < $request->amount) {
                throw new \Exception('Insufficient balance in the source account.');
            }

            // Check for negative amount
            if ($request->amount <= 0) {
                throw new \Exception('Transfer amount must be positive.');
            }

            // Update sender balances
            $senderTransaction->amount -= $request->amount;
            if ($senderAccount) {
                if ($senderAccount->current_balance < $request->amount) {
                    throw new \Exception('Insufficient balance in the linked account.');
                }
                $senderAccount->current_balance -= $request->amount;
            }

            // Update receiver balances
            $receiverTransaction->amount += $request->amount;
            if ($receiverAccount) {
                $receiverAccount->current_balance += $request->amount;
            }

            // Save all records
            $senderTransaction->save();
            $receiverTransaction->save();
            if ($senderAccount) $senderAccount->save();
            if ($receiverAccount) $receiverAccount->save();

            // Create transfer record without using create()
            $moneyTransfer = new MoneyTransfer();
            $moneyTransfer->user = Auth::id();
            $moneyTransfer->from = $request->from_account;
            $moneyTransfer->to = $request->to_account;
            $moneyTransfer->date = $request->transaction_date;
            $moneyTransfer->amount = $request->amount;
            $moneyTransfer->remark = $request->remarks ?? null;
        
            $moneyTransfer->save();

            DB::commit();

            return redirect()
                ->route('moneytransfer.view')
                ->with('success', 'Money transferred successfully.');

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            DB::rollBack();
            return redirect()
                ->route('moneytransfer.view')
                ->with('error', 'One or more accounts not found.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Money transfer failed: ' . $e->getMessage());
            return redirect()
                ->route('moneytransfer.view')
                ->with('error', 'Transfer failed: ' . $e->getMessage());
        }
    }

    // public function destroy($id)
    // {
    //     if (Auth::check()) {
    //         try {
    //             DB::transaction(function () use ($id) {
    //                 // Find the money transfer record by ID
    //                 $moneyTransfer = MoneyTransfer::find($id);

    //                 // Check if the money transfer record exists
    //                 if (!$moneyTransfer) {
    //                     throw new \Exception('Money transfer record not found.');
    //                 }

    //                 $amount = $moneyTransfer->amount;

    //                 // Update the balances of the sender and receiver transactions
    //                 $senderTransaction = Transaction::find($moneyTransfer->from);
    //                 $receiverTransaction = Transaction::find($moneyTransfer->to);

    //                 if ($senderTransaction && $receiverTransaction) {
    //                     $senderTransaction->amount += $amount;
    //                     $receiverTransaction->amount -= $amount;

    //                     $senderTransaction->save();
    //                     $receiverTransaction->save();
    //                 } else {
    //                     throw new \Exception('One or both of the transactions do not exist.');
    //                 }

    //                 // Delete the money transfer record
    //                 $moneyTransfer->delete();
    //             });

    //             return redirect()->route('moneytransfer.view')->with('success', 'Money transfer record deleted successfully.');
    //         } catch (\Exception $e) {
    //             return redirect()->route('moneytransfer.view')->with('error', $e->getMessage());
    //         }
    //     } else {
    //         return redirect()->route('welcome')->with('error', 'User not authenticated.');
    //     }
    // }


    public function destroy($id)
{
    // Validate authentication
    if (!Auth::check()) {
        return redirect()->route('login')->with('error', 'Please login to perform this action.');
    }

    try {
        DB::transaction(function () use ($id) {
            // Lock and find the money transfer record
            $moneyTransfer = MoneyTransfer::lockForUpdate()->find($id);
            
            if (!$moneyTransfer) {
                throw new \Exception('Money transfer record not found.');
            }

            // Verify the transfer hasn't been reversed already
            if ($moneyTransfer->status === 'reversed') {
                throw new \Exception('This transfer has already been reversed.');
            }

            $amount = $moneyTransfer->amount;
            
            // Lock and find related transactions
            $senderTransaction = Transaction::lockForUpdate()->find($moneyTransfer->from);
            $receiverTransaction = Transaction::lockForUpdate()->find($moneyTransfer->to);

            if (!$senderTransaction || !$receiverTransaction) {
                throw new \Exception('Associated accounts not found.');
            }

            // Verify receiver has sufficient balance for reversal
            if ($receiverTransaction->amount < $amount) {
                throw new \Exception('Insufficient balance in receiver account to reverse this transfer.');
            }

            // Reverse the transaction
            $senderTransaction->amount += $amount;
            $receiverTransaction->amount -= $amount;

            // Update account balances if they exist
            if ($senderTransaction->account_id) {
                $senderAccount = Account::lockForUpdate()->find($senderTransaction->account_id);
                if ($senderAccount) {
                    $senderAccount->current_balance += $amount;
                    $senderAccount->save();
                }
            }

            if ($receiverTransaction->account_id) {
                $receiverAccount = Account::lockForUpdate()->find($receiverTransaction->account_id);
                if ($receiverAccount) {
                    $receiverAccount->current_balance -= $amount;
                    $receiverAccount->save();
                }
            }

            // Save transactions
            $senderTransaction->save();
            $receiverTransaction->save();

            // Mark as reversed instead of deleting (for audit trail)
             
            $moneyTransfer->status = 'reversed';
            $moneyTransfer->reversed_at = now();
            $moneyTransfer->reversed_by = Auth::id();
            $moneyTransfer->save();
        });

        return redirect()
            ->route('moneytransfer.view')
            ->with('success', 'Money transfer reversed successfully.');

    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
        return redirect()
            ->route('moneytransfer.view')
            ->with('error', 'Record not found.');

    } catch (\Exception $e) {
        \Log::error('Money transfer reversal failed: ' . $e->getMessage());
        return redirect()
            ->route('moneytransfer.view')
            ->with('error', 'Reversal failed: ' . $e->getMessage());
    }
}
   
    public function add_expenditure_towards(Request $request){
        // Create a new Expenditure instance
        // dd($request->all());
        $expenditure = new Expenditure();
        $expenditure->name = $request->name;
        $expenditure->description = $request->description;
        
        // Assign the authenticated user's ID to the 'user_id' attribute
        $expenditure->user = Auth::id();

        // Save the expenditure to the database
        if($expenditure->save()){
            return redirect()->back()->with("success", "Expenditure toward added successfully");
        } else {
            return redirect()->back()->with("error", "Failed to add expenditure");
        }
    }

    public function addExpenditureMain(Request $request) {
        // dd($request->all());
        if(Auth::user()) {
            $method = Transaction::find($request->method);
    
            if($method->amount < $request->amount) {
                return redirect()->back()->with("error", "Insufficient balance");
            }
           

            DB::beginTransaction();
    
            try {
                $expenditureMain = new ExpenditureMain();
                $expenditureMain->company_name = $request->branch;
                $expenditureMain->date = $request->transaction_date;
                $expenditureMain->receive_from = $request->account_type;
                $expenditureMain->from_account = $request->from_account;
                $expenditureMain->toward = $request->selected_towards;
                $expenditureMain->amount = $request->amount;
                $expenditureMain->method = $request->method;
                $expenditureMain->remark = $request->remarks;
                $expenditureMain->user = Auth::id();
    
                $method->amount -= $request->amount;
           
                // Update account balances if they exist
                if ($method->account_id) {
                    $senderAccount = Account::lockForUpdate()->find($method->account_id);
                    if ($senderAccount) {
                        $senderAccount->current_balance -= $amount;
                        $senderAccount->save();
                    }
                }

                $expenditureMain->save();
                $method->save();
    
                DB::commit();
                return redirect()->back()->with("success", "Expenditure added successfully");
    
            } catch (\Exception $e) {
                DB::rollBack();
                return redirect()->back()->with("error", "Failed to add expenditure: " . $e->getMessage());
            }
        } else {
            return view('welcome');
        }
    }

    public function expenditure_report()
    {
        if(Auth::user()){

            $expenditures = Expenditure::where([
                ['user', Auth::id()],
            ])->get();

            return view('report.expenditure.index', compact('expenditures'));
        }
        else{
            return view('welcome');
        }
       
    }

   

    public function expenditure_report_result(Request $request)
    {
        if (Auth::user()) {

            // dd($request->all());
            // Get the start and end dates from the request
            $startDate = $request->input('start_date');
            $endDate = $request->input('end_date');
            $towards = $request->input('towards');

            // Fetch expenditures based on the filters
            $query = ExpenditureMain::query();

            // Convert the dates to MySQL format (Y-m-d)
            // Convert the dates from 'MM/DD/YYYY' to MySQL 'Y-m-d' format and handle errors
            if ($startDate) {
                $startDate = (new DateTime($startDate))->format('Y-m-d');
            }
            if ($endDate) {
                $endDate = (new DateTime($endDate))->format('Y-m-d');
            }

            // Handle date filtering
            if ($startDate && $endDate) {
                // Filter records between startDate and endDate
                $query->whereDate('date', '>=', $startDate)
                    ->whereDate('date', '<=', $endDate);
            } elseif ($startDate) {
                // Filter records from startDate onwards
                $query->whereDate('date', '>=', $startDate);
            } elseif ($endDate) {
                // Filter records up to endDate
                $query->whereDate('date', '<=', $endDate);
            }

            // Handle 'toward' filter
            if ($towards) {
                $query->where('toward', $towards);
            }

            // Retrieve expenditures
            $expenditures = $query->get();

            // Map expenditures with the correct 'toward' and 'method' values
            foreach ($expenditures as $expenditure) {
                $expenditure->toward = Expenditure::where('id', $expenditure->toward)->value('name');
                $expenditure->method = Transaction::find($expenditure->method)->value('name');
            }

            // Render the view and return as JSON
            $html = ViewFacade::make('report.expenditure.report_result', [
                'startdate' => $startDate,
                'enddate' => $endDate,
                'expenditures' => $expenditures
            ])->render();

            return response()->json(['html' => $html]);
        } else {
            return view('welcome');
        }
    }
    
    public function destroyExpenditureMain($id)
    {
        // Check authentication first
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please login to perform this action.');
        }

        DB::beginTransaction();

        try {
            // Lock and find the expenditure record
            $expenditureMain = ExpenditureMain::lockForUpdate()->find($id);
            
            if (!$expenditureMain) {
                throw new \Exception('Expenditure record not found.');
            }

            // Authorization check
            if ($expenditureMain->user_id != Auth::id()) {
                throw new \Exception('You are not authorized to delete this expenditure.');
            }

            // Lock and find the transaction method
            $method = Transaction::lockForUpdate()->find($expenditureMain->method);
            if (!$method) {
                throw new \Exception('Associated transaction method not found.');
            }

            $amount = $expenditureMain->amount;

            // Restore the balance
            $method->amount += $amount;
            $method->save();

            // Update account balance if exists
            if ($method->account_id) {
                $account = Account::lockForUpdate()->find($method->account_id);
                if ($account) {
                    $account->current_balance += $amount;
                    $account->save();
                }
            }

            // Soft delete if using, otherwise hard delete
            if (method_exists($expenditureMain, 'trashed')) {
                $expenditureMain->delete();
            } else {
                $expenditureMain->forceDelete();
            }

            DB::commit();

            return redirect()
                ->back()
                ->with('success', 'Expenditure deleted and balance restored successfully.');

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->with('error', 'Record not found.');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Expenditure deletion failed: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
                'expenditure_id' => $id,
                'exception' => $e
            ]);
            
            return redirect()
                ->back()
                ->with('error', 'Failed to delete expenditure: ' . $e->getMessage());
        }
    }
        


}
