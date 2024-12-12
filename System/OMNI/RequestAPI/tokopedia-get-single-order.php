<?php
require_once '../APITokenTokopedia.php';
require_once '../Process/addneworders.php';
// require_once '../../DBConnection.php';

// $query = "SELECT OrderID FROM order_header WHERE OrderStatus NOT IN ('600', '700') AND OrderID IS NOT NULL";
// $result = $conn->query($query);

// if ($result === false) {
//     die("Error: Query database gagal dijalankan: " . $conn->error);
// }

function getSingleOrder($order_id)
{
    if (empty($order_id)) {
        error_log('Error: Order ID tidak boleh kosong.');
        return null;
    }

    if (!preg_match('/^\d+$/', $order_id)) {
        error_log("Error: Order ID {$order_id} memiliki format tidak valid.");
        return null;
    }

    $apiToken = new APITokenTokopedia();
    $token = $apiToken->getToken();
    $fs_id = $apiToken->getFsId();


    $singleorderData = [];

    if (empty($token) || empty($fs_id)) {
        error_log('Error: Token atau FS ID tidak valid.');
        return null;
    }

    $url = "https://fs.tokopedia.net/v2/fs/{$fs_id}/order?order_id={$order_id}";
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

    $headers = [
        "Authorization: {$token}",
    ];
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

    $response = curl_exec($curl);

    if (curl_errno($curl)) {
        error_log("Error saat mencoba mengambil OrderID: {$order_id}, Error: " . curl_error($curl));
        curl_close($curl);
        return null;
    }

    curl_close($curl);
    if ($response == 'not found') {
        error_log("OrderID: {$order_id} tidak ditemukan.");
        return null;
    }

    $responseData = json_decode($response, true);

    if (curl_errno($curl)) {
        $error_message = 'Error: ' . curl_error($curl);
        error_log("Error pada Shop ID $order_id: " . $error_message);
    } else {
        error_log("Response dari API untuk Shop ID $order_id: " . $response);

        $responseData = json_decode($response, true);
        if (isset($responseData['data']) && !empty($responseData['data'])) {
            $singleorderData = array_merge($singleorderData, $responseData['data']);
        } else {
            error_log("Data tidak ditemukan untuk Shop ID $order_id.");
        }
    }
    $addNewOrders = new AddNewOrders();
    $orderStatus = $singleorderData['order_status'];
    $orderStatusDesc = $addNewOrders->getOrderStatusDesc($orderStatus);
    $result = $addNewOrders->updateOrderStatus($order_id, $orderStatus, $orderStatusDesc);
    if ($result) {
        error_log("Data berhasil disimpan ke dalam database.");
    } else {
        error_log("Gagal menyimpan data ke dalam database.");
    }
    return $singleorderData;
}

