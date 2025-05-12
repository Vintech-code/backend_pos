<?php
// app/Http/Controllers/ProductController.php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        return response()->json(Product::all());
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'image' => 'nullable|file|image|max:2048',
            'sizes' => 'nullable',
            'colors' => 'nullable',
            'types' => 'nullable',
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        // Decode JSON arrays
        $data['sizes'] = $request->sizes ? json_decode($request->sizes, true) : null;
        $data['colors'] = $request->colors ? json_decode($request->colors, true) : null;
        $data['types'] = $request->types ? json_decode($request->types, true) : null;

        $product = Product::create($data);

        return response()->json($product, 201);
    }

    public function show(Product $product)
    {
        return response()->json($product);
    }

    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'name' => 'sometimes|required|string',
            'price' => 'sometimes|required|numeric',
            'stock' => 'sometimes|required|integer',
            'image' => 'nullable|file|image|max:2048',
            'sizes' => 'nullable',
            'colors' => 'nullable',
            'types' => 'nullable',
        ]);

        if ($request->hasFile('image')) {
            // Optionally delete old image here
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        $data['sizes'] = $request->sizes ? json_decode($request->sizes, true) : null;
        $data['colors'] = $request->colors ? json_decode($request->colors, true) : null;
        $data['types'] = $request->types ? json_decode($request->types, true) : null;

        $product->update($data);

        return response()->json($product);
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return response()->json(null, 204);
    }
}
