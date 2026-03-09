<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['category', 'user']);

        // Check if user is authenticated
        $user = auth('sanctum')->user();

        \Log::info('Products API called', [
            'has_user' => $user !== null,
            'user_id' => $user?->id,
            'user_role' => $user?->role,
            'auth_header' => $request->header('Authorization') ? 'present' : 'missing'
        ]);

        // Only filter by active for non-authenticated users
        // Authenticated users (admin, merchant, user) see all products
        if (!$user) {
            $query->where('active', true);
        }

        if ($request->has('featured')) {
            $query->where('featured', true);
        }

        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->has('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->has('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        if ($request->has('sort')) {
            switch ($request->sort) {
                case 'price_asc':
                    $query->orderBy('price', 'asc');
                    break;
                case 'price_desc':
                    $query->orderBy('price', 'desc');
                    break;
                case 'popular':
                    $query->orderBy('sales', 'desc');
                    break;
                default:
                    $query->orderBy('created_at', 'desc');
            }
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $perPage = $request->get('per_page', 12);
        $products = $query->paginate($perPage);

        \Log::info('Products returned', [
            'total' => $products->total(),
            'active_count' => $query->clone()->where('active', true)->count(),
            'inactive_count' => $query->clone()->where('active', false)->count()
        ]);

        return response()->json($products);
    }

    // Get merchant's own products
    public function myProducts(Request $request)
    {
        if ($request->user()->role !== 'merchant') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $query = Product::with(['category'])
            ->where('user_id', $request->user()->id);

        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        $products = $query->orderBy('created_at', 'desc')->get();

        return response()->json($products);
    }

    public function store(Request $request)
    {
        // Only merchants and admins can create products
        if (!in_array($request->user()->role, ['merchant', 'admin'])) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validator = Validator::make($request->all(), [
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'old_price' => 'nullable|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|string',
            'images' => 'nullable|array',
            'images.*' => 'nullable|string',
            'brand' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Prepare images array
        $images = [];
        if ($request->has('images') && is_array($request->images)) {
            $images = array_filter($request->images); // Remove empty values
        }
        // If main image exists and not in images array, add it
        if ($request->image && !in_array($request->image, $images)) {
            array_unshift($images, $request->image);
        }

        $product = Product::create([
            'user_id' => $request->user()->id,
            'category_id' => $request->category_id,
            'name' => $request->name,
            'slug' => Str::slug($request->name) . '-' . uniqid(),
            'description' => $request->description,
            'price' => $request->price,
            'old_price' => $request->old_price,
            'stock' => $request->stock,
            'image' => $request->image ?? 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=800',
            'images' => !empty($images) ? $images : null,
            'brand' => $request->brand,
            'featured' => $request->user()->role === 'admin' ? ($request->featured ?? false) : false,
            'active' => true,
        ]);

        return response()->json([
            'message' => 'Product created successfully',
            'product' => $product->load('category')
        ], 201);
    }

    public function show($id)
    {
        $product = Product::with(['category', 'user'])->findOrFail($id);
        $product->increment('views');
        return response()->json($product);
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        // Check authorization
        if ($request->user()->role === 'merchant' && $request->user()->id !== $product->user_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if ($request->user()->role === 'user') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validator = Validator::make($request->all(), [
            'category_id' => 'sometimes|exists:categories,id',
            'name' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'price' => 'sometimes|numeric|min:0',
            'old_price' => 'nullable|numeric|min:0',
            'stock' => 'sometimes|integer|min:0',
            'image' => 'nullable|string',
            'images' => 'nullable|array',
            'images.*' => 'nullable|string',
            'brand' => 'nullable|string|max:255',
            'active' => 'sometimes|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $allowedFields = [
            'category_id',
            'name',
            'description',
            'price',
            'old_price',
            'stock',
            'image',
            'brand'
        ];

        // Handle active status changes
        if ($request->has('active')) {
            if ($request->user()->role === 'admin') {
                // Admin can always change active status
                $allowedFields[] = 'active';
                // If admin is activating, unlock the product for merchant control
                // If admin is deactivating, lock it
                $allowedFields[] = 'admin_locked';
                $updateData = $request->only($allowedFields);
                $updateData['admin_locked'] = !$request->active;
            } elseif ($request->user()->role === 'merchant') {
                // Merchant can only change active status if product is not admin-locked
                if ($product->admin_locked) {
                    return response()->json([
                        'message' => 'This product has been locked by an administrator. You cannot change its status.',
                        'admin_locked' => true
                    ], 403);
                }
                $allowedFields[] = 'active';
                $updateData = $request->only($allowedFields);
            }
        } else {
            $updateData = $request->only($allowedFields);
        }

        // Handle images array
        if ($request->has('images')) {
            $images = [];
            if (is_array($request->images)) {
                $images = array_filter($request->images);
            }
            // If main image exists and not in images array, add it
            if ($request->image && !in_array($request->image, $images)) {
                array_unshift($images, $request->image);
            }
            $updateData['images'] = !empty($images) ? $images : null;
        }

        // Only admin can set featured
        if ($request->user()->role === 'admin' && $request->has('featured')) {
            $updateData['featured'] = $request->featured;
        }

        $product->update($updateData);

        return response()->json([
            'message' => 'Product updated successfully',
            'product' => $product->load('category')
        ]);
    }

    public function destroy(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        // Check authorization
        if ($request->user()->role === 'merchant' && $request->user()->id !== $product->user_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if ($request->user()->role === 'user') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $product->delete();
        return response()->json(['message' => 'Product deleted successfully']);
    }
}
