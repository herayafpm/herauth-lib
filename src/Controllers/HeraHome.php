<?php

namespace Raydragneel\HerauthLib\Controllers;

class HeraHome extends BaseController
{
    public function index()
    {
        $data = [];
        return $this->view('dashboard', $data);
    }

    public function redirLocale()
    {
        return redirect()->to(base_locale_url());
    }

    
}
