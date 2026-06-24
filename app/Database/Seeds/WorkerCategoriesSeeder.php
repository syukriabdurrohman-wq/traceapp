<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class WorkerCategoriesSeeder extends Seeder
{
    public function run(): void
    {
        $now  = date('Y-m-d H:i:s');
        $rows = [
            ['name' => 'Pelaksana KSO', 'slug' => 'pelaksana-kso', 'sort_order' => 1, 'is_active' => 1],
            ['name' => 'Pelaksana Subkon / Vendor', 'slug' => 'pelaksana-subkon-vendor', 'sort_order' => 2, 'is_active' => 1],
            ['name' => 'Gudang', 'slug' => 'gudang', 'sort_order' => 3, 'is_active' => 1],
            ['name' => 'Logistik', 'slug' => 'logistik', 'sort_order' => 4, 'is_active' => 1],
            ['name' => 'Peralatan', 'slug' => 'peralatan', 'sort_order' => 5, 'is_active' => 1],
            ['name' => 'HSE', 'slug' => 'hse', 'sort_order' => 6, 'is_active' => 1],
            ['name' => 'QA / QC', 'slug' => 'qa-qc', 'sort_order' => 7, 'is_active' => 1],
            ['name' => 'Survey', 'slug' => 'survey', 'sort_order' => 8, 'is_active' => 1],
            ['name' => 'Mekanik & Elektrikal', 'slug' => 'mekanik-elektrikal', 'sort_order' => 9, 'is_active' => 1],
            ['name' => 'Pekerja Subkon / Vendor', 'slug' => 'pekerja-subkon-vendor', 'sort_order' => 10, 'is_active' => 1],
            ['name' => 'Pekerja Harian', 'slug' => 'pekerja-harian', 'sort_order' => 11, 'is_active' => 1],
            ['name' => 'Tukang', 'slug' => 'tukang', 'sort_order' => 12, 'is_active' => 1],
        ];

        foreach ($rows as $row) {
            $this->db->query(
                'INSERT INTO `WorkerCategories` (`name`, `slug`, `sort_order`, `is_active`, `created_at`, `updated_at`)
                 VALUES (?, ?, ?, ?, ?, ?)
                 ON DUPLICATE KEY UPDATE
                 `name` = VALUES(`name`),
                 `sort_order` = VALUES(`sort_order`),
                 `is_active` = VALUES(`is_active`),
                 `updated_at` = VALUES(`updated_at`)',
                [$row['name'], $row['slug'], $row['sort_order'], $row['is_active'], $now, $now]
            );
        }
    }
}
