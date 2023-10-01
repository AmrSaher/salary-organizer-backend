<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoriesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function store(Request $request)
    {
        $attrs = $request->validate([
            'title' => ['required', 'string'],
            'color' => ['required', 'string'],
            'icon' => ['required', 'string'],
        ]);

        // Check if user has this category
        if ($request->user()->categories()->where('title', $request->input('title'))->first()) {
            return response()->json([
                'errors' => [
                    'title' => [
                        'This category is already exist.',
                    ],
                ],
            ], 401);
        }

        $request->user()->categories()->create($attrs);

        return response()->json([
            'message' => 'Category created successfully!',
        ]);
    }
}
