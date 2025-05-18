<?php


namespace App\Http\Controllers;
use App\Models\ProductHistory;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;


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
        $path = $request->file('image')->store('products', 'public');
        $data['image'] = Storage::url($path); // Store the public URL
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
    $validated = $request->validate([
        'price' => 'sometimes|numeric|min:0',
        'stock' => 'sometimes|integer|min:0',
    ]);
    $product->update($validated);
    return response()->json($product);
}

    public function destroy(Product $product)
    {
        $product->delete();
        return response()->json(null, 204);
    }
   public function checkout(Request $request)
{
    $validated = $request->validate([
        'items' => 'required|array',
        'items.*.id' => 'required|integer|exists:products,id',
        'items.*.quantity' => 'required|integer|min:1',
    ]);

    $historyRecords = [];
    
    DB::beginTransaction();
    try {
        foreach ($validated['items'] as $item) {
            $product = Product::findOrFail($item['id']);

            if ($product->stock < $item['quantity']) {
                throw new \Exception("Insufficient stock for {$product->name}");
            }

            // Update product stock
            $product->stock -= $item['quantity'];
            $product->save();

            // Create history record
            $historyRecords[] = ProductHistory::create([
                'product_id' => $product->id,
                'product_name' => $product->name,
                'quantity' => $item['quantity'],
                'price' => $product->price,
                'checked_out_at' => now(),
            ]);
        }

        DB::commit();
        
        return response()->json([
            'message' => 'Checkout successful',
            'history' => $historyRecords
        ]);
    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json([
            'message' => $e->getMessage()
        ], 400);
    }
}
public function history()
{
    $history = ProductHistory::orderBy('checked_out_at', 'desc')->get();
    return response()->json($history);
}

}
