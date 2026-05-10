<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\MenuCategoryController;
use App\Http\Controllers\Api\ExpenseController;
use App\Http\Controllers\Api\AppSettingController;

Route::get('/ping', fn() => response()->json(['status' => 'ok', 'time' => now()]));

Route::post('/auth/login',    [AuthController::class, 'login']);
Route::post('/auth/register', [AuthController::class, 'register']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/auth/logout', [AuthController::class, 'logout']);

    // Products
    Route::get('/products',            [ProductController::class, 'index']);
    Route::post('/products',           [ProductController::class, 'store']);
    Route::put('/products/{id}',       [ProductController::class, 'update']);
    Route::delete('/products/{id}',    [ProductController::class, 'destroy']);
    Route::put('/products/{id}/promo', [ProductController::class, 'setPromo']);

    // Menu Categories
    Route::get('/menu-categories',     [MenuCategoryController::class, 'index']);

    // Customers
    Route::get('/customers',              [CustomerController::class, 'index']);
    Route::post('/customers',             [CustomerController::class, 'upsert']);
    Route::get('/customers/{id}/orders',  [CustomerController::class, 'orders']);
    Route::get('/customers/{id}/points',  [CustomerController::class, 'points']);

    // Orders
    Route::get('/orders',              [OrderController::class, 'index']);
    Route::post('/orders',             [OrderController::class, 'store']);
    Route::get('/orders/pending',      [OrderController::class, 'pending']);
    Route::get('/orders/{id}/items',   [OrderController::class, 'items']);
    Route::post('/orders/{id}/settle', [OrderController::class, 'settle']);
    Route::post('/orders/{id}/invoice',[OrderController::class, 'uploadInvoice']);

    // Expenses
    Route::get('/expenses',            [ExpenseController::class, 'index']);
    Route::post('/expenses',           [ExpenseController::class, 'store']);
    Route::put('/expenses/{id}',       [ExpenseController::class, 'update']);
    Route::delete('/expenses/{id}',    [ExpenseController::class, 'destroy']);

    // Settings
    Route::get('/settings/{key}',      [AppSettingController::class, 'show']);
    Route::post('/settings',           [AppSettingController::class, 'upsert']);

    // Dashboard & Users
    Route::get('/dashboard',           [DashboardController::class, 'index']);
    Route::get('/users',               [UserController::class, 'index']);
    Route::put('/users/{id}',          [UserController::class, 'update']);
    Route::delete('/users/{id}',       [UserController::class, 'destroy']);
});