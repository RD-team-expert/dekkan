@extends('layouts.app-layout')

@section('title', 'تفاصيل الشراء')
@section('header', 'تفاصيل الشراء')
@section('add-route', route('purchases.create'))

@section('content')
    <div class="bg-white p-6 rounded shadow-md">
        <div class="mb-4">
            <h2 class="text-lg font-medium text-gray-700">معلومات الشراء</h2>
            <p><strong>تاريخ الشراء:</strong> {{ $purchase->date->format('Y-m-d') }}</p>
            <p><strong>المستخدم:</strong> {{ $purchase->user->name }}</p>
        </div>

        <div class="mb-4">
            <h2 class="text-lg font-medium text-gray-700">تفاصيل المنتج</h2>
            <table class="w-full border-collapse border border-gray-300">
                <thead>
                <tr class="bg-gray-100">
                    <th class="border border-gray-300 p-2">المنتج</th>
                    <th class="border border-gray-300 p-2">الكمية</th>
                    <th class="border border-gray-300 p-2">سعر الشراء</th>
                    <th class="border border-gray-300 p-2">سعر البيع</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td class="border border-gray-300 p-2">{{ $purchase->product->name }}</td>
                    <td class="border border-gray-300 p-2">{{ $purchase->quantity }}</td>
                    <td class="border border-gray-300 p-2">{{ number_format($purchase->purchase_price, 2) }}</td>
                    <td class="border border-gray-300 p-2">{{ number_format($purchase->selling_price, 2) }}</td>
                </tr>
                </tbody>
            </table>
        </div>

        <div class="flex justify-end">
            <a href="{{ route('purchases.edit', $purchase->id) }}"
               class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                تعديل الشراء
            </a>
        </div>
    </div>
@endsection
