<?php

namespace Raydragneel\HerauthLib\Database\Seeds;

use CodeIgniter\Database\Seeder;
use Raydragneel\HerauthLib\Models\GroupModel;

class GroupSeeder extends Seeder
{
	public function run()
	{
		$group_model = model(GroupModel::class);
		$datas = [
			[
				'nama' => 'superadmin'
			],
			[
				'nama' => 'admin'
			],
		];
		foreach ($datas as $data) {
			$group_model->save($data);
		}
	}
}
