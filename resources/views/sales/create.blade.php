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
    <!-- Include html5-qrcode and Select2 libraries -->
    <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(() => {
            let productIndex = 1;
            let currentScannerIndex = null;
            let html5QrcodeScanner = null;

            // Initialize Select2 for product dropdowns
            function initializeSelect2(index) {
                $(`#product_id_${index}`).select2({
                    placeholder: 'اختر منتج أو ابحث بالاسم أو الباركود',
                    allowClear: true,
                    matcher: function(params, data) {
                        if (!params.term || params.term.trim() === '') {
                            return data;
                        }
                        const term = params.term.toLowerCase();
                        const text = data.text.toLowerCase();
                        const barcode = $(data.element).data('barcode')?.toString().toLowerCase() || '';
                        return (text.includes(term) || barcode.includes(term)) ? data : null;
                    }
                });
            }

            // Initialize first product dropdown
            initializeSelect2(0);

            // Update total price for a product
            function updateTotalPrice(index) {
                const select = document.getElementById(`product_id_${index}`);
                const quantity = document.getElementById(`quantity_${index}`)?.value || 0;
                const sellingPrice = select?.selectedOptions[0]?.getAttribute('data-selling-price') || 0;
                const totalPrice = (quantity * sellingPrice).toFixed(2);
                document.getElementById(`total_price_${index}`).value = totalPrice;
                updateGrandTotal();
            }

            // Update grand total
            function updateGrandTotal() {
                let grandTotal = 0;
                document.querySelectorAll('[id^="total_price_"]').forEach(input => {
                    grandTotal += parseFloat(input.value || 0);
                });
                document.getElementById('grand_total').value = grandTotal.toFixed(2);
            }

            // Fetch product by barcode
            function fetchProductByBarcode(barcode, index) {
                if (!barcode) return;
                fetch(`{{ url('products/by-barcode') }}/${barcode}`, {
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    },
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            $(`#product_id_${index}`).val(data.product.id).trigger('change');
                            updateTotalPrice(index);
                            alert('تم العثور على المنتج!');
                        } else {
                            alert('لم يتم العثور على المنتج.');
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching product:', error);
                        alert('حدث خطأ أثناء البحث عن المنتج.');
                    });
            }

            // Event listeners for the first product
            const initialSelect = document.getElementById('product_id_0');
            const initialQuantity = document.getElementById('quantity_0');
            const initialBarcode = document.getElementById('barcode_0');
            initialSelect.addEventListener('change', () => updateTotalPrice(0));
            initialQuantity.addEventListener('input', () => updateTotalPrice(0));
            initialBarcode.addEventListener('input', () => {
                const barcode = initialBarcode.value.trim();
                if (barcode) fetchProductByBarcode(barcode, 0);
            });

            // Add new product
            document.getElementById('add-product').addEventListener('click', () => {
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
                document.getElementById('products-container').appendChild(newItem);

                // Initialize Select2 for new dropdown
                initializeSelect2(productIndex);

                // Attach event listeners
                const newSelect = document.getElementById(`product_id_${productIndex}`);
                const newQuantity = document.getElementById(`quantity_${productIndex}`);
                const newBarcode = document.getElementById(`barcode_${productIndex}`);
                newSelect.addEventListener('change', () => updateTotalPrice(productIndex));
                newQuantity.addEventListener('input', () => updateTotalPrice(productIndex));
                newBarcode.addEventListener('input', () => {
                    const barcode = newBarcode.value.trim();
                    if (barcode) fetchProductByBarcode(barcode, productIndex);
                });

                // Show remove button for first product
                const firstRemoveButton = document.querySelector('.product-item:first-child .remove-product');
                if (firstRemoveButton) {
                    firstRemoveButton.classList.remove('hidden');
                }

                productIndex++;
            });

            // Remove product
            document.getElementById('products-container').addEventListener('click', (e) => {
                if (e.target.classList.contains('remove-product')) {
                    const productItems = document.querySelectorAll('.product-item');
                    if (productItems.length > 1) {
                        e.target.parentElement.remove();
                        updateGrandTotal();
                        if (productItems.length === 2) {
                            const firstRemoveButton = document.querySelector('.product-item:first-child .remove-product');
                            if (firstRemoveButton) {
                                firstRemoveButton.classList.add('hidden');
                            }
                        }
                    }
                }
            });

            // Barcode scanner
            document.addEventListener('click', (e) => {
                if (e.target.classList.contains('scan-barcode')) {
                    const index = e.target.getAttribute('data-index');
                    const scannerContainer = document.getElementById('scanner-container');
                    scannerContainer.classList.toggle('hidden');

                    if (!scannerContainer.classList.contains('hidden')) {
                        currentScannerIndex = index;
                        html5QrcodeScanner = new Html5Qrcode("reader");
                        html5QrcodeScanner.start(
                            { facingMode: "environment" },
                            {
                                fps: 10,
                                qrbox: { width: 250, height: 100 },
                                formatsToSupport: [
                                    Html5QrcodeSupportedFormats.CODE_128,
                                    Html5QrcodeSupportedFormats.EAN_13,
                                    Html5QrcodeSupportedFormats.EAN_8,
                                    Html5QrcodeSupportedFormats.UPC_A,
                                    Html5QrcodeSupportedFormats.UPC_E
                                ]
                            },
                            (decodedText) => {
                                document.getElementById(`barcode_${index}`).value = decodedText;
                                html5QrcodeScanner.stop().then(() => {
                                    scannerContainer.classList.add('hidden');
                                    fetchProductByBarcode(decodedText, index);
                                });
                            },
                            (errorMessage) => {
                                console.warn('Scan error:', errorMessage);
                            }
                        ).catch((err) => {
                            console.error('Failed to start scanner:', err);
                            alert('فشل في تشغيل الماسح. تأكد من السماح باستخدام الكاميرا.');
                        });
                    } else if (html5QrcodeScanner) {
                        html5QrcodeScanner.stop().catch((err) => {
                            console.error('Failed to stop scanner:', err);
                        });
                    }
                }
            });
        });
    </script>
@endpush
