<?php
require_once '../APITokenTokopedia.php';

$requestData = file_get_contents('php://input');

$data = json_decode($requestData, true);

$productID = isset($data['product_id']) ? $data['product_id'] : null;

error_log("Product ID: " . json_encode($productID));

if ($productID === null || !is_array($productID)) {
    echo json_encode(['error' => 'Product ID is required and must be an array.']);
    exit();
}

$productID = array_map('intval', $productID);

error_log("Processed Product ID: " . json_encode($productID));

$apiToken = new APITokenTokopedia();
$token = $apiToken->getToken();
$fs_id = $apiToken->getFsId();
$shop_ids = $apiToken->getShopIds();
$headers = $apiToken->getHeaders();

$url = "https://fs.tokopedia.net/v3/products/fs/{$fs_id}/delete?shop_id={$shop_ids[0]}";

$data = [
    "product_id" => $productID
];

$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, $url);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

$response = curl_exec($curl);

if (curl_errno($curl)) {
    $error_message = 'Error: ' . curl_error($curl);
    echo json_encode(['error' => $error_message]);
} else {
    $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    if ($httpCode !== 200) {
        echo json_encode(['error' => "API responded with status code {$httpCode}", 'response' => $response]);
    } else {
        $responseData = json_decode($response, true);
        echo json_encode($responseData);
    }
}
curl_close($curl);


?>