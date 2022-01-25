<?php

namespace Raydragneel\HerauthLib\Database\Seeds;

use CodeIgniter\Database\Seeder;
use Raydragneel\HerauthLib\Models\ClientPermissionModel;
use Raydragneel\HerauthLib\Models\GroupModel;
use Raydragneel\HerauthLib\Models\GroupPermissionModel;
use Raydragneel\HerauthLib\Models\PermissionModel;

class PermissionSeeder extends Seeder
{
    public function run()
    {
        $permission_model = model(PermissionModel::class);
        $datas = [
            [
                "nama" => "auth.login",
                "deskripsi" => "Can Login",
                "must_login" => 0,
                "groups" => [
                    "superadmin",
                ],
                "clients" => [
                    1
                ],
            ],
            [
                "nama" => "profil.can_get",
                "deskripsi" => "Get Data Profil (nama & username)",
                "groups" => [
                    "superadmin",
                ],
                "clients" => [
                    1
                ],
            ],
        ];
        $datas = array_merge($datas,require __DIR__."/permissions/group.php");
        $datas = array_merge($datas,require __DIR__."/permissions/admin.php");
        $datas = array_merge($datas,require __DIR__."/permissions/client.php");
        $datas = array_merge($datas,require __DIR__."/permissions/permission.php");
        $datas = array_merge($datas,require __DIR__."/permissions/request_log.php");
        $group_model = model(GroupModel::class);
        $group_permission_model = model(GroupPermissionModel::class);
        $client_permission_model = model(ClientPermissionModel::class);
        foreach ($datas as $data) {
            if ($permission_model->save($data)) {
                $permission_id = $permission_model->getInsertID();
                foreach ($data["groups"] as $group) {
                    $group = $group_model->findGroupByName($group);
                    if ($group) {
                        $group_permission_model->save([
                            "group_id" => $group->id,
                            "permission_id" => $permission_id,
                        ]);
                    }
                }
                foreach ($data["clients"] as $client) {
                    $client_permission_model->save([
                        "client_id" => $client,
                        "permission_id" => $permission_id,
                    ]);
                }
            }
        }
    }
}
