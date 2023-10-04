<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Expense;

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
}
