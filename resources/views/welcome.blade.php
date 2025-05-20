@extends('layouts.app-layout')

@section('title', 'لوحة التحكم')
@section('header', 'لوحة التحكم')

@section('content')
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <!-- Products Card -->
        <a href="{{ route('products.index') }}" class="bg-white p-4 rounded-md shadow-md hover:shadow-lg transition">
            <div class="flex items-center">
                <svg class="w-8 h-8 text-blue-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path>
                </svg>
                <div>
                    <h2 class="text-lg font-medium">المنتجات</h2>
                    <p class="text-gray-600">إدارة المنتجات</p>
                </div>
            </div>
        </a>

        <!-- Sales Card -->
        <a href="{{ route('sales.index') }}" class="bg-white p-4 rounded-md shadow-md hover:shadow-lg transition">
            <div class="flex items-center">
                <svg class="w-8 h-8 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                <div>
                    <h2 class="text-lg font-medium">المبيعات</h2>
                    <p class="text-gray-600">عرض وتسجيل المبيعات</p>
                </div>
            </div>
        </a>

        <!-- Purchases Card -->
        <a href="{{ route('purchases.index') }}" class="bg-white p-4 rounded-md shadow-md hover:shadow-lg transition">
            <div class="flex items-center">
                <svg class="w-8 h-8 text-yellow-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                <div>
                    <h2 class="text-lg font-medium">المشتريات</h2>
                    <p class="text-gray-600">عرض وتسجيل المشتريات</p>
                </div>
            </div>
        </a>

        <!-- Payment Receipts Card -->
        <a href="{{ route('payment_receipts.index') }}" class="bg-white p-4 rounded-md shadow-md hover:shadow-lg transition">
            <div class="flex items-center">
                <svg class="w-8 h-8 text-purple-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                <div>
                    <h2 class="text-lg font-medium">المدفوعات والإيصالات</h2>
                    <p class="text-gray-600">إدارة الحسابات</p>
                </div>
            </div>
        </a>
    </div>
@endsection