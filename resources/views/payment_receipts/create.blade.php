@extends('layouts.app-layout')

@section('title', 'إضافة دفعة أو إيصال')
@section('header', 'إضافة دفعة أو إيصال')
@section('add-route', route('payment_receipts.create'))

@section('content')
    <form action="{{ route('payment_receipts.store') }}" method="POST" class="bg-white p-6 rounded shadow-md">
        @csrf
        <div class="mb-4 form-group">
            <label for="type" class="form-label">النوع</label>
            <select name="type" id="type" class="form-select @error('type') error @enderror" required>
                <option value="">اختر النوع</option>
                <option value="payment" {{ old('type') == 'payment' ? 'selected' : '' }}>دفعة</option>
                <option value="receipt" {{ old('type') == 'receipt' ? 'selected' : '' }}>إيصال</option>
            </select>
            @error('type')
            <p class="form-error">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="amount" class="block text-sm font-medium text-gray-700">المبلغ</label>
            <input type="number" name="amount" id="amount" value="{{ old('amount') }}" step="0.01" min="0"
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
        </div>

        <div class="mb-4">
            <label for="date" class="block text-sm font-medium text-gray-700">تاريخ العملية</label>
            <input type="datetime-local" name="date" id="date" value="{{ old('date') }}"
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
        </div>

        <div class="mb-4">
            <label for="notes" class="block text-sm font-medium text-gray-700">ملاحظات</label>
            <textarea name="notes" id="notes" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">{{ old('notes') }}</textarea>
        </div>

        <div class="flex justify-end">
            <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
                تسجيل العملية
            </button>
        </div>
    </form>
@endsection
