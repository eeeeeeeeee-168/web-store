<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class CategoryController extends Controller
{
    public function index(): JsonResponse
    {
        $categories = Category::where('is_active', true)
                               ->orderBy('sort_order')
                               ->get();
        return response()->json($categories);
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'name'    => 'required|string',
            'name_km' => 'required|string',
            'slug'    => 'required|string|unique:categories',
        ]);

        $category = Category::create($request->all());
        return response()->json([
            'message'  => 'បន្ថែមប្រភេទបានជោគជ័យ',
            'category' => $category,
        ], 201);
    }

    public function update(Request $request, string $id): JsonResponse
    {
        $category = Category::findOrFail($id);
        $category->update($request->all());
        return response()->json([
            'message'  => 'កែប្រែប្រភេទបានជោគជ័យ',
            'category' => $category,
        ]);
    }

    public function destroy(string $id): JsonResponse
    {
        Category::findOrFail($id)->delete();
        return response()->json(['message' => 'លុបប្រភេទបានជោគជ័យ']);
    }
}
