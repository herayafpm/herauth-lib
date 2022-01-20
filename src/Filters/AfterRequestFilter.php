<?php

namespace Raydragneel\HerauthLib\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;
use Raydragneel\HerauthLib\Models\RequestLogModel;

class AfterRequestFilter implements FilterInterface
{

    public function before(RequestInterface $request, $arguments = null)
    {
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        $request_log_model = model(RequestLogModel::class);
        $ipAddress = $request->getIPAddress();
        $userAgent = $request->getUserAgent()->getAgentString() ?? '';
        $path = $request->uri->getPath();
        $method = $request->getMethod();
        $username = null;
        if ($response->getStatusCode() != 500) {
            $body = json_decode($response->getBody());
            $message = $body->message ?? "";
        }else{
            $message = $response->getReason();
        }
        if (!empty($request->jenis_akses)) {
            if($request->jenis_akses === 'web'){
                $session = service('session');
                if($session->has('username')){
                    $username = $session->get('username');
                }
            }
        }
        $request_log_model->save([
            'username'            => $username,
            'path'            => $path,
            'method'            => $method,
            'ip'            => $ipAddress,
            'user_agent'            => $userAgent,
            'status_code'            => $response->getStatusCode(),
            'status_message'            => $message,
        ]);
        return $response;
    }
}