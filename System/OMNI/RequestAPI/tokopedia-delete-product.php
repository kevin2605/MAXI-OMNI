<?php
require_once '../APITokenTokopedia.php';

$productID = $_POST['product_id']; // ID produk yang akan dihapus, dalam bentuk array integer

if (empty($productID)) {
    echo json_encode(['error' => 'Product ID is required']);
    exit;
}

$apiToken = new APITokenTokopedia();

$token = $apiToken->getToken();
$fs_id = $apiToken->getFsId();
$shop_ids = $apiToken->getShopIds();
$headers = $apiToken->getHeaders();

// URL API untuk menghapus produk
$url = "https://fs.tokopedia.net/v3/products/fs/{$fs_id}/delete?shop_id={$shop_ids[0]}";

// Siapkan data body JSON untuk request
$data = [
    'product_id' => $productID  // Ubah menjadi array lagi di PHP untuk kebutuhan API
];

// Inisialisasi cURL
$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, $url);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));  // Mengirim data dalam format JSON
curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

// Eksekusi cURL
$response = curl_exec($curl);

// Cek error
if (curl_errno($curl)) {
    $error_message = 'Error: ' . curl_error($curl);
    echo json_encode(['error' => $error_message]);
} else {
    $responseData = json_decode($response, true);
    echo json_encode($responseData); // Mengirimkan respons API untuk debug
}

// Tutup cURL
curl_close($curl);
?>

?>