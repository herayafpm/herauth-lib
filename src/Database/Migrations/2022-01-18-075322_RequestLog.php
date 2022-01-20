<?php

namespace Raydragneel\HerauthLib\Database\Migrations;

use CodeIgniter\Database\Migration;

class RequestLog extends Migration
{
    public function up()
	{
		$this->forge->addField([
			'id'               => ['type' => 'int', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
			'username'            => ['type' => 'varchar', 'constraint' => 255, 'null' => true],
			'client'      => ['type' => 'text', 'null' => true],
			'path'            => ['type' => 'varchar', 'constraint' => 255],
			'method'            => ['type' => 'varchar', 'constraint' => 255],
			'ip'            => ['type' => 'varchar', 'constraint' => 255],
            'user_agent'            => ['type' => 'text', 'null' => true],
			'status_code'            => ['type' => 'int', 'constraint' => 11],
			'status_message'            => ['type' => 'text'],
			'created_at'       => ['type' => 'datetime', 'null' => true],
			'updated_at'       => ['type' => 'datetime', 'null' => true],
			'deleted_at'       => ['type' => 'datetime', 'null' => true],
		]);
		$this->forge->addKey('id', true);
		$this->forge->createTable('herauth_request_log');
	}

	public function down()
	{
		$this->forge->dropTable('herauth_request_log');
	}
}
