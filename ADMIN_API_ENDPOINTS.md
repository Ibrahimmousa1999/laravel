# Admin Settings API Endpoints Documentation

This document lists all the API endpoints required for the Admin Settings page functionality.

## Base URL
All endpoints are prefixed with `/api/admin/`

## Authentication
All endpoints require admin authentication. Add middleware: `auth:sanctum` and check `user()->role === 'admin'`

---

## 1. Bulk Status Management Endpoints

### 1.1 Bulk Update Users Status
**Endpoint:** `PUT /api/admin/bulk-status/users`

**Request Body:**
```json
{
  "active": true  // or false
}
```

**Controller Method Example:**
```php
public function bulkStatusUsers(Request $request)
{
    $active = $request->input('active');
    
    User::where('role', 'user')->update(['active' => $active]);
    
    return response()->json([
        'message' => 'Users status updated successfully',
        'count' => User::where('role', 'user')->count()
    ]);
}
```

---

### 1.2 Bulk Update Merchants Status
**Endpoint:** `PUT /api/admin/bulk-status/merchants`

**Request Body:**
```json
{
  "active": true  // or false
}
```

**Controller Method Example:**
```php
public function bulkStatusMerchants(Request $request)
{
    $active = $request->input('active');
    
    User::where('role', 'merchant')->update(['active' => $active]);
    
    return response()->json([
        'message' => 'Merchants status updated successfully',
        'count' => User::where('role', 'merchant')->count()
    ]);
}
```

---

### 1.3 Bulk Update Products Status
**Endpoint:** `PUT /api/admin/bulk-status/products`

**Request Body:**
```json
{
  "active": true  // or false
}
```

**Controller Method Example:**
```php
public function bulkStatusProducts(Request $request)
{
    $active = $request->input('active');
    
    Product::query()->update(['active' => $active]);
    
    return response()->json([
        'message' => 'Products status updated successfully',
        'count' => Product::count()
    ]);
}
```

---

### 1.4 Bulk Update Categories Status
**Endpoint:** `PUT /api/admin/bulk-status/categories`

**Request Body:**
```json
{
  "active": true  // or false
}
```

**Controller Method Example:**
```php
public function bulkStatusCategories(Request $request)
{
    $active = $request->input('active');
    
    Category::query()->update(['active' => $active]);
    
    return response()->json([
        'message' => 'Categories status updated successfully',
        'count' => Category::count()
    ]);
}
```

---

## 2. Reset/Delete Data Endpoints

### 2.1 Reset Users
**Endpoint:** `DELETE /api/admin/reset/users`

**Controller Method Example:**
```php
public function resetUsers()
{
    $count = User::where('role', 'user')->count();
    User::where('role', 'user')->delete();
    
    return response()->json([
        'message' => 'Users reset successfully',
        'deleted_count' => $count
    ]);
}
```

---

### 2.2 Reset Merchants
**Endpoint:** `DELETE /api/admin/reset/merchants`

**Controller Method Example:**
```php
public function resetMerchants()
{
    $count = User::where('role', 'merchant')->count();
    
    // Delete merchant products first
    $merchantIds = User::where('role', 'merchant')->pluck('id');
    Product::whereIn('user_id', $merchantIds)->delete();
    
    // Delete merchants
    User::where('role', 'merchant')->delete();
    
    return response()->json([
        'message' => 'Merchants reset successfully',
        'deleted_count' => $count
    ]);
}
```

---

### 2.3 Reset Products
**Endpoint:** `DELETE /api/admin/reset/products`

**Controller Method Example:**
```php
public function resetProducts()
{
    $count = Product::count();
    Product::query()->delete();
    
    return response()->json([
        'message' => 'Products reset successfully',
        'deleted_count' => $count
    ]);
}
```

---

### 2.4 Reset Categories
**Endpoint:** `DELETE /api/admin/reset/categories`

**Controller Method Example:**
```php
public function resetCategories()
{
    $count = Category::count();
    Category::query()->delete();
    
    // Optional: Set product category_id to null
    Product::query()->update(['category_id' => null]);
    
    return response()->json([
        'message' => 'Categories reset successfully',
        'deleted_count' => $count
    ]);
}
```

---

### 2.5 Reset Orders
**Endpoint:** `DELETE /api/admin/reset/orders`

**Controller Method Example:**
```php
public function resetOrders()
{
    $count = Order::count();
    
    // Delete order items first
    OrderItem::query()->delete();
    
    // Delete orders
    Order::query()->delete();
    
    return response()->json([
        'message' => 'Orders reset successfully',
        'deleted_count' => $count
    ]);
}
```

---

### 2.6 Reset All Data
**Endpoint:** `DELETE /api/admin/reset/all`

**Controller Method Example:**
```php
public function resetAll()
{
    // Delete all data except admins
    OrderItem::query()->delete();
    Order::query()->delete();
    Product::query()->delete();
    Category::query()->delete();
    User::whereIn('role', ['user', 'merchant'])->delete();
    
    return response()->json([
        'message' => 'All data reset successfully',
        'note' => 'Admin accounts preserved'
    ]);
}
```

---

## 3. Routes Registration

