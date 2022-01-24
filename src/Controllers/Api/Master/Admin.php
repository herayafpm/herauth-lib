<?php

namespace Raydragneel\HerauthLib\Controllers\Api\Master;

use Raydragneel\HerauthLib\Controllers\Api\BaseResourceApi;
use Raydragneel\HerauthLib\Models\AdminModel;
use Raydragneel\HerauthLib\Models\UserGroupModel;

class Admin extends BaseResourceApi
{
    protected $modelName = AdminModel::class;

    public function datatable()
    {
        $data = $this->getDataRequest();
        $like = [
            'nama' => $data['search']['value'] ?? ''
        ];
        $this->request->message_after = lang("Api.successRetrieveRequest", [lang("Web.master.admin")]);
        return $this->respond($this->datatable_get(['withDeleted' => true, 'like' => $like]), 200);
    }

    public function add()
    {
        $data = $this->getDataRequest();
        $rules = [
            'username' => [
                'label'  => lang("Auth.labelUsername", [lang("Web.master.admin")]),
                'rules'  => "required|is_unique[herauth_admin.username]",
                'errors' => []
            ],
            'nama' => [
                'label'  => lang("Api.validation.master.nama", [lang("Web.master.admin")]),
                'rules'  => "required",
                'errors' => []
            ],
            'password' => [
                'label'  => lang("Auth.labelPassword", [lang("Web.master.admin")]),
                'rules'  => "required|min_length[6]",
                'errors' => []
            ],
        ];

        if (!$this->validate($rules)) {
            return $this->response->setStatusCode(400)->setJSON(["status" => false, "message" => lang("Validation.errorValidation"), "data" => $this->validator->getErrors()]);
        }
        $insertData = [
            'username' => $data['username'],
            'nama' => $data['nama'],
            'password' => $data['password'],
        ];

        if ($this->model->save($insertData)) {
            return $this->respond(["status" => true, "message" => lang("Api.successAddRequest", [lang("Web.master.admin")]), "data" => ['redir' => herauth_base_locale_url('master/admin')]], 200);
        } else {
            return $this->respond(["status" => false, "message" => lang("Api.failAddRequest", [lang("Web.master.admin")]), "data" => []], 400);
        }
    }
    public function edit($id = null)
    {
        $admin = $this->model->withDeleted(true)->find($id);
        if (!$admin) {
            return $this->response->setStatusCode(404)->setJSON(["status" => false, "message" => lang("Api.ApiRequestNotFound", [lang("Web.master.admin")]), "data" => []]);
        }
        $data = $this->getDataRequest();
        $rules = [
            'username' => [
                'label'  => lang("Auth.labelUsername", [lang("Web.master.admin")]),
                'rules'  => "required|is_unique[herauth_admin.username,id,{$id}]",
                'errors' => []
            ],
            'nama' => [
                'label'  => lang("Api.validation.master.nama", [lang("Web.master.admin")]),
                'rules'  => "required",
                'errors' => []
            ],
            'password' => [
                'label'  => lang("Auth.labelPassword", [lang("Web.master.admin")]),
                'rules'  => "sometime_len[6]",
                'errors' => []
            ],
        ];

        if (!$this->validate($rules)) {
            return $this->response->setStatusCode(400)->setJSON(["status" => false, "message" => lang("Validation.errorValidation"), "data" => $this->validator->getErrors()]);
        }
        $update_data = [
            'username' => $data['username'],
            'nama' => $data['nama'],
            'password' => $data['password'],
        ];

        if (empty($data['password'])) {
            unset($update_data['password']);
        }

        if ($this->model->update($id, $update_data)) {
            return $this->respond(["status" => true, "message" => lang("Api.successEditRequest", [lang("Web.master.admin")]), "data" => ['redir' => herauth_base_locale_url('master/admin')]], 200);
        } else {
            return $this->respond(["status" => false, "message" => lang("Api.failEditRequest", [lang("Web.master.admin")]), "data" => []], 400);
        }
    }
    public function delete($id = null)
    {
        $data = $this->getDataRequest();
        if (isset($data['purge'])) {
            $admin = $this->model->where(['username !=' => 'superadmin'])->withDeleted(true)->find($id);
        } else {
            $admin = $this->model->where(['username !=' => 'superadmin'])->find($id);
        }
        if ($admin) {
            if (isset($data['purge'])) {
                $delete = $this->model->delete($id, true);
            } else {
                $delete = $this->model->delete($id);
            }
            if ($delete) {
                if (isset($data['purge'])) {
                    $message = lang("Api.successPurgeRequest", [lang("Web.master.admin")]);
                } else {
                    $message = lang("Api.successDeleteRequest", [lang("Web.master.admin")]);
                }
                return $this->respond(["status" => true, "message" => $message, "data" => []], 200);
            } else {
                if (isset($data['purge'])) {
                    $message = lang("Api.failPurgeRequest", [lang("Web.master.admin")]);
                } else {
                    $message = lang("Api.failDeleteRequest", [lang("Web.master.admin")]);
                }
                return $this->respond(["status" => false, "message" => $message, "data" => []], 400);
            }
        }
        return $this->respond(["status" => false, "message" => lang("Api.ApiRequestNotFound", [lang("Web.master.admin")]), "data" => []], 404);
    }
    public function restore($id = null)
    {
        $admin = $this->model->withDeleted(true)->find($id);
        if ($admin) {
            if ($this->model->restore($id)) {
                return $this->respond(["status" => true, "message" => lang("Api.successRestoreRequest", [lang("Web.master.admin")]), "data" => []], 200);
            } else {
                return $this->respond(["status" => false, "message" => lang("Api.failRestoreRequest", [lang("Web.master.admin")]), "data" => []], 400);
            }
        }
        return $this->respond(["status" => false, "message" => lang("Api.ApiRequestNotFound", [lang("Web.master.admin")]), "data" => []], 404);
    }
    public function groups($id = null)
    {
        $admin = $this->model->withDeleted(true)->find($id);
        if ($admin) {
            return $this->respond(["status" => true, "message" => lang("Api.successRetrieveRequest", [lang("Web.master.admin")]), "data" => $admin[0]->groups], 200);
        }
        return $this->respond(["status" => false, "message" => lang("Api.ApiRequestNotFound", [lang("Web.master.admin")]), "data" => []], 404);
    }

    public function save_group($id = null)
    {
        $data = $this->getDataRequest();
        $admin = $this->model->withDeleted(true)->find($id);
        if ($admin) {
            $user_group_model = model(UserGroupModel::class);
            foreach ($data['groups'] as $group) {
                $user_group = $user_group_model->where(['username' => $admin->username, 'group_id' => $group['id']])->withDeleted(true)->first();
                if ($user_group) {
                    if ($group['checked']) {
                        $user_group_model->update($user_group->id, [
                            'deleted_at' => null
                        ]);
                    } else {
                        $user_group_model->delete($user_group->id);
                    }
                } else {
                    if ($group['checked']) {
                        $user_group_model->save(['username' => $admin->username, 'group_id' => $group['id']]);
                    }
                }
            }
            return $this->respond(["status" => true, "message" => lang("Api.successSaveGroupRequest",[lang("Web.master.admin")]), "data" => []], 200);
        }
        return $this->respond(["status" => false, "message" => lang("Api.ApiRequestNotFound", [lang("Web.master.admin")]), "data" => []], 404);
    }
}
