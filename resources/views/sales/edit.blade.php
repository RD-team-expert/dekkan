@extends('layouts.app-layout')

@section('title', 'تعديل المبيعة')
@section('header', 'تعديل المبيعة')
@section('add-route', route('sales.create'))

@section('content')
    <form action="{{ route('sales.update', $sale->id) }}" method="POST" class="bg-white p-6 rounded shadow-md">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label for="date_time" class="block text-sm font-medium text-gray-700">تاريخ ووقت المبيعة</label>
            <input type="datetime-local" name="date_time" id="date_time" value="{{ $sale->date_time->format('Y-m-d\TH:i') }}"
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm @error('date_time') border-red-500 @enderror" required>
            @error('date_time')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <h2 class="text-lg font-medium text-gray-700">تفاصيل المنتج</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label for="product_id" class="block text-sm font-medium text-gray-700">المنتج</label>
                    <select name="product_id" id="product_id"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm product-select @error('product_id') border-red-500 @enderror" required>
                        <option value="">اختر منتج</option>
                        @foreach ($products as $product)
                            <option value="{{ $product->id }}" data-selling-price="{{ $product->latestPurchase->selling_price ?? 0 }}"
                                {{ $sale->product_id == $product->id ? 'selected' : '' }}>
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
                    <input type="number" name="quantity" id="quantity" min="1" value="{{ old('quantity', $sale->quantity) }}"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm quantity-input @error('quantity') border-red-500 @enderror" required>
                    @error('quantity')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="total_price" class="block text-sm font-medium text-gray-700">السعر الإجمالي</label>
                    <input type="number" name="total_price" id="total_price" step="0.01" min="0" value="{{ old('total_price', $sale->total_price) }}"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm bg-gray-100 @error('total_price') border-red-500 @enderror" readonly>
                    @error('total_price')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <div class="flex justify-end space-x-2">
            <a href="{{ route('sales.show', $sale->id) }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
                إلغاء
            </a>
            <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
                حفظ التعديلات
            </button>
        </div>
    </form>
@endsection

@push('scripts')
    <script>
        console.log('Sales edit script loaded');

        document.addEventListener('DOMContentLoaded', () => {
            console.log('DOM fully loaded');

            const select = document.getElementById('product_id');
            const quantityInput = document.getElementById('quantity');
            const totalPriceInput = document.getElementById('total_price');

            function updateTotalPrice() {
                const quantity = quantityInput.value || 0;
                const sellingPrice = select.options[select.selectedIndex].getAttribute('data-selling-price') || 0;
                const totalPrice = (quantity * sellingPrice).toFixed(2);
                totalPriceInput.value = totalPrice;
            }

            select.addEventListener('change', updateTotalPrice);
            quantityInput.addEventListener('input', updateTotalPrice);

            // Initial calculation
            updateTotalPrice();
        });
    </script>
@endpush
