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

class AlterUsers extends Migration
{
    public function up()
    {
        $fields = [
            'auth_token' => [
                'type' => ' TEXT',
                'after' => 'password',
                'null' => true
            ]
        ];

        $this->forge->addColumn('user', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('user', 'auth_token');
    }
}
