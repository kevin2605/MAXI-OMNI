<?php
include "../../DBConnection.php";
require_once '../APITokenTokopedia.php';

// Ambil data dari request
$requestData = file_get_contents('php://input');
$data = json_decode($requestData, true);

// Ambil product_id dari data
$productID = isset($data['product_id']) ? $data['product_id'] : null;

error_log("Product ID: " . json_encode($productID));

// Validasi product_id
if ($productID === null || !is_array($productID)) {
    echo json_encode(['error' => 'Product ID is required and must be an array.']);
    exit();
}

// Konversi product_id ke integer
$productID = array_map('intval', $productID);

error_log("Processed Product ID: " . json_encode($productID));

try {
    // Mulai transaksi database
    $conn->beginTransaction();

    // Query untuk mengupdate status produk di database
    $placeholders = implode(',', array_fill(0, count($productID), '?'));
    $sqlUpdate = "UPDATE productomni SET Status = 2 WHERE ProductID IN ($placeholders)";
    $stmtUpdate = $conn->prepare($sqlUpdate);
    $stmtUpdate->execute($productID);

    // Commit transaksi database
    $conn->commit();

    // Jika update database berhasil, lanjutkan menghapus produk dari API Tokopedia
    $apiToken = new APITokenTokopedia();
    $token = $apiToken->getToken();
    $fs_id = $apiToken->getFsId();
    $shop_ids = $apiToken->getShopIds();
    $headers = $apiToken->getHeaders();

    // URL API untuk menghapus produk
    $url = "https://fs.tokopedia.net/v3/products/fs/{$fs_id}/delete?shop_id={$shop_ids[0]}";

    // Data yang akan dikirim ke API
    $data = [
        "product_id" => $productID
    ];

    // Inisialisasi cURL
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

    // Eksekusi request ke API
    $response = curl_exec($curl);

    if (curl_errno($curl)) {
        // Jika terjadi error pada cURL
        $error_message = 'Error: ' . curl_error($curl);
        echo json_encode(['error' => $error_message]);
    } else {
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        if ($httpCode !== 200) {
            // Jika API merespons dengan status code selain 200
            echo json_encode(['error' => "API responded with status code {$httpCode}", 'response' => $response]);
        } else {
            // Jika penghapusan dari API berhasil
            $responseData = json_decode($response, true);
            echo json_encode(["success" => "Product status updated and deleted from API successfully.", "response" => $responseData]);
        }
    }

    // Tutup koneksi cURL
    curl_close($curl);

} catch (Exception $e) {
    // Rollback transaksi database jika terjadi error
    $conn->rollBack();
    echo json_encode(["error" => $e->getMessage()]);
}
?>