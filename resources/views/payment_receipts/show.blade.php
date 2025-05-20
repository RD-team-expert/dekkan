@extends('layouts.app-layout')

@section('title', 'تفاصيل إيصال الدفع')
@section('header', 'تفاصيل إيصال الدفع')
@section('add-route', route('payment_receipts.create'))

@section('content')
    <div class="bg-white p-6 rounded shadow-md">
        <div class="mb-4">
            <h2 class="text-lg font-medium text-gray-700">معلومات إيصال الدفع</h2>
            <p><strong>رقم الإيصال:</strong> {{ $paymentReceipt->id }}</p>
            <p><strong>تاريخ الدفع:</strong> {{ $paymentReceipt->date->format('Y-m-d') }}</p>
            <p><strong>المستخدم:</strong> {{ $paymentReceipt->user->name }}</p>
            <p><strong>المبلغ:</strong> {{ number_format($paymentReceipt->amount, 2) }}</p>
            @if ($paymentReceipt->note)
                <p><strong>ملاحظة:</strong> {{ $paymentReceipt->note }}</p>
            @endif
        </div>

        <div class="flex justify-end">
            <a href="{{ route('payment_receipts.edit', $paymentReceipt->id) }}"
               class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                تعديل إيصال الدفع
            </a>
        </div>
    </div>
@endsection
