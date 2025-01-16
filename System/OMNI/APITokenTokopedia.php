<?php
class APITokenTokopedia
{
    private $token;
    private $fs_id;
    private $shop_ids;

    public function __construct()
    {
        $this->token = "Bearer c:kbaK1rXLS_Ssp1Ljy-HFIw";
        $this->fs_id = 15239;
        $this->shop_ids = [8664717];
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