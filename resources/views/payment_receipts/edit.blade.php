@extends('layouts.app-layout')

@section('title', 'تعديل إيصال الدفع')
@section('header', 'تعديل إيصال الدفع')
@section('add-route', route('payment_receipts.create'))

@section('content')
    <form action="{{ route('payment_receipts.update', $paymentReceipt->id) }}" method="POST" class="bg-white p-6 rounded shadow-md">
        @csrf
        @method('PUT')

        <div class="mb-4 form-group">
            <label for="date" class="form-label">تاريخ الدفع</label>
            <input type="date" name="date" id="date" value="{{ $paymentReceipt->date->format('Y-m-d') }}"
                   class="form-input @error('date') error @enderror" required>
            @error('date')
            <p class="form-error">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="amount" class="block text-sm font-medium text-gray-700">المبلغ</label>
            <input type="number" name="amount" id="amount" step="0.01" min="0" value="{{ old('amount', $paymentReceipt->amount) }}"
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm @error('amount') border-red-500 @enderror" required>
            @error('amount')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="note" class="block text-sm font-medium text-gray-700">ملاحظة (اختياري)</label>
            <textarea name="note" id="note" rows="3"
                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm @error('note') border-red-500 @enderror">{{ old('note', $paymentReceipt->note) }}</textarea>
            @error('note')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex justify-end space-x-2">
            <a href="{{ route('payment_receipts.show', $paymentReceipt->id) }}"
               class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
                إلغاء
            </a>
            <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
                حفظ التعديلات
            </button>
        </div>
    </form>
@endsection
