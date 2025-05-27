<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\PaymentReceiptRequest;
use App\Models\PaymentReceipt;

class PaymentReceiptController extends Controller
{
    public function index(): \Illuminate\Contracts\View\View
    {
        $paymentReceipts = PaymentReceipt::latest()->paginate(10);
        return view('payment_receipts.index', compact('paymentReceipts'));
    }

    public function create(): \Illuminate\Contracts\View\View
    {
        return view('payment_receipts.create');
    }

    public function store(PaymentReceiptRequest $request): \Illuminate\Http\RedirectResponse
    {
        $data = $request->validated();
        $data['user_id'] = auth()->id(); // or $request->user()->id

        PaymentReceipt::create($data);

        return redirect()->route('payment_receipts.index')->with('success', 'تم الإنشاء بنجاح');
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

    public function update(PaymentReceiptRequest $request, PaymentReceipt $paymentReceipt): \Illuminate\Http\RedirectResponse
    {

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

        return redirect()->route('payment_receipts.show', $paymentReceipt->id)->with('success', 'تم تحديث إيصال الدفع بنجاح');
    }

    public function destroy(PaymentReceipt $paymentReceipt): \Illuminate\Http\RedirectResponse
    {
        $paymentReceipt->delete();
        return redirect()->route('payment_receipts.index')->with('success', 'تم الحذف بنجاح');
    }
}
