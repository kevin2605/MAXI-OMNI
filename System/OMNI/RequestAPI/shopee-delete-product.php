<?php
include "../APITokenShopee.php";

$productID = isset($_POST['product_id']) ? (int) $_POST['product_id'] : null;


error_log("Item ID: " . $productID);



$apiToken = new APITokenShopee();
$partnerId = $apiToken->getPartnerId();
$shopId = $apiToken->getShopId();
$accessToken = $apiToken->getAccessToken();
$partnerKey = $apiToken->getPartnerKey();
$api_path = '/api/v2/product/unlist_item';
$timestamp = time();

$base_string = $partnerId . $api_path . $timestamp . $accessToken . $shopId;
$signature = hash_hmac('sha256', $base_string, $partnerKey);

// URL API Shopee
$url = "https://partner.test-stable.shopeemobile.com/api/v2/product/unlist_item?"
    . "partner_id={$partnerId}"
    . "&timestamp={$timestamp}"
    . "&access_token={$accessToken}"
    . "&shop_id={$shopId}"
    . "&sign={$signature}";

$data = [

    "item_list" => [
        [
            "item_id" => $productID,
            "unlist" => true
        ]
    ]
];

// Debugging log
error_log("Request Data: " . json_encode($data));
error_log("Request URL: " . $url);
error_log("Signature: " . $signature);
error_log("Timestamp: " . $timestamp);

// Inisialisasi cURL
$ch = curl_init($url);
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
    CURLOPT_POSTFIELDS => json_encode($data),
    CURLOPT_VERBOSE => true
]);

// Eksekusi request
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

// Debugging response
error_log("API Response: " . $response);
error_log("HTTP Code: " . $httpCode);

// Cek error pada cURL
if (curl_errno($ch)) {
    echo json_encode([
        'error' => 'cURL Error: ' . curl_error($ch),
        'timestamp' => $timestamp
    ]);
} else {
    $responseData = json_decode($response, true);
    if ($httpCode == 200) {
        echo json_encode([
            'success' => true,
            'message' => 'Stock updated successfully',
            'data' => $responseData,
            'timestamp' => $timestamp
        ]);
    } else {
        echo json_encode([
            'error' => 'Failed to update stock. HTTP Code: ' . $httpCode,
            'response' => $responseData,
            'timestamp' => $timestamp
        ]);
    }
}

$responseData = json_decode($response, true);
error_log("API Debug Response Data: " . json_encode($responseData));

if (isset($responseData['data']['failure_list']) && count($responseData['data']['failure_list']) > 0) {
    error_log("API failure details: " . json_encode($responseData['data']['failure_list']));
} else {
    error_log("API update stock successful: " . json_encode($responseData));
}

// Tutup cURL
curl_close($ch);
?>