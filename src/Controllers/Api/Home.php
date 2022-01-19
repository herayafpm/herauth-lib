<?php

namespace Raydragneel\HerauthLib\Controllers\Api;

class Home extends BaseResourceApi
{
    public function index()
    {
        return $this->respond(["status" => true, "message" => "OK", "data" => []], 200);
    }
}