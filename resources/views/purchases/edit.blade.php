@extends('layouts.app-layout')

@section('title', 'تعديل الشراء')
@section('header', 'تعديل الشراء')
@section('add-route', route('purchases.create'))

@section('content')
    <form action="{{ route('purchases.update', $purchase->id) }}" method="POST" class="bg-white p-6 rounded shadow-md">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label for="date" class="block text-sm font-medium text-gray-700">تاريخ الشراء</label>
            <input type="date" name="date" id="date" value="{{ $purchase->date->format('Y-m-d') }}"
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm @error('date') border-red-500 @enderror" required>
            @error('date')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <h2 class="text-lg font-medium text-gray-700">تفاصيل المنتج</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label for="product_id" class="block text-sm font-medium text-gray-700">المنتج</label>
                    <select name="product_id" id="product_id"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm @error('product_id') border-red-500 @enderror" required>
                        <option value="">اختر منتج</option>
                        @foreach ($Products as $product)
                            <option value="{{ $product->id }}"
                                {{ $purchase->product_id == $product->id ? 'selected' : '' }}>
                                {{ $product->name }} (المخزن: {{ $product->stock_quantity }})
                            </option>
                        @endforeach
                    </select>
                    @error('product_id')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="quantity" class="block text-sm font-medium text-gray-700">الكمية</label>
                    <input type="number" name="quantity" id="quantity" min="1" value="{{ old('quantity', $purchase->quantity) }}"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm @error('quantity') border-red-500 @enderror" required>
                    @error('quantity')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="purchase_price" class="block text-sm font-medium text-gray-700">سعر الشراء</label>
                    <input type="number" name="purchase_price" id="purchase_price" step="0.01" min="0"
                           value="{{ old('purchase_price', $purchase->purchase_price) }}"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm @error('purchase_price') border-red-500 @enderror" required>
                    @error('purchase_price')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="selling_price" class="block text-sm font-medium text-gray-700">سعر البيع</label>
                    <input type="number" name="selling_price" id="selling_price" step="0.01" min="0"
                           value="{{ old('selling_price', $purchase->selling_price) }}"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm @error('selling_price') border-red-500 @enderror" required>
                    @error('selling_price')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <div class="flex justify-end space-x-2">
            <a href="{{ route('purchases.show', $purchase->id) }}"
               class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
                إلغاء
            </a>
            <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
                حفظ التعديلات
            </button>
        </div>
    </form>
@endsection
