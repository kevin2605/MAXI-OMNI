<?php
class APITokenTokopedia
{
    private $token;

    public function __construct()
    {
        $this->token = "Bearer c:myeCuhAYTS68QAZCp7OcYQ";
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