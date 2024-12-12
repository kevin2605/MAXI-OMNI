<?php
include "../APITokenTokopedia.php";

$productID = isset($_POST['product_id']) ? (int) $_POST['product_id'] : null;
$newStock = isset($_POST['new_stock']) ? (int) $_POST['new_stock'] : null;

error_log("Product ID: " . $productID);
error_log("New Stock: " . $newStock);

if ($productID === null || $newStock === null) {
    echo json_encode(['error' => 'Product ID and new stock are required.']);
    exit();
}

if ($newStock < 0) {
    echo json_encode(['error' => 'Stock cannot be negative']);
    exit();
}

$apiToken = new APITokenTokopedia();
$fs_id = $apiToken->getFsId();
$shop_ids = $apiToken->getShopIds();
$shop_id = $shop_ids[0];
$headers = $apiToken->getHeaders();

$url = "https://fs.tokopedia.net/inventory/v1/fs/{$fs_id}/stock/update?shop_id={$shop_id}";

$data = [
    [
        "product_id" => $productID,
        "new_stock" => $newStock
    ]
];

$ch = curl_init($url);

curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_HTTPHEADER => array_merge($headers, ['Content-Type: application/json']),
    CURLOPT_POSTFIELDS => json_encode($data),
    CURLOPT_VERBOSE => true
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

error_log("API Response: " . $response);
error_log("HTTP Code: " . $httpCode);

if (curl_errno($ch)) {
    echo json_encode([
        'success' => false,
        'error' => 'cURL Error: ' . curl_error($ch)
    ]);
} else {
    $responseData = json_decode($response, true);
    if ($httpCode == 200) {
        echo json_encode([
            'success' => true,
            'message' => 'Stock updated successfully',
            'data' => $responseData
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'error' => 'Failed to update stock. HTTP Code: ' . $httpCode,
            'response' => $responseData
        ]);
    }
}

curl_close($ch);
?>