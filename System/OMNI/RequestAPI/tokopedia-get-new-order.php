<?php
require_once '../APITokenTokopedia.php';
require_once '../Process/addneworders.php';

function getNewOrders($from_date, $to_date, $page, $per_page)
{
    $apiToken = new APITokenTokopedia();
    $headers = $apiToken->getHeaders();
    $fs_id = $apiToken->getFsId();
    $shop_ids = $apiToken->getShopIds();

    $products = [];

    foreach ($shop_ids as $shop_id) {
        $url = "https://fs.tokopedia.net/v2/order/list?fs_id={$fs_id}&shop_id={$shop_id}&from_date={$from_date}&to_date={$to_date}&page={$page}&per_page={$per_page}";

        error_log("URL API untuk Shop ID $shop_id: " . $url);

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

        $response = curl_exec($curl);

        if (curl_errno($curl)) {
            $error_message = 'Error: ' . curl_error($curl);
            error_log("Error pada Shop ID $shop_id: " . $error_message);
        } else {
            error_log("Response dari API untuk Shop ID $shop_id: " . $response);

            $responseData = json_decode($response, true);
            if (isset($responseData['data']) && !empty($responseData['data'])) {
                $allOrders = array_merge($products, $responseData['data']);
            } else {
                error_log("Data tidak ditemukan untuk Shop ID $shop_id.");
            }
        }

        curl_close($curl);
    }

    return $products;
}

function processNewOrder($orderData)
{
    $addNewOrders = new AddNewOrders();
    $result = $addNewOrders->processOrders([$orderData]);
    return $result;
}
?>