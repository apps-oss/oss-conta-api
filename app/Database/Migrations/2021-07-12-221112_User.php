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

class User extends Migration
{
    // define table name
    protected $table_name  = 'user';

    /**
     * Create the structure of the users table
     *
     * Stores the information of the system users
     */
    public function up()
    {
        // create table structure
        $this->forge->addField(array(
            'id_user' => array(
                'type'           => 'INT',
                'constraint'     => 11,
                'auto_increment' => true
            ),
            'first_name' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
            ],
            'last_name' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
            ],
            'user_name' => [
                'type'       => 'VARCHAR',
                'constraint' => '30',
                'unique'     => true
            ],
            'email' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
            ],
            'password' => [
                'type'       => 'LONGTEXT',
                'comment'    => 'SHA256',
            ],
            'picture' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true
            ],
            'status' => [
                'type'       => 'TINYINT',
                'constraint' => '1',
                'comment'    => '0 = disabled, 1 = enabled ',
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
            ),
        ));

        // add primary key
        $this->forge->addKey('id_user', true);

        // create table
        $this->forge->createTable($this->table_name);

    }

    /**
     * delete table
     */
    public function down()
    {
        $this->forge->dropTable($this->table_name);
    }
}
