<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class BrandController extends Controller
{
    public function index() : JsonResponse
    {
        $brands = Brand::all();
        return response()->json($brands);
    }

    public function store(Request $request) : JsonResponse
    {
        $request->validate([
            'name' => 'required|string'
        ]);

        $brand = Brand::create($request->all());
        return response()->json($brand, 201);
    }

    public function show(Brand $brand) : JsonResponse
    {
        return response()->json($brand);
    }

    public function update(Request $request, Brand $brand) : JsonResponse
    {
        $request->validate([
            'name' => 'required|string'
        ]);
        $brand->update($request->all());
        return response()->json($brand);
    }

    public function destroy(Brand $brand) : JsonResponse
    {
        $brand->delete();
        return response()->json(null, 204);
    }
}
