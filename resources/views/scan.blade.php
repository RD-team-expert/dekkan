<!DOCTYPE html>
<html>
<head>
    <title>Scan QR Code</title>
</head>
<body>
<h1>Scan Product QR Code</h1>
<div id="qr-reader" style="width: 500px"></div>
<div id="result"></div>

<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
<script>
    const html5QrCode = new Html5Qrcode("qr-reader");
    const qrCodeSuccessCallback = (decodedText, decodedResult) => {
        // Send the scanned QR code value to the Laravel backend
        fetch('/scan-product', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
            },
            body: JSON.stringify({ qr_code: decodedText }),
        })
            .then(response => response.json())
            .then(data => {
                if (data.product) {
                    document.getElementById('result').innerText =
                        `Product Found: ${data.product.name} (Price: ${data.product.price})`;
                } else {
                    document.getElementById('result').innerText = 'Product not found!';
                }
            })
            .catch(error => {
                document.getElementById('result').innerText = 'Error scanning product!';
            });

        // Stop scanning after successful scan
        html5QrCode.stop();
    };

    // Start scanning
    html5QrCode.start(
        { facingMode: "environment" }, // Use back camera
        { fps: 10, qrbox: 250 }, // Scanning settings
        qrCodeSuccessCallback,
        (errorMessage) => {
            console.log('QR Code scan error:', errorMessage);
        }
    );
</script>
</body>
</html>
