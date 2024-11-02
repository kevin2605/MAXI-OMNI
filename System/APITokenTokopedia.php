<?php
class APITokenTokopedia
{
    private $token;

    public function __construct()
    {
        $this->token = "Bearer c:Kioy9qFbSYyxy_wmk0NsiA";
    }

    public function getToken()
    {
        return $this->token;
    }

    public function getHeaders()
    {
        return [
            "Authorization: {$this->token}",
        ];
    }
}
?>