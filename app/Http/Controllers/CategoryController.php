<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index() 
    {
        $categories = Category::paginate(10);
    }

    public function store(Request $request) 
    {
        $request->validate([
            'name' => 'required|string',
            'image' => 'required|image'
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
        $category = Category::create($request->all());
        return response()->json($category, 201);
    }

    public function show(Category $category) 
    {
        return response()->json($category);
    }

    public function update(Request $request, Category $category) 
    {
        $request->validate([
            'name' => 'required|string',
            'image' => 'required|image'
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
        
        $category->update($request->all());
        return response()->json($category);
    }

    public function destroy(Category $category) 
    {
        $category->delete();
        return response()->json(null, 204);
    }
}
