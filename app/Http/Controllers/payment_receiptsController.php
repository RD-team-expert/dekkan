<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\payment_receiptsRequest;
use App\Models\Payment_receipts;

class payment_receiptsController extends Controller
{
    public function index(): \Illuminate\Contracts\View\View
    {
        $payment_receipts = Payment_receipts::latest()->paginate(10);
        return view('payment_receipts.index', compact('payment_receipts'));
    }

    public function create(): \Illuminate\Contracts\View\View
    {
        return view('payment_receipts.create');
    }

    public function store(payment_receiptsRequest $request): \Illuminate\Http\RedirectResponse
    {
        Payment_receipts::create($request->validated());
        return redirect()->route('payment_receipts.index')->with('success', 'Created successfully');
    }

    public function show($id)
    {
        $paymentReceipt = Payment_receipts::with('user')->findOrFail($id);
        return view('payment_receipts.show', compact('paymentReceipt'));
    }

    public function edit($id)
    {
        $paymentReceipt = Payment_receipts::findOrFail($id);
        return view('payment_receipts.edit', compact('paymentReceipt'));
    }

    public function update(payment_receiptsRequest $request, Payment_receipts $id): \Illuminate\Http\RedirectResponse
    {
        $paymentReceipt = Payment_receipts::findOrFail($id);
        $request->validate([
            'date' => 'required|date',
            'amount' => 'required|numeric|min:0',
            'note' => 'nullable|string|max:255',
        ]);

        $paymentReceipt->update([
            'date' => $request->date,
            'amount' => $request->amount,
            'note' => $request->note,
        ]);

        return redirect()->route('Payment_receipts.show', $paymentReceipt->id)->with('success', 'Payment receipt updated successfully');
    }

    public function destroy(Payment_receipts $Payment_receipts): \Illuminate\Http\RedirectResponse
    {
        $Payment_receipts->delete();
        return redirect()->route('Payment_receipts.index')->with('success', 'Deleted successfully');
    }
}
