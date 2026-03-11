<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\ImageUploadController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\PageController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);
Route::get('/auth/google/redirect', [AuthController::class, 'googleRedirect']);
Route::get('/auth/google/callback', [AuthController::class, 'googleCallback']);

// Public product and category routes
Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{id}', [ProductController::class, 'show']);
Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/categories/{id}', [CategoryController::class, 'show']);

// Public pages routes
Route::get('/pages/active', [PageController::class, 'active']);
Route::get('/pages/{slug}', [PageController::class, 'show']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    // Auth routes
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::get('/auth/user', [AuthController::class, 'user']);

    // User profile routes
    Route::get('/profile', [UserController::class, 'profile']);
    Route::put('/profile', [UserController::class, 'updateProfile']);
    Route::delete('/profile', [UserController::class, 'deleteAccount']);

    // Image upload routes (for merchants and admins)
    Route::post('/upload/image', [ImageUploadController::class, 'upload']);
    Route::delete('/upload/image', [ImageUploadController::class, 'delete']);

    // Merchant routes
    Route::get('/merchant/products', [ProductController::class, 'myProducts']);

    // Product routes (merchants and admins)
    Route::post('/products', [ProductController::class, 'store']);
    Route::put('/products/{id}', [ProductController::class, 'update']);
    Route::delete('/products/{id}', [ProductController::class, 'destroy']);

    // Category routes (admins only)
    Route::post('/categories', [CategoryController::class, 'store']);
    Route::put('/categories/{id}', [CategoryController::class, 'update']);
    Route::delete('/categories/{id}', [CategoryController::class, 'destroy']);

    // Pages routes (admins only)
    Route::get('/pages', [PageController::class, 'index']);
    Route::post('/pages', [PageController::class, 'store']);
    Route::put('/pages/{id}', [PageController::class, 'update']);
    Route::delete('/pages/{id}', [PageController::class, 'destroy']);

    // Order routes
    Route::get('/orders', [OrderController::class, 'index']);
    Route::post('/orders', [OrderController::class, 'store']);
    Route::get('/orders/{id}', [OrderController::class, 'show']);
    Route::put('/orders/{id}', [OrderController::class, 'update']);
    Route::delete('/orders/{id}', [OrderController::class, 'destroy']);

    // Admin only - User management routes
    Route::get('/admin/users', [UserController::class, 'index']);
    Route::get('/admin/merchants', [UserController::class, 'getMerchants']);
    Route::post('/admin/merchants', [UserController::class, 'createMerchant']);
    Route::put('/admin/users/{id}', [UserController::class, 'update']);
    Route::delete('/admin/users/{id}', [UserController::class, 'destroy']);
    Route::post('/admin/users/{id}/toggle-active', [UserController::class, 'toggleActive']);

    // Dashboard statistics routes
    Route::get('/dashboard/admin/stats', [DashboardController::class, 'adminStats']);
    Route::get('/dashboard/merchant/stats', [DashboardController::class, 'merchantStats']);
    Route::get('/dashboard/user/stats', [DashboardController::class, 'userStats']);
});

// Admin Settings Routes (outside auth:sanctum to handle CORS properly)
Route::prefix('admin')->middleware(['auth:sanctum'])->group(function () {
    // Admin User Management
    Route::post('create-admin', [App\Http\Controllers\AdminController::class, 'createAdmin']);

    // Bulk Status Management
    Route::put('bulk-status/users', [App\Http\Controllers\AdminController::class, 'bulkStatusUsers']);
    Route::put('bulk-status/merchants', [App\Http\Controllers\AdminController::class, 'bulkStatusMerchants']);
    Route::put('bulk-status/products', [App\Http\Controllers\AdminController::class, 'bulkStatusProducts']);
    Route::put('bulk-status/categories', [App\Http\Controllers\AdminController::class, 'bulkStatusCategories']);

    // Reset/Delete Data
    Route::delete('reset/users', [App\Http\Controllers\AdminController::class, 'resetUsers']);
    Route::delete('reset/merchants', [App\Http\Controllers\AdminController::class, 'resetMerchants']);
    Route::delete('reset/products', [App\Http\Controllers\AdminController::class, 'resetProducts']);
    Route::delete('reset/categories', [App\Http\Controllers\AdminController::class, 'resetCategories']);
    Route::delete('reset/orders', [App\Http\Controllers\AdminController::class, 'resetOrders']);
    Route::delete('reset/all', [App\Http\Controllers\AdminController::class, 'resetAll']);
});
