<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Product::query();

        if ($request->filled('search')) {
            $query->where('name', 'like', "%{$request->search}%")
                  ->orWhere('sku', 'like', "%{$request->search}%");
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        $products = $query->orderBy('name')->paginate($request->get('per_page', 15));

        return response()->json([
            'success' => true,
            'data'    => $products,
        ]);
    }

    public function show(Product $product): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data'    => $product,
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'sku'         => 'required|string|unique:products,sku',
            'price'       => 'required|integer|min:0',
            'stock'       => 'required|integer|min:0',
            'category'    => 'nullable|string|max:100',
            'description' => 'nullable|string',
        ]);

        $product = Product::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Produk berhasil ditambahkan.',
            'data'    => $product,
        ], 201);
    }

    public function update(Request $request, Product $product): JsonResponse
    {
        $validated = $request->validate([
            'name'        => 'sometimes|string|max:255',
            'sku'         => 'sometimes|string|unique:products,sku,' . $product->id,
            'price'       => 'sometimes|integer|min:0',
            'stock'       => 'sometimes|integer|min:0',
            'category'    => 'nullable|string|max:100',
            'description' => 'nullable|string',
        ]);

        $product->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Produk berhasil diupdate.',
            'data'    => $product->fresh(),
        ]);
    }

    public function destroy(Product $product): JsonResponse
    {
        $product->delete();

        return response()->json([
            'success' => true,
            'message' => 'Produk berhasil dihapus.',
        ]);
    }
}
