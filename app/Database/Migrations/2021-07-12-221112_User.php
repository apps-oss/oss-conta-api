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
            'id_user_type' => array(
                'type'       => 'INT',
                'constraint' => 11,
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
                'type'       => 'VARCHAR',
                'constraint' => '128',
                'comment'    => 'SHA512',
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
            'CONSTRAINT fk_user_type FOREIGN KEY (id_user_type) 
             REFERENCES user_type (id_user_type) ON DELETE RESTRICT ON UPDATE CASCADE'
        ));

        // add primary key
        $this->forge->addKey('id_user', true);

        // create table
        $this->forge->createTable($this->table_name);

        // insert first record
        $this->db->table($this->table_name)->insert([
            'id_user_type' => 1,
            'first_name'  => 'ADMIN',
            'last_name'   => 'ADMIN',
            'user_name'   => 'ADMIN',
            'email'       => 'admin@admin.com',
            'password'    => 'f146d5f4a14c117a715dcb9d1554127b7b52a08bb3642ab86f32324c5d79efc1f2cba088b4368ec63de7ba34709cbb0eb8abf5d8f66fc2755827462a9611fe69',
            'picture'     => '',
            'status'      => 1,
            'created_by'  => 1,
            'updated_by'  => 1
        ]);

        // modify the user_type table to add foreign keys
        $this->db->query('ALTER TABLE `user_type` ADD CONSTRAINT `fk_user_type_created` 
        FOREIGN KEY(`created_by`) REFERENCES user(`id_user`) 
        ON DELETE RESTRICT ON UPDATE CASCADE;');

        $this->db->query('ALTER TABLE `user_type` ADD CONSTRAINT `fk_user_type_updated`
        FOREIGN KEY(`updated_by`) REFERENCES user(`id_user`)
        ON DELETE RESTRICT ON UPDATE CASCADE;');

        $this->db->query('ALTER TABLE `user_type` ADD CONSTRAINT `fk_user_type_deleted`
        FOREIGN KEY(`deleted_by`) REFERENCES user(`id_user`)
        ON DELETE RESTRICT ON UPDATE CASCADE;');
    }

    /**
     * delete table
     */
    public function down()
    {
        $this->forge->dropTable($this->table_name);
    }
}
