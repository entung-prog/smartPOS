<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        try {
            $query = Product::query();

            // BUG FIX: Wrap search in closure to prevent orWhere from breaking category filter
            if ($request->filled('search')) {
                $query->where(function ($q) use ($request) {
                    $q->where('name', 'like', "%{$request->search}%")
                      ->orWhere('sku', 'like', "%{$request->search}%");
                });
            }

            if ($request->filled('category')) {
                $query->where('category', $request->category);
            }

            $products = $query->orderBy('name')->paginate($request->get('per_page', 15));

            return response()->json([
                'success' => true,
                'data'    => $products,
            ]);
        } catch (\Exception $e) {
            Log::error('ProductController@index failed', ['error' => $e->getMessage()]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat data produk.',
            ], 500);
        }
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

        try {
            $product = Product::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Produk berhasil ditambahkan.',
                'data'    => $product,
            ], 201);
        } catch (\Exception $e) {
            Log::error('ProductController@store failed', ['error' => $e->getMessage()]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan produk.',
            ], 500);
        }
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

        try {
            $product->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Produk berhasil diupdate.',
                'data'    => $product->fresh(),
            ]);
        } catch (\Exception $e) {
            Log::error('ProductController@update failed', ['error' => $e->getMessage(), 'product_id' => $product->id]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupdate produk.',
            ], 500);
        }
    }

    public function destroy(Product $product): JsonResponse
    {
        try {
            // Check if product has transaction items (FK constraint)
            if ($product->transactionItems()->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => "Produk '{$product->name}' tidak bisa dihapus karena sudah memiliki riwayat transaksi.",
                ], 422);
            }

            $product->delete();

            return response()->json([
                'success' => true,
                'message' => 'Produk berhasil dihapus.',
            ]);
        } catch (\Exception $e) {
            Log::error('ProductController@destroy failed', ['error' => $e->getMessage(), 'product_id' => $product->id]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus produk.',
            ], 500);
        }
    }
}
