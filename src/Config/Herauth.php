<?php

namespace Raydragneel\HerauthLib\Config;

use CodeIgniter\Config\BaseConfig;

class Herauth extends BaseConfig
{
    public $sym = "+";
    public $duration = 10;
    public $unit = 'seconds';
    public $symRefresh = "+";
    public $durationRefresh = 20;
    public $unitRefresh = 'seconds';

    public $privatePath = APPPATH . "../keys/private.pem";
    public $publicPath = APPPATH . "../keys/public.pem";
    public $redirectLogin = 'login';
    public $redirectMain = '';
}