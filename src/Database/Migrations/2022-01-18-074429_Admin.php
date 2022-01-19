<?php

namespace Raydragneel\HerauthLib\Database\Migrations;

use CodeIgniter\Database\Migration;

class Admin extends Migration
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
            'username'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
                'unique'        => true
            ],
            'nama'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
            ],
            'password'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
            ],
            'created_at'       => ['type' => 'datetime', 'null' => true],
            'updated_at'       => ['type' => 'datetime', 'null' => true],
            'deleted_at'       => ['type' => 'datetime', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('herauth_admin');
    }

    public function down()
    {
        $this->forge->dropTable('herauth_admin');
    }
}
