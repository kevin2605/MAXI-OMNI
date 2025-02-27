<?php
require_once '../APITokenTokopedia.php';


function getAllCategories()
{
    $apiToken = new APITokenTokopedia();
    $headers = $apiToken->getHeaders();
    $fs_id = $apiToken->getFsId();

    // URL API untuk mendapatkan kategori tanpa keyword
    $url = "https://fs.tokopedia.net/inventory/v1/fs/{$fs_id}/product/category";

    error_log("URL API untuk mendapatkan kategori: " . $url);

    // Inisialisasi cURL
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

    // Eksekusi request
    $response = curl_exec($curl);
    $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);

    if (curl_errno($curl)) {
        $error_message = 'Error: ' . curl_error($curl);
        error_log("Error saat mengambil data kategori: " . $error_message);
        curl_close($curl);
        return [];
    } else {
        error_log("Response HTTP Code: " . $http_code);
        error_log("Response dari API kategori: " . $response);

        curl_close($curl);
        $responseData = json_decode($response, true);

        // Cek apakah response valid dan mengandung kategori
        if ($http_code == 200 && isset($responseData['data']['categories'])) {
            return $responseData['data']['categories'];
        } else {
            error_log("Data kategori tidak ditemukan atau response tidak valid.");
            return [];
        }
    }
}

// Panggil fungsi tanpa keyword
$categories = getAllCategories();



?>