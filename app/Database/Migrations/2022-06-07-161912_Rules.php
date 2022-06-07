<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Rules extends Migration
{
    public function up()
    {

        $this->forge->addField([
             'id' => [
                'type' => 'INT',
                'auto_increment' => true,
            ],
            'ptype' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => true,
            ],
            'v0' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => true,
			],
			'v1' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => true,
			],
			'v2' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => true,
			],
			'v3' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => true,
			],
			'v4' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => true,
			],
			'v5' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('rules');
    }

    public function down()
    {
        $this->forge->dropTable('rules');
    }
}
