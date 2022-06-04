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

class AccountingPeriods extends Migration
{
    protected $table_name = 'accounting_period';

    public function up()
    {
        $this->forge->addField([
            'id_period' => [
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => true,
            ],
            'begin_date' => [
                'type' => 'DATE',
            ],
            'end_date' => [
                'type' => 'DATE',
            ],
            'correlative' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'status' => [
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

        $this->forge->addPrimaryKey('id_period');
        
        $this->forge->createTable($this->table_name);
    }

    public function down()
    {
        $this->forge->dropTable($this->table_name);
    }
}
