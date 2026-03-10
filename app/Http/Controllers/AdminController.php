<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    // Check if user is admin
    private function checkAdmin()
    {
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized. Admin access required.');
        }
    }

    // Bulk Status Management Methods

    public function bulkStatusUsers(Request $request)
    {
        $this->checkAdmin();

        $active = $request->input('active');
        $count = User::where('role', 'user')->update(['active' => $active]);

        return response()->json([
            'message' => 'Users status updated successfully',
            'updated_count' => $count
        ]);
    }

    public function bulkStatusMerchants(Request $request)
    {
        $this->checkAdmin();

        $active = $request->input('active');
        $count = User::where('role', 'merchant')->update(['active' => $active]);

        return response()->json([
            'message' => 'Merchants status updated successfully',
            'updated_count' => $count
        ]);
    }

    public function bulkStatusProducts(Request $request)
    {
        $this->checkAdmin();

        $active = $request->input('active');
        // When admin changes status, lock it so merchants cannot override
        $count = Product::query()->update([
            'active' => $active,
            'admin_locked' => !$active  // Lock if deactivating, unlock if activating
        ]);

        return response()->json([
            'message' => 'Products status updated successfully',
            'updated_count' => $count
        ]);
    }

    public function bulkStatusCategories(Request $request)
    {
        $this->checkAdmin();

        $active = $request->input('active');
        $count = Category::query()->update(['active' => $active]);

        return response()->json([
            'message' => 'Categories status updated successfully',
            'updated_count' => $count
        ]);
    }

    // Reset/Delete Data Methods

    public function resetUsers()
    {
        $this->checkAdmin();

        $count = User::where('role', 'user')->count();
        User::where('role', 'user')->delete();

        return response()->json([
            'message' => 'Users reset successfully',
            'deleted_count' => $count
        ]);
    }

    public function resetMerchants()
    {
        $this->checkAdmin();

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

    public function resetProducts()
    {
        $this->checkAdmin();

        $count = Product::count();
        Product::query()->delete();

        return response()->json([
            'message' => 'Products reset successfully',
            'deleted_count' => $count
        ]);
    }

    public function resetCategories()
    {
        $this->checkAdmin();

        $count = Category::count();
        Category::query()->delete();

        // Set product category_id to null
        Product::query()->update(['category_id' => null]);

        return response()->json([
            'message' => 'Categories reset successfully',
            'deleted_count' => $count
        ]);
    }

    public function resetOrders()
    {
        $this->checkAdmin();

        $count = Order::count();

        // Delete orders (cascade should handle order_items if set up)
        Order::query()->delete();

        return response()->json([
            'message' => 'Orders reset successfully',
            'deleted_count' => $count
        ]);
    }

    public function resetAll()
    {
        $this->checkAdmin();

        // Delete all data except admins
        Order::query()->delete();
        Product::query()->delete();
        Category::query()->delete();
        User::whereIn('role', ['user', 'merchant'])->delete();

        return response()->json([
            'message' => 'All data reset successfully',
            'note' => 'Admin accounts preserved'
        ]);
    }

    // Create Admin User (Only accessible by existing admins)
    public function createAdmin(Request $request)
    {
        $this->checkAdmin();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'phone' => 'nullable|string|max:20',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'role' => 'admin',
            'active' => true,
        ]);

        return response()->json([
            'message' => 'Admin user created successfully',
            'user' => $user
        ], 201);
    }
}
