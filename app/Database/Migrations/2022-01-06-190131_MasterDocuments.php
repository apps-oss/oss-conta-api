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

class MasterDocuments extends Migration
{
    protected $table_name = 'master_documents';

    public function up()
    {
        $this->forge->addField([
            'id_document' => [
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => true,
            ],
            'document_code' => [
                'type' => 'VARCHAR',
                'constraint' => '50',
            ],
            'document_name' => [
                'type' => 'VARCHAR',
                'constraint' => '50',
            ],
            'document_number' => [
                'type' => 'INT',
                'constraint' => '11'
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

        $this->forge->addPrimaryKey('id_document');

        $this->forge->createTable($this->table_name);
    }

    public function down()
    {
        $this->forge->dropTable($this->table_name);
    }
}
