<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\Transaction;
use App\Models\Account;
use Illuminate\Support\Facades\Auth; // Add this line


class TransactionController extends Controller
{
    public function index()
    {
        if(Auth::user()){
            $user = Auth::id();
            // $transactions = Transaction::where([['is_delete',0],['is_active',1],['user',$user]])->get();
            $transactions = Transaction::with('account')
                ->where('user', $user)
                ->where('is_delete', 0)
                ->where('is_active', 1)
                 ->where(function($query) {
                    $query->whereNull('account_id') // Transactions without an account
                        ->orWhereHas('account', function($q) {
                            $q->where('is_delete', 0)
                                ->where('is_active', 1);
                        });
                })
                ->orderBy('created_at', 'desc')
                ->get();
            // dd($transactions);
            return view('transaction/index', compact('transactions'));
        }
        else{
            return view('welcome');
        }
    }

  

    public function store(Request $request)
    {
        // dd($request->all());
        // Validate common fields for all account types
        $request->validate([
            'account_type' => 'required|in:Cash,Bank,Mobile Banking,Credit Card',
            'account_name' => 'required|string|max:100',
        ]);

        // Additional validation based on account type
        switch ($request->account_type) {
            case 'Bank':
                $request->validate([
                    'account_number' => 'required|string|max:50',
                    'bank_name' => 'required|string|max:100',
                    'branch' => 'required|string|max:100',
                    'routing_no' => 'nullable|string|max:50',
                ]);
                break;
                
            case 'Mobile banking':
                $request->validate([
                    'mobile_account_number' => 'required|string|max:50',
                ]);
                break;
                
            case 'Credit Card':
                $request->validate([
                    'card_number' => 'required|string|max:20',
                    'card_csv' => 'required|string|max:5',
                    'card_expiry' => 'required|string|max:10',
                ]);
                break;
        }

        if (Auth::user()) {
            $account = new Account();
            $account->account_type = $request->account_type;
            $account->account_name = strtoupper($request->account_name);
            $account->current_balance = $request->current_balance;
            $account->user_id = Auth::id();
            
            // Set account-specific fields
            switch ($request->account_type) {
                case 'Bank':
                    $account->account_number = $request->account_number;
                    $account->bank_name = $request->bank_name;
                    $account->branch = $request->branch;
                    $account->routing_no = $request->routing_no;
                    break;
                    
                case 'Mobile banking':
                    $account->mobile_account_number = $request->mobile_account_number;
                    break;
                    
                case 'Credit Card':
                    $account->card_number = $request->card_number;
                    $account->card_csv = $request->card_csv;
                    $account->card_expiry = $request->card_expiry;
                    break;
            }
            
            $account->save();

            // Create initial transaction for the account
            $transaction = new Transaction();
            $transaction->account_id = $account->id;
            $transaction->transaction_type = $request->account_type;
            $transaction->name = $request->account_name;
            // $transaction->description = 'Account opening balance';
            $transaction->amount = $request->current_balance;
            // $transaction->account_number = $request->account_number;
            // $transaction->transaction_type = $request->current_balance >= 0 ? 'Deposit' : 'Withdrawal';
            $transaction->user = Auth::id();
            $transaction->save();

            return redirect()->route('transaction.view')->with('success', 'Account created successfully');
        }

        return redirect()->route('login');
    }

    public function edit($id)
    {
        if (Auth::user()) {
            $transaction = Transaction::with('account')
                    ->where('id', $id)
                    ->where(function($query) {
                        $query->whereNull('account_id') // Transactions without an account
                            ->orWhereHas('account', function($q) {
                                $q->where('is_delete', 0)
                                    ->where('is_active', 1);
                            });
                    })->first();
            $accounts = Account::where('user_id', Auth::id())
                            ->where('is_delete', 0)
                            ->where('is_active', 1)
                            ->get();
            // dd($transaction[0]);
            return view('transaction.edit', compact('transaction', 'accounts'));
        }
        
        return redirect()->route('login');
    }

    public function update(Request $request, $id)
    {
        // dd($request->all());
        if (!Auth::user()) {
            return redirect()->route('login');
        }

        // Validate common fields for all account types
        $validatedData = $request->validate([
            'account_type' => 'required|in:Cash,Bank,Mobile Banking,Credit Card',
            'account_name' => 'required|string|max:100',
            
        ]);

        // Additional validation based on account type
        switch ($request->account_type) {
            case 'Bank':
                $request->validate([
                    'account_number' => 'required|string|max:50',
                    'bank_name' => 'required|string|max:100',
                    'branch' => 'required|string|max:100',
                    'routing_no' => 'nullable|string|max:50',
                ]);
                break;
                
            case 'Mobile banking':
                $request->validate([
                    'mobile_account_number' => 'required|string|max:50',
                ]);
                break;
                
            case 'Credit Card':
                $request->validate([
                    'card_number' => 'required|string|max:20',
                    'card_csv' => 'required|string|max:20',
                    'card_expiry' => 'required|string|max:10',
                ]);
                break;
        }

        try {
            \DB::beginTransaction();

            // Update the transaction
            $transaction = Transaction::findOrFail($id);
            $transaction->name = $request->account_name;
            $transaction->amount = $request->current_balance;
            $transaction->transaction_type = $request->account_type;
          
            $transaction->updated_at = now(); // Set current timestamp
            if (!$transaction->save()) {
                    throw new \Exception('Failed to save transaction');
            }
            // Update the associated account
            if ($transaction->account_id) {
                $account = Account::findOrFail($transaction->account_id);
                $account->account_name = strtoupper($request->account_name);
                $account->account_type = ($request->account_type);
                $account->current_balance = $request->current_balance;
                $account->account_number = $request->account_number ?? null;
                $account->bank_name = $request->bank_name ?? null;
                $account->branch = $request->branch ?? null;
                $account->routing_no = $request->routing_no ?? null;
                $account->mobile_account_number = $request->mobile_account_number ?? null;
                $account->card_number = $request->card_number ?? null;
                $account->card_csv = $request->card_csv ?? null;
                $account->card_expiry = $request->card_expiry ?? null;
                $account->updated_at = now();

                if (!$account->save()) {
                throw new \Exception('Failed to save account');
            }
            }

            \DB::commit();

            return redirect()->route('transaction.view')
                ->with('success', 'Transaction updated successfully');
                
        } catch (\Exception $e) {
            \DB::rollBack();
            return redirect()->back()
                ->with('error', 'Transaction update failed: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function delete($id)
    {
        if(Auth::user()){
            $transaction = Transaction::findOrFail($id);
            $transaction->is_delete = 1;
            if($transaction->save()){
                return redirect()->route('transaction.view')->with('success', 'Transaction deleted successfully');
            }
            else{
                return redirect()->route('transaction.view')->with('error', 'Transaction deleted failed');
            }
            return redirect()->route('transaction.view')->with('error', 'Transaction deleted failed');
        }
        else{
            return view('welcome');
        }
        
    }
}

?>