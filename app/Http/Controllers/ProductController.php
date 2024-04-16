<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ProductController extends Controller
{
    public function index() : JsonResponse
    {
        $products = Product::paginate(10);
        if ($products->isEmpty()) {
            return response()->json('No products found', 404);
        }
        return response()->json($products);
    }

    public function store(Request $request) : JsonResponse
    {
        $request->validate([
            'name' => 'required|string',
            'price' => 'required|numeric',
            'brand_id' => 'required|exists:brands,id',
            'category_id' => 'required|exists:categories,id',
            'image' => 'required|image', 
            'is_trendy' => 'boolean',
            'is_available' => 'boolean',
            'amount' => 'numeric',
            'discount' => 'numeric'
        ]);
        
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $name = time() . '.' . $image->getClientOriginalExtension();
            $destinationPath = public_path('/images');
            $imagePath = $destinationPath . '/' . $name;
            $image->move($destinationPath, $name);
            $request->merge(['image' => $imagePath]);
        }
        $product = Product::create($request->all());
        return response()->json($product, 201);
    }

    public function show(Product $product) : JsonResponse
    {
        return response()->json($product);
    }

    public function update(Request $request, Product $product) : JsonResponse
    {
        $request->validate([
            'name' => 'required|string',
            'price' => 'required|numeric',
            'brand_id' => 'required|exists:brands,id',
            'category_id' => 'required|exists:categories,id',
            'image' => 'required|image', 
            'is_trendy' => 'required|boolean',
            'is_available' => 'required|boolean',
            'amount' => 'required|numeric',
            'discount' => 'required|numeric'
        ]);

        // check if the image is present
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $name = time() . '.' . $image->getClientOriginalExtension();
            $destinationPath = public_path('/images');
            $imagePath = $destinationPath . '/' . $name;
            $image->move($destinationPath, $name);
            $request->merge(['image' => $imagePath]);
        }
        
        $product->update($request->all());
        return response()->json($product);
    }

    public function destroy(Product $product) : JsonResponse
    {
        $product->delete();
        return response()->json(null, 204);
    }
}
