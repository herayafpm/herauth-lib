<?php

namespace Raydragneel\HerauthLib\Controllers\Api;

use Raydragneel\HerauthLib\Entities\AdminEntity;
use Raydragneel\HerauthLib\Models\AdminModel;

class Auth extends BaseResourceApi
{
    protected $modelName = AdminModel::class;
    protected function rules_login($key = null)
    {
        $rules = [
            'username' => [
                'label'  => lang("Auth.labelUsername"),
                'rules'  => 'required',
                'errors' => []
            ],
            'password' => [
                'label'  => lang("Auth.labelPassword"),
                'rules'  => 'required',
                'errors' => []
            ],
        ];
        if ($key) {
            if (!key_exists($key, $rules)) {
                throw new DomainException(lang("Validation.notFound"), 400);
            } else {
                return [
                    $key => $rules[$key]
                ];
            }
        } else {
            return $rules;
        }
    }

    public function login()
    {

        try {
            $rules = $this->rules_login();
        } catch (\DomainException $th) {
            return $this->response->setStatusCode($th->getCode())->setJSON(["status" => false, "message" => $th->getMessage(), "data" => []]);
        }
        if (!$this->validate($rules)) {
            return $this->response->setStatusCode(400)->setJSON(["status" => false, "message" => lang("Validation.errorValidation"), "data" => $this->validator->getErrors()]);
        }
        $data = $this->getDataRequest();
        $admin_entity = new AdminEntity($data);
        $login_success = $this->model->attempt($admin_entity);
        $username = $admin_entity->username;
        $message = $this->model->getMessage();
        if ($login_success) {
            if ($this->jenis_akses === 'web') {
                $ses['jenis'] = 'admin';
                $ses['username'] = $username;
                $ses['nama'] = $login_success->nama;
                $this->session->set($ses);
            }
            return $this->respond(["status" => true, "message" => $message, "data" => [
                'redir' => herauth_base_url("")
            ]],200);
        } else {
            return $this->respond(["status" => false, "message" => $message, "data" => []],400);
        }
    }
}