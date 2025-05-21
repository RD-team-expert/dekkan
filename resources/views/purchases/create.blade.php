@extends('layouts.app-layout')

@section('title', 'إضافة شراء')
@section('header', 'إضافة شراء')
@section('add-route', route('purchases.create'))

@section('content')
    <form action="{{ route('purchases.store') }}" method="POST" class="bg-white p-6 rounded shadow-md">
        @csrf
        <div class="mb-4">
            <label for="date" class="block text-sm font-medium text-gray-700">تاريخ الشراء</label>
            <input type="date" name="date" id="date" value="{{ old('date') }}"
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
        </div>

        <div id="products-container">
            <div class="product-item mb-4 p-4 border rounded">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label for="product_id_0" class="block text-sm font-medium text-gray-700">المنتج</label>
                        <select name="products[0][product_id]" id="product_id_0"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                            <option value="">اختر منتج</option>
                            @foreach ($Products as $product)
                                <option value="{{ $product->id }}">{{ $product->name }} (المخزن: {{ $product->stock_quantity }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="quantity_0" class="block text-sm font-medium text-gray-700">الكمية</label>
                        <input type="number" name="products[0][quantity]" id="quantity_0" min="1"
                               value="{{ old('products.0.quantity') }}"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                    </div>
                    <div>
                        <label for="purchase_price_0" class="block text-sm font-medium text-gray-700">سعر الشراء</label>
                        <input type="number" name="products[0][purchase_price]" id="purchase_price_0" step="0.01" min="0"
                               value="{{ old('products.0.purchase_price') }}"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                    </div>
                    <div>
                        <label for="selling_price_0" class="block text-sm font-medium text-gray-700">سعر البيع</label>
                        <input type="number" name="products[0][selling_price]" id="selling_price_0" step="0.01" min="0"
                               value="{{ old('products.0.selling_price') }}"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                    </div>
                </div>
                <button type="button" class="remove-product mt-2 text-red-600 hover:text-red-800 hidden">إزالة</button>
            </div>
        </div>

        <button type="button" id="add-product"
                class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 mb-4">
            إضافة منتج آخر
        </button>

        <div class="flex justify-end">
            <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
                تسجيل الشراء
            </button>
        </div>
    </form>
@endsection

@push('scripts')
    <script>
        console.log('Purchase create script loaded at:', new Date().toISOString());

        document.addEventListener('DOMContentLoaded', () => {
            console.log('DOM fully loaded');

            const addProductButton = document.getElementById('add-product');
            const productsContainer = document.getElementById('products-container');

            if (addProductButton && productsContainer) {
                console.log('Add product button and container found');

                let productIndex = 1;

                addProductButton.addEventListener('click', () => {
                    console.log('Add product button clicked, adding product #' + productIndex);

                    const newItem = document.createElement('div');
                    newItem.classList.add('product-item', 'mb-4', 'p-4', 'border', 'rounded');
                    newItem.innerHTML = `
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label for="product_id_${productIndex}" class="block text-sm font-medium text-gray-700">المنتج</label>
                                <select name="products[${productIndex}][product_id]" id="product_id_${productIndex}"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                                    <option value="">اختر منتج</option>
                                    @foreach ($Products as $product)
                    <option value="{{ $product->id }}">{{ $product->name }} (المخزن: {{ $product->stock_quantity }})</option>
                                    @endforeach
                    </select>
                </div>
                <div>
                    <label for="quantity_${productIndex}" class="block text-sm font-medium text-gray-700">الكمية</label>
                                <input type="number" name="products[${productIndex}][quantity]" id="quantity_${productIndex}" min="1"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                            </div>
                            <div>
                                <label for="purchase_price_${productIndex}" class="block text-sm font-medium text-gray-700">سعر الشراء</label>
                                <input type="number" name="products[${productIndex}][purchase_price]" id="purchase_price_${productIndex}" step="0.01" min="0"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                            </div>
                            <div>
                                <label for="selling_price_${productIndex}" class="block text-sm font-medium text-gray-700">سعر البيع</label>
                                <input type="number" name="products[${productIndex}][selling_price]" id="selling_price_${productIndex}" step="0.01" min="0"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                            </div>
                        </div>
                        <button type="button" class="remove-product mt-2 text-red-600 hover:text-red-800">إزالة</button>
                    `;
                    productsContainer.appendChild(newItem);
                    productIndex++;

                    // Show "Remove" button for the first product if more than one product exists
                    const firstRemoveButton = productsContainer.querySelector('.product-item:first-child .remove-product');
                    if (firstRemoveButton) {
                        firstRemoveButton.classList.remove('hidden');
                    }
                });

                productsContainer.addEventListener('click', (e) => {
                    if (e.target.classList.contains('remove-product')) {
                        console.log('Remove product button clicked');
                        const productItems = productsContainer.querySelectorAll('.product-item');
                        if (productItems.length > 1) {
                            e.target.parentElement.remove();
                            // Hide "Remove" button for the first product if only one remains
                            if (productItems.length === 2) {
                                const firstRemoveButton = productsContainer.querySelector('.product-item:first-child .remove-product');
                                if (firstRemoveButton) {
                                    firstRemoveButton.classList.add('hidden');
                                }
                            }
                        }
                    }
                });
            } else {
                console.error('Add product button or container not found');
            }
        });
    </script>
@endpush
