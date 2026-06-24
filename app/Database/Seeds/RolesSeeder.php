<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class RolesSeeder extends Seeder
{
    public function run(): void
    {
        $now  = date('Y-m-d H:i:s');
        $rows = [
            [
                'code'        => 'Admin',
                'name'        => 'Admin',
                'description' => 'Akses penuh monitoring, user management, dan pelaporan.',
            ],
            [
                'code'        => 'Supervisor',
                'name'        => 'Supervisor / PIC / Pelaksana',
                'description' => 'User lapangan yang mengisi laporan harian.',
            ],
            [
                'code'        => 'Manager',
                'name'        => 'Manager',
                'description' => 'Akses rekap dan trend kemajuan pekerjaan.',
            ],
        ];

        foreach ($rows as $row) {
            $this->db->query(
                'INSERT INTO `Roles` (`code`, `name`, `description`, `created_at`, `updated_at`)
                 VALUES (?, ?, ?, ?, ?)
                 ON DUPLICATE KEY UPDATE
                 `name` = VALUES(`name`),
                 `description` = VALUES(`description`),
                 `updated_at` = VALUES(`updated_at`)',
                [$row['code'], $row['name'], $row['description'], $now, $now]
            );
        }
    }
}
