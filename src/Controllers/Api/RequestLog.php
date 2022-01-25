<?php

namespace Raydragneel\HerauthLib\Controllers\Api;

use Raydragneel\HerauthLib\Models\RequestLogModel;

class RequestLog extends BaseResourceApi
{
    protected $modelName = RequestLogModel::class;

    public function datatable()
    {
        herauth_grant("request_log.post_datatable");
        $data = $this->getDataRequest();
        $like = [
            'username' => $data['search']['value'] ?? '',
        ];
        $orLike = [
            'client' => $data['search']['value'] ?? '',
            'path' => $data['search']['value'] ?? '',
        ];
        $this->request->message_after = lang("Api.successRetrieveRequest", [lang("Web.requestLog")]);
        return $this->respond($this->datatable_get(['like' => $like,'orLike' => $orLike]), 200);
    }
}