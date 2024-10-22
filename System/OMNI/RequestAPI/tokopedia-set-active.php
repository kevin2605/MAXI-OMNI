<?php
// Ambil productID dari request POST
$productIDs = $_POST['product_id'];

if (!is_array($productIDs)) {
    $productIDs = [$productIDs];
}

$productIDs = array_map('intval', $productIDs);

$url = "https://fs.tokopedia.net/v1/products/fs/19044/active?shop_id=17971369";
$access_token = 'c:pduAyTeTRMiw_cThila3FA';

if (empty($productIDs)) {
    http_response_code(400);
    echo json_encode(['error' => 'Product IDs are required.']);
    exit();
}

$data = [
    "product_id" => $productIDs
];

error_log("Data yang dikirim ke API: " . json_encode($data)); // Log data to the server's error log

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Authorization: Bearer ' . trim($access_token),
    'Content-Type: application/json'
]);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

$response = curl_exec($ch);

// Check for cURL errors
if (curl_errno($ch)) {
    $error_message = 'Error: ' . curl_error($ch);
    error_log($error_message); // Log the error message
    echo json_encode(['error' => $error_message, 'product_ids' => $productIDs]);
} else {
    // Check if the response is empty
    if (empty($response)) {
        error_log("Response dari API adalah kosong untuk product IDs: " . json_encode($productIDs)); // Log the empty response
        echo json_encode(['error ' => 'Response is empty', 'product_ids' => $productIDs]);
    } else {
        // Log the response from the API
        error_log("Response dari API: " . $response); // Log response to the server's error log
        echo json_encode(['response' => json_decode($response), 'product_ids' => $productIDs]);
    }
}

curl_close($ch);
?>