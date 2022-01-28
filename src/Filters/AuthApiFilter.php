<?php

namespace Raydragneel\HerauthLib\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;
use DomainException;
use Raydragneel\HerauthLib\Libraries\ClaEncrypter;
use Raydragneel\HerauthLib\Libraries\ClaJWT;
use Raydragneel\HerauthLib\Models\ClientModel;

class AuthApiFilter implements FilterInterface
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
            if ($request->uri->getSegments()[$segment] === 'web') {
                $session = service('session');
                if (!$session->has('username')) {
                    throw new DomainException(lang("Filters.notAuthorized"));
                }
            } else {
                // if (!$request->hasHeader('user-key')) {
                //     throw new DomainException(lang("Filters.userKey.IsRequired"));
                // }
                if($request->hasHeader('user-key')){
                    $userKey = $request->getHeader('user-key')->getValue() ?? '';
                    if (empty($userKey)) {
                        throw new DomainException(lang("Filters.userKey.cannotEmpty"));
                    }
                    if (strpos($userKey, 'Bearer ') === false) {
                        throw new DomainException(lang("Filters.userKey.errorStructure"));
                    }
                    $userKey = explode(" ", $userKey);
                    if (sizeof($userKey) < 2) {
                        throw new DomainException(lang("Filters.userKey.errorStructure"));
                    }
                    $jwt = ClaJWT::decode($userKey[1]);
                    $request->__username = $jwt->username;
                    $request->__model = ClaEncrypter::decrypt($jwt->model ?? '');
                }
            }
        } catch (\UnexpectedValueException $th) {
            $data_res['message'] = $th->getMessage();
            $data_res['data'] = ['login_action' => true];
            $after_request_filter = new AfterRequestFilter();
            $response = $response->setStatusCode(401)->setJSON($data_res);
            return $after_request_filter->after($request, $response, $arguments);
        } catch (\DomainException $th) {
            if (!empty($th->getMessage())) {
                $data_res['message'] = $th->getMessage();
            }
            $after_request_filter = new AfterRequestFilter();
            $response = $response->setStatusCode(401)->setJSON($data_res);
            return $after_request_filter->after($request, $response, $arguments);
        } catch (\Exception $th) {
            $data_res['message'] = $th->getMessage();
            $after_request_filter = new AfterRequestFilter();
            $response = $response->setStatusCode(500)->setJSON($data_res);
            return $after_request_filter->after($request, $response, $arguments);
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do something here
    }
}
