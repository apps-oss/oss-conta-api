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

class UserType extends Migration
{

    // define table name
    protected $table_name  = 'user_type';

    /**
     * Create the user_type table structure
     * 
     * The user type table is used to define the roles of the users 
     * and the assignment of permissions, each type of user should have a 
     * list of permissions assigned in the system
     */
    public function up()
    {
        // create table structure
        $this->forge->addField(array(
            'id_user_type' => array(
                'type'           => 'INT',
                'constraint'     => 11,
                'auto_increment' => TRUE
            ),
            'name' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
            ],
            'description' => [
                'type'       => 'VARCHAR',
                'constraint' => 250
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
                'null'       => TRUE
            ),
            'deleted_at' => array(
                'type'   => 'DATETIME',
                'null'   => TRUE
            ),

        ));

        // add primary key
        $this->forge->addKey('id_user_type', TRUE);
        // create table
        $this->forge->createTable($this->table_name);

        // insert first record
        $this->db->table($this->table_name)->insert([
            'name'        => 'SUPER ADMINISTRADOR',
            'description' => 'ADMINISTRADOR CON TODOS LOS PRIVILEGIOS DEL SISTEMA',
            'created_by'  => 1,
            'updated_by'  => 1
        ]);
    }

    /**
     * delete table
     */
    public function down()
    {
        $this->forge->dropTable($this->table_name);
    }
}
