<?php

namespace Raydragneel\HerauthLib\Database\Migrations;

use CodeIgniter\Database\Migration;

class ClientWhitelist extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'          => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'client_id'      => ['type' => 'int', 'constraint' => 11, 'unsigned' => true, 'default' => 0],
            'whitelist_name' => ['type' => 'varchar', 'constraint' => '255'],
            'whitelist_type' => ['type' => 'varchar', 'constraint' => '255'],
            'whitelist_key' => ['type' => 'varchar', 'constraint' => '255'],
            'created_at'       => ['type' => 'datetime', 'null' => true],
            'updated_at'       => ['type' => 'datetime', 'null' => true],
            'deleted_at'       => ['type' => 'datetime', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('client_id', 'herauth_client', 'id', '', '');
        $this->forge->createTable('herauth_client_whitelist');
    }

    public function down()
    {
        $this->forge->dropTable('herauth_client_whitelist');
    }
}
