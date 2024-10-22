<?php
// Ambil data int
$productID = isset($_POST['product_id']) ? (int) $_POST['product_id'] : null;
$newStock = isset($_POST['new_stock']) ? (int) $_POST['new_stock'] : null;

error_log("Product ID: " . $productID);
error_log("New Stock: " . $newStock);

if ($productID === null || $newStock === null) {
    http_response_code(400);
    echo json_encode(['error' => 'Product ID and new stock are required.']);
    exit();
}

$url = 'https://fs.tokopedia.net/inventory/v1/fs/19044/stock/update?shop_id=17971369';
$access_token = 'c:pduAyTeTRMiw_cThila3FA';

$data = [
    [
        "product_id" => $productID,
        "new_stock" => $newStock
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

$response = curl_exec($ch);

if (curl_errno($ch)) {
    echo json_encode(['error' => 'Error: ' . curl_error($ch)]);
} else {
    echo $response; // Tampilkan respons dari API
}

curl_close($ch);
?>