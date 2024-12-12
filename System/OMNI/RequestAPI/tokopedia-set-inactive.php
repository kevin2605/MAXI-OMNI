<?php
include "../APITokenTokopedia.php";

// Ambil productID dari request POST
$productIDs = $_POST['product_id'];

if (!is_array($productIDs)) {
    $productIDs = [$productIDs];
}

$productIDs = array_map('intval', $productIDs);

//  API Tokopedia
$apiToken = new APITokenTokopedia();
$headers = $apiToken->getHeaders();
$fs_id = $apiToken->getFsId();
$shop_ids = $apiToken->getShopIds();
$shop_id = $shop_ids[0];

$url = "https://fs.tokopedia.net/v1/products/fs/{$fs_id}/inactive?shop_id={$shop_id}";

if (empty($productIDs)) {
    http_response_code(400);
    echo json_encode(['error' => 'Product IDs are required.']);
    exit();
}

$data = [
    "product_id" => $productIDs
];

error_log("Data yang dikirim ke API Inactive: " . json_encode($data));

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array_merge($headers, ['Content-Type: application/json']));
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

if (curl_errno($ch)) {
    $error_message = 'Curl Error: ' . curl_error($ch);
    error_log($error_message);
    echo json_encode(['error' => $error_message, 'product_ids' => $productIDs]);
} else {
    error_log("Response dari API Inactive: " . $response);
    $responseData = json_decode($response, true);

    if ($httpCode == 200) {
        echo json_encode([
            'success' => true,
            'message' => 'Produk berhasil dinonaktifkan',
            'response' => $responseData
        ]);
    } else {
        echo json_encode([
            'error' => 'Gagal menonaktifkan produk',
            'http_code' => $httpCode,
            'response' => $responseData
        ]);
    }
}

curl_close($ch);
?>