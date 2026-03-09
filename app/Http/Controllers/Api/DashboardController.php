<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Product;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    // Admin dashboard stats
    public function adminStats(Request $request)
    {
        if ($request->user()->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $stats = [
            'total_users' => User::where('role', 'user')->count(),
            'total_merchants' => User::where('role', 'merchant')->count(),
            'total_products' => Product::count(),
            'total_orders' => Order::count(),
            'total_revenue' => Order::where('status', '!=', 'cancelled')->sum('total'),
            'recent_orders' => Order::with(['user', 'items.product'])
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get(),
            'top_products' => Product::withCount('orderItems')
                ->orderBy('order_items_count', 'desc')
                ->limit(5)
                ->get()
        ];

        return response()->json($stats);
    }

    // Merchant dashboard stats
    public function merchantStats(Request $request)
    {
        if ($request->user()->role !== 'merchant') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $merchantId = $request->user()->id;

        $stats = [
            'total_products' => Product::where('user_id', $merchantId)->count(),
            'active_products' => Product::where('user_id', $merchantId)->where('active', true)->count(),
            'total_orders' => Order::whereHas('items.product', function($q) use ($merchantId) {
                $q->where('user_id', $merchantId);
            })->count(),
            'total_revenue' => Order::whereHas('items.product', function($q) use ($merchantId) {
                $q->where('user_id', $merchantId);
            })->where('status', '!=', 'cancelled')->sum('total'),
            'recent_orders' => Order::with(['user', 'items.product'])
                ->whereHas('items.product', function($q) use ($merchantId) {
                    $q->where('user_id', $merchantId);
                })
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get(),
            'low_stock_products' => Product::where('user_id', $merchantId)
                ->where('stock', '<', 10)
                ->orderBy('stock', 'asc')
                ->limit(5)
                ->get()
        ];

        return response()->json($stats);
    }

    // User dashboard stats
    public function userStats(Request $request)
    {
        $userId = $request->user()->id;

        $stats = [
            'total_orders' => Order::where('user_id', $userId)->count(),
            'pending_orders' => Order::where('user_id', $userId)->where('status', 'pending')->count(),
            'completed_orders' => Order::where('user_id', $userId)->where('status', 'delivered')->count(),
            'total_spent' => Order::where('user_id', $userId)->where('status', '!=', 'cancelled')->sum('total'),
            'recent_orders' => Order::with(['items.product'])
                ->where('user_id', $userId)
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get()
        ];

        return response()->json($stats);
    }
}
