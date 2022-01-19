<?php

namespace Raydragneel\HerauthLib\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;
use DomainException;

class AuthFilter implements FilterInterface
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
            if ($request->uri->getSegments()[0] === 'api') {

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
