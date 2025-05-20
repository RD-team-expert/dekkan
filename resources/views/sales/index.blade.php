@extends('layouts.app-layout')

@section('title', 'سجل المبيع')
@section('header', 'سجل المبيع')
@section('add-route', route('sales.create'))

@section('content')
    <div class="grid grid-cols-1 gap-4">
        @forelse ($sales as $sale)
            <div class="flex items-center bg-white p-2 rounded-md shadow-md">
                <div class="flex-1">
                    <h2 class="text-lg font-medium">{{ $sale->product->name }}</h2>
                    <p class="text-gray-600">التاريخ: {{ $sale->date_time->format('Y-m-d H:i') }}</p>
                    <p class="text-gray-600">الكمية: {{ $sale->quantity }}</p>
                    <p class="text-gray-600">إجمالي المنتجات: {{ $sale->total_products }}</p>
                    <p class="text-gray-600">أدخلها: {{ $sale->user->name }}</p>
                </div>
                <a href="{{ route('sales.show', $sale) }}" class="text-blue-600 hover:text-blue-800">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                </a>
            </div>
        @empty
            <p class="text-center text-gray-500">لا توجد مبيعات حاليًا.</p>
        @endforelse
    </div>

    <div class="mt-4">
        {{ $sales->links() }}
    </div>
@endsection