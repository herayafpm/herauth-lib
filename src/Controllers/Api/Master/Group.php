<?php

namespace Raydragneel\HerauthLib\Controllers\Api\Master;

use Raydragneel\HerauthLib\Controllers\Api\BaseResourceApi;
use Raydragneel\HerauthLib\Models\GroupModel;

class Group extends BaseResourceApi
{
    protected $modelName = GroupModel::class;
    
    public function datatable()
    {
        $this->request->message_after = 'Berhasil mengambil group';
        return $this->respond($this->datatable_get(), 200);
    }

    public function add()
    {
        $data = $this->getDataRequest();
        $rules = [
            'nama' => [
                'label'  => "Nama",
                'rules'  => "required",
                'errors' => []
            ]
        ];

        if (!$this->validate($rules)) {
            return $this->response->setStatusCode(400)->setJSON(["status" => false, "message" => lang("Validation.errorValidation"), "data" => $this->validator->getErrors()]);
        }
        $insertData = [
            'nama' => $data['nama'],
            'deskripsi' => $data['desc'],
        ];

        if ($this->model->save($insertData)) {
            return $this->respond(["status" => true, "message" => "Berhasil menambah group", "data" => ["status" => true, "message" => "Berhasil menambah tingkatan", "data" => [
                'redir' => herauth_base_locale_url('master/group')
            ]]], 200);
        } else {
            return $this->respond(["status" => false, "message" => "Gagal menambah group", "data" => []], 400);
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
                return $this->respond(["status" => true, "message" => "Berhasil menghapus group", "data" => []], 200);
            } else {
                return $this->respond(["status" => false, "message" => "Gagal menghapus group", "data" => []], 400);
            }
        }
        return $this->respond(["status" => false, "message" => "Group tidak ditemukkan", "data" => []], 404);
    }
    public function restore($id = null)
    {
        $group = $this->model->withDeleted(true)->find($id);
        if ($group) {
            if ($this->model->restore($id)) {
                return $this->respond(["status" => true, "message" => "Berhasil mengembalikan group", "data" => []], 200);
            } else {
                return $this->respond(["status" => false, "message" => "Gagal mengembalikan group", "data" => []], 400);
            }
        }
        return $this->respond(["status" => false, "message" => "Group tidak ditemukkan", "data" => []], 404);
    }
}
