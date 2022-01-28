<?php

namespace Raydragneel\HerauthLib\Libraries;

class ClaEncrypter
{
    public static function encrypt($string = '')
    {
        $encrypter = service('encrypter');
        return $encrypter->encrypt($string);
    }
    public static function decrypt($string = '')
    {
        $encrypter = service('encrypter');
        return $encrypter->decrypt($string);
    }
}