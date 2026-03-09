# Inactive Products Debug Guide

## Issue
When admin deactivates all products from Settings page, they disappear from the Admin Products table instead of showing with "Inactive" status.

## Backend Verification ✅

### 1. Database Schema
- **File:** `database/migrations/2026_03_07_110928_create_products_table.php`
- **Line 28:** `$table->boolean('active')->default(true);`
- ✅ Column exists with correct type

### 2. Product Model
- **File:** `app/Models/Product.php`
- **Line 27:** `'active'` in fillable array
- **Line 41:** `'active' => 'boolean'` in casts
- ✅ Model configured correctly

### 3. API Controller Logic
- **File:** `app/Http/Controllers/Api/ProductController.php`
- **Lines 19-30:** Admin/Merchant check logic
```php
$user = $request->user();
$isAdminOrMerchant = $user && ($user->role === 'admin' || $user->role === 'merchant');

if (!$isAdminOrMerchant) {
    $query->where('active', true);
}
```
- ✅ Admins and merchants should see ALL products (active + inactive)
- ✅ Only non-authenticated users and regular users see only active products

### 4. Logging Added
- Lines 22-26: Logs user info when API is called
- Lines 73-77: Logs product counts (total, active, inactive)

## Frontend Verification ✅

### 1. Products Page
- **File:** `frontend/src/views/dashboard/admin/Products.vue`
- **Lines 193-212:** Filtering logic
```javascript
const filteredProducts = computed(() => {
  const matchesStatus = !statusFilter.value || 
    (statusFilter.value === 'active' && p.active) || 
    (statusFilter.value === 'inactive' && !p.active)
  return matchesSearch && matchesCategory && matchesStatus
})
```
- ✅ When statusFilter is empty, ALL products should show
- ✅ Debug logging added to track data flow

### 2. Debug Logging Added
- Lines 160-163: Logs loaded products count (total, active, inactive)
- Lines 197-210: Logs filter values and filtered results

## Testing Steps

### Step 1: Check Backend Logs
1. Navigate to: `backend/storage/logs/laravel.log`
2. Clear the log file or note the current position
3. In browser, go to: `http://localhost:3000/dashboard/admin/products`
4. Check the log file for entries like:
```
Products API called
user_id: X
user_role: admin
is_admin_or_merchant: true

Products returned
total: X
active_count: Y
inactive_count: Z
```

### Step 2: Check Frontend Console
1. Open browser DevTools (F12)
2. Go to Console tab
3. Navigate to: `http://localhost:3000/dashboard/admin/products`
4. Look for console output:
```
Loaded products: [...]
Total products: X
Active products: Y
Inactive products: Z
Filtering products with: {statusFilter: "", ...}
Filtered products count: X
```

### Step 3: Test Deactivate All
1. Go to: `http://localhost:3000/dashboard/admin/settings`
2. Click "✕ Deactivate All" under Products
3. Wait for success message
4. Navigate to: `http://localhost:3000/dashboard/admin/products`
5. Refresh the page (F5)
6. Check console output again

## Expected Behavior

**After deactivating all products:**
- Backend log should show: `inactive_count: X` (where X > 0)
- Frontend console should show: `Inactive products: X` (where X > 0)
- Frontend console should show: `Filtered products count: X` (matching total)
- Products table should display all products with red "Inactive" badges

## Possible Issues & Solutions

### Issue 1: Backend returns 0 inactive products
**Cause:** Authentication issue - user not recognized as admin
**Solution:** Check `users` table, verify user role is 'admin'
**SQL Check:**
```sql
SELECT id, name, email, role FROM users WHERE id = YOUR_USER_ID;
```

### Issue 2: Frontend shows 0 filtered products but has inactive products
**Cause:** Frontend filtering bug
**Solution:** Check statusFilter value in console - should be empty string ""

### Issue 3: Products not updating after bulk deactivate
**Cause:** Cache or stale data
**Solution:** 
- Hard refresh browser (Ctrl+Shift+R)
- Clear browser cache
- Check if bulk-status API actually succeeded

## Quick SQL Checks

### Check if products exist and their status:
```sql
SELECT id, name, active FROM products LIMIT 10;
```

### Count active vs inactive:
```sql
SELECT 
  COUNT(*) as total,
  SUM(CASE WHEN active = 1 THEN 1 ELSE 0 END) as active_count,
  SUM(CASE WHEN active = 0 THEN 1 ELSE 0 END) as inactive_count
FROM products;
```

### Manually deactivate all products (for testing):
```sql
UPDATE products SET active = 0;
```

### Manually activate all products (to restore):
```sql
UPDATE products SET active = 1;
```

## Next Steps

1. Run the testing steps above
2. Share the console output and log entries
3. Based on the output, we can identify the exact issue:
   - If backend returns inactive products → Frontend filtering issue
   - If backend doesn't return inactive products → Authentication or query issue
   - If no products returned at all → Database or API connection issue