Add these routes to your `routes/api.php` file:

```php
// Admin Settings Routes (require admin middleware)
Route::middleware(['auth:sanctum', 'admin'])->prefix('admin')->group(function () {
    
    // Bulk Status Management
    Route::put('bulk-status/users', [AdminController::class, 'bulkStatusUsers']);
    Route::put('bulk-status/merchants', [AdminController::class, 'bulkStatusMerchants']);
    Route::put('bulk-status/products', [AdminController::class, 'bulkStatusProducts']);
    Route::put('bulk-status/categories', [AdminController::class, 'bulkStatusCategories']);
    
    // Reset/Delete Data
    Route::delete('reset/users', [AdminController::class, 'resetUsers']);
    Route::delete('reset/merchants', [AdminController::class, 'resetMerchants']);
    Route::delete('reset/products', [AdminController::class, 'resetProducts']);
    Route::delete('reset/categories', [AdminController::class, 'resetCategories']);
    Route::delete('reset/orders', [AdminController::class, 'resetOrders']);
    Route::delete('reset/all', [AdminController::class, 'resetAll']);
});
```

---

## 4. Admin Middleware Example

Create `app/Http/Middleware/AdminMiddleware.php`:

```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            return response()->json([
                'message' => 'Unauthorized. Admin access required.'
            ], 403);
        }

        return $next($request);
    }
}
```

Register in `app/Http/Kernel.php`:
```php
protected $middlewareAliases = [
    // ...
    'admin' => \App\Http\Middleware\AdminMiddleware::class,
];
```

---

## 5. Complete Controller Example

Create or update `app/Http/Controllers/AdminController.php`:

```php
<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    // Bulk Status Methods
    public function bulkStatusUsers(Request $request)
    {
        $active = $request->input('active');
        User::where('role', 'user')->update(['active' => $active]);
        
        return response()->json([
            'message' => 'Users status updated successfully',
            'count' => User::where('role', 'user')->count()
        ]);
    }

    public function bulkStatusMerchants(Request $request)
    {
        $active = $request->input('active');
        User::where('role', 'merchant')->update(['active' => $active]);
        
        return response()->json([
            'message' => 'Merchants status updated successfully',
            'count' => User::where('role', 'merchant')->count()
        ]);
    }

    public function bulkStatusProducts(Request $request)
    {
        $active = $request->input('active');
        Product::query()->update(['active' => $active]);
        
        return response()->json([
            'message' => 'Products status updated successfully',
            'count' => Product::count()
        ]);
    }

    public function bulkStatusCategories(Request $request)
    {
        $active = $request->input('active');
        Category::query()->update(['active' => $active]);
        
        return response()->json([
            'message' => 'Categories status updated successfully',
            'count' => Category::count()
        ]);
    }

    // Reset Methods
    public function resetUsers()
    {
        $count = User::where('role', 'user')->count();
        User::where('role', 'user')->delete();
        
        return response()->json([
            'message' => 'Users reset successfully',
            'deleted_count' => $count
        ]);
    }

    public function resetMerchants()
    {
        $count = User::where('role', 'merchant')->count();
        $merchantIds = User::where('role', 'merchant')->pluck('id');
        Product::whereIn('user_id', $merchantIds)->delete();
        User::where('role', 'merchant')->delete();
        
        return response()->json([
            'message' => 'Merchants reset successfully',
            'deleted_count' => $count
        ]);
    }

    public function resetProducts()
    {
        $count = Product::count();
        Product::query()->delete();
        
        return response()->json([
            'message' => 'Products reset successfully',
            'deleted_count' => $count
        ]);
    }

    public function resetCategories()
    {
        $count = Category::count();
        Category::query()->delete();
        Product::query()->update(['category_id' => null]);
        
        return response()->json([
            'message' => 'Categories reset successfully',
            'deleted_count' => $count
        ]);
    }

    public function resetOrders()
    {
        $count = Order::count();
        OrderItem::query()->delete();
        Order::query()->delete();
        
        return response()->json([
            'message' => 'Orders reset successfully',
            'deleted_count' => $count
        ]);
    }

    public function resetAll()
    {
        OrderItem::query()->delete();
        Order::query()->delete();
        Product::query()->delete();
        Category::query()->delete();
        User::whereIn('role', ['user', 'merchant'])->delete();
        
        return response()->json([
            'message' => 'All data reset successfully',
            'note' => 'Admin accounts preserved'
        ]);
    }
}
```

---

## 6. Testing the Endpoints

Use these curl commands to test (replace TOKEN with your admin token):

```bash
# Test Bulk Status
curl -X PUT http://localhost:8000/api/admin/bulk-status/users \
  -H "Authorization: Bearer TOKEN" \
  -H "Content-Type: application/json" \
  -d '{"active": true}'

# Test Reset
curl -X DELETE http://localhost:8000/api/admin/reset/users \
  -H "Authorization: Bearer TOKEN"
```

---

## Summary

**Total Endpoints:** 10
- 4 Bulk Status endpoints (PUT)
- 6 Reset/Delete endpoints (DELETE)

All endpoints require admin authentication and return JSON responses with success messages and relevant counts.
