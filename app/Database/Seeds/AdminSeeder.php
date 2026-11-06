<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class AdminSeeder extends Seeder
{
    public function run()
    {
        $data = [
            'username' => 'admin',
            'password' => password_hash('Admin@123', PASSWORD_BCRYPT),
        ];

        // Cek apakah username sudah ada, agar tidak duplikat saat seed ulang
        $existing = $this->db->table('admin')
            ->where('username', $data['username'])
            ->countAllResults();

        if ($existing === 0) {
            $this->db->table('admin')->insert($data);
            echo "Admin seeder: akun 'admin' berhasil dibuat.\n";
        } else {
            echo "Admin seeder: akun 'admin' sudah ada, dilewati.\n";
        }
    }
}
