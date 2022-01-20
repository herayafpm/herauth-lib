<?php

namespace Raydragneel\HerauthLib\Controllers\Api\Master;

use Raydragneel\HerauthLib\Controllers\Api\BaseResourceApi;
use Raydragneel\HerauthLib\Models\GroupModel;

class Group extends BaseResourceApi
{
    protected $modelName = GroupModel::class;

    public function delete($id = null)
    {
        $group = $this->model->where(['nama !=' => 'superadmin'])->find($id);
        if($group){
            if($this->model->delete($id)){
                return $this->respond(["status" => true, "message" => "Berhasil menghapus group", "data" => []], 200);
            }else{
                return $this->respond(["status" => false, "message" => "Gagal menghapus group", "data" => []], 400);
            }
        }
        return $this->respond(["status" => false, "message" => "Group tidak ditemukkan", "data" => []], 404);
    }
}
