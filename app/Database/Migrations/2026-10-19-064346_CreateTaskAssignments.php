<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTaskAssignments extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'task_id'    => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'user_id'    => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
        ]);

        $this->forge->addKey(['task_id', 'user_id'], true);   // composite primary key: no duplicates
        $this->forge->addKey('user_id');                      // "tasks for this user" lookups
        $this->forge->addForeignKey('task_id', 'tasks', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');

        $this->forge->createTable('task_assignments');
    }

    public function down()
    {
        $this->forge->dropTable('task_assignments');
    }
}
