<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Http\Request;

class ExpensesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index($category_id)
    {
        $expenses = Expense::where('category_id', $category_id)->get();

        return response()->json($expenses);
    }

    public function store(Request $request, $category_id)
    {
        $attrs = $request->validate([
            'title' => ['required', 'string'],
            'cost' => ['required', 'numeric'],
        ]);

        Expense::create([
            ...$attrs,
            'user_id' => $request->user()->id,
            'category_id' => $category_id
        ]);

        return response()->json([
            'message' => 'Expense created successfully!',
        ]);
    }

    public function destroy(Request $request, Expense $expense)
    {
        if ($request->user()->id != $expense->user_id) return response()->json([
            'message' => 'Fuck you',
        ]);

        $expense->delete();
        return response()->json([
            'message' => 'Expense deleted successfully!',
        ]);
    }
}
