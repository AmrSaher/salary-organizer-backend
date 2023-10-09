<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }
    
    public function store(Request $request)
    {
        $attrs = $request->validate([
            'title' => ['required', 'string'],
            'cost' => ['required', 'numeric'],
            'color' => ['required', 'string'],
            'icon' => ['required', 'string'],
            'isIncome' => ['boolean'],
        ]);

        Transaction::create([
            ...$attrs,
            'user_id' => $request->user()->id,
        ]);

        return response()->json([
            'message' => 'Transaction created successfully!',
        ]);
    }

    public function destroy(Request $request, Transaction $transaction)
    {
        if ($request->user()->id != $transaction->user_id) return response()->json([
            'message' => 'Fuck you',
        ]);

        $transaction->delete();
        return response()->json([
            'message' => 'Transaction deleted successfully!',
        ]);
    }
}
