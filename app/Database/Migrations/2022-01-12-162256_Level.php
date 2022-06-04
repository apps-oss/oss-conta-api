<?php

/**
 * This file is part of the FUPAPP.
 *
 * (c) Open Solution Systems <operaciones@tumundolaboral.com.sv>
 *
 * For the full copyright and license information, please refere to LICENSE file
 * that has been distributed with this source code.
 */

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Level extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_level' => [
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => true,
            ],
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => '50'
            ],
            'number_places' => [
                'type' => 'INT',
                'constraint' => 11
            ],
            'created_by' => array(
                'type'       => 'INT',
                'constraint' => 11,
            ),
            'created_at DATETIME DEFAULT CURRENT_TIMESTAMP',
            'updated_by' => array(
                'type'       => 'INT',
                'constraint' => 11,
            ),
            'updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ',
            'deleted_by' => array(
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => true
            ),
            'deleted_at' => array(
                'type'   => 'DATETIME',
                'null'   => true
            )
        ]);

        $this->forge->addPrimaryKey('id_level');

        $this->forge->createTable('level');
    }

    public function down()
    {
        $this->forge->dropTable('level');
    }
}
