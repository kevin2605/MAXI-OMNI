<?php

class APITokenShopee
{
    private $partnerId;
    private $partnerKey;
    private $shopId;
    private $accessToken;
    private $host;

    public function __construct()
    {
        // Isi dengan data autentikasi Shopee kamu
        $this->partnerId = 1261102;
        $this->partnerKey = '4b5979525171416f6664687a704f534a694b4f647471794f6658536c59694161';
        $this->shopId = 133104;
        $this->accessToken = '43586454624777756c7a745666495854'; // Token akses yang didapat dari getTokenShopLevel
        $this->host = 'https://partner.test-stable.shopeemobile.com';
    }
    public function getpartnerId()
    {
        return $this->partnerId;
    }
    public function getPartnerKey()
    {
        return $this->partnerKey;
    }
    public function getShopId()
    {
        return $this->shopId;
    }
    public function getAccessToken()
    {
        return $this->accessToken;
    }
    public function getHost()
    {
        return $this->host;
    }

    // Fungsi untuk mendapatkan access token baru
    public function getTokenShopLevel($code)
    {

        $path = "/api/v2/auth/token/get";
        $timest = time();
        $body = [
            "code" => $code,
            "shop_id" => $this->shopId,
            "partner_id" => $this->partnerId
        ];

        $baseString = sprintf("%s%s%s", $this->partnerId, $path, $timest);
        $sign = hash_hmac('sha256', $baseString, $this->partnerKey);

        $url = sprintf("%s%s?partner_id=%s&timestamp=%s&sign=%s", $this->host, $path, $this->partnerId, $timest, $sign);

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($body));
        curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($curl);
        curl_close($curl);

        $ret = json_decode($response, true);
        if (isset($ret['access_token'])) {
            $this->accessToken = $ret['access_token']; // Update access token
        }

        return $ret;
    }

    // Fungsi untuk generate signature
    private function generateSignature($apiPath)
    {
        $timest = time();
        $baseString = sprintf("%s%s%s%s%s", $this->partnerId, $apiPath, $timest, $this->accessToken, $this->shopId);
        return hash_hmac('sha256', $baseString, $this->partnerKey);
    }

    // Fungsi untuk mendapatkan headers
    public function getHeaders()
    {
        return [
            "Content-Type: application/json"
        ];
    }

    // Fungsi untuk build URL API
    public function buildUrl($apiPath, $params = [])
    {
        $signature = $this->generateSignature($apiPath);
        $queryParams = http_build_query([
            'partner_id' => $this->partnerId,
            'shop_id' => $this->shopId,
            'timestamp' => time(),
            'access_token' => $this->accessToken,
            'sign' => $signature
        ]);

        return sprintf("%s%s?%s", $this->host, $apiPath, $queryParams);
    }

}
?>