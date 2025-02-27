<?php
include "../APITokenShopee.php";

$productID = isset($_POST['item_id']) ? (int) $_POST['item_id'] : null;
$newPrice = isset($_POST['new_price']) ? (float) $_POST['new_price'] : null;

error_log("Item ID: " . $productID);
error_log("New Price: " . $newPrice);

if ($productID === null || $newPrice === null) {
    echo json_encode(['error' => 'Item ID and new price are required.']);
    exit();
}

if ($newPrice <= 0) {
    echo json_encode(['error' => 'Price must be greater than 0']);
    exit();
}

$apiToken = new APITokenShopee();
$partnerId = $apiToken->getPartnerId();
$shopId = $apiToken->getShopId();
$accessToken = $apiToken->getAccessToken();
$partnerKey = $apiToken->getPartnerKey();

$apiPath = '/api/v2/product/update_price';
$timestamp = time();
$baseString = sprintf("%s%s%s%s%s", $partnerId, $apiPath, $timestamp, $accessToken, $shopId);
$signature = hash_hmac('sha256', $baseString, $partnerKey);

$url = "https://partner.test-stable.shopeemobile.com/api/v2/product/update_price?"
    . "partner_id={$partnerId}"
    . "&timestamp={$timestamp}"
    . "&access_token={$accessToken}"
    . "&shop_id={$shopId}"
    . "&sign={$signature}";

$data = [
    "item_id" => $productID,
    "price_list" => [
        [
            "model_id" => 0,
            "original_price" => $newPrice
        ]
    ]
];

error_log("Request Data: " . json_encode($data));
error_log("Request URL: " . $url);
error_log("Signature: " . $signature);
error_log("Timestamp: " . $timestamp);
error_log("Access Token: " . $accessToken);

$ch = curl_init($url);
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
    CURLOPT_POSTFIELDS => json_encode($data),
    CURLOPT_VERBOSE => true
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

error_log("API Response: " . $response);
error_log("HTTP Code: " . $httpCode);

if (curl_errno($ch)) {
    echo json_encode([
        'error' => 'cURL Error: ' . curl_error($ch)
    ]);
} else {
    $responseData = json_decode($response, true);
    if ($httpCode == 200) {
        echo json_encode([
            'success' => true,
            'message' => 'Price updated successfully',
            'data' => $responseData
        ]);
    } else {
        echo json_encode([
            'error' => 'Failed to update price. HTTP Code: ' . $httpCode,
            'response' => $responseData
        ]);
    }
}

curl_close($ch);
?>