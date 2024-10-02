<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UsersTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'name' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'email' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'unique' => true,
            ],
            'username' => [
                'type'       => 'VARCHAR',
                'constraint' => '30',
                'unique'     => true,  // Make username unique
            ],
            'image' => [
                'type'       => 'VARCHAR',
                'constraint' => '200',
                'null'       => true,   // Allow image to be null
            ],

            'password' => [
                'type'       => 'VARCHAR',
                'constraint' => '200',
            ],
            'created_at datetime default current_timestamp',
            'updated_at datetime default current_timestamp on update current_timestamp'
        ]);

        // Add primary key
        $this->forge->addKey('id', true);

        // Create the table
        $this->forge->createTable('users');
    }

    public function down()
    {
        // Drop the table if exists
        $this->forge->dropTable('users');
    }
}
