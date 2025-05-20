@extends('layouts.app-layout')

@section('title', 'إضافة مبيعة')
@section('header', 'إضافة مبيعة')
@section('add-route', route('sales.create'))

@section('content')
    <form action="{{ route('sales.store') }}" method="POST" class="bg-white p-6 rounded shadow-md">
        @csrf
        <div class="mb-4">
            <label for="date_time" class="block text-sm font-medium text-gray-700">تاريخ ووقت المبيعة</label>
            <input type="datetime-local" name="date_time" id="date_time" value="{{ old('date_time') }}"
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
        </div>

        <div id="products-container">
            <div class="product-item mb-4 p-4 border rounded">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label for="product_id_0" class="block text-sm font-medium text-gray-700">المنتج</label>
                        <select name="products[0][product_id]" id="product_id_0"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm product-select" required>
                            <option value="">اختر منتج</option>
                            @foreach ($products as $product)
                                <option value="{{ $product->id }}" data-selling-price="{{ $product->latestPurchase->selling_price ?? 0 }}">
                                    {{ $product->name }} (المخزن: {{ $product->stock_quantity }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="quantity_0" class="block text-sm font-medium text-gray-700">الكمية</label>
                        <input type="number" name="products[0][quantity]" id="quantity_0" min="1"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm quantity-input" required>
                    </div>
                    <div>
                        <label for="total_price_0" class="block text-sm font-medium text-gray-700">السعر الإجمالي</label>
                        <input type="number" name="products[0][total_price]" id="total_price_0" step="0.01" min="0"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm bg-gray-100" readonly>
                    </div>
                </div>
                <button type="button" class="remove-product mt-2 text-red-600 hover:text-red-800 hidden">إزالة</button>
            </div>
        </div>

        <button type="button" id="add-product"
                class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 mb-4">
            إضافة منتج آخر
        </button>

        <div class="mb-4">
            <label for="grand_total" class="block text-sm font-medium text-gray-700">الإجمالي الكلي</label>
            <input type="number" id="grand_total" step="0.01" min="0"
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm bg-gray-100" readonly>
        </div>

        <div class="flex justify-end">
            <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
                تسجيل المبيعة
            </button>
        </div>
    </form>
@endsection

@push('scripts')
    <script>
        console.log('Sales create script loaded');

        document.addEventListener('DOMContentLoaded', () => {
            console.log('DOM fully loaded');

            const addProductButton = document.getElementById('add-product');
            const productsContainer = document.getElementById('products-container');
            const grandTotalInput = document.getElementById('grand_total');

            if (addProductButton && productsContainer && grandTotalInput) {
                console.log('Add product button, container, and grand total found');

                let productIndex = 1;

                function updateTotalPrice(index) {
                    const select = document.getElementById(`product_id_${index}`);
                    const quantity = document.getElementById(`quantity_${index}`).value || 0;
                    const sellingPrice = select ? parseFloat(select.options[select.selectedIndex].getAttribute('data-selling-price') || 0) : 0;
                    const totalPrice = (quantity * sellingPrice).toFixed(2);
                    document.getElementById(`total_price_${index}`).value = totalPrice;
                    updateGrandTotal();
                }

                function updateGrandTotal() {
                    let grandTotal = 0;
                    document.querySelectorAll('[id^="total_price_"]').forEach(input => {
                        grandTotal += parseFloat(input.value || 0);
                    });
                    grandTotalInput.value = grandTotal.toFixed(2);
                }

                // Initial event listeners for the first product
                const initialSelect = document.getElementById('product_id_0');
                const initialQuantity = document.getElementById('quantity_0');
                initialSelect.addEventListener('change', () => updateTotalPrice(0));
                initialQuantity.addEventListener('input', () => updateTotalPrice(0));

                addProductButton.addEventListener('click', () => {
                    console.log('Add product button clicked, adding product #' + productIndex);

                    const newItem = document.createElement('div');
                    newItem.classList.add('product-item', 'mb-4', 'p-4', 'border', 'rounded');
                    newItem.innerHTML = `
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label for="product_id_${productIndex}" class="block text-sm font-medium text-gray-700">المنتج</label>
                                <select name="products[${productIndex}][product_id]" id="product_id_${productIndex}"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm product-select" required>
                                    <option value="">اختر منتج</option>
                                    @foreach ($products as $product)
                    <option value="{{ $product->id }}" data-selling-price="{{ $product->latestPurchase->selling_price ?? 0 }}">
                                            {{ $product->name }} (المخزن: {{ $product->stock_quantity }})
                                        </option>
                                    @endforeach
                    </select>
                </div>
                <div>
                    <label for="quantity_${productIndex}" class="block text-sm font-medium text-gray-700">الكمية</label>
                                <input type="number" name="products[${productIndex}][quantity]" id="quantity_${productIndex}" min="1"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm quantity-input" required>
                            </div>
                            <div>
                                <label for="total_price_${productIndex}" class="block text-sm font-medium text-gray-700">السعر الإجمالي</label>
                                <input type="number" name="products[${productIndex}][total_price]" id="total_price_${productIndex}" step="0.01" min="0"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm bg-gray-100" readonly>
                            </div>
                        </div>
                        <button type="button" class="remove-product mt-2 text-red-600 hover:text-red-800">إزالة</button>
                    `;
                    productsContainer.appendChild(newItem);
                    productIndex++;

                    const firstRemoveButton = productsContainer.querySelector('.product-item:first-child .remove-product');
                    if (firstRemoveButton) {
                        firstRemoveButton.classList.remove('hidden');
                    }

                    // Attach event listeners to new inputs
                    const newSelect = newItem.querySelector('.product-select');
                    const newQuantity = newItem.querySelector('.quantity-input');
                    newSelect.addEventListener('change', () => updateTotalPrice(productIndex - 1));
                    newQuantity.addEventListener('input', () => updateTotalPrice(productIndex - 1));
                });

                productsContainer.addEventListener('click', (e) => {
                    if (e.target.classList.contains('remove-product')) {
                        console.log('Remove product button clicked');
                        const productItems = productsContainer.querySelectorAll('.product-item');
                        if (productItems.length > 1) {
                            e.target.parentElement.remove();
                            updateGrandTotal();
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
                console.error('Add product button, container, or grand total not found');
            }
        });
    </script>
@endpush
