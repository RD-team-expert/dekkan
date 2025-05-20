@extends('layouts.app-layout')

@section('title', 'تفاصيل المنتج')
@section('header', 'تفاصيل المنتج')
@section('add-route', route('products.create'))

@section('content')
    <div class="bg-white p-6 rounded shadow-md">
        <div class="flex items-center mb-4">
            <img src="{{ $product->image_url ? asset('storage/' . $product->image_url) : asset('images/placeholder.png') }}"
                 alt="{{ $product->name }}"
                 class="w-24 h-24 object-cover rounded mr-4">
            <div>
                <h2 class="text-xl font-bold">{{ $product->name }}</h2>
                <p class="text-gray-600">الفئة: {{ $product->category }}</p>
            </div>
        </div>

        <div class="space-y-2">
            <p class="text-gray-700">الكمية في المخزن: <span class="font-medium {{ $product->stock_quantity < $product->quantity_alert ? 'text-red-600' : 'text-green-600' }}">{{ $product->stock_quantity }}</span></p>
            <p class="text-gray-700">كمية التنبيه: <span class="font-medium">{{ $product->quantity_alert }}</span></p>
            <p class="text-gray-700">الحد الأدنى للطلب: <span class="font-medium">{{ $product->min_order }}</span></p>
            <p class="text-gray-700">أضيف بواسطة: <span class="font-medium">{{ $product->user->name }}</span></p>
            <p class="text-gray-700">تاريخ الإضافة: <span class="font-medium">{{ $product->created_at->format('Y-m-d H:i') }}</span></p>
            <p class="text-gray-700">آخر تحديث: <span class="font-medium">{{ $product->updated_at->format('Y-m-d H:i') }}</span></p>
        </div>

        <div class="flex justify-end space-x-2 mt-4">
            <a href="{{ route('products.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
                رجوع
            </a>
            <a href="{{ route('products.edit', $product) }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                تعديل
            </a>
        </div>
    </div>
@endsection
