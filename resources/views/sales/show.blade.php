@extends('layouts.app-layout')

@section('title', 'تفاصيل المبيعة')
@section('header', 'تفاصيل المبيعة')
@section('add-route', route('sales.create'))

@section('content')
    <div class="bg-white p-6 rounded shadow-md">
        <div class="mb-4">
            <h2 class="text-lg font-medium text-gray-700">معلومات المبيعة</h2>
            <p><strong>تاريخ ووقت المبيعة:</strong> {{ $sale->date_time->format('Y-m-d H:i') }}</p>
            <p><strong>المستخدم:</strong> {{ $sale->user->name }}</p>
        </div>

        <div class="mb-4">
            <h2 class="text-lg font-medium text-gray-700">المنتجات المباعة</h2>
            <table class="w-full border-collapse border border-gray-300">
                <thead>
                <tr class="bg-gray-100">
                    <th class="border border-gray-300 p-2">المنتج</th>
                    <th class="border border-gray-300 p-2">الكمية</th>
                    <th class="border border-gray-300 p-2">سعر المبيع</th>
                    <th class="border border-gray-300 p-2">السعر الإجمالي</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td class="border border-gray-300 p-2">{{ $sale->product->name }}</td>
                    <td class="border border-gray-300 p-2">{{ $sale->quantity }}</td>
                    <td class="border border-gray-300 p-2">{{ number_format($sale->selling_price, 2) }}</td>
                    <td class="border border-gray-300 p-2">{{ number_format($sale->total_price, 2) }}</td>
                </tr>
                </tbody>
            </table>
        </div>

        <div class="flex justify-end">
            <a href="{{ route('sales.edit', $sale->id) }}"
               class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                تعديل المبيعة
            </a>
        </div>
    </div>
@endsection
