<?php

namespace Raydragneel\HerauthLib\Controllers\Api\Master;

use Raydragneel\HerauthLib\Controllers\Api\BaseResourceApi;
use Raydragneel\HerauthLib\Models\GroupModel;
use Raydragneel\HerauthLib\Models\GroupPermissionModel;
use Raydragneel\HerauthLib\Models\UserGroupModel;

class Group extends BaseResourceApi
{
    protected $modelName = GroupModel::class;

    public function index()
    {
        herauth_grant("group.post_groups");
        $data = $this->getDataRequest();
        $limit = -1;
        $offset = 0;
        if (isset($data['limit'])) {
            $limit = (int) $data['limit'];
        }
        if (isset($data['offset'])) {
            $offset = (int) $data['offset'];
        }
        if ($limit > 0) {
            $groups = $this->model->select("id,nama,deskripsi")->findAll($limit, $offset);
        } else {
            $groups = $this->model->select("id,nama,deskripsi")->findAll();
        }
        return $this->respond(["status" => true, "message" => lang("Api.successRetrieveRequest", [lang("Web.master.group")]), "data" => $groups], 200);
    }
    public function datatable()
    {
        herauth_grant("group.post_datatable");
        $data = $this->getDataRequest();
        $like = [
            'nama' => $data['search']['value'] ?? ''
        ];
        $this->request->message_after = lang("Api.successRetrieveRequest", [lang("Web.master.group")]);
        return $this->respond($this->datatable_get(['withDeleted' => true, 'like' => $like]), 200);
    }

    public function add()
    {
        herauth_grant("group.post_add");
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
            'deskripsi' => $data['deskripsi'] ?? '',
        ];

