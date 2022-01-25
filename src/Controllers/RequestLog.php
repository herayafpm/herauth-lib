<?php

namespace Raydragneel\HerauthLib\Controllers;

use Raydragneel\HerauthLib\Models\RequestLogModel;

class RequestLog extends BaseController
{
    protected $modelName = RequestLogModel::class;

    public function index()
    {
        herauth_grant("request_log.view_index","page");
        $data = [
            'page_title' => lang("Web.requestLog"),
            'url_datatable' => herauth_web_url($this->root_view . "request_log/datatable"),
        ];
        return $this->view("request_log/index", $data);
    }
}