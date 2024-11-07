<?php
require_once '../APITokenTokopedia.php';

function getAllProducts($page = 1, $per_page = 10)
{
    $apiToken = new APITokenTokopedia();
    $headers = $apiToken->getHeaders();
    $fs_id = $apiToken->getFsId();
    $shop_ids = $apiToken->getShopIds();

    $allProducts = [];

    foreach ($shop_ids as $shop_id) {
        $url = "https://fs.tokopedia.net/inventory/v1/fs/{$fs_id}/product/info?shop_id={$shop_id}&page={$page}&per_page={$per_page}";

        error_log("URL API untuk Shop ID $shop_id: " . $url);

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

        $response = curl_exec($curl);

        if (curl_errno($curl)) {
            $error_message = 'Error: ' . curl_error($curl);
            error_log("Error pada Shop ID $shop_id: " . $error_message);
        } else {
            error_log("Response dari API untuk Shop ID $shop_id: " . $response);

            $responseData = json_decode($response, true);
            if (isset($responseData['data']) && !empty($responseData['data'])) {
                $allProducts = array_merge($allProducts, $responseData['data']);
            } else {
                error_log("Data tidak ditemukan untuk Shop ID $shop_id.");
            }
        }

        curl_close($curl);
    }

    return $allProducts;
}

function getProductVariants($product_id)
{
    $apiToken = new APITokenTokopedia();
    $headers = $apiToken->getHeaders();
    $fs_id = $apiToken->getFsId();

    $url = "https://fs.tokopedia.net/inventory/v1/fs/{$fs_id}/product/variant/{$product_id}";

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

    $response = curl_exec($curl);
    curl_close($curl);

    return json_decode($response, true);
}
?>