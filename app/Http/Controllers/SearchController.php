<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->input('q');
        
        if (strlen($query) < 3) {
            return response()->json([]);
        }

        // Search across multiple tables
        $results = [];
        
        // Example: Search users
        $users = DB::table('users')
            ->where('name', 'like', "%{$query}%")
            ->orWhere('email', 'like', "%{$query}%")
            ->select('id', 'name', 'email')
            ->limit(5)
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'name' => $item->name,
                    'passport' => '',
                    'link' => route('profile.edit', $item->id),
                    'type' => 'User'
                ];
            });

        $orders = DB::table('order')
            ->where('user', '=', auth()->id())
            ->where('is_active', '=', 1)
            ->where('is_delete', '=', 0)
            ->where('passport_no', 'like', "%{$query}%")
            ->orWhere('name', 'like', "%{$query}%")
            ->orWhere('invoice', 'like', "%{$query}%")
            ->select('id', 'invoice', 'passport_no')
            ->limit(5)
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'name' => $item->invoice,
                    'passport' => $item->passport_no,
                    'link' => route('order.show', $item->id),
                    'type' => 'Order'
                ];
            });
        $tickets = DB::table('tickets')
            ->where('user', '=', auth()->id())
            ->where('is_active', '=', 1)
            ->where('is_delete', '=', 0)
            ->where('ticket_no', 'like', "%{$query}%")
            ->orWhere('passenger', 'like', "%{$query}%")
            ->orWhere('invoice', 'like', "%{$query}%")
            ->select('id', 'invoice', 'ticket_no')
            ->limit(5)
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'name' => $item->invoice,
                    'ticket' => $item->ticket_no,
                    'link' => route('ticket_view', $item->id),
                    'type' => 'Tickets'
                ];
            });
        
        $results = array_merge($results, $users->toArray(), $orders->toArray(), $tickets->toArray());
        // dd($results);
        // Add more tables as needed (products, posts, etc.)
        
        return response()->json($results);
    }
}