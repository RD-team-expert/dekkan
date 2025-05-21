<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\PurchaseRequest;
use App\Models\Product;

use App\Models\Purchase;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PurchaseController extends Controller
{
    public function index(): \Illuminate\Contracts\View\View
    {
        $purchases = Purchase::latest()->paginate(10);
        return view('purchases.index', compact('purchases'));
    }

    public function create(): \Illuminate\Contracts\View\View
    {
        return view('purchases.create');
    }

    public function store(PurchaseRequest $request): \Illuminate\Http\RedirectResponse
    {
       $request->validate([
        'date' => 'date',
        'product_id' => 'integer',
        'quantity' => 'integer',
        'purchase_price' => 'numeric',
        'selling_price' => 'numeric',
    ]);
        // Begin a database transaction to ensure data consistency
        DB::beginTransaction();

        try {
            $user = Auth::user();

            // Process each product in the purchase
            foreach ($request->products as $item) {
                $product = Product::findOrFail($item['product_id']);

                // Create a purchase record
                Purchase::create([
                    'user_id' => $user->id,
                    'date' => $request->date,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'purchase_price' => $item['purchase_price'],
                    'selling_price' => $item['selling_price'],
                ]);

                // Update product stock
                $product->stock_quantity += $item['quantity'];
                $product->save();
            }

            // Commit the transaction
            DB::commit();

            return redirect()->route('purchases.create')->with('success', 'Purchase recorded successfully');
        } catch (\Exception $e) {
            // Roll back the transaction on error
            DB::rollBack();

            return back()->withErrors(['error' => 'Failed to record purchase: ' . $e->getMessage()]);
        }
    }

    public function show(Purchase $purchase): \Illuminate\Contracts\View\View
    {
        return view('purchases.show', compact('purchase'));
    }

    public function edit(Purchase $purchase): \Illuminate\Contracts\View\View
    {
        return view('purchases.edit', compact('purchase'));
    }

    public function update(Request $request, $id)
    {
        $purchase = Purchase::findOrFail($id);
        $request->validate([
            'date' => 'required|date',
            'product_id' => 'required|integer|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'purchase_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
        ]);

        // Adjust stock (revert old quantity, apply new quantity)
        $product = Product::findOrFail($request->product_id);
        $oldQuantity = $purchase->quantity;
        $product->stock_quantity -= $oldQuantity; // Revert old purchase
        $product->stock_quantity += $request->quantity; // Apply new purchase
        $product->save();

        $purchase->update([
            'date' => $request->date,
            'product_id' => $request->product_id,
            'quantity' => $request->quantity,
            'purchase_price' => $request->purchase_price,
            'selling_price' => $request->selling_price,
        ]);

        return redirect()->route('purchases.show', $purchase->id)->with('success', 'Purchase updated successfully');
    }

    public function destroy(Purchase $purchase): \Illuminate\Http\RedirectResponse
    {
        $purchase->delete();
        return redirect()->route('purchases.index')->with('success', 'Deleted successfully');
    }
}
