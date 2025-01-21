<?php
class APITokenTokopedia
{
    private $token;
    private $fs_id;
    private $shop_ids;

    public function __construct()
    {
        $this->token = "Bearer c:cie6aueR7y_Dj-Gn4EMXQ";
        $this->fs_id = 19044;
        $this->shop_ids = [17971369];
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
            "Content-Type: application/json"
        ];
    }
}
?>