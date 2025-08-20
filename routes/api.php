<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CarController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Public routes
Route::prefix('v1')->group(function () {
    // Auth routes with rate limiting
    Route::post('/register', [AuthController::class, 'register'])->middleware('rate.limit:5,1');
    Route::post('/login', [AuthController::class, 'login'])->middleware('rate.limit:5,1');
    
    // Car routes (public)
    Route::get('/cars', [CarController::class, 'index']);
    Route::get('/cars/{id}', [CarController::class, 'show']);
    Route::get('/cars/{carId}/models', [CarController::class, 'models']);
    Route::get('/models/{modelId}/variants', [CarController::class, 'variants']);
    Route::get('/variants/{id}', [CarController::class, 'variant']);
    Route::get('/accessories/{id}', [CarController::class, 'accessory']);
    Route::get('/search', [CarController::class, 'search']);
    
    // Inventory public endpoints for stock info
    \App\Http\Controllers\Api\InventoryController::class;
    Route::get('/inventory/count', [\App\Http\Controllers\Api\InventoryController::class, 'count']);
    Route::get('/inventory/by-variant', [\App\Http\Controllers\Api\InventoryController::class, 'byVariant']);
    
    // Protected routes
    Route::middleware('auth:sanctum')->group(function () {
        // Auth
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/user', [AuthController::class, 'user']);
        
        // Orders - TODO: Refactor to work with new CarVariant/Accessory structure
        // Route::get('/orders', [OrderController::class, 'index']);
        // Route::get('/orders/{id}', [OrderController::class, 'show']);
        // Route::post('/orders', [OrderController::class, 'store']);
        // Route::post('/orders/{id}/cancel', [OrderController::class, 'cancel']);
        // Route::get('/orders/{id}/track', [OrderController::class, 'track']);
    });
}); 