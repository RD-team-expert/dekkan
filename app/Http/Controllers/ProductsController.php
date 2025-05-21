<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\productsRequest;
use App\Models\products;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProductsController extends Controller
{
    public function index(): \Illuminate\Contracts\View\View
    {
        $products = Products::with('user')->latest()->paginate(10);

        return view('products.index', compact('products'));
    }

    public function create(): \Illuminate\Contracts\View\View
    {
        return view('products.create');
    }

    public function store(productsRequest $request): \Illuminate\Http\RedirectResponse
    {
        $request->validated();
        // Handle image upload
        $imageUrl = null;
        if ($request->hasFile('image')) {
            $imageUrl = $request->file('image')->store('products', 'public');
        }

        // Create the product
        Products::create([
            'user_id' => Auth::user()->id,
            'name' => $request->name,
            'category' => $request->category,
            'image_url' => $imageUrl,
            'quantity_alert' => $request->quantity_alert,
            'min_order' => $request->min_order,
            'stock_quantity' => $request->stock_quantity,
        ]);
        return redirect()->route('products.index')->with('success', 'Created successfully');
    }

    public function edit(products $product): \Illuminate\Contracts\View\View
    {
        return view('products.edit', compact('product'));
    }

    public function show(products $product): \Illuminate\Contracts\View\View
    {

        $product->load('user');
        return view('products.show', compact('product'));
    }


    public function update(productsRequest $request, Products $products): \Illuminate\Http\RedirectResponse
    {
        // Validate the request data
        $request->validated();
        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($products->image_url) {
                Storage::disk('public')->delete($products->image_url);
            }
            $products->image_url = $request->file('image')->store('products', 'public');
        }

        // Update the product
        $products->update([
            'name' => $request->name,
            'category' => $request->category,
            'quantity_alert' => $request->quantity_alert,
            'min_order' => $request->min_order,
            'stock_quantity' => $request->stock_quantity,
        ]);
        return redirect()->route('products.index')->with('success', 'Updated successfully');
    }

    public function destroy(Products $products): \Illuminate\Http\RedirectResponse
    {
        // Delete image if exists
        if ($products->image_url) {
            Storage::disk('public')->delete($products->image_url);
        }

        // Delete the product
        $products->delete();
        return redirect()->route('products.index')->with('success', 'Deleted successfully');
    }

    public function alerts()
    {
        // Retrieve products where stock_quantity is below quantity_alert
        $alerts = products::whereColumn('stock_quantity', '<', 'quantity_alert')
            ->with('user')
            ->get();

        return view('products.alerts', compact('alerts'));
    }

    public function getByBarcode($barcode)
    {
        $product = Products::where('barcode', $barcode)->first();
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

    public function scanProduct(Request $request)
    {
        $qrCode = $request->input('qr_code');

        // Find the product by the QR code value (e.g., product ID or SKU)
        $product = products::where('id', $qrCode)
            ->orWhere('sku', $qrCode)
            ->first();

        if ($product) {
            return response()->json([
                'product' => [
                    'id' => $product->id,
                    'name' => $product->name,
                    'price' => $product->price,
                    // Add other fields as needed
                ]
            ]);
        }

        return response()->json(['product' => null], 404);
    }

}
