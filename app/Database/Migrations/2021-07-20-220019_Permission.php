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

class Permission extends Migration
{
    // define table name
    protected $table_name  = 'permission';

    /**
     * Create the structure of the users permission
     * 
     * Stores system permissions
     */
    public function up()
    {
        // create table structure
        $this->forge->addField(array(
            'id_permission' => array(
                'type'           => 'INT',
                'constraint'     => 11,
                'auto_increment' => TRUE
            ),
            // 'id_menu' => array(
            //     'type'       => 'INT',
            //     'constraint' => 11,
            // ),
            'name' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
            ],
            'route' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
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
            'CONSTRAINT fk_permission_menu FOREIGN KEY (id_menu) 
             REFERENCES menu(id_menu)  ON DELETE RESTRICT ON UPDATE CASCADE',
            'CONSTRAINT fk_permission_created FOREIGN KEY (created_by) 
             REFERENCES user(id_user)  ON DELETE RESTRICT ON UPDATE CASCADE',
             'CONSTRAINT fk_permission_updated FOREIGN KEY (updated_by) 
             REFERENCES user(id_user)  ON DELETE RESTRICT ON UPDATE CASCADE',
             'CONSTRAINT fk_permission_deleted FOREIGN KEY (deleted_by) 
             REFERENCES user(id_user)  ON DELETE RESTRICT ON UPDATE CASCADE',
        ));

        // add primary key
        $this->forge->addKey('id_permission', TRUE);

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
