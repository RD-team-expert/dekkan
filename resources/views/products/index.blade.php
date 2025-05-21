@extends('layouts.app-layout')

@section('title', 'قائمة المنتجات')

@section('header', 'قائمة المنتجات')

@section('search-action', 'onkeyup="searchProducts(event)"')

@section('content')
    <!-- Product List -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        @forelse ($products as $product)
            <div class="flex flex-col sm:flex-row items-center bg-white p-3 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-200">
                <img src="{{ $product->image_url ? asset('storage/' . $product->image_url) : asset('images/placeholder.png') }}"
                     alt="{{ $product->name }}"
                     class="w-24 h-24 sm:w-20 sm:h-20 object-cover rounded-lg mb-2 sm:mb-0 sm:mr-3">
                <div class="flex-1 text-center sm:text-right">
                    <h2 class="text-lg font-medium text-gray-800">{{ $product->name }}</h2>
                    <p class="text-gray-600 text-sm">الكمية في المخزن: {{ $product->stock_quantity }}</p>
                </div>
                <div class="flex items-center gap-3 mt-2 sm:mt-0">
                    <span class="text-red-500 font-bold">0</span>
                    <div class="flex gap-2">
                        <a href="{{ route('products.show', $product) }}"
                           class="text-blue-600 hover:text-blue-800 p-1.5 rounded-full hover:bg-blue-50">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                        </a>
                        <a href="{{ route('products.edit', $product) }}"
                           class="text-green-600 hover:text-green-800 p-1.5 rounded-full hover:bg-green-50">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                        </a>
                        <form action="{{ route('products.destroy', $product) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="text-red-600 hover:text-red-800 p-1.5 rounded-full hover:bg-red-50"
                                    onclick="return confirm('هل أنت متأكد؟')">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <p class="text-center text-gray-500 col-span-full py-8">لا توجد منتجات حاليًا.</p>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $products->links() }}
    </div>

    <!-- Floating Action Buttons -->
    <div class="fixed bottom-20 right-4 flex flex-col gap-4">
        <a href="{{ route('products.create') }}"
           class="bg-blue-500 text-white rounded-full w-12 h-12 flex items-center justify-center hover:bg-blue-600 shadow-lg transform hover:scale-105 transition-all">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
        </a>
    </div>
@endsection

    <!-- Bottom Navigation Bar -->
    <nav class="fixed bottom-0 left-0 w-full bg-white border-t border-gray-200 p-2 flex justify-around items-center shadow-lg">
        <a href="/" class="flex flex-col items-center text-gray-600 hover:text-blue-600 transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7m-9 9l-7-7"></path>
            </svg>
            <span class="text-xs mt-1">الرئيسية</span>
        </a>
        <a href="{{ route('sales.create') }}" class="flex flex-col items-center text-gray-600 hover:text-blue-600 transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
            <span class="text-xs mt-1">المبيعات</span>
        </a>
        <a href="{{ route('purchases.create') }}" class="flex flex-col items-center text-gray-600 hover:text-blue-600 transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            <span class="text-xs mt-1">المشتريات</span>
        </a>
        <a href="{{ route('payment_receipts.create') }}" class="flex flex-col items-center text-gray-600 hover:text-blue-600 transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
            </svg>
            <span class="text-xs mt-1">المدفوعات</span>
        </a>
    </nav>
</div>
</body>
</html>
