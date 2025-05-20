@extends('layouts.app-layout')

@section('title', 'نقص بالكميات')
@section('header', 'نقص بالكميات')

@section('content')
    <div class="grid grid-cols-1 gap-4">
        @forelse ($alerts as $product)
            <div class="flex items-center bg-yellow-100 p-2 rounded-md shadow-md">
                <img src="{{ $product->image_url ? asset('storage/' . $product->image_url) : asset('images/placeholder.png') }}"
                     alt="{{ $product->name }}"
                     class="w-16 h-16 object-cover rounded mr-2">
                <div class="flex-1">
                    <h2 class="text-lg font-medium">{{ $product->name }}</h2>
                    <p class="text-gray-600">الكمية في المخزن: {{ $product->stock_quantity }}</p>
                    <p class="text-red-600">كمية التنبيه: {{ $product->quantity_alert }}</p>
                </div>
                <a href="{{ route('products.edit', $product) }}" class="text-green-600 hover:text-green-800">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                </a>
            </div>
        @empty
            <p class="text-center text-gray-500">لا توجد تنبيهات حاليًا.</p>
        @endforelse
    </div>
@endsection
