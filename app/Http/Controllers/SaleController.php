<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Product;
use Illuminate\Http\Request;

use App\Http\Requests\SaleRequest;

// Replace this line
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class SaleController extends Controller
{
    public function index(): \Illuminate\Contracts\View\View
    {
        $sales = Sale::latest()->paginate(10);
        return view('sales.index', compact('sales'));
    }

    public function create(): \Illuminate\Contracts\View\View
    {
        $products = Product::with(['latestPurchase' => function ($query) {
            $query->latest('created_at')->select('product_id', 'selling_price');
        }])->get();

        return view('sales.create', compact('products'));
    }

    public function store(SaleRequest $request): \Illuminate\Http\RedirectResponse
    {
        $request->validate([
            'date_time' => 'date',
            'product_id' => 'integer',
            'quantity' => 'integer',
            'total_products' => 'integer',
        ]);

        DB::beginTransaction();

        try {
            $user = Auth::user();

            foreach ($request->products as $item) {
                $product = Product::findOrFail($item['product_id']);
                $latestPurchase = $product->latestPurchase;

                // Use the latest selling price from purchases
                $sellingPrice = $latestPurchase ? $latestPurchase->selling_price : 0;
                $expectedTotal = $item['quantity'] * $sellingPrice;

                // Validate total_price matches the calculated value (optional strict check)
                if (abs($item['total_price'] - $expectedTotal) > 0.01) {
                    throw new \Exception('عدم تطابق السعر الإجمالي للمنتج رقم: ' . $item['product_id']);
                }

                // Check stock availability
                if ($product->stock_quantity < $item['quantity']) {
                    throw new \Exception('المخزون غير كافٍ للمنتج: ' . $product->name);
                }

                // Create a sale record
                Sale::create([
                    'user_id' => $user->id,
                    'date_time' => $request->date_time,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'selling_price' => $sellingPrice,
                    'total_price' => $item['total_price'],
                ]);

                // Update product stock
                $product->stock_quantity -= $item['quantity'];
                $product->save();
            }

            DB::commit();

            return redirect()->route('sales.create')->with('success', 'تم تسجيل عملية البيع بنجاح');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'فشل في تسجيل عملية البيع: ' . $e->getMessage()]);
        }
    }

    public function show(Sale $sale): \Illuminate\Contracts\View\View
    {
        $sale->load(['product', 'user']);

        return view('sales.show', compact('sale'));
    }

    public function edit(Sale $sale): \Illuminate\Contracts\View\View
    {
        $products = Product::with(['latestPurchase' => function ($query) {
            $query->latest('created_at')->select('product_id', 'selling_price');
        }])->get();
        return view('sales.edit', compact('sale', 'products'));
    }

    public function update(SaleRequest $request, Sale $sale): \Illuminate\Http\RedirectResponse
    {
        $request->validate([
            'date_time' => 'required|date',
            'product_id' => 'required|integer|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'total_price' => 'required|numeric|min:0',
        ]);

        $product = Product::findOrFail($request->product_id);
        $latestPurchase = $product->latestPurchase;
        $sellingPrice = $latestPurchase ? $latestPurchase->selling_price : 0;
        $expectedTotal = $request->quantity * $sellingPrice;

        if (abs($request->total_price - $expectedTotal) > 0.01) {
            return back()->withErrors(['total_price' => 'عدم تطابق السعر الإجمالي']);
        }

        // Adjust stock (revert old quantity, apply new quantity)
        $oldQuantity = $sale->quantity;
        $product->stock_quantity += $oldQuantity; // Revert old sale
        $product->stock_quantity -= $request->quantity; // Apply new sale
        if ($product->stock_quantity < 0) {
            return back()->withErrors(['quantity' => 'المخزون غير كافٍ']);
        }
        $product->save();

        $sale->update([
            'date_time' => $request->date_time,
            'product_id' => $request->product_id,
            'quantity' => $request->quantity,
            'selling_price' => $sellingPrice,
            'total_price' => $request->total_price,
        ]);

        return redirect()->route('sales.show', $sale->id)->with('success', 'تم تحديث عملية البيع بنجاح');
    }

    public function destroy(Sale $sale): \Illuminate\Http\RedirectResponse
    {
        $sale->delete();
        return redirect()->route('sales.index')->with('success', 'تم الحذف بنجاح');
    }

    public function searchProducts(Request $request)
    {
        // Remove this line
     $query = $request->get('query', '');
        $products = Product::with(['latestPurchase' => function ($query) {
            $query->latest('created_at')->select('product_id', 'selling_price');
        }])
        ->where('name', 'LIKE', '%' . $query . '%')
        ->limit(20)
        ->get();
        
        return response()->json($products->map(function ($product) {
            return [
                'id' => $product->id,
                'name' => $product->name,
                'barcode' => $product->barcode,
                'stock_quantity' => $product->stock_quantity,
                'selling_price' => $product->latestPurchase->selling_price ?? 0
            ];
        }));
    }
}
