<?php

namespace Raydragneel\HerauthLib\Controllers;

class Home extends BaseController
{
    public function index()
    {
        $data = [];
        return $this->view('dashboard', $data);
    }

    
}
