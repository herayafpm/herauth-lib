<?php

namespace Raydragneel\HerauthLib\Controllers\Api\Master;

use Raydragneel\HerauthLib\Controllers\Api\BaseResourceApi;
use Raydragneel\HerauthLib\Models\PermissionModel;

class Permission extends BaseResourceApi
{
    protected $modelName = PermissionModel::class;

    public function datatable()
    {
        $data = $this->getDataRequest();
        $like = [
            'nama' => $data['search']['value'] ?? ''
        ];
        $this->request->message_after = lang("Api.successRetrieveRequest", [lang("Web.master.permission")]);
        return $this->respond($this->datatable_get(['withDeleted' => true,'like' => $like]), 200);
    }

    public function add()
    {
        $data = $this->getDataRequest();
        $rules = [
            'nama' => [
                'label'  => lang("Api.validation.master.nama", [lang("Web.master.permission")]),
                'rules'  => "required|is_unique[herauth_permission.nama]",
                'errors' => []
            ]
        ];

        if (!$this->validate($rules)) {
            return $this->response->setStatusCode(400)->setJSON(["status" => false, "message" => lang("Validation.errorValidation"), "data" => $this->validator->getErrors()]);
        }
        $insertData = [
            'nama' => $data['nama'],
            'deskripsi' => $data['deskripsi'],
        ];

        if ($this->model->save($insertData)) {
            return $this->respond(["status" => true, "message" => lang("Api.successAddRequest", [lang("Web.master.permission")]), "data" => ['redir' => herauth_base_locale_url('master/permission')]], 200);
        } else {
            return $this->respond(["status" => false, "message" => lang("Api.failAddRequest", [lang("Web.master.permission")]), "data" => []], 400);
        }
    }
    public function edit($id = null)
    {
        $permission = $this->model->find($id);
        if (!$permission) {
            return $this->response->setStatusCode(404)->setJSON(["status" => false, "message" => lang("Api.ApiRequestNotFound", [lang("Web.master.permission")]), "data" => []]);
        }
        $data = $this->getDataRequest();
        $rules = [
            'nama' => [
                'label'  => lang("Api.validation.master.nama", [lang("Web.master.permission")]),
                'rules'  => "required|is_unique[herauth_permission.nama,id,{$id}]",
                'errors' => []
            ]
        ];

        if (!$this->validate($rules)) {
            return $this->response->setStatusCode(400)->setJSON(["status" => false, "message" => lang("Validation.errorValidation"), "data" => $this->validator->getErrors()]);
        }
        $update_data = [
            'nama' => $data['nama'],
            'deskripsi' => $data['deskripsi'],
        ];

        if ($this->model->update($id, $update_data)) {
            return $this->respond(["status" => true, "message" => lang("Api.successEditRequest", [lang("Web.master.permission")]), "data" => ['redir' => herauth_base_locale_url('master/permission')]], 200);
        } else {
            return $this->respond(["status" => false, "message" => lang("Api.failEditRequest", [lang("Web.master.permission")]), "data" => []], 400);
        }
    }
    public function delete($id = null)
    {
        $data = $this->getDataRequest();
        if (isset($data['purge'])) {
            $permission = $this->model->withDeleted(true)->find($id);
        } else {
            $permission = $this->model->find($id);
        }
        if ($permission) {
            if (isset($data['purge'])) {
                $delete = $this->model->delete($id, true);
            } else {
                $delete = $this->model->delete($id);
            }
            if ($delete) {
                if (isset($data['purge'])) {
                    $message = lang("Api.successPurgeRequest", [lang("Web.master.permission")]);
                } else {
                    $message = lang("Api.successDeleteRequest", [lang("Web.master.permission")]);
                }
                return $this->respond(["status" => true, "message" => $message, "data" => []], 200);
            } else {
                if (isset($data['purge'])) {
                    $message = lang("Api.failPurgeRequest", [lang("Web.master.permission")]);
                } else {
                    $message = lang("Api.failDeleteRequest", [lang("Web.master.permission")]);
                }
                return $this->respond(["status" => false, "message" => $message, "data" => []], 400);
            }
        }
        return $this->respond(["status" => false, "message" => lang("Api.ApiRequestNotFound", [lang("Web.master.permission")]), "data" => []], 404);
    }
    public function restore($id = null)
    {
        $permission = $this->model->withDeleted(true)->find($id);
        if ($permission) {
            if ($this->model->restore($id)) {
                return $this->respond(["status" => true, "message" => lang("Api.successRestoreRequest", [lang("Web.master.permission")]), "data" => []], 200);
            } else {
                return $this->respond(["status" => false, "message" => lang("Api.failRestoreRequest", [lang("Web.master.permission")]), "data" => []], 400);
            }
        }
        return $this->respond(["status" => false, "message" => lang("Api.ApiRequestNotFound", [lang("Web.master.permission")]), "data" => []], 404);
    }
}
