<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', [
            'except' => [
                'login',
                'register',
            ],
        ]);
    }

    public function show(Request $request)
    {
        $user = User::with([
            'profile',
            'categories',
            'transactions',
        ])->where('id', $request->user()->id)->first();
        $user->profile->spend = 0;
        $user->profile->residual = 0;

        foreach ($user->expenses as $expense) {
            $user->profile->spend += $expense->cost;
        }
        foreach ($user->transactions as $transaction) {
            if ($transaction->isIncome) {
                $user->profile->residual += $transaction->cost;
            } else {
                $user->profile->spend += $transaction->cost;
            }
        }

        $user->profile->residual += $user->profile->salary - $user->profile->spend;

        return response()->json($user);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (!$token = Auth::attempt($credentials)) {
            return response()->json([
                'errors' => [
                    'email' => [
                        'Email address or password is wrong.',
                    ],
                ],
            ], 401);
        }

        return $this->respondWithToken($token);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        $credentials = $request->validate([
            'name' => ['required', 'string', 'unique:users,name'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        $user = User::create($credentials);

        Profile::create([
            'user_id' => $user->id,
        ]);

        if (!$token = Auth::attempt($credentials)) {
            return response()->json([
                'errors' => [
                    'email' => [
                        'Email address or password is wrong.',
                    ],
                ],
            ], 401);
        }

        return $this->respondWithToken($token);
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        Auth::logout();

        return response()->json([
            'message' => 'Successfully logged out',
        ]);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(Auth::refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => Auth::factory()->getTTL() * 60,
        ]);
    }
}
