<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DefaultSeeder extends Seeder
{
    public function run()
    {

        $enforcer = \Config\Services::enforcer();
        // insert first record
        $this->db->table('user')->insert([
            'first_name'  => 'ADMIN',
            'last_name'   => 'ADMIN',
            'user_name'   => 'ADMIN',
            'email'       => 'admin@admin.com',
            'password'    => '0f09b5c5dff5079b1493b319b43ea18f67404e80fc66405d79868b901f6a9f9993ddb3cac426ac340791eba24e7106de4c5dd19dbde8e336c0025af5f21b998b',
            'picture'     => '',
            'status'      => 1,
            'created_by'  => 1,
            'updated_by'  => 1
        ]);

        // adding all permisions for admin user
        $enforcer->addRoleForUser(1, 'admin');

        $actions = ['index','show','store','update','destroy'];

        $modules = [
            'accounting_period',
            'daily_movement',
            'accounting_catalog',
            'master_documets',
            'closing_codes',
            'reports_titles',
            'level',
        ];

        foreach ($modules as $module) {
            foreach ($actions as $action) {
                $enforcer->addPolicy('admin', $module, $action);
            }
        }

    }
}
