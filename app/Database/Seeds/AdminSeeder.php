<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class AdminSeeder extends Seeder
{
    public function run()
    {

        /*
         * Creation of admin account
         * 1. change details below for both auth data and userdata
         * 2. leave permission as it is
         * 3. run php spark db:seed AdminSeeder
         *
         * */

        $authData = [
            'emp_no'   => '000001',
            'username' => 'sample',
            'password' => password_hash('Password', PASSWORD_BCRYPT),
            'email'    => 'youremail@gmail.com'
        ];

        $userData = [
            'emp_no'     => '000001',
            'name'       => 'John Doe',
            'position'   => 'Site Administrator',
            'department' => 'ICT Main',
            'superior'   => null,
            'user_group' => 'admin'
        ];

        $permissions = ['admin.access', 'user.create', 'user.disable', 'user.edit', 'form.create', 'form.edit', 'form.cancel'];

        $this->db->table('auth_users')->insert($authData);
        $this->db->table('auth_user_details')->insert($userData);

        foreach($permissions as $permission)
        {
            $this->db->table('user_permissions')->insert(['emp_no' => '000001', 'permission' => $permission]);
        }
    }
}
