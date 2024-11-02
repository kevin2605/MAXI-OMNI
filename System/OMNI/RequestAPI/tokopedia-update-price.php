<?php
include "../../APITokenTokopedia.php";

// Ambil data int
$productID = isset($_POST['product_id']) ? (int) $_POST['product_id'] : null;
$newPrice = isset($_POST['new_price']) ? (int) $_POST['new_price'] : null;

error_log("Product ID: " . $productID);
error_log("New Price: " . $newPrice);

if ($productID === null || $newPrice === null) {
    http_response_code(400);
    echo json_encode(['error' => 'Product ID and new price are required.']);
    exit();
}

$url = 'https://fs.tokopedia.net/inventory/v1/fs/19044/price/update?shop_id=17971369';
$access_token = 'c:Oiam__x1Roy-6xg708C55A';

$data = [
    [
        "product_id" => $productID,
        "new_price" => $newPrice
    ]
];

$ch = curl_init($url);

curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Authorization: Bearer ' . trim($access_token),
    'Content-Type: application/json'
]);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

curl_setopt($ch, CURLOPT_VERBOSE, true);

$response = curl_exec($ch);

if (curl_errno($ch)) {
    echo 'Error: ' . curl_error($ch);
} else {
    echo 'Response: ' . $response;
}

curl_close($ch);
?>