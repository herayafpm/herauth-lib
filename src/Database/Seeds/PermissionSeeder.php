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
                "nama" => "profil.can_get",
                "deskripsi" => "Get Data Profil (nama & username)",
                "groups" => [
                    "superadmin",
                ],
                "clients" => [
                    1
                ]
            ]
        ];
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
