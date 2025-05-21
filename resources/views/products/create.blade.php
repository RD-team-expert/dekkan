@extends('layouts.app-layout')

@section('title', 'إضافة منتج')
@section('header', 'إضافة منتج')
@section('add-route', route('products.create'))
@section('search-action', '')

@section('content')
    <form action="{{ route('products.store') }}" method="POST" class="bg-white p-6 rounded shadow-md" enctype="multipart/form-data">
        @csrf

        <div class="mb-4">
            <label for="barcode" class="block text-sm font-medium text-gray-700">الباركود</label>
            <div class="flex space-x-2">
                <input type="text" name="barcode" id="barcode" value="{{ old('barcode') }}"
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm @error('barcode') border-red-500 @enderror"
                       placeholder="امسح الباركود أو أدخله يدويًا">
                <button type="button" id="scan-barcode" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                    مسح الباركود
                </button>
            </div>
            @error('barcode')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div id="scanner-container" class="hidden mb-4">
            <div id="reader" class="w-full max-w-md mx-auto"></div>
        </div>

        <div class="mb-4">
            <label for="name" class="block text-sm font-medium text-gray-700">اسم المنتج</label>
            <input type="text" name="name" id="name" value="{{ old('name') }}"
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm @error('name') border-red-500 @enderror" required>
            @error('name')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="category" class="block text-sm font-medium text-gray-700">الفئة</label>
            <input type="text" name="category" id="category" value="{{ old('category') }}"
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm @error('category') border-red-500 @enderror" required>
            @error('category')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="stock_quantity" class="block text-sm font-medium text-gray-700">كمية المخزون</label>
            <input type="number" name="stock_quantity" id="stock_quantity" min="0" value="{{ old('stock_quantity', 0) }}"
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm @error('stock_quantity') border-red-500 @enderror" required>
            @error('stock_quantity')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="quantity_alert" class="block text-sm font-medium text-gray-700">تنبيه الكمية</label>
            <input type="number" name="quantity_alert" id="quantity_alert" min="0" value="{{ old('quantity_alert') }}"
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm @error('quantity_alert') border-red-500 @enderror" required>
            @error('quantity_alert')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="min_order" class="block text-sm font-medium text-gray-700">الحد الأدنى للطلب</label>
            <input type="number" name="min_order" id="min_order" min="0" value="{{ old('min_order') }}"
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm @error('min_order') border-red-500 @enderror" required>
            @error('min_order')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="image" class="block text-sm font-medium text-gray-700">صورة المنتج</label>
            <input type="file" name="image" id="image"
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm @error('image') border-red-500 @enderror">
            @error('image')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex justify-end">
            <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
                إضافة المنتج
            </button>
        </div>
    </form>
@endsection

@push('scripts')
    <!-- Include html5-qrcode library -->
    <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const scanButton = document.getElementById('scan-barcode');
            const scannerContainer = document.getElementById('scanner-container');
            const barcodeInput = document.getElementById('barcode');
            let html5QrcodeScanner;

            // Function to fetch product by barcode
            function fetchProductByBarcode(barcode) {
                if (!barcode) return; // Prevent fetching if barcode is empty
                fetch(`{{ url('products/by-barcode') }}/${barcode}`, {
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    },
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Auto-fill form with product details
                            document.getElementById('name').value = data.product.name;
                            document.getElementById('category').value = data.product.category || '';
                            document.getElementById('stock_quantity').value = data.product.stock_quantity;
                            document.getElementById('quantity_alert').value = data.product.quantity_alert || '';
                            document.getElementById('min_order').value = data.product.min_order || '';
                            alert('تم العثور على المنتج! يمكنك تعديل التفاصيل إذا لزم الأمر.');
                            // Redirect to edit page for existing product
                            window.location.href = `{{ url('products') }}/${data.product.id}/edit`;
                        } else {
                            // Clear form fields if no product is found
                            document.getElementById('name').value = '';
                            document.getElementById('category').value = '';
                            document.getElementById('stock_quantity').value = 0;
                            document.getElementById('quantity_alert').value = '';
                            document.getElementById('min_order').value = '';
                            alert('لم يتم العثور على المنتج. يمكنك إضافة منتج جديد باستخدام هذا الباركود.');
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching product:', error);
                        alert('حدث خطأ أثناء البحث عن المنتج.');
                    });
            }

            // Barcode scan button event
            scanButton.addEventListener('click', () => {
                scannerContainer.classList.toggle('hidden');
                if (!scannerContainer.classList.contains('hidden')) {
                    // Initialize the scanner
                    html5QrcodeScanner = new Html5Qrcode("reader");
                    html5QrcodeScanner.start(
                        { facingMode: "environment" }, // Use rear camera if available
                        {
                            fps: 10,
                            qrbox: { width: 250, height: 100 }, // Adjust for barcode shape
                            formatsToSupport: [
                                Html5QrcodeSupportedFormats.CODE_128,
                                Html5QrcodeSupportedFormats.EAN_13,
                                Html5QrcodeSupportedFormats.EAN_8,
                                Html5QrcodeSupportedFormats.UPC_A,
                                Html5QrcodeSupportedFormats.UPC_E
                            ]
                        },
                        (decodedText, decodedResult) => {
                            // On successful scan
                            barcodeInput.value = decodedText;
                            html5QrcodeScanner.stop().then(() => {
                                scannerContainer.classList.add('hidden');
                                fetchProductByBarcode(decodedText);
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
            });

            // Barcode input change event
            barcodeInput.addEventListener('input', () => {
                const barcode = barcodeInput.value.trim();
                if (barcode.length > 0) {
                    fetchProductByBarcode(barcode);
                } else {
                    // Clear form fields if barcode input is empty
                    document.getElementById('name').value = '';
                    document.getElementById('category').value = '';
                    document.getElementById('stock_quantity').value = 0;
                    document.getElementById('quantity_alert').value = '';
                    document.getElementById('min_order').value = '';
                }
            });
        });
    </script>
@endpush
