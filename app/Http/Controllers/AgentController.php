<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\Agent;
use Illuminate\Support\Facades\Auth; // Add this line
use App\Http\Controllers\ReportController;


class AgentController extends Controller
{
  
    public function index(ReportController $reportController)
    {
        if (Auth::user()) {
            $user = Auth::id();
            $agents = Agent::where([
                ['is_delete', 0],
                ['is_active', 1],
                ['user', $user]
            ])->get();
            
            // First make the method public in ReportController
            $processedData = $reportController->processTransactions($agents, 'agent', request());
              // Map due amounts to agents
            $agents = $agents->map(function ($agent) use ($processedData) {
                $agentData = collect($processedData)->firstWhere('agent_supplier_id', $agent->id);
                $agent->due_amount = $agentData['due_amount'] ?? 0;
                
                return $agent;
            });
            
            // For debugging
            // dd($agents);
            
            return view('agent/index', compact('agents'));
        }
        
        return view('welcome');
    }

    public function store(Request $request)
    {
        if(Auth::user()){
            $agent = new Agent();
            $agent->name = $request->name;
            $agent->phone = $request->phone;
            $agent->email = $request->email;
            $agent->address = $request->address;
            $agent->district = $request->district;
            $agent->country = $request->country;
            $agent->description = $request->description;
            $agent->opening_balance = $request->opening_balance;
            $agent['user'] = Auth::id();
            $agent->save();
            return redirect()->route('agent.view')->with('success', 'Agent added successfully');
        }
        else{
            return view('welcome');
        }
        
    }
    public function edit($id)
    {
        if(Auth::user()){
            $id = decrypt($id);
            $agent = Agent::findOrFail($id);
            return view('agent.edit', compact('agent'));
        }
        else{
            return view('welcome');
        }
        
    }
        public function update(Request $request, $id)
        {
            if(Auth::user()){
                   // dd($request->all(), $id);
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'phone' => 'required|string|max:20',
            ]);

            if($validatedData){
                $agent = Agent::find($id);
                $agent->name = $request->name;
                $agent->phone = $request->phone;
                $agent->email = $request->email;
                $agent->address = $request->address;
                $agent->district = $request->district;
                $agent->country = $request->country;
                $agent->description = $request->description;
                $agent->opening_balance = $request->opening_balance;
                

                if($agent->save()){
                    return redirect()->route('agent.view')->with('success', 'Agent updated successfully');
                }
                else{
                    return redirect()->route('agent.view')->with('error', 'Agent updated failed');
                }
            }         

            return redirect()->route('agent.view')->with('error', 'Agent updated failed');
            }
            else{
                return view('welcome');
            }
         
        }

    public function delete($id)
    {
        if(Auth::user()){
            $agent = Agent::findOrFail($id);
            $agent->is_delete = 1;
            if($agent->save()){
                return redirect()->route('agent.view')->with('success', 'Agent deleted successfully');
            }
            else{
                return redirect()->route('agent.view')->with('error', 'Agent deleted failed');
            }
            return redirect()->route('agent.view')->with('error', 'Agent deleted failed');
        }
        else{
            return view('welcome');
        }
       
        
    }
}

?>