<?php
include "../APITokenTokopedia.php";

// Ambil data int
$productID = isset($_POST['product_id']) ? (int) $_POST['product_id'] : null;
$newPrice = isset($_POST['new_price']) ? (int) $_POST['new_price'] : null;

// Log untuk debugging
error_log("Product ID: " . $productID);
error_log("New Price: " . $newPrice);

// Validasi input
if ($productID === null || $newPrice === null) {
    echo json_encode(['error' => 'Product ID and new price are required.']);
    exit();
}

if ($newPrice <= 0) {
    echo json_encode(['error' => 'Price must be greater than 0']);
    exit();
}

// Inisialisasi APITokenTokopedia
$apiToken = new APITokenTokopedia();
$fs_id = $apiToken->getFsId();
$shop_ids = $apiToken->getShopIds();
$shop_id = $shop_ids[0];
$headers = $apiToken->getHeaders();

// URL dengan menggunakan fs_id dan shop_id dari APITokenTokopedia
$url = "https://fs.tokopedia.net/inventory/v1/fs/{$fs_id}/price/update?shop_id={$shop_id}";

// Data yang akan dikirim ke API Tokopedia
$data = [
    [
        "product_id" => $productID,
        "new_price" => $newPrice
    ]
];

// Inisialisasi cURL
$ch = curl_init($url);

curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_HTTPHEADER => array_merge($headers, ['Content-Type: application/json']),
    CURLOPT_POSTFIELDS => json_encode($data),
    CURLOPT_VERBOSE => true
]);

// Eksekusi request
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

// Log response untuk debugging
error_log("API Response: " . $response);
error_log("HTTP Code: " . $httpCode);

if (curl_errno($ch)) {
    echo json_encode([
        'error' => 'cURL Error: ' . curl_error($ch)
    ]);
} else {
    if ($httpCode == 200) {
        // Jika berhasil
        echo json_encode([
            'success' => true,
            'message' => 'Price updated successfully',
            'data' => json_decode($response, true)
        ]);
    } else {
        // Jika gagal
        echo json_encode([
            'error' => 'Failed to update price. HTTP Code: ' . $httpCode,
            'response' => json_decode($response, true)
        ]);
    }
}

curl_close($ch);
?>