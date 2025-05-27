@extends('layouts.app-layout')

@section('title', 'تعديل منتج')
@section('header', 'تعديل منتج')
@section('add-route', route('products.create'))

@section('content')
    <form action="{{ route('products.update', $product) }}" method="POST" enctype="multipart/form-data" class="bg-white p-6 rounded shadow-md">
        @csrf
        @method('PUT')

        <div class="mb-4 form-group">
            <label for="name" class="form-label">اسم المنتج</label>
            <input type="text" name="name" id="name" value="{{ old('name', $product->name) }}"
                   class="form-input @error('name') error @enderror" required>
            @error('name')
            <p class="form-error">{{ $message }}</p>
            @enderror
        </div>

        

        <div class="mb-4">
            <label for="image" class="block text-sm font-medium text-gray-700">صورة المنتج (اتركه فارغًا إذا لم ترغب في التغيير)</label>
            <input type="file" name="image" id="image" class="mt-1 block w-full">
            @if ($product->image_url)
                <img src="{{ asset('storage/' . $product->image_url) }}" alt="{{ $product->name }}" class="mt-2 w-24 h-24 object-cover rounded">
            @endif
        </div>

        <div class="mb-4">
            <label for="quantity_alert" class="block text-sm font-medium text-gray-700">كمية التنبيه</label>
            <input type="number" name="quantity_alert" id="quantity_alert" value="{{ old('quantity_alert', $product->quantity_alert) }}" min="1"
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
        </div>

        <div class="mb-4">
            <label for="min_order" class="block text-sm font-medium text-gray-700">الحد الأدنى للطلب</label>
            <input type="number" name="min_order" id="min_order" value="{{ old('min_order', $product->min_order) }}" min="1"
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
        </div>

        <div class="mb-4">
            <label for="stock_quantity" class="block text-sm font-medium text-gray-700">كمية المخزن</label>
            <input type="number" name="stock_quantity" id="stock_quantity" value="{{ old('stock_quantity', $product->stock_quantity) }}" min="0"
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
        </div>

        <div class="flex justify-end space-x-2">
            <a href="{{ route('products.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
                إلغاء
            </a>
            <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
                حفظ التعديلات
            </button>
        </div>
    </form>
@endsection
