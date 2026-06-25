<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class HeavyEquipmentCategoriesSeeder extends Seeder
{
    public function run(): void
    {
        $now  = date('Y-m-d H:i:s');
        $rows = [
            ['name' => 'Tongkang', 'slug' => 'tongkang', 'sort_order' => 1, 'is_active' => 1],
            ['name' => 'Crane Service', 'slug' => 'crane', 'sort_order' => 2, 'is_active' => 1],
            ['name' => 'Boring Machine', 'slug' => 'boring-machine', 'sort_order' => 3, 'is_active' => 1],
            ['name' => 'Dump Truck', 'slug' => 'dump-truck', 'sort_order' => 4, 'is_active' => 1],
            ['name' => 'Excavator', 'slug' => 'excavator', 'sort_order' => 5, 'is_active' => 1],
            ['name' => 'Loader', 'slug' => 'loader', 'sort_order' => 6, 'is_active' => 1],
            ['name' => 'Vibro Hammer', 'slug' => 'vibro-hammer', 'sort_order' => 7, 'is_active' => 1],
            ['name' => 'Truck Mixer', 'slug' => 'truck-mixer', 'sort_order' => 8, 'is_active' => 1],
            ['name' => 'Bulldozer', 'slug' => 'bulldozer', 'sort_order' => 90, 'is_active' => 0],
            ['name' => 'Vibroroller', 'slug' => 'vibroroller', 'sort_order' => 91, 'is_active' => 0],
            ['name' => 'Hyab Crane', 'slug' => 'hyab-crane', 'sort_order' => 92, 'is_active' => 0],
            ['name' => 'Ponton', 'slug' => 'ponton', 'sort_order' => 93, 'is_active' => 0],
        ];

        foreach ($rows as $row) {
            $this->db->query(
                'INSERT INTO `HeavyEquipmentCategories` (`name`, `slug`, `sort_order`, `is_active`, `created_at`, `updated_at`)
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
