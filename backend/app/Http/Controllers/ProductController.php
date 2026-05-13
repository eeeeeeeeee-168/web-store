<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ProductController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Product::active();

        if ($request->category_id) {
            $query->where('category_id', $request->category_id);
        }
        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('name_km', 'like', '%' . $request->search . '%');
            });
        }
        if ($request->featured) {
            $query->featured();
        }
        if ($request->min_price) {
            $query->where('price', '>=', (float) $request->min_price);
        }
        if ($request->max_price) {
            $query->where('price', '<=', (float) $request->max_price);
        }

        $sortBy  = $request->sort_by ?? 'created_at';
        $sortDir = $request->sort_dir ?? 'desc';
        $query->orderBy($sortBy, $sortDir);

        $products = $query->paginate($request->per_page ?? 12);

        return response()->json($products);
    }

    public function show(string $id): JsonResponse
    {
        $product = Product::with('category')->findOrFail($id);
        return response()->json($product);
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'name'        => 'required|string',
            'price'       => 'required|numeric|min:0',
            'stock'       => 'required|integer|min:0',
            'category_id' => 'required|string',
        ]);

        $product = Product::create($request->all());
        return response()->json([
            'message' => 'បន្ថែមផលិតផលបានជោគជ័យ',
            'product' => $product,
        ], 201);
    }

    public function update(Request $request, string $id): JsonResponse
    {
        $product = Product::findOrFail($id);
        $product->update($request->all());
        return response()->json([
            'message' => 'កែប្រែផលិតផលបានជោគជ័យ',
            'product' => $product,
        ]);
    }

    public function destroy(string $id): JsonResponse
    {
        Product::findOrFail($id)->delete();
        return response()->json(['message' => 'លុបផលិតផលបានជោគជ័យ']);
    }

    public function featured(): JsonResponse
    {
        $products = Product::active()->featured()->limit(8)->get();
        return response()->json($products);
    }
}
