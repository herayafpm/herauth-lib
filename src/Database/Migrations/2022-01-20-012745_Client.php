<?php

namespace Raydragneel\HerauthLib\Database\Migrations;

use CodeIgniter\Database\Migration;

class Client extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'               => ['type' => 'int', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'client_key'            => ['type' => 'varchar', 'constraint' => 255],
            'nama'            => ['type' => 'varchar', 'constraint' => 255],
            'expired'            => ['type' => 'DATETIME', 'null' => true],
            'hit_limit'            => ['type' => 'int', 'constraint' => 11, 'null' => true],
            'created_at'       => ['type' => 'datetime', 'null' => true],
            'updated_at'       => ['type' => 'datetime', 'null' => true],
            'deleted_at'       => ['type' => 'datetime', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('client_key');
        $this->forge->createTable('herauth_client');
    }

    public function down()
    {
        $this->forge->dropTable('herauth_client');
    }
}
