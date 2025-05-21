<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Models\Product;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(): \Illuminate\Contracts\View\View
    {
        $products = Product::with('user')->latest()->paginate(10);

        return view('products.index', compact('products'));
    }

    public function create(): \Illuminate\Contracts\View\View
    {
        return view('products.create');
    }

    public function store(ProductRequest $request): \Illuminate\Http\RedirectResponse
    {
        $request->validated();
        // Handle image upload
        $imageUrl = null;
        if ($request->hasFile('image')) {
            $imageUrl = $request->file('image')->store('products', 'public');
        }

        // Create the product
         $p=Product::create([
             'barcode' => $request->barcode, // Add barcode
            'user_id' => Auth::user()->id,
            'name' => $request->name,
            'image_url' => $imageUrl,
            'quantity_alert' => $request->quantity_alert,
            'min_order' => $request->min_order,
            'stock_quantity' => $request->stock_quantity,
        ]);
        return redirect()->route('products.index')->with('success', 'Created successfully');
    }

    public function show(Product $product): \Illuminate\Contracts\View\View
    {
        $product->load('user');
        return view('products.show', compact('product'));
    }

    public function edit(Product $product): \Illuminate\Contracts\View\View
    {
        return view('products.edit', compact('product'));
    }

    public function update(ProductRequest $request, Product $product): \Illuminate\Http\RedirectResponse
    {

        // Validate the request data
        $request->validated();
        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($product->image_url) {
                Storage::disk('public')->delete($product->image_url);
            }
            $product->image_url = $request->file('image')->store('products', 'public');
        }

        // Update the product
        $product->update([
            'name' => $request->name,
            'quantity_alert' => $request->quantity_alert,
            'min_order' => $request->min_order,
            'stock_quantity' => $request->stock_quantity,
        ]);
        return redirect()->route('products.index')->with('success', 'Updated successfully');
    }

    public function destroy(Product $product): \Illuminate\Http\RedirectResponse
    {
        // Delete image if exists
        if ($product->image_url) {
            Storage::disk('public')->delete($product->image_url);
        }

        // Delete the product
        $product->delete();
        return redirect()->route('products.index')->with('success', 'Deleted successfully');
    }

    public function alerts()
    {
        // Retrieve products where stock_quantity is below quantity_alert
        $alerts = Product::whereColumn('stock_quantity', '<', 'quantity_alert')
            ->with('user')
            ->get();

        return view('products.alerts', compact('alerts'));
    }

    public function getByBarcode($barcode)
    {
        $product = Product::where('barcode', $barcode)->first();
        if ($product) {
            return response()->json([
                'success' => true,
                'product' => $product,
            ]);
        }
        return response()->json([
            'success' => false,
            'message' => 'Product not found',
        ]);
    }

}
