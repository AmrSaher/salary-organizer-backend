<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class ProfilesController extends Controller
{
    public function update(Request $request)
    {
        $attributes = $request->validate([
            'first_name' => ['required', 'string'],
            'last_name' => ['required', 'string'],
            'birth_date' => ['required', 'date'],
            'salary' => ['required', 'numeric', 'min:1000'],
        ]);

        $request->user()->profile()->update($attributes);

        return response()->json([
            'message' => 'Profile updated successfully!',
        ], 200);
    }
}
