<?php

namespace Raydragneel\HerauthLib\Controllers;

use CodeIgniter\Exceptions\PageNotFoundException;

class Assets extends BaseController
{
    public function file($any = false)
    {
        $path = str_replace('herauth/assets/', '', $this->request->uri->getPath());
        $file = __DIR__ . "/../../assets/$path";
        if (file_exists($file)) {
            $ctype = mime_content_type($file);
            if($ctype === 'directory'){
                throw new PageNotFoundException();
            }
            $ctype = $this->parseMimeType($path,$ctype);
            header("Pragma:public");
            header("Expired:0");
            header("Cache-Control:must-revalidate");
            header("Content-Control:public");
            header("Content-Description: File Transfer");
            header("Content-Type: $ctype");
            header("Content-Transfer-Encoding:binary");
            header("Content-Length:" . filesize($file));
            flush();
            readfile($file);
            exit();
        } else {
            throw new PageNotFoundException();
        }
    }

    public function parseMimeType($path,$ctype)
    {
        if(strpos($path,'.css') !== false){
            $ctype = 'text/css';
        }
        if(strpos($path,'.js') !== false){
            $ctype = 'text/javascript';
        }
        if(strpos($path,'.svg') !== false){
            $ctype = 'image/svg+xml';
        }
        return $ctype;
    }
}
