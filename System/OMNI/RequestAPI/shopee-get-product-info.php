<?php
require_once '../APITokenShopee.php';
function getShopeeProducts()
{


    $apiToken = new APITokenShopee();
    $partnerId = $apiToken->getPartnerId();
    $shopId = $apiToken->getShopId();
    $accessToken = $apiToken->getAccessToken();
    $partnerKey = $apiToken->getPartnerKey();
    $host = $apiToken->getHost();
    $itemIdList = '1914178,1916268';

    $apiPath = '/api/v2/product/get_item_base_info';
    $timestamp = time();

    $baseString = sprintf("%s%s%s%s%s", $partnerId, $apiPath, $timestamp, $accessToken, $shopId);
    $signature = hash_hmac('sha256', $baseString, $partnerKey);

    $url = "https://partner.test-stable.shopeemobile.com/api/v2/product/get_item_base_info?"
        . "access_token={$accessToken}"
        . "&need_complaint_policy=true"
        . "&item_id_list={$itemIdList}"
        . "&need_tax_info=true"
        . "&partner_id={$partnerId}"
        . "&shop_id={$shopId}"
        . "&sign={$signature}"
        . "&timestamp={$timestamp}";

    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
    ]);

    $response = curl_exec($curl);
    if (curl_errno($curl)) {
        error_log('Error:' . curl_error($curl));
        return [];
    }

    curl_close($curl);

    echo "<pre>";
    print_r($response);
    echo "</pre>";

    $responseData = json_decode($response, true);
    if (isset($responseData['response']['item_list'])) {
        return $responseData['response']['item_list'];
    }

    return [];
}
function getShopeeProductVariants($product_id)
{
    $apiToken = new APITokenShopee();
    $partnerId = $apiToken->getPartnerId();
    $shopId = $apiToken->getShopId();
    $accessToken = $apiToken->getAccessToken();
    $partnerKey = $apiToken->getPartnerKey();
    $host = $apiToken->getHost();

    $api_path = '/api/v2/product/get_model_list';
    $timestamp = time();

    // Buat base string sesuai urutan yang benar
    $base_string = $partnerId . $api_path . $timestamp . $accessToken . $shopId;

    // Hitung signature menggunakan HMAC-SHA256
    $signature = hash_hmac('sha256', $base_string, $partnerKey);

    // Bangun URL API
    $url = "https://partner.test-stable.shopeemobile.com/api/v2/product/get_model_list?"
        . "access_token={$accessToken}"
        . "&item_id={$product_id}"
        . "&partner_id={$partnerId}"
        . "&shop_id={$shopId}"
        . "&sign={$signature}"
        . "&timestamp={$timestamp}";

    // Gunakan cURL untuk mengirim request
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json'
        ),
    ));

    $response = curl_exec($curl);

    if (curl_errno($curl)) {
        error_log('Error:' . curl_error($curl));
        curl_close($curl);
        return [];
    }

    curl_close($curl);

    // Decode response JSON
    $responseData = json_decode($response, true);

    if (isset($responseData['error']) && !empty($responseData['error'])) {
        error_log("Error dari API: " . $responseData['message']);
        return [];
    }

    return $responseData; // Kembalikan data respons
}

?>