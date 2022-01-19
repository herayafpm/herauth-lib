<?php

namespace Raydragneel\HerauthLib\Controllers;

class Auth extends BaseController
{
    public function login()
    {
        $data = [];
        return $this->view('auth/login',$data);
    }
}
