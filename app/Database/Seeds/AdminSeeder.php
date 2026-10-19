<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class AdminSeeder extends Seeder
{
    public function run()
    {
        $email = 'admin@example.com';

        // Prevent duplicate admin accounts
        $existingUser = $this->db->table('users')
            ->where('email', $email)
            ->get()
            ->getRowArray();

        if ($existingUser) {
            echo "Admin account already exists.\n";
            return;
        }

        $this->db->table('users')->insert([
            'name'       => 'Administrator',
            'email'      => $email,
            'password'   => password_hash(
                '12345678',
                PASSWORD_DEFAULT
            ),
            'role'       => 'admin',
            'status'     => 'active',
            'email_verified_at' => date('Y-m-d H:i:s'),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        echo "Admin account created successfully.\n";
    }
}
