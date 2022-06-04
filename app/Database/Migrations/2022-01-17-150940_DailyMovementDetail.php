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

class DailyMovementDetail extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_detail' => [
                'type' => 'INT',
                'constraint' => '11',
                'auto_increment' => true,
            ],
            'id_movement' => [
                'type' => 'INT',
                'constraint' => '11',
            ],
            'account_code' => [
                'type' => 'VARCHAR',
                'constraint' => '20'
            ],
            'description' => [
                'type' => 'VARCHAR',
                'constraint' => '256'
            ],
            'specific_concept' => [
                'type' => 'VARCHAR',
                'constraint' => '256'
            ],
            'quantity' => [
                'type' => 'INT',
                'constraint' => '11'
            ],
            'document' => [
                'type' => 'VARCHAR',
                'constraint' => '50',
                'null' => true
            ],
            'reference' => [
                'type' => 'VARCHAR',
                'constraint' => '50',
                'null' => true
            ],
            'value' => [
                'type' => 'DECIMAL',
                'constraint' => '11,2'
            ],
            'movement_type' => [
                'type' => 'TINYINT',
                'constraint' => '1'
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

        $this->forge->addPrimaryKey('id_detail');
        $this->forge->addForeignKey(
            'id_movement',
            'daily_movement',
            'id_movement'
        );
        $this->forge->createTable('daily_movement_details');
    }

    public function down()
    {
        $this->forge->dropTable('daily_movement_details');
    }
}
