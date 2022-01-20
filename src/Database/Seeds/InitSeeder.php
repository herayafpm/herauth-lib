<?php

namespace Raydragneel\HerauthLib\Database\Seeds;

use CodeIgniter\Database\Seeder;

class InitSeeder extends Seeder
{
	public function run()
	{
		$this->call(GroupSeeder::class);
		$this->call(AdminSeeder::class);
		$this->call(ClientSeeder::class);
		$this->call(PermissionSeeder::class);
	}
}
