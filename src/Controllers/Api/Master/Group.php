<?php

namespace Raydragneel\HerauthLib\Controllers\Api\Master;

use Raydragneel\HerauthLib\Controllers\Api\BaseResourceApi;
use Raydragneel\HerauthLib\Models\GroupModel;

class Group extends BaseResourceApi
{
    protected $modelName = GroupModel::class;

    public function index()
    {
        $data = $this->getDataRequest();
        $groups = $this->model->select("id,nama,deskripsi")->findAll();
        return $this->respond(["status" => true, "message" => lang("Api.successRetrieveRequest", [lang("Web.master.group")]), "data" => $groups], 200);
    }
    public function datatable()
    {
        $data = $this->getDataRequest();
        $like = [
            'nama' => $data['search']['value'] ?? ''
        ];
        $this->request->message_after = lang("Api.successRetrieveRequest", [lang("Web.master.group")]);
        return $this->respond($this->datatable_get(['withDeleted' => true,'like' => $like]), 200);
    }

    public function add()
    {
        $data = $this->getDataRequest();
        $rules = [
            'nama' => [
                'label'  => lang("Api.validation.master.nama", [lang("Web.master.group")]),
                'rules'  => "required|is_unique[herauth_group.nama]",
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
            return $this->respond(["status" => true, "message" => lang("Api.successAddRequest", [lang("Web.master.group")]), "data" => ['redir' => herauth_base_locale_url('master/group')]], 200);
        } else {
            return $this->respond(["status" => false, "message" => lang("Api.failAddRequest", [lang("Web.master.group")]), "data" => []], 400);
        }
    }
    public function edit($id = null)
    {
        $group = $this->model->withDeleted(true)->find($id);
        if (!$group) {
            return $this->response->setStatusCode(404)->setJSON(["status" => false, "message" => lang("Api.ApiRequestNotFound", [lang("Web.master.group")]), "data" => []]);
        }
        $data = $this->getDataRequest();
        $rules = [
            'nama' => [
                'label'  => lang("Api.validation.master.nama", [lang("Web.master.group")]),
                'rules'  => "required|is_unique[herauth_group.nama,id,{$id}]",
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
            return $this->respond(["status" => true, "message" => lang("Api.successEditRequest", [lang("Web.master.group")]), "data" => ['redir' => herauth_base_locale_url('master/group')]], 200);
        } else {
            return $this->respond(["status" => false, "message" => lang("Api.failEditRequest", [lang("Web.master.group")]), "data" => []], 400);
        }
    }
    public function delete($id = null)
    {
        $data = $this->getDataRequest();
        if (isset($data['purge'])) {
            $group = $this->model->where(['nama !=' => 'superadmin'])->withDeleted(true)->find($id);
        } else {
            $group = $this->model->where(['nama !=' => 'superadmin'])->find($id);
        }
        if ($group) {
            if (isset($data['purge'])) {
                $delete = $this->model->delete($id, true);
            } else {
                $delete = $this->model->delete($id);
            }
            if ($delete) {
                if (isset($data['purge'])) {
                    $message = lang("Api.successPurgeRequest", [lang("Web.master.group")]);
                } else {
                    $message = lang("Api.successDeleteRequest", [lang("Web.master.group")]);
                }
                return $this->respond(["status" => true, "message" => $message, "data" => []], 200);
            } else {
                if (isset($data['purge'])) {
                    $message = lang("Api.failPurgeRequest", [lang("Web.master.group")]);
                } else {
                    $message = lang("Api.failDeleteRequest", [lang("Web.master.group")]);
                }
                return $this->respond(["status" => false, "message" => $message, "data" => []], 400);
            }
        }
        return $this->respond(["status" => false, "message" => lang("Api.ApiRequestNotFound", [lang("Web.master.group")]), "data" => []], 404);
    }
    public function restore($id = null)
    {
        $group = $this->model->withDeleted(true)->find($id);
        if ($group) {
            if ($this->model->restore($id)) {
                return $this->respond(["status" => true, "message" => lang("Api.successRestoreRequest", [lang("Web.master.group")]), "data" => []], 200);
            } else {
                return $this->respond(["status" => false, "message" => lang("Api.failRestoreRequest", [lang("Web.master.group")]), "data" => []], 400);
            }
        }
        return $this->respond(["status" => false, "message" => lang("Api.ApiRequestNotFound", [lang("Web.master.group")]), "data" => []], 404);
    }
}
