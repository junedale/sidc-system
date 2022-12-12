<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Users extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'emp_no'     => ['type' => 'VARCHAR', 'constraint' => '11'],
            'username'   => ['type' => 'VARCHAR', 'constraint' => 256, 'unique' => true],
            'email'      => ['type' => 'VARCHAR', 'constraint' => 256, 'unique' => true],
            'password'   => ['type' => 'VARCHAR', 'constraint' => 128],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
            'deleted_at' => ['type' => 'DATETIME', 'null' => true]
        ]);

        $this->forge->addPrimaryKey('emp_no');
        $this->forge->createTable('auth_users', true);

        $this->forge->addField([
            'emp_no'     => ['type' => 'VARCHAR', 'constraint' => 11],
            'name'       => ['type' => 'VARCHAR', 'constraint' => 256],
            'position'   => ['type' => 'VARCHAR', 'constraint' => 256],
            'department' => ['type' => 'VARCHAR', 'constraint' => 256],
            'superior'   => ['type' => 'VARCHAR', 'constraint' => 11, 'null' => true],
            'user_group' => ['type' => 'VARCHAR', 'constraint' => 64, 'default' => 'user'],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
            'deleted_at' => ['type' => 'DATETIME', 'null' => true]

        ]);

        $this->forge->addPrimaryKey('emp_no');
        $this->forge->addForeignKey('emp_no', 'auth_users', 'emp_no');
        $this->forge->createTable('auth_user_details', true);


        $this->forge->addField([
            'id'          => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'emp_no'      => ['type' => 'VARCHAR', 'constraint' => 11],
            'permission'  => ['type' => 'VARCHAR', 'constraint' => 64]
        ]);

        $this->forge->addPrimaryKey('id');
        $this->forge->addForeignKey('emp_no', 'auth_users', 'emp_no');
        $this->forge->createTable('user_permissions', true);
    }

    public function down()
    {
        $this->db->disableForeignKeyChecks();

        $this->forge->dropTable('auth_users', true);
        $this->forge->dropTable('auth_user_details'. true);
        $this->forge->dropTable('user_permissions', true);

        $this->db->enableForeignKeyChecks();

    }
}
