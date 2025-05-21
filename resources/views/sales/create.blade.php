@extends('layouts.app-layout')

@section('title', 'إضافة مبيعة')
@section('header', 'إضافة مبيعة')
@section('add-route', route('sales.create'))

@section('content')
    <form action="{{ route('sales.store') }}" method="POST" class="bg-white p-6 rounded shadow-md">
        @csrf
        <div class="mb-4">
            <label for="date_time" class="block text-sm font-medium text-gray-700">تاريخ ووقت المبيعة</label>
            <input type="datetime-local" name="date_time" id="date_time" value="{{ old('date_time', now()->format('Y-m-d\TH:i')) }}"
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
        </div>

        <div id="products-container">
            <div class="product-item mb-4 p-4 border rounded">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label for="barcode_0" class="block text-sm font-medium text-gray-700">الباركود</label>
                        <div class="flex space-x-2">
                            <input type="text" id="barcode_0" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm barcode-input"
                                   placeholder="امسح الباركود أو أدخله يدويًا">
                            <button type="button" class="scan-barcode bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600"
                                    data-index="0">مسح</button>
                        </div>
                    </div>
                    <div>
                        <label for="product_id_0" class="block text-sm font-medium text-gray-700">المنتج</label>
                        <select name="products[0][product_id]" id="product_id_0"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm product-select" required>
                            <option value="">اختر منتج</option>
                            @foreach ($products as $product)
                                <option value="{{ $product->id }}"
                                        data-selling-price="{{ $product->latestPurchase->selling_price ?? 0 }}"
                                        data-stock-quantity="{{ $product->stock_quantity }}"
                                        data-barcode="{{ $product->barcode }}">
                                    {{ $product->name }} (المخزن: {{ $product->stock_quantity }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="quantity_0" class="block text-sm font-medium text-gray-700">الكمية</label>
                        <input type="number" name="products[0][quantity]" id="quantity_0" min="1" value="1"
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

        <div id="scanner-container" class="hidden mb-4">
            <div id="reader" class="w-full max-w-md mx-auto"></div>
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
    <!-- Include html5-qrcode library -->
    <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const productsContainer = document.getElementById('products-container');
            const addProductButton = document.getElementById('add-product');
            const grandTotalInput = document.getElementById('grand_total');
            let productIndex = 0;
            let html5QrcodeScanners = [];

            // Initialize the first product row
            initProductRow(0);
            updateGrandTotal();

            // Add product button click handler
            if (addProductButton) {
                addProductButton.addEventListener('click', () => {
                    productIndex++;
                    const newItem = document.createElement('div');
                    newItem.classList.add('product-item', 'mb-4', 'p-4', 'border', 'rounded');
                    newItem.innerHTML = `
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div>
                                <label for="barcode_${productIndex}" class="block text-sm font-medium text-gray-700">الباركود</label>
                                <div class="flex space-x-2">
                                    <input type="text" id="barcode_${productIndex}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm barcode-input"
                                           placeholder="امسح الباركود أو أدخله يدويًا">
                                    <button type="button" class="scan-barcode bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600"
                                            data-index="${productIndex}">مسح</button>
                                </div>
                            </div>
                            <div>
                                <label for="product_id_${productIndex}" class="block text-sm font-medium text-gray-700">المنتج</label>
                                <select name="products[${productIndex}][product_id]" id="product_id_${productIndex}"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm product-select" required>
                                    <option value="">اختر منتج</option>
                                    @foreach ($products as $product)
                                        <option value="{{ $product->id }}"
                                                data-selling-price="{{ $product->latestPurchase->selling_price ?? 0 }}"
                                                data-stock-quantity="{{ $product->stock_quantity }}"
                                                data-barcode="{{ $product->barcode }}">
                                            {{ $product->name }} (المخزن: {{ $product->stock_quantity }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="quantity_${productIndex}" class="block text-sm font-medium text-gray-700">الكمية</label>
                                <input type="number" name="products[${productIndex}][quantity]" id="quantity_${productIndex}" min="1" value="1"
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
                    
                    // Initialize the new product row
                    initProductRow(productIndex);
                    
                    // Show remove button for the first product if more than one product exists
                    const firstRemoveButton = productsContainer.querySelector('.product-item:first-child .remove-product');
                    if (firstRemoveButton) {
                        firstRemoveButton.classList.remove('hidden');
                    }
                });
            }

            // Event delegation for remove product buttons
            productsContainer.addEventListener('click', (e) => {
                if (e.target.classList.contains('remove-product')) {
                    const productItem = e.target.closest('.product-item');
                    productItem.remove();
                    updateGrandTotal();
                    
                    // Hide remove button for the first product if only one product exists
                    const productItems = productsContainer.querySelectorAll('.product-item');
                    if (productItems.length === 1) {
                        const firstRemoveButton = productItems[0].querySelector('.remove-product');
                        if (firstRemoveButton) {
                            firstRemoveButton.classList.add('hidden');
                        }
                    }
                }
            });

            // Initialize product row functionality
            function initProductRow(index) {
                const productSelect = document.getElementById(`product_id_${index}`);
                const quantityInput = document.getElementById(`quantity_${index}`);
                const totalPriceInput = document.getElementById(`total_price_${index}`);
                const barcodeInput = document.getElementById(`barcode_${index}`);
                const scanButton = document.querySelector(`.scan-barcode[data-index="${index}"]`);
                
                // Barcode input handler
                if (barcodeInput) {
                    barcodeInput.addEventListener('input', (e) => {
                        const barcode = e.target.value.trim();
                        if (barcode) {
                            // Find product option with matching barcode
                            const productOption = Array.from(productSelect.options).find(
                                option => option.dataset.barcode === barcode
                            );
                            
                            if (productOption) {
                                productSelect.value = productOption.value;
                                // Trigger change event to update price
                                const changeEvent = new Event('change');
                                productSelect.dispatchEvent(changeEvent);
                            }
                        }
                    });
                }
                
                // Barcode scanner button handler
                if (scanButton) {
                    scanButton.addEventListener('click', () => {
                        const scannerContainer = document.getElementById('scanner-container');
                        scannerContainer.classList.remove('hidden');
                        
                        // Stop any existing scanner
                        html5QrcodeScanners.forEach(scanner => {
                            if (scanner.isScanning) {
                                scanner.stop();
                            }
                        });
                        
                        // Create new scanner
                        const html5QrcodeScanner = new Html5Qrcode("reader");
                        html5QrcodeScanners.push(html5QrcodeScanner);
                        
                        html5QrcodeScanner.start(
                            { facingMode: "environment" },
                            { fps: 10, qrbox: 250 },
                            (decodedText) => {
                                // Stop scanning after successful scan
                                html5QrcodeScanner.stop();
                                scannerContainer.classList.add('hidden');
                                
                                // Set barcode value and trigger input event
                                barcodeInput.value = decodedText;
                                const inputEvent = new Event('input', { bubbles: true });
                                barcodeInput.dispatchEvent(inputEvent);
                            },
                            (errorMessage) => {
                                // Handle scan error (optional)
                                console.error(errorMessage);
                            }
                        ).catch(err => {
                            console.error("Scanner start error:", err);
                        });
                    });
                }
                
                // Product select change handler
                if (productSelect) {
                    productSelect.addEventListener('change', () => {
                        const selectedOption = productSelect.options[productSelect.selectedIndex];
                        const sellingPrice = parseFloat(selectedOption.dataset.sellingPrice || 0);
                        const quantity = parseInt(quantityInput.value || 1);
                        
                        // Update total price
                        totalPriceInput.value = (sellingPrice * quantity).toFixed(2);
                        updateGrandTotal();
                        
                        // Update barcode field if available
                        if (barcodeInput && selectedOption.dataset.barcode) {
                            barcodeInput.value = selectedOption.dataset.barcode;
                        }
                    });
                }
                
                // Quantity input change handler
                if (quantityInput) {
                    quantityInput.addEventListener('input', () => {
                        if (productSelect.value) {
                            const selectedOption = productSelect.options[productSelect.selectedIndex];
                            const sellingPrice = parseFloat(selectedOption.dataset.sellingPrice || 0);
                            const quantity = parseInt(quantityInput.value || 1);
                            
                            // Update total price
                            totalPriceInput.value = (sellingPrice * quantity).toFixed(2);
                            updateGrandTotal();
                        }
                    });
                }
            }
            
            // Update grand total
            function updateGrandTotal() {
                const totalPriceInputs = document.querySelectorAll('[id^="total_price_"]');
                let grandTotal = 0;
                
                totalPriceInputs.forEach(input => {
                    grandTotal += parseFloat(input.value || 0);
                });
                
                if (grandTotalInput) {
                    grandTotalInput.value = grandTotal.toFixed(2);
                }
            }
        });
    </script>
@endpush

