<?php

use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Settings API Routes
|--------------------------------------------------------------------------
|
| Add these routes to your routes/api.php file inside the admin middleware group
| Or include this file in your api.php using: require __DIR__.'/admin_settings_routes.php';
|
*/

// Bulk Status Management Routes
Route::put('bulk-status/users', [AdminController::class, 'bulkStatusUsers']);
Route::put('bulk-status/merchants', [AdminController::class, 'bulkStatusMerchants']);
Route::put('bulk-status/products', [AdminController::class, 'bulkStatusProducts']);
Route::put('bulk-status/categories', [AdminController::class, 'bulkStatusCategories']);

// Reset/Delete Data Routes
Route::delete('reset/users', [AdminController::class, 'resetUsers']);
Route::delete('reset/merchants', [AdminController::class, 'resetMerchants']);
Route::delete('reset/products', [AdminController::class, 'resetProducts']);
Route::delete('reset/categories', [AdminController::class, 'resetCategories']);
Route::delete('reset/orders', [AdminController::class, 'resetOrders']);
Route::delete('reset/all', [AdminController::class, 'resetAll']);