        if ($this->model->save($insertData)) {
            return $this->respond(["status" => true, "message" => lang("Api.successAddRequest", [lang("Web.master.group")]), "data" => ['redir' => herauth_base_locale_url('master/group')]], 200);
        } else {
            return $this->respond(["status" => false, "message" => lang("Api.failAddRequest", [lang("Web.master.group")]), "data" => []], 400);
        }
    }
    public function edit($id = null)
    {
        herauth_grant("group.post_edit");
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
            'deskripsi' => $data['deskripsi'] ?? '',
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
            herauth_grant("group.post_purge");
            $group = $this->model->where(['nama !=' => 'superadmin'])->withDeleted(true)->find($id);
        } else {
            herauth_grant("group.post_delete");
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
        herauth_grant("group.post_restore");
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
    public function users($id = null)
    {
        herauth_grant("group.post_users");
        $group = $this->model->withDeleted(true)->find($id);
        if ($group) {
            $user_group_model = model(UserGroupModel::class);
            $data = $this->getDataRequest();
            $limit = -1;
            $offset = 0;
            if (isset($data['limit'])) {
                $limit = (int) $data['limit'];
            }
            if (isset($data['offset'])) {
                $offset = (int) $data['offset'];
            }
            if ($limit > 0) {
                $user_groups = $user_group_model->where(['group_id' => $id])->findAll($limit, $offset);
            } else {
                $user_groups = $user_group_model->where(['group_id' => $id])->findAll();
            }
            return $this->respond(["status" => true, "message" => lang("Api.successRetrieveRequest", [lang("Web.master.user") . " " . lang("Web.master.group")]), "data" => $user_groups], 200);
        }
        return $this->respond(["status" => false, "message" => lang("Api.ApiRequestNotFound", [lang("Web.master.user") . " " . lang("Web.master.group")]), "data" => []], 404);
    }

    public function delete_user_group($id = null)
    {
        herauth_grant("group.post_delete_user_group");
        $rules = [
            'username' => [
                'label'  => lang("Auth.labelUsername"),
                'rules'  => "required",
                'errors' => []
            ]
        ];

        if (!$this->validate($rules)) {
            return $this->response->setStatusCode(400)->setJSON(["status" => false, "message" => lang("Validation.errorValidation"), "data" => $this->validator->getErrors()]);
        }
        $data = $this->getDataRequest();
        $group = $this->model->withDeleted(true)->find($id);
        if ($group) {
            $user_group_model = model(UserGroupModel::class);
            $user_group = $user_group_model->where(['group_id' => $id, 'username' => $data['username']])->withDeleted(true)->first();
            if ($user_group) {
                $delete = $user_group_model->delete($user_group->id, true);
                if ($delete) {
                    return $this->respond(["status" => true, "message" => lang("Api.successDeleteRequest", [lang("Web.master.user") . " " . lang("Web.master.group")]), "data" => []], 200);
                } else {
                    return $this->respond(["status" => false, "message" => lang("Api.failDeleteRequest", [lang("Web.master.user") . " " . lang("Web.master.group")]), "data" => []], 400);
                }
            }
        }
        return $this->respond(["status" => false, "message" => lang("Api.ApiRequestNotFound", [lang("Web.master.user") . " " . lang("Web.master.group")]), "data" => []], 404);
    }
    public function add_user_group($id = null)
    {
        herauth_grant("group.post_add_user_group");
        $data = $this->getDataRequest();
        $group = $this->model->withDeleted(true)->find($id);
        if ($group) {
            $rules = [
                'username' => [
                    'label'  => lang("Auth.labelUsername"),
                    'rules'  => "required",
                    'errors' => []
                ]
            ];

            if (!$this->validate($rules)) {
                return $this->response->setStatusCode(400)->setJSON(["status" => false, "message" => lang("Validation.errorValidation"), "data" => $this->validator->getErrors()]);
            }
            $user_group_model = model(UserGroupModel::class);
            $user_group = $user_group_model->where(['group_id' => $id, 'username' => $data['username']])->withDeleted(true)->first();
            if ($user_group) {
                $save = $user_group_model->update($user_group->id, ['deleted_at' => null]);
            } else {
                $save = $user_group_model->save(['group_id' => $id, 'username' => $data['username']]);
            }
            if ($save) {
                return $this->respond(["status" => true, "message" => lang("Api.successAddRequest", [lang("Web.master.user") . " " . lang("Web.master.group")]), "data" => []], 200);
            } else {
                return $this->respond(["status" => false, "message" => lang("Api.failAddRequest", [lang("Web.master.user") . " " . lang("Web.master.group")]), "data" => []], 400);
            }
        }
        return $this->respond(["status" => false, "message" => lang("Api.ApiRequestNotFound", [lang("Web.master.user") . " " . lang("Web.master.group")]), "data" => []], 404);
    }

    public function permissions($id = null)
    {
        herauth_grant("group.post_permissions");
        $group = $this->model->withDeleted(true)->find($id);
        if ($group) {
            $data = $this->getDataRequest();
            $limit = -1;
            $offset = 0;
            if (isset($data['limit'])) {
                $limit = (int) $data['limit'];
            }
            if (isset($data['offset'])) {
                $offset = (int) $data['offset'];
            }
            $group_permissions = $group->getPermissions($limit,$offset);
            return $this->respond(["status" => true, "message" => lang("Api.successRetrieveRequest", [lang("Web.master.group")]), "data" => $group_permissions], 200);
        }
        return $this->respond(["status" => false, "message" => lang("Api.ApiRequestNotFound", [lang("Web.master.group")]), "data" => []], 404);
    }

    public function save_permissions($id = null)
    {
        herauth_grant("group.post_save_permissions");
        $data = $this->getDataRequest();
        $group = $this->model->withDeleted(true)->find($id);
        if ($group) {
            $rules = [
                'permissions' => [
                    'label'  => lang("Web.master.permission")."s",
                    'rules'  => "required",
                    'errors' => []
                ]
            ];
    
            if (!$this->validate($rules)) {
                return $this->response->setStatusCode(400)->setJSON(["status" => false, "message" => lang("Validation.errorValidation"), "data" => $this->validator->getErrors()]);
            }
            $group_permission_model = model(GroupPermissionModel::class);
            foreach ($data['permissions'] as $permission) {
                $group_permission = $group_permission_model->where(['group_id' => $group->id, 'permission_id' => $permission['id']])->withDeleted(true)->first();
                if ($group_permission) {
                    if ($permission['checked']) {
                        $group_permission_model->update($group_permission->id, [
                            'deleted_at' => null
                        ]);
                    } else {
                        $group_permission_model->delete($group_permission->id);
                    }
                } else {
                    if ($permission['checked']) {
                        $group_permission_model->save(['group_id' => $group->id, 'permission_id' => $permission['id']]);
                    }
                }
            }
            return $this->respond(["status" => true, "message" => lang("Api.successSaveGroupRequest", [lang("Web.master.permission")]), "data" => []], 200);
        }
        return $this->respond(["status" => false, "message" => lang("Api.ApiRequestNotFound", [lang("Web.master.group")]), "data" => []], 404);
    }
}
