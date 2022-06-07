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

class AccountingCatalog extends Migration
{
    protected $table_name  = 'accounting_catalog';
    public function up()
    {
        // create table structure
        $this->forge->addField([
            'id_catalog' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'auto_increment' => TRUE
            ],
            'code' => [
                'type'       => 'TINYINT',
                'constraint' => 255,
            ],
            'description' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'level' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
            ],
            'exercivse_settlement' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
            ],
            'charge_credit' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
            ],
            'sign' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
            ],
            'letter' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
            ],
            'chargeable' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
            ],
            'reference' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
            ],
            'budget' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
            ],
            'type' => [
                'type'       => 'VARCHAR',
                'constraint' => 10,
            ],
            'created_by' => [
                'type'       => 'INT',
                'constraint' => 11
            ],
            'created_at DATETIME DEFAULT CURRENT_TIMESTAMP',
            'updated_by' => [
                'type'       => 'INT',
                'constraint' => 11
            ],
            'updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
             on update CURRENT_TIMESTAMP',
            'deleted_by' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => TRUE
            ],
            'deleted_at' => [
                'type'   => 'DATETIME',
                'null'   => TRUE
            ],
        ]);

        // add primary key
        $this->forge->addKey('id_catalog', TRUE);
        $this->forge->addUniqueKey('code');
        // create table
        $this->forge->createTable($this->table_name);
    }

    public function down()
    {
        $this->forge->dropTable($this->table_name);
    }
}
