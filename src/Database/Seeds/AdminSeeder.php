<?php

namespace Raydragneel\HerauthLib\Database\Seeds;
use CodeIgniter\Database\Seeder;
use Raydragneel\HerauthLib\Entities\AdminEntity;
use Raydragneel\HerauthLib\Models\AdminModel;
use Raydragneel\HerauthLib\Models\GroupModel;
use Raydragneel\HerauthLib\Models\UserGroupModel;

class AdminSeeder extends Seeder
{
	public function run()
	{
		$admin_model = model(AdminModel::class);
		$password = 'adminn';
		$datas = [
			[
				'username' => 'superadmin',
				'nama' => 'Super Admin',
				'password' => $password,
				'groups' => [
					'superadmin',
				],
			],
		];
		$group_model = model(GroupModel::class);
		$user_group_model = model(UserGroupModel::class);
		foreach ($datas as $data) {
			$admin_entity = new AdminEntity($data);
			if ($admin_model->save($admin_entity)) {
				$username = $admin_entity->username;
				foreach ($data['groups'] as $group) {
					$group = $group_model->findGroupByName($group);
					if ($group) {
						$user_group_model->save(['group_id' => $group->id, 'username' => $username]);
					}
				}
			}
		}
	}
}
