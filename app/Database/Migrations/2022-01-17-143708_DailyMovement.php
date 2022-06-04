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

class DailyMovement extends Migration
{
    protected $table_name = 'daily_movement';

    public function up()
    {
        $this->forge->addField([
            'id_movement' => [
                'type' => 'INT',
                'constraint' => '11',
                'auto_increment' => true,
            ],
            'id_period' => [
                'type' => 'INT',
                'constraint' => 11
            ],
            'date' => [
                'type' => 'DATE',
            ],
            'id_document_type' => [
                'type' => 'INT',
                'constraint' => '11',
            ],
            'correlative' => [
                'type' => 'VARCHAR',
                'constraint' => '50',
            ],
            'general_concept' => [
                'type' => 'VARCHAR',
                'constraint' => '256',
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

        $this->forge->addPrimaryKey('id_movement');

        $this->forge->addForeignKey(
            'id_document_type',
            'master_documents',
            'id_document'
        );

        $this->forge->addForeignKey('id_period', 'accounting_period', 'id_period');
        
        $this->forge->createTable($this->table_name);
    }

    public function down()
    {
        $this->forge->dropTable($this->table_name);
    }
}
