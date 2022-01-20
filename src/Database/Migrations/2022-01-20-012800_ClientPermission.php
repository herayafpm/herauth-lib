<?php

namespace Ray\Database\Database\Migrations;

use CodeIgniter\Database\Migration;

class ClientPermission extends Migration
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
            'permission_id'      => ['type' => 'int', 'constraint' => 11, 'unsigned' => true, 'default' => 0],
            'created_at'       => ['type' => 'datetime', 'null' => true],
            'updated_at'       => ['type' => 'datetime', 'null' => true],
            'deleted_at'       => ['type' => 'datetime', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('client_id', 'herauth_client', 'id', '', '');
        $this->forge->addForeignKey('permission_id', 'herauth_permission', 'id', '', '');
        $this->forge->createTable('herauth_client_permission');
    }

    public function down()
    {
        $this->forge->dropTable('herauth_client_permission');
    }
}
