<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\PaymentReceiptRequest;
use App\Models\Payment_receipts;
use App\Models\PaymentReceipt;

class PaymentReceiptController extends Controller
{
    public function index(): \Illuminate\Contracts\View\View
    {
        $payment_receipts = PaymentReceipt::latest()->paginate(10);
        return view('payment_receipts.index', compact('payment_receipts'));
    }

    public function create(): \Illuminate\Contracts\View\View
    {
        return view('payment_receipts.create');
    }

    public function store(PaymentReceiptRequest $request): \Illuminate\Http\RedirectResponse
    {
        PaymentReceipt::create($request->validated());
        return redirect()->route('payment_receipts.index')->with('success', 'Created successfully');
    }

    public function show($id): \Illuminate\Contracts\View\View
    {
        $paymentReceipt = PaymentReceipt::with('user')->findOrFail($id);
        return view('payment_receipts.show', compact('paymentReceipt'));
    }

    public function edit(PaymentReceipt $paymentReceipt): \Illuminate\Contracts\View\View
    {
        return view('payment_receipts.edit', compact('paymentReceipt'));
    }

    public function update(PaymentReceiptRequest $request, PaymentReceipt $id): \Illuminate\Http\RedirectResponse
    {

        $paymentReceipt = PaymentReceipt::findOrFail($id);
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

        return redirect()->route('payment_receipts.show', $paymentReceipt->id)->with('success', 'Payment receipt updated successfully');
    }

    public function destroy(PaymentReceipt $paymentReceipt): \Illuminate\Http\RedirectResponse
    {
        $paymentReceipt->delete();
        return redirect()->route('payment_receipts.index')->with('success', 'Deleted successfully');
    }
}
