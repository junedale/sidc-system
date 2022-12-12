<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\RawSql;

class FormTable extends Migration
{
    public function up()
    {

        // Leave request table
        $this->forge->addField([
            'id'          => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'emp_no'      => ['type' => 'VARCHAR', 'constraint' => 11],
            'reason'      => ['type' => 'VARCHAR', 'constraint' => 256],
            'purpose'     => ['type' => 'VARCHAR', 'constraint' => 256],
            'leave_date'  => ['type' => 'DATETIME'],
            'status'      => ['type' => 'TINYINT', 'constraint' => 1, 'unsigned' => true, 'null' => true],
            'rev_by'      => ['type' => 'VARCHAR', 'constraint' => 256, 'null' => true, 'default' => null],
            'app_by'      => ['type' => 'VARCHAR', 'constraint' => 256, 'null' => true, 'default' => null],
            'initial_app' => ['type' => 'TINYINT', 'constraint' => 1, 'unsigned' => true],
            'final_app'   => ['type' => 'TINYINT', 'constraint' => 1, 'unsigned' => true],
            'date_added'  => ['type' => 'DATETIME', 'default' => new RawSql('CURRENT_TIMESTAMP')]
        ]);

        $this->forge->addPrimaryKey('id');
        $this->forge->addForeignKey('emp_no', 'auth_users', 'emp_no');
        $this->forge->createTable('leave_requests', true);

        // Overtime request table
        $this->forge->addField([
            'id'          => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'emp_no'      => ['type' => 'VARCHAR', 'constraint' => 11],
            'ot_date'     => ['type' => 'DATETIME'],
            'rev_by'      => ['type' => 'VARCHAR', 'constraint' => 256, 'null' => true, 'default' => null],
            'app_by'      => ['type' => 'VARCHAR', 'constraint' => 256, 'null' => true, 'default' => null],
            'initial_app' => ['type' => 'TINYINT', 'constraint' => 1, 'unsigned' => true],
            'final_app'   => ['type' => 'TINYINT', 'constraint' => 1, 'unsigned' => true],
            'status'      => ['type' => 'TINYINT', 'constraint' => 1, 'unsigned' => true, 'null' => true],
            'date_added'  => ['type' => 'DATETIME', 'default' => new RawSql('CURRENT_TIMESTAMP')]
        ]);

        $this->forge->addPrimaryKey('id');
        $this->forge->addForeignKey('emp_no', 'auth_users', 'emp_no');
        $this->forge->createTable('overtime_requests', true);


        // Official business table
        $this->forge->addField([
            'id'          => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'emp_no'      => ['type' => 'VARCHAR', 'constraint' => 11],
            'remarks'     => ['type' => 'VARCHAR', 'constraint' => 256, 'null' => true, 'default' => null],
            'transit'     => ['type' => 'VARCHAR', 'constraint' => 64],
            'rev_by'      => ['type' => 'VARCHAR', 'constraint' => 256, 'null' => true, 'default' => null],
            'app_by'      => ['type' => 'VARCHAR', 'constraint' => 256, 'null' => true, 'default' => null],
            'initial_app' => ['type' => 'TINYINT', 'constraint' => 1, 'unsigned' => true],
            'final_app'   => ['type' => 'TINYINT', 'constraint' => 1, 'unsigned' => true],
            'status'      => ['type' => 'TINYINT', 'constraint' => 1, 'unsigned' => true, 'null' => true],
            'date_added'  => ['type' => 'DATETIME', 'default' => new RawSql('CURRENT_TIMESTAMP')]

        ]);

        $this->forge->addPrimaryKey('id');
        $this->forge->addForeignKey('emp_no', 'auth_users', 'emp_no');
        $this->forge->createTable('ob_requests', true);

        // Official business items table
        $this->forge->addField([
            'id'          => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'request_id'  => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'purpose'     => ['type' => 'VARCHAR', 'constraint' => 256],
            'destination' => ['type' => 'VARCHAR', 'constraint' => 256],
            'sched_date'  => ['type' => 'DATE', 'null' => true],
            'departure'   => ['type' => 'DATETIME', 'null' => true],
            'arrival'     => ['type' => 'DATETIME', 'null' => true]
        ]);

        $this->forge->addPrimaryKey('id');
        $this->forge->addForeignKey('request_id', 'ob_requests', 'id');
        $this->forge->createTable('ob_items');


        // Stock request table
        $this->forge->addField([
            'id'          => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'emp_no'      => ['type' => 'VARCHAR', 'constraint' => 11],
            'purpose'     => ['type' => 'VARCHAR', 'constraint' => 256],
            'rev_by'      => ['type' => 'VARCHAR', 'constraint' => 256, 'null' => true, 'default' => null],
            'app_by'      => ['type' => 'VARCHAR', 'constraint' => 256, 'null' => true, 'default' => null],
            'initial_app' => ['type' => 'TINYINT', 'constraint' => 1, 'unsigned' => true],
            'final_app'   => ['type' => 'TINYINT', 'constraint' => 1, 'unsigned' => true],
            'status'      => ['type' => 'TINYINT', 'constraint' => 1, 'unsigned' => true, 'null' => true],
            'date_added'  => ['type' => 'DATETIME', 'default' => new RawSql('CURRENT_TIMESTAMP')]
        ]);

        $this->forge->addPrimaryKey('id');
        $this->forge->addForeignKey('emp_no', 'auth_users', 'emp_no');
        $this->forge->createTable('stock_requests', true);

        $this->forge->addField([
            'id'          => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'request_id'  => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'item_name'   => ['type' => 'VARCHAR', 'constraint' => 256],
            'quantity'    => ['type' => 'TINYINT', 'constraint' => 4]
        ]);

        $this->forge->addPrimaryKey('id');
        $this->forge->addForeignKey('request_id', 'stock_requests', 'id');
        $this->forge->createTable('stock_request_items', true);
    }

    public function down()
    {
        $this->db->disableForeignKeyChecks();

        $this->forge->dropTable('leave_requests', true);
        $this->forge->dropTable('overtime_requests', true);
        $this->forge->dropTable('ob_requests', true);
        $this->forge->dropTable('ob_items', true);
        $this->forge->dropTable('stock_requests', true);
        $this->forge->dropTable('stock_request_items', true);

        $this->db->enableForeignKeyChecks();
    }
}
