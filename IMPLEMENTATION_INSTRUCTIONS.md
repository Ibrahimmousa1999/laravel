# Admin Settings API - Implementation Instructions

## ✅ Files Created

I've created the following files for you:

1. **`app/Http/Controllers/AdminController.php`** - Complete controller with all 10 methods
2. **`routes/admin_settings_routes.php`** - All route definitions
3. **`ADMIN_API_ENDPOINTS.md`** - Complete API documentation

## 🚀 Quick Setup (3 Steps)

### Step 1: Update your `routes/api.php`

Add these lines to your `routes/api.php` file:

```php
// Admin Settings Routes
Route::middleware(['auth:sanctum'])->prefix('admin')->group(function () {
    // Check if user is admin
    Route::middleware(function ($request, $next) {
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized. Admin access required.'], 403);
        }
        return $next($request);
    })->group(function () {
        require __DIR__.'/admin_settings_routes.php';
    });
});
```

**OR** copy the routes directly from `routes/admin_settings_routes.php` into your `routes/api.php`.

### Step 2: Verify the Controller

The `AdminController.php` file has been created at:
```
backend/app/Http/Controllers/AdminController.php
```

Make sure it exists and has all 10 methods.

### Step 3: Test the API

Run this command to see all routes:
```bash
php artisan route:list --path=admin
```

You should see 10 new routes:
- 4 PUT routes for bulk-status
- 6 DELETE routes for reset

## 🧪 Testing

Test with curl (replace YOUR_TOKEN with your admin token):

```bash
# Test bulk status
curl -X PUT http://localhost:8000/api/admin/bulk-status/merchants \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{"active": true}'

# Test reset
curl -X DELETE http://localhost:8000/api/admin/reset/users \
  -H "Authorization: Bearer YOUR_TOKEN"
```

## 📋 All Endpoints

### Bulk Status (PUT)
- `/api/admin/bulk-status/users`
- `/api/admin/bulk-status/merchants`
- `/api/admin/bulk-status/products`
- `/api/admin/bulk-status/categories`

### Reset/Delete (DELETE)
- `/api/admin/reset/users`
- `/api/admin/reset/merchants`
- `/api/admin/reset/products`
- `/api/admin/reset/categories`
- `/api/admin/reset/orders`
- `/api/admin/reset/all`

## ⚠️ Important Notes

1. **Authentication Required**: All endpoints require `auth:sanctum` middleware
2. **Admin Only**: User must have `role === 'admin'`
3. **Database Columns**: Make sure your tables have an `active` column (boolean/tinyint)
4. **Cascade Deletes**: Configure foreign key constraints if needed

## 🔧 Troubleshooting

**If routes not found:**
1. Clear route cache: `php artisan route:clear`
2. Clear config cache: `php artisan config:clear`
3. Check if AdminController exists
4. Verify routes are registered in api.php

**If 403 Unauthorized:**
1. Check if user is authenticated
2. Verify user role is 'admin'
3. Check token is valid

**If 500 Server Error:**
1. Check Laravel logs: `storage/logs/laravel.log`
2. Verify database columns exist
3. Check model relationships

## ✨ Done!

After completing these steps, your Admin Settings page will be fully functional with all activate/deactivate and reset features working.
