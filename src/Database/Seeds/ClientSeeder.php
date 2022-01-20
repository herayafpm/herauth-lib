<?php

namespace Raydragneel\HerauthLib\Database\Seeds;

use CodeIgniter\Database\Seeder;
use Raydragneel\HerauthLib\Models\ClientModel;
use Raydragneel\HerauthLib\Models\ClientWhitelistModel;

class ClientSeeder extends Seeder
{
	public function run()
	{
		$client_model = model(ClientModel::class);
		$datas = [
			[
				'client_key' => '384d8a1a-8cde-4a22-803a-5a8415b0ffd8',
                'nama' => 'Testing',
                'expired' => date("Y-m-d H:i:s",strtotime("2021-02-02")),
                'hit_limit' => 100,
                'whitelists' => [
                    [
                        'whitelist_name' => 'IP Local',
                        'whitelist_type' => 'ip',
                        'whitelist_key' => '127.0.0.1'
                    ],
                    [
                        'whitelist_name' => 'Android',
                        'whitelist_type' => 'android',
                        'whitelist_key' => 'cc17c3991115kkb0kkk9c3c919c1eb9939kb2035;com.app.yourapp'
                    ],
                    [
                        'whitelist_name' => 'IOS',
                        'whitelist_type' => 'ios',
                        'whitelist_key' => 'com.app.yourapp'
                    ],
                ]
			],
		];
        $client_whitelist_model = model(ClientWhitelistModel::class);
		foreach ($datas as $data) {
			if($client_model->save($data)){
                $client_id = $client_model->getInsertID();
                foreach ($data['whitelists'] as $white) {
                    $white['client_id'] = $client_id;
                    $client_whitelist_model->save($white);
                }
            }
		}
	}
}
