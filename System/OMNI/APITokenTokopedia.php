<?php
class APITokenTokopedia
{
    private $token;
    private $fs_id;
    private $shop_ids;

    public function __construct()
    {
        $this->token = "Bearer c:BH5ccmX6Qg6upAtYrUbV6A"; // Pastikan token ini valid
        $this->fs_id = 15239;
        $this->shop_ids = [5312174]; // Pastikan ID toko ini valid
    }

    public function getToken()
    {
        return $this->token;
    }

    public function getFsId()
    {
        return $this->fs_id;
    }

    public function getShopIds()
    {
        return $this->shop_ids;
    }

    public function getHeaders()
    {
        return [
            "Authorization: {$this->token}",
        ];
    }
}
?>