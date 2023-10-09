<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\ExpensesController;
use App\Http\Controllers\ProfilesController;
use App\Http\Controllers\TransactionsController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('api')->group(function () {
    // Auth
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::get('user', [AuthController::class, 'show']);

    // Profile
    Route::put('profile', [ProfilesController::class, 'update']);

    // Categories
    Route::post('categories', [CategoriesController::class, 'store']);
    Route::delete('categories/{category}', [CategoriesController::class, 'destroy']);

    // Expenses
    Route::get('expenses/{category}', [ExpensesController::class, 'index']);
    Route::post('expenses/{category}', [ExpensesController::class, 'store']);
    Route::delete('expenses/{expense}', [ExpensesController::class, 'destroy']);

    // Transactions
    Route::post('transactions', [TransactionsController::class, 'store']);
});