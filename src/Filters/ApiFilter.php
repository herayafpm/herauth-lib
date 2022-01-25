<?php

namespace Raydragneel\HerauthLib\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;
use DomainException;
use Raydragneel\HerauthLib\Models\ClientModel;

class ApiFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $response = service("response");
        $data_res = [
            'status' => false,
            'message' => "",
            'data' => []
        ];
        try {
            $segment = 0;
            if ($request->uri->getSegments()[0] === 'herauth') {
                $segment = 1;
            }
            if ($request->uri->getSegments()[$segment] === 'api') {
                if (!$request->hasHeader('api-key')) {
                    throw new DomainException(lang("Filters.apiKey.IsRequired"));
                }
                $apiKey = $request->getHeader('api-key')->getValue() ?? '';
                if (empty($apiKey)) {
                    throw new DomainException(lang("Filters.apiKey.cannotEmpty"));
                }
                $client_model = model(ClientModel::class);
                $client = $client_model->findByClientKey($apiKey);
                if (empty($client)) {
                    throw new DomainException(lang("Filters.apiKey.notFound"));
                }
                $client->cekWhitelist();
                $request->client_data = $client;
            }
        } catch (\DomainException $th) {
            if(!empty($th->getMessage())){
                $data_res['message'] = $th->getMessage();
            }
            $after_request_filter = new AfterRequestFilter();
            $response = $response->setStatusCode(401)->setJSON($data_res);
            return $after_request_filter->after($request, $response, $arguments);
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do something here
    }
}
